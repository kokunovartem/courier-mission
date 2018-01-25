<?php
$_config = [
    'db' => [
        'db_type' => 'mysql',
        'db_name' => 'couriers',
        'db_user' => 'root',
        'db_host' => 'localhost',
        'db_pwd' => ''
    ],
    'header' => 'views/layouts/header.php',
    'footer' => 'views/layouts/footer.php',
    'css' => [
        '/assets/css/bootstrap.min.css',
        '/assets/css/styles.css',
    ],
    'js' => [
        '/assets/js/jquery-2.1.4.min.js',
        '/assets/js/bootstrap.min.js',
        '/assets/js/bootstrapValidator.min.js',
        '/assets/js/libs.js',
        '/assets/js/main.js',
    ],
    'courier_option' => [
        'time_relax' => 24 * 3600 //24 часa
    ]
];