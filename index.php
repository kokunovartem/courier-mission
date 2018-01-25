<?php

require_once 'autoloader.php';
require_once 'config/config.php';

use app\vendor\Main;

(new Main($_config))->run();
