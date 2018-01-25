<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 22.01.2018
 * Time: 19:50
 */

namespace app\controllers;


use app\models\Couriers;
use app\models\Site;

class CourierController extends FrontController
{
    public function indexAction()
    {
        $controller = FrontController::getInstance();

        $params = $controller->getParams();
        $model = new Couriers();
        $model->title = 'Список курьеров';
        $model->findAll();
        $result = $model->render('../views/couriers/index.php');

        $controller->setBody($result);
    }
}