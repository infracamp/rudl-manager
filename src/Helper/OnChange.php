<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 26.07.18
 * Time: 13:57
 */

namespace RudlManager\Helper;


class OnChange
{

    private static $lastval = [];

    public static function OnChange($value, callable $callable=null, $key) :bool
    {
        if ( ! isset(self::$lastval[$key]) || self::$lastval[$key] !== $value) {
            self::$lastval[$key] = $value;
            if ($callable !== null)
                $callable();
            return true;
        }
        return false;
    }


}