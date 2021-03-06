<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 17:43
 */

namespace RudlManager\Db;



use Phore\Dba\Entity\Entity;

class CloudFrontDomain
{
    use Entity;

    const __META__ = [
        "primaryKey" => "domain"
    ];

    public $domain;

    /**
     * Foreign Key of Service::serviceId
     *
     * @var string
     */
    public $serviceId;
}