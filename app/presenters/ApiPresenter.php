<?php

namespace App\Presenters;

class ApiPresenter extends \Nette\Application\UI\Presenter
{
    protected function beforeRender()
    {
        parent::beforeRender();
        die(1);
    }

    public function actionMhd()
    {
        $json = file_get_contents("http://sotoris.cz/DataSource/CityHack2015/vehiclesBrno.aspx");
        echo $json;
    }
}
