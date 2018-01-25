<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 22.01.2018
 * Time: 19:09
 */

namespace app\vendor;


class Application
{
    public $db;
    static $_instance;

    public static function getInstance($config)
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    private function __construct($config)
    {
        $dbConfig = $config['db'];
        $params = $dbConfig['db_type'] . ':dbname=' . $dbConfig['db_name'] . ';host=' . $dbConfig['db_host'];
        try {
            $this->db = new \PDO($params, $dbConfig['db_user'], $dbConfig['db_pwd']);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }
}