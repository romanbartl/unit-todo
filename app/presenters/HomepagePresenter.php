<?php

namespace App\Presenters;

use App\Item;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Helper\Builder\ApiHelperBuilder;
use Ivory\GoogleMap\Helper\Builder\MapHelperBuilder;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Event\Event;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Overlay\Marker;

class HomepagePresenter extends BasePresenter
{
    public function beforeRender()
    {
        $places = $this->EntityManager->getRepository(Item::class)->findAll();

        $map = new Map();

        $map->setVariable('map');

        $map->setAutoZoom(false);
        $map->setCenter(new Coordinate(49.1950602, 16.6068371));
        $map->setMapOption('zoom', 15);

        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '100%');

        foreach ($places as $place) {
            $marker = new Marker(new Coordinate($place->getLati(), $place->getLongi()));
            $infoWindow = new InfoWindow("<strong>" . $place->getName() . "</strong><br>" . $place->getDescription() );
            $marker->setInfoWindow($infoWindow);
            $map->getOverlayManager()->addMarker($marker);
        }


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
    getBusMarkers();}'
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
}