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