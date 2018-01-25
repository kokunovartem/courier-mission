<?php

namespace app\controllers;

use app\helpers\DateWork;
use app\helpers\Pagination;
use app\models\Couriers;
use app\models\Missions;
use app\models\Regions;
use app\vendor\Main;

class MissionController extends FrontController
{
    public function indexAction()
    {
        $controller = FrontController::getInstance();

        $params = $controller->getParams();
        $model = new Missions();
        $model->title = 'Список поездок';
        /*Выбор даты*/
        $begin_dt = isset($params['begin_dt']) ? DateWork::formatToSql($params['begin_dt']) : '';
        $end_dt = isset($params['end_dt']) ? date('Y-m-d', strtotime($params['end_dt']) + 3600*24) : '';

        /*Паджинация*/
        $limit_default = 20;
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) ? (int)$params['limit'] : $limit_default;
        $count_rows = $model->getCount($begin_dt, $end_dt);
        $count_pages = ceil($count_rows/$limit);
        $start = $page == 1 ? 0 : $limit * ($page - 1);
        $pagination = (new Pagination());
        $pagination->setCurrent($page);
        $pagination->setTotal($count_pages);

        $model->findAll($start, $limit, $begin_dt, $end_dt);

        /*Переменные для передачи в вид*/
        $model->vars['region_list'] = Regions::getRegionsList();
        $model->vars['time_relax'] = round(Main::$config['courier_option']['time_relax'] / 3600);
        $model->vars['pagination'] = $pagination;
        $model->vars['count_rows'] = $count_rows;
        $model->vars['begin_dt'] = $begin_dt ? DateWork::formatSqlDt($begin_dt) : '';
        $model->vars['end_dt'] = $end_dt ? DateWork::formatSqlDt($end_dt) : '';

        $result = $model->render('../views/missions/index.php');

        $controller->setBody($result);
    }

    //Обращение к методу проиходит при создании формы "Отправить курьера". Возвращает json со свободными курьерами
    public function get_free_courierAction()
    {
        $couriers = Couriers::getFreeCouriers();
        echo json_encode($couriers);
    }

    //К этому методу происходит обращение при отправке формы "Отправить курьера"
    public function create_travelAction()
    {
        $courier_id = isset($_POST['courier']) ? (int)$_POST['courier'] : 0;
        $region_id = isset($_POST['region']) ? (int)$_POST['region'] : 0;

        //Проверяем корректность принятых данных
        if ($courier_id && $region_id && Couriers::exists($courier_id) && Regions::exists($region_id) && Couriers::notBusy($courier_id)) {
            echo json_encode(Missions::setMission($courier_id, $region_id));
        } else {
            echo false;
        }
    }

    //Обращение к методу происходит при клике на "Очистить историю записей"
    //Вызывает соответствующий метод модели, который очищает историю записей и делает всех курьеров свободными
    public function clear_historyAction()
    {
        Missions::clearHistory();
    }

    //Обращение к методу происходит при клике на "Создать случайную историю записей"
    //Вызывает соответствующий метод модели, который создает записи с июня 2015 года
    public function create_random_historyAction()
    {
        set_time_limit(0);
        Missions::createRandomHistory();
    }


}