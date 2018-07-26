<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 16.07.18
 * Time: 15:29
 */

namespace RudlManager\Helper;


class Log {


    public $logs = [];


    public function log($msg, array $backtrace) :self
    {
        $file = basename($backtrace["file"]);
        $this->logs[] = "[$file:{$backtrace["line"]}] " . $msg;
        return $this;
    }

    private static $instance = null;

    public static function Get() : self
    {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }


}
