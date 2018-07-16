<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.07.18
 * Time: 08:11
 */

namespace RudlManager\Mod\LetsEncrypt;


use KH\Mod\KSApp;
use Phore\MicroApp\Controller\Controller;
use Phore\MicroApp\Type\QueryParams;
use Phore\MicroApp\Type\Request;
use Phore\MicroApp\Type\Route;
use Phore\MicroApp\Type\RouteParams;

class LetsEncryptRenewController
{
    use Controller;



    protected function requestCert($serviceId, array $domains)
    {
        ks_exec(
            "certbot certonly --work-dir :workdir --logs-dir :workdir --config-dir :workdir --webroot -w :webroot --register-unsafely-without-email --agree-tos -d :domains",
            [
                "webroot" => LetsEncryptModule::CHALLENGE_ROOT_DIR,
                "workdir" => LetsEncryptModule::LE_WORK_DIR,
                "domains" => implode (",",$domains)
            ]);

        $crtData = file_get_contents(LetsEncryptModule::LE_WORK_DIR . "/live/$domains[0]/fullchain.pem");
        $crtData .= file_get_contents(LetsEncryptModule::LE_WORK_DIR . "/live/$domains[0]/privkey.pem");

        $crtMeta = openssl_x509_parse($crtData);
        if ($crtMeta === false) {
            throw new \Exception("Invalid x509 cert data." . openssl_error_string());
        }
    }



    /**
     * Route: /api/v1/letsencrypt/renew/:serviceId?
     *
     * @param Request     $request
     * @param Route       $route
     * @param RouteParams $routeParams
     * @param QueryParams $GET
     */
    public function on_get(
        Request $request,
        Route $route,
        RouteParams $routeParams,
        QueryParams $GET,
        KSApp $app
    ) {
        $routeParams->
        $serviceDomainList = [];
        foreach ($app->confFile["cloudfront"] as $service) {
            if ( ! isset ($serviceDomainList[$service["service"]]))
                $serviceDomainList[$service["service"]] = [];
            foreach ($service["domains"] as $curDomain) {
                $serviceDomainList[$service["service"]][] = (string)$curDomain;
            }
        }


        app()->outJSON("obtaining ssl-certificates");
    }

}