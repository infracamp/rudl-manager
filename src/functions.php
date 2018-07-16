<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 15:19
 */


function ks_exec($cmd, array $args=[], $returnArray=false)
{
    $cmd = preg_replace_callback( '/\?|\:[a-z0-9_\-]+/i',
        function ($match) use (&$argsCounter, &$args) {
            if ($match[0] === '?') {
                if(empty($args)){
                    throw new \Exception("Index $argsCounter missing");
                }
                $argsCounter++;
                return escapeshellarg(array_shift($args));
            }
            $key = substr($match[0], 1);
            if (!isset($args[$key])){
                throw new \Exception("Key '$key' not found");
            }
            return escapeshellarg($args[$key]);
        },
        $cmd);
    exec($cmd . " 2>&1", $output, $return);
    if ($return !== 0)
        throw new Exception("Command '$cmd' returned with code $return. " . implode("", $output));
    if ($returnArray)
        return $output;
    return implode("", $output);
}