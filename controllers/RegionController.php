<?php

namespace app\controllers;

use app\models\Regions;


class RegionController extends FrontController
{
    public function indexAction()
    {
        $controller = FrontController::getInstance();

        $model = new Regions();
        $model->findAll();
        $model->title = 'Список регионов';
        $result = $model->render('../views/regions/index.php');

        $controller->setBody($result);
    }
}