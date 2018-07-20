<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 10:05
 */

namespace RudlManager\Db;


class Stack
{

    const __META__ = [
        "primaryKey" => "stackName"
    ];

    public $stackName;

    public $source;

    public $stackConfig;

    public $updateKey;

}