<?php

use app\helpers\StringsWork;
use app\helpers\DateWork;

?>
<div class="container">
    <h1><?=$this->title?></h1>
    <div class="row" style="margin-bottom: 20px">
        <div class="col-lg-12">
            <form action="/missions/" method="get" class="form-inline" id="check_date">
                <div class="form-group">
                    <label for="">c</label>
                    <input type="text" class="form-control date-input" name="begin_dt" value="<?=$this->vars['begin_dt']?>">
                </div>
                <div class="form-group">
                    <label for="">по</label>
                    <input type="text" class="form-control date-input" name="end_dt" value="<?=$this->vars['end_dt']?>">
                </div>
                <input type="submit" value="Искать" class="btn btn-success">
                <a href="/mission/" class="btn btn-default">Сбросить фильтры</a>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table missions-table">
                <tr>
                    <th>ID</th>
                    <th>ФИО курьера, ID</th>
                    <th>Направлен в</th>
                    <th>Дата выезда из Москвы</th>
                    <th>Время в пути (в часах)</th>
                    <th>Дата прибытия в регион</th>
                    <th>Время отдыха (в часах)</th>
                </tr>
                <?php if (!empty($this->properties)):
                    foreach ($this->properties as $num => $prop):?>
                        <tr>
                            <td><?=$prop['id']?></td>
                            <td><?=StringsWork::clearStr($prop['courier'])?></td>
                            <td><?=StringsWork::clearStr($prop['city'])?></td>
                            <td><?=DateWork::formatSqlDateTime($prop['begin_dt'])?></td>
                            <td><?=round($prop['travel_time'] / 3600)?></td>
                            <td><?=DateWork::formatSqlDateTime($prop['arrival_dt'])?></td>
                            <td><?=$this->vars['time_relax']?></td>
                        </tr>
                    <?php endforeach;
                else:?>
                <tr>
                    <td colspan="7">Нет записей о поездках</td>
                </tr>
                <?php endif;?>
            </table>
            <p>Количество записей: <?=$this->vars['count_rows']?></p>
            <?php echo $this->vars['pagination']->parse();?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="btn btn-success" id="create_travel">Отправить курьера</div>
            <div class="btn btn-default" id="clear_travel_list">Очистить историю записей</div>
            <div class="btn btn-default" id="create_travel_list">Создать случайную историю записей</div>
        </div>
    </div>
</div>

<!-------ФОРМА СОЗДАНИЯ ПОЕЗДКИ------->
<div id="travel_create_form" class="modal-form hidden">
    <p class="close-btn"><i class="glyphicon glyphicon-remove-circle" aria-hidden="true"></i></p>
    <div class="alert alert-danger hidden" role="alert" id="no_couriers">Нет свободных курьеров</div>
    <h2>Отправить курьера</h2>
    <form action="/mission/create_travel" method="post">
        <div class="form-group">
            <label for="">Выбрать курьера</label>
            <select name="courier" id="couriers_select" class="form-control">

            </select>
        </div>
        <div class="form-group">
            <label for="">Выбрать регион</label>
            <select name="region" id="" class="form-control">
        <?php if (!empty($this->vars['region_list'])):
            foreach ($this->vars['region_list'] as $region):?>
                <option value="<?=$region['id']?>"><?=$region['title']?></option>
            <?php endforeach;
        endif;?>
            </select>
        </div>
        <button class="btn btn-success">Отправить</button>
        <div class="btn btn-default" id="cancel">Отмена</div>
    </form>
</div>