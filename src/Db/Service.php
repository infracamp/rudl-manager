<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 10:48
 */

namespace RudlManager\Db;



use Phore\Dba\Entity\Entity;

class Service
{
    use Entity;

    const __META__ = [
        "primaryKey" => "serviceId"
    ];

    public $serviceName;

    public $source;

    public $serviceConfig;

    public $updateKey;

}