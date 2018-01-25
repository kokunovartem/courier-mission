<?php

use app\helpers\StringsWork;
?>
<div class="container">
    <h1><?=$this->title?></h1>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Регион</th>
                    <th>Время в пути(часов)</th>
                </tr>
                <?php if (!empty($this->properties)):
                    foreach ($this->properties as $num => $prop):?>
                        <tr>
                            <td><?=$prop['id']?></td>
                            <td><?=StringsWork::clearStr($prop['title'])?></td>
                            <td><?=$this->getTravelTimeInHours($num)?></td>
                        </tr>
                    <?php endforeach;
                endif;?>
            </table>
        </div>
    </div>
</div>