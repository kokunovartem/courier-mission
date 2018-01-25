<?php

namespace app\controllers;

use app\vendor\Controller;

class FrontController implements IController
{
    protected $_controller, $_action, $_params, $_header, $_body, $_footer;
    static $_instance;

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));

        $this->_controller = !empty($splits[0]) ?
            __NAMESPACE__. '\\' . ucfirst($splits[0]).  'Controller' :
            __NAMESPACE__. '\SiteController';

        $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';

        if(!empty($splits[2])) {
            $keys = $values = [];
            for ($i = 2, $cnt = count($splits); $i < $cnt; $i++) {
                if($i % 2 == 0) {
                    $keys[] = $splits[$i];
                } else {
                    $values[] = $splits[$i];
                }
            }
            if (count($keys) == count(($values))) {
                $this->_params = array_combine($keys, $values);
            } else {
                $this->_params = '';
            }


        }

    }

    public function route()
    {
        if(class_exists($this->getController())) {
            $rc = new \ReflectionClass($this->getController());
            if ($rc->implementsInterface(__NAMESPACE__.'\IController')) {
                if($rc->hasMethod($this->getAction())) {
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    $method->invoke($controller);
                } else {
                    header("HTTP/1.0 404 Not Found");
                    //throw new \Exception("Controller has not method Action");
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                //throw new \Exception("Controller does not implement Interface");
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            //throw new \Exception("Controller does not exist");
        }
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function setBody($result)
    {
        $this->_body = $result;
    }

    public function getBody()
    {
        echo $this->_body;
    }

}