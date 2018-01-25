<?php
/**
 * Created by PhpStorm.
 * User: Temishe
 * Date: 23.01.2018
 * Time: 18:33
 */

namespace app\helpers;


class StringsWork
{
    public static function clearStr($str)
    {
        return htmlentities(strip_tags($str));
    }
}