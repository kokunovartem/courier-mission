<?php

namespace app\controllers;

use app\models\Site;

class SiteController extends FrontController
{
    public function indexAction()
    {
        $controller = FrontController::getInstance();
        $model = new Site();
        $model->title = 'Курьеры';
        $result = $model->render('../views/site/index.php');

        $controller->setBody($result);
    }
}