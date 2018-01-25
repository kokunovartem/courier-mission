<?php
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->title?></title>
    <?php if (!empty($this->_css)):
        foreach ($this->_css as $css):?>
            <link href="<?=$css?>" rel="stylesheet">
        <?php endforeach;
    endif;?>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">ИС Курьеры</a>
            </div>

            <?php
            $links = ['courier' => 'Курьеры', 'mission' => 'Поездки', 'region' => 'Регионы'];
            ?>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php foreach ($links as $link => $anchor):
                        $active = $this->_action == $link ? 'active ' : '';?>
                    <li class="<?=$active?>"><a href="/<?=$link?>/"><?=$anchor?></a></li>
                    <?php endforeach;?>
                </ul>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div>


