<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 17:43
 */

namespace RudlManager\Db;


use OttoDB\Entity\Entity;

class Service
{
    use Entity;

    const __META__ = [
        "primary-key" => "serviceId"
    ];

    /**
     * Primary Key
     *
     * @var string
     */
    public $serviceId;

    /**
     * @var string
     */
    public $source;


    public $cert_id;
    public $cert_data;
    public $cert_valid_from;
    public $cert_valid_till;
    public $cert_status;
    public $cert_error_msg;
}