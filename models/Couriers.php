<?php

namespace app\models;

use app\vendor\Main;
use app\vendor\Model;

class Couriers extends Model
{
    public function findAll()
    {
        $result = Main::$app->db->query("select * from couriers");
        $this->properties = $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    //Форматирует статус
    public function outputStatus($num)
    {
        if ($this->properties[$num]['busy_until']) {
            return 'Занят до ' . date('d.m.Y H:i:s', $this->properties[$num]['busy_until']);
        } else {
            return 'Свободен';
        }
    }

    //Возвращает свободных курьеров
    public static function getFreeCouriers()
    {
        $result = Main::$app->db->query("
        select id, concat(last_name, ' ', first_name, ' ', patr_name) 'courier' from couriers
        where  busy_until is null or busy_until <= unix_timestamp()");
        return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    //Существует ли курьер с таким id
    public static function exists($id)
    {
        $result = Main::$app->db->query("select id from couriers where id = $id");
        return $result ? (bool)$result->fetch()['id'] : false;
    }

    //Возвращает true, если курьер свободен
    public static function notBusy($id)
    {
        $result = Main::$app->db->query("select id from couriers where id = $id and (busy_until is null or busy_until <= unix_timestamp())");
        return $result ? (bool)$result->fetch()['id'] : false;
    }

    //Возвращает id и занятость курьеров
    public static function getCouriersIdAndBusy()
    {
        $result = Main::$app->db->query("select id, busy_until from couriers");
        return $result ? $result->fetchAll(\PDO::FETCH_ASSOC) : [];
    }
}