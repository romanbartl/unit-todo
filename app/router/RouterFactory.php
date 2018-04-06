<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{
    use Nette\StaticClass;

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $router[] = new Route('forum/tema/<topicId>-<topicTitle>[/strana-<visualPaginator-page>]', array(
            'presenter' => 'Forum',
            'action' => 'viewTopic',
            'topicTitle' => [
                Route::FILTER_OUT => 'Nette\Utils\Strings::webalize',
            ],
            "visualPaginator-page" => null
        ));

        $router[] = new Route('forum/zalozit/<threadId>', array(
            'presenter' => 'Forum',
            'action' => 'zalozit'
        ));

        $router[] = new Route('forum/<threadId>-<threadName>[/strana-<visualPaginator-page>]', array(
            'presenter' => 'Forum',
            'action' => 'thread',
            'threadName' => [
                Route::FILTER_OUT => 'Nette\Utils\Strings::webalize',
            ],
            "visualPaginator-page" => null
        ));

        $router[] = new Route('hrac/<user>[/<page>]', array(
            'presenter' => 'Hrac',
            'action' => 'default',
            "page" => 'zakladni'
        ));

        $router[] = new Route('galerie[/strana-<visualPaginator-page>]', array(
            'presenter' => 'Galerie',
            'action' => 'default',
            "visualPaginator-page" => null
        ));

        $router[] = new Route('[strana-<visualPaginator-page>]', array(
            'presenter' => 'Homepage',
            'action' => 'default',
            "visualPaginator-page" => null
        ));

        $router[] = new Route('<presenter>[/<action>]', 'Homepage:default');
        return $router;
    }

}
