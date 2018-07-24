<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 10:05
 */

namespace RudlManager\Db;



use Phore\Dba\Entity\Entity;

class Stack
{
    use Entity;

    const __META__ = [
        "primaryKey" => "stackName"
    ];

    public $stackName;

    public $source;

    public $stackConfig;

    public $updateKey;

}