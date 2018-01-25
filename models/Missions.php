<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 23.01.2018
 * Time: 19:09
 */

namespace app\models;


use app\helpers\DateWork;
use app\helpers\StringsWork;
use app\vendor\Main;
use app\vendor\Model;

class Missions extends Model
{
    //Метод получает из базы данных и записывает в свойство модели properties
    //все поездки (с учетом параметров выбора дат и для паджинации)
    public function findAll($start, $limit, $begin_dt, $end_dt)
    {
        $query =
            "select cm.id, 
              concat(c.last_name, ' ', c.first_name, ' ', c.patr_name, ' (ID ', c.id, ')') 'courier',
              r.title 'city', r.travel_time, cm.begin_dt, cm.arrival_dt, c.busy_until 
            from couriers c, regions r, courier_missions cm
            where c.id= cm.couriers_id and r.id = cm.regions_id";
        $query .= $begin_dt ? " and begin_dt >= '$begin_dt'" : '';
        $query .= $end_dt ? " and begin_dt <= '$end_dt'" : '';
        $query .= " order by cm.id desc";
        $query .= " limit $start, $limit";

        $result = Main::$app->db->query($query);
        $this->properties = $result ? $result->fetchAll(\PDO::FETCH_ASSOC) :[];
    }

    //Метод возвращает поездку по ID
    public static function findOne($id)
    {
        $query = "select cm.id, 
                    concat(c.last_name, ' ', c.first_name, ' ', c.patr_name, ' (ID ', c.id, ')') 'courier',
                    r.title 'city', r.travel_time, cm.begin_dt, cm.arrival_dt, c.busy_until 
                  from couriers c, regions r, courier_missions cm
                  where c.id= cm.couriers_id and r.id = cm.regions_id and cm.id=$id";
        $result = Main::$app->db->query($query);
        return $result ? $result->fetchAll(\PDO::FETCH_ASSOC)[0] : [];
    }

    //Метод создает новую поездку
    public static function setMission($courier_id, $region_id)
    {
        $time_relax = Main::$config['courier_option']['time_relax'];//Время отдыха курьеров. Задается в настройках
        $success = true;//Флаг успеха
        $dt = date('Y-m-d H:i:s');
        $db = Main::$app->db;//Подключение к базе данных
        $db->beginTransaction();//Нужно выполнить два запроса, от выполнения обоих зависит стабильность приложения. Поэтому стартуем транзакцию
        $mission_id = 0;

        try {
            //Запрос на создание поездки
            $query = "insert into courier_missions (couriers_id, regions_id, begin_dt, arrival_dt)
                  values($courier_id, $region_id, '$dt', from_unixtime(unix_timestamp() + (select travel_time from regions
                  where id=$region_id)/2))";

            //Если запрос не прошел или вернулось 0 затронутых строк
            if($db->exec($query) == false) {
                $success = false;//Флаг успеха в фолз
            } else {
                $mission_id = $db->lastInsertId();//Если запрос прошел получаем ID вставленной записи
            }

            //Запрос на изменение поля "Занят до" у курьера
            $query = "update couriers set 
                        busy_until= (unix_timestamp() + (select travel_time from regions where id=$region_id) + $time_relax)
                      where id=$courier_id";

            //Если запрос не прошел или вернулось 0 затронутых строк
            if($db->exec($query) == false) {
                $success = false;//Флаг успеха в фолз
            };

        } catch (\Exception $e) {
            $db->rollBack();//Если перехватили ошибку, откат
            $success = false;
            echo 'Выброшено исключение: ',  $e->getMessage();
        }
        if($success) {
            $db->commit();//Если все в пордяке - коммит
            $mission = Missions::findOne($mission_id);//Получаем вновь созданную запись для передачи в js
            if( is_array($mission)) {
                //Очищаем данные и модифицируем некоторые поля (можно было бы сделать и на mysql)
                foreach ($mission as $key => &$item) {
                    $item = StringsWork::clearStr($item);
                    if ($key == 'begin_dt') {
                        $item = DateWork::formatSqlDateTime($item);
                    }
                    if($key == 'busy_until') {
                        $item = date('d.m.Y H:i:s', $item);
                    }
                    if($key == 'travel_time') {
                        $item = round($item/3600);
                    }

                }
                $mission['time_relax'] = round($time_relax/3600);//Добавляем поле со временем отдыха
            }
            return $mission;
        } else {
            $db->rollBack();//Если флаг не тру, откатываем изменения
            return false;
        }
    }

    //Создает случайную историю поездок
    public static function createRandomHistory()
    {
        $db = Main::$app->db;
        $db->exec("truncate courier_missions");//Очишаем таблицу с поездками
        $db->exec("update couriers set busy_until = null");//Делаем всех курьеров свободными
        $couriers = Couriers::getCouriersIdAndBusy();//Получаем курьеров (поля id и busy_until)
        $regions = Regions::getRegionsIdAndTravelTime();//Получаем региоры (поля id и travel_time)
        $countRegions = count($regions);
        $time = strtotime('2015-06-01') + rand(25000, 68400);//Задаем псевдослучайное время начала работы
        $now = time();
        $step = 3600;//Первоначальный шаг цикла
        $missions = [];//Массив поездок
        $time_relax = Main::$config['courier_option']['time_relax'];//Время отдыха курьера
        //Готовим запрос на вставку данных в поездки
        $sth = $db->prepare("insert into courier_missions (couriers_id, regions_id, begin_dt, arrival_dt) values (?,?,?,?)");
        //Готовим запрос на вставку данных в поездки
        $sth2 = $db->prepare("update couriers set busy_until = ? where id= ?");
        //Цикл по временной метке до тех пор пока меньше сейчас
        for ($t = $time, $i=0; $t < $now; $t += $step) {
            //Если первый курьер занят на данное время
            if ($couriers[0]['busy_until'] > $t) {
                $t = $couriers[0]['busy_until'];//Переставляем метку на момент занятости курьера
                continue;//Пропускаем цикл
            } else {
                $step = rand(1800, 4200);//Далаем шаг случайным
            }

            $keys = [];//Вспомогательные массивы для сортировки курьеров
            $busy_until = [];//Вспомогательные массивы для сортировки курьеров
            //Цикл по курьерам
            foreach ($couriers as &$courier) {
                $region = $regions[rand(0, $countRegions)];//Случайно выбираем регион, куда отправить курьера

                //Заполнение массива поездок
                $missions[$i][] = $courier['id'];
                $missions[$i][] = $region['id'];
                $missions[$i][] = date('Y-m-d H:i:s', $t);
                $missions[$i][] = date('Y-m-d H:i:s', $t + $region['travel_time']);

                //Занятость курьера до (временная метка)
                $courier['busy_until'] = $t + $region['travel_time'] + $time_relax;

                $sth->execute($missions[$i]);//Вставляем записи в БД

                //Формируем массивы для сортировки курьеров
                $keys[] = $courier['id'];
                $busy_until[] = $courier['busy_until'];
                ++$i;
            }
            array_multisort($busy_until, SORT_ASC, $keys, SORT_ASC, $couriers);//Сортировка курьеров по занятости
        }

        foreach ($couriers as $courier) {
            //Вставка окончательной занятости курьеров в БД
            $sth2->execute([$courier['busy_until'], $courier['id']]);
        }

    }

    //Возвращиет количество поездок (для паджинации)
    public function getCount($begin_dt, $end_dt)
    {
        $query = "select count(*) from courier_missions where 1";
        $query .= $begin_dt ? " and begin_dt >= '$begin_dt'" : '';
        $query .= $end_dt ? " and begin_dt <= '$end_dt'" : '';
        $result = Main::$app->db->query($query);
        return $result ? $result->fetch(\PDO::FETCH_NUM)[0] : 0;
    }

    //Очищает историю поездок
    public static function clearHistory()
    {
        $db = Main::$app->db;
        $db->exec("truncate courier_missions");
        $db->exec("update couriers set busy_until = null");
    }
}