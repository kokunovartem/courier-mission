<?php

namespace app\models;

use app\vendor\Main;
use app\vendor\Model;

class Regions extends Model
{
    //Присваиваем массив регионов в свойство properties модели
    public function findAll()
    {
        $result = Main::$app->db->query("select * from regions order by show_order");
        $this->properties = $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    //Возвращает время поезки в часах или пустую строку
    public function getTravelTimeInHours($num)
    {

        if ($this->properties[$num]['travel_time']) {
            return $this->properties[$num]['travel_time'] / 3600;
        } else {
            return '';
        }
    }

    //Возвращает id и название региона
    public static function getRegionsList()
    {
        $result = Main::$app->db->query("select id, title from regions order by show_order");
        return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    //Возвращает true, если регион существует
    public static function exists($id)
    {
        $result = Main::$app->db->query("select id from regions where id = $id");
        return $result ? (bool)$result->fetch()['id'] : false;
    }

    //Возвращает списко регионов и время в пути
    public static function getRegionsIdAndTravelTime()
    {
        $result = Main::$app->db->query("select id, travel_time from regions");
        return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }
}