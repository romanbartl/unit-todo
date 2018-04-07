<?php

namespace App\Presenters;

use App\Item;
use App\Planner;
use App\Tag;
use Doctrine\ORM\Query\ResultSetMapping;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Helper\Builder\ApiHelperBuilder;
use Ivory\GoogleMap\Helper\Builder\MapHelperBuilder;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Event\Event;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Service\Base\Location\CoordinateLocation;
use Ivory\GoogleMap\Service\Base\TravelMode;
use Ivory\GoogleMap\Service\Direction\DirectionService;
use Ivory\GoogleMap\Service\Direction\Request\DirectionRequest;
use Nette;

class HomepagePresenter extends BasePresenter
{
    /** @var Nette\Http\Session */
    private $session;

    /** @var Nette\Http\SessionSection */
    private $sessionSection;

    public function __construct(Nette\Http\Session $session)
    {
        $this->session = $session;

        // a získáme přístup do sekce 'mySection':
        $this->sessionSection = $session->getSection('mySection');
    }

    public function startup()
    {
        parent::startup();

        $session = $this->getSession();
        $this->sessionSection = $session->getSection('planner');

        if (!isset($this->sessionSection->planner)) {
            $this->sessionSection->planner = [];
            $this->sessionSection->totalDistance = 0;
            $this->sessionSection->totalTime = 0;
        }

        $this->template->planner = $this->sessionSection->planner;
        $this->template->totalDistance = $this->sessionSection->totalDistance;
        $this->template->totalTime = $this->sessionSection->totalTime;
    }

    public function beforeRender()
    {
        $this->template->addFilter('metersToReadable', function ($meters) {
            if ($meters < 1000) {
                return $meters . "m";
            }

            return round(($meters / 1000), 2) . "km";
        });

        $this->template->addFilter('secondsToReadable', function ($seconds) {
            if ($seconds < 60) {
                return $seconds . "s";
            }

            return ceil(($seconds / 60)) . "min";
        });

        $this->template->places = $this->EntityManager->getRepository(Item::class)->findAll();

        $map = new Map();

        $map->setVariable('map');

        $map->setAutoZoom(false);
        $map->setCenter(new Coordinate(49.1950602, 16.6068371));
        $map->setMapOption('zoom', 15);

        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '100%');

        $dragend = new Event(
            $map->getVariable(),
            'dragend',
            'function(){window.allowedBounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(49.11883,16.45615),
                    new google.maps.LatLng(49.29826,16.70746));
                    checkIfInArea();}'
        );

        $dragstart = new Event(
            $map->getVariable(),
            'dragstart',
            'function(){savePreLocation();}'
        );

        $zoom = new Event(
            $map->getVariable(),
            'zoom_changed',
            'function(){if (map.getZoom() < 13) map.setZoom(13);}'
        );

        $idle = new Event(
            $map->getVariable(),
            'idle',
            'function(){
            var stylers = [ {
                featureType: "poi",
                elementType: "labels.icon",
                stylers: [
                  { visibility: "off" }
                ]
              }
            ];
        map.setOptions({styles: stylers});
        getBusMarkers();
        placeMarkers();}'
        );

        $map->getEventManager()->addDomEvent($dragend);
        $map->getEventManager()->addDomEvent($dragstart);
        $map->getEventManager()->addDomEvent($zoom);
        $map->getEventManager()->addDomEvent($idle);

        $mapHelper = MapHelperBuilder::create()->build();
        $apiHelper = ApiHelperBuilder::create()
            ->setKey($this->context->parameters["google"]["apiKey"])
            ->setLanguage("cs")
            ->build();

        $this->template->mapHelper = $mapHelper->render($map);
        $this->template->mapApiHelper = $apiHelper->render([$map]);
    }

    public function handleChangeRoute($index, $typ)
    {
        if ($typ == "pesky") {
            $typ = "pěšky";
        }

        $data = [];
        $data[] = $this->sessionSection->planner[$index][0];
        $data[] = $this->sessionSection->planner[$index][1];
        $data[] = $this->sessionSection->planner[$index][2];

        $this->sessionSection->totalDistance -= $this->sessionSection->planner[$index][3];
        $this->sessionSection->totalTime -= $this->sessionSection->planner[$index][4];

        $route = $this->getTransportTime($typ, $this->sessionSection->planner[$index][1], $this->sessionSection->planner[$index][2],
            $this->sessionSection->planner[$index + 1][1], $this->sessionSection->planner[$index + 1][2]);

        $data[] = $route[0];
        $data[] = $route[1];
        $data[] = $typ;

        $this->sessionSection->totalDistance += $route[0];
        $this->sessionSection->totalTime += $route[1];

        $this->sessionSection->planner[$index] = $data;

        $this->template->totalDistance = $this->sessionSection->totalDistance;
        $this->template->totalTime = $this->sessionSection->totalTime;
        $this->template->planner = $this->sessionSection->planner;
        $this->redrawControl('planner');
    }

    public function handleCancelPlanner()
    {
        $this->sessionSection->planner = [];
        $this->sessionSection->totalDistance = 0;
        $this->sessionSection->totalTime = 0;

        $this->template->planner = $this->sessionSection->planner;
        $this->template->totalDistance = $this->sessionSection->totalDistance;
        $this->template->totalTime = $this->sessionSection->totalTime;
        $this->redrawControl('planner');
    }

    public function handleAddPoint($name, $lati, $longi)
    {
        if ($this->isAjax()) {
            $session = $this->getSession();
            $this->sessionSection = $session->getSection('planner');

            if (count($this->sessionSection->planner) > 0) {
                $index = count($this->sessionSection->planner) - 1;
                $data = [];
                $data[] = $this->sessionSection->planner[$index][0];
                $data[] = $this->sessionSection->planner[$index][1];
                $data[] = $this->sessionSection->planner[$index][2];

                $route = $this->getTransportTime("pěšky", $this->sessionSection->planner[$index][1], $this->sessionSection->planner[$index][2], $lati, $longi);

                $data[] = $route[0];
                $data[] = $route[1];
                $data[] = "pěšky";

                $this->sessionSection->totalDistance += $route[0];
                $this->sessionSection->totalTime += $route[1];

                $this->sessionSection->planner[$index] = $data;
            }

            $this->sessionSection->planner[] = array($name, $lati, $longi, null, null, null);
            $this->template->planner = $this->sessionSection->planner;
            $this->template->totalDistance = $this->sessionSection->totalDistance;
            $this->template->totalTime = $this->sessionSection->totalTime;
            $this->redrawControl('planner');
        }

    }

    public function getTransportTime($type, $lati, $longi, $latiTo, $longiTo)
    {
        $data = [];
        $request = new DirectionRequest(
            new CoordinateLocation(new Coordinate($lati, $longi)),
            new CoordinateLocation(new Coordinate($latiTo, $longiTo))
        );

        if ($type == "pěšky") {
            $request->setTravelMode(TravelMode::WALKING);
        } elseif ($type == "autem") {
            $request->setTravelMode(TravelMode::DRIVING);
        } elseif ($type == "mhd") {
            $request->setTravelMode(TravelMode::TRANSIT);
        }

        $request->setRegion('cs');
        $request->setLanguage('cs');

        $direction = new DirectionService(
            new Client(),
            new GuzzleMessageFactory()
        );

        $response = $direction->route($request);
        foreach ($response->getRoutes() as $route) {
            foreach ($route->getLegs() as $leg) {
                $data[] = $leg->getDistance()->getValue();
                $data[] = $leg->getDuration()->getValue();
            }
        }

        return $data;
    }
}