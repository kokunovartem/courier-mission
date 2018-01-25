<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 22.01.2018
 * Time: 20:39
 */

namespace app\vendor;


class Model
{
    protected $_header, $_footer, $_css, $_js, $_action;
    public $title;
    public $properties;
    public $vars;

    public function __construct()
    {
        $this->_header = Main::$config['header'];
        $this->_footer = Main::$config['footer'];
        $this->_css = Main::$config['css'];
        $this->_js = Main::$config['js'];
        $this->getAction();
    }

    public function render($file)
    {
        ob_start();
        include $this->_header;
        include(dirname(__FILE__) . '/' . $file);
        include $this->_footer;
        return ob_get_clean();
    }

    //Метод записыват в свойство _action модели текущий актион
    //Необходима для подсветки активного класса в верхнем меню
    protected function getAction()
    {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));
        $this->_action = !empty($splits[0]) ? $splits[0] : 'index';
    }
}