<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 17:43
 */

namespace RudlManager\Db;



use Phore\Dba\Entity\Entity;

class CloudFrontService
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
     * @var string  STATIC|DYNAMIC
     */
    public $source;

    /**
     * @var string LETSENCRYPT|NONE|STATIC
     */
    public $cert_type = "NONE";


    /**
     * Switch to SSL if cert is available and valid.
     *
     * @var int
     */
    public $auto_upgrade_ssl = 0;

    public $cert_id;
    public $cert_data;
    public $cert_valid_from;
    public $cert_valid_till;
    public $cert_status;
    public $cert_error_msg;
}