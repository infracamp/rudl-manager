<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 6/12/18
 * Time: 1:22 AM
 */

require __DIR__ . "/../vendor/autoload.php";

$docker = new \RudlManager\Docker\DockerCmd();
print_r ($docker->exec("ps"));