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


    public function log($msg) :self
    {
        $this->logs[] = $msg;
        return $this;
    }


}
