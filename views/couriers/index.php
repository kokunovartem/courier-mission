<?php

use app\helpers\StringsWork;
use app\helpers\DateWork;
?>
<div class="container">
    <h1><?=$this->title?></h1>
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Дата рождения</th>
                    <th>Работает с</th>
                    <th>Статус</th>
                </tr>
        <?php if (!empty($this->properties)):
            foreach ($this->properties as $num => $prop):?>
                <tr>
                    <td><?=$prop['id']?></td>
                    <td><?=StringsWork::clearStr($prop['last_name'])?></td>
                    <td><?=StringsWork::clearStr($prop['first_name'])?></td>
                    <td><?=StringsWork::clearStr($prop['patr_name'])?></td>
                    <td><?=DateWork::formatSqlDt($prop['birthday_dt'])?></td>
                    <td><?=DateWork::formatSqlDT($prop['create_dt'])?></td>
                    <td><?=$this->outputStatus($num)?></td>
                </tr>
                    <?php endforeach;
                endif;?>
            </table>
        </div>
    </div>
</div>
