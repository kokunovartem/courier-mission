<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 23.01.2018
 * Time: 18:35
 */

namespace app\helpers;


class DateWork
{
    public static function formatSqlDt($date, $format='d.m.Y')
    {
        return date($format, strtotime($date));
    }

    public static function formatSqlDateTime($date, $format='d.m.Y H:i:s')
    {
        return date($format, strtotime($date));
    }

    public static function formatToSql($date, $format='Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
}