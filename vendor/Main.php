<?php

namespace app\vendor;

use app\controllers\FrontController;

class Main
{
    public static $app;
    public static $config;
    protected $front;

    public function __construct($config)
    {
        self::$config = $config;
    }

    public function run()
    {
        self::$app = Application::getInstance(self::$config);
        $this->front = FrontController::getInstance();
        $this->front->route();
        $this->front->getBody();
    }

    public static function app()
    {
        return self::$app;
    }


}