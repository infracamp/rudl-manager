<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 6/12/18
 * Time: 1:23 AM
 */


namespace RudlManager\Docker;


class DockerCmd {



    public function exec ($command) {
        exec("sudo docker $command --no-trunc --format '{{json . }}'", $out, $ret);
        if ($ret != 0)
            throw new \Exception("invalid return value $ret: " . implode (" ", $out));
        $ret = json_decode("[" . implode(",", $out) ."]", true);
        if ($ret === false)
            throw new \Exception("Cannot json decode: $out");
        return $ret;

    }




}