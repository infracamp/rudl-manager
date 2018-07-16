<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 17:43
 */

namespace RudlManager\Db;


use OttoDB\Entity\Entity;

class Domain
{
    use Entity;

    const __META__ = [
        "primary-key" => "domain"
    ];

    public $domain;

    /**
     * Foreign Key of Service::serviceId
     *
     * @var string
     */
    public $serviceId;
}