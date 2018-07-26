<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 15:19
 */


function logInfo($msg)
{
    $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
    \RudlManager\Helper\Log::Get()->log($msg, $bt[0]);
}


function changed($value, callable $fn = null) : bool
{
    $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
    $key = $bt[0]["file"] . "#" . $bt[0]["line"];
    return \RudlManager\Helper\OnChange::OnChange($value, $fn, $key);
}