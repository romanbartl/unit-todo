<?php

namespace App\Presenters;

class ApiPresenter extends \Nette\Application\UI\Presenter
{
    protected function beforeRender()
    {
        parent::beforeRender();
        die(1);
    }

    public function actionMhdBus()
    {
        $json = file_get_contents("http://sotoris.cz/DataSource/CityHack2015/vehiclesBrno.aspx?traction=bus");
        echo $json;
    }

    public function actionMhdTBus()
    {
        $json = file_get_contents("http://sotoris.cz/DataSource/CityHack2015/vehiclesBrno.aspx?traction=tbus");
        echo $json;
    }

    public function actionMhdTram()
    {
        $json = file_get_contents("http://sotoris.cz/DataSource/CityHack2015/vehiclesBrno.aspx?traction=tram");
        echo $json;
    }
}
