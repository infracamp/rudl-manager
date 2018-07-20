<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 29.06.18
 * Time: 16:40
 */

namespace RudlManager\Mod\LetsEncrypt;


class CertData
{

    public $id;
    public $domain;
    public $aliasDomains;

    public $crtData;

    public $firstSeen;
    public $lastSeen;

    public $validTo;
    public $validFrom;

    public $status;

    public $lastFail;
    public $lastFailMsg;

}