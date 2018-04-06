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

        $router[] = new Route('Api/<action>', array(
            'presenter' => 'Api'
        ));

        $router[] = new Route('<presenter>[/<action>]', array(
            'presenter' => 'Homepage',
            'action' => 'default'
        ));
        return $router;
    }

}
