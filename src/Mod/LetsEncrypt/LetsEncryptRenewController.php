<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.07.18
 * Time: 08:11
 */

namespace RudlManager\Mod\LetsEncrypt;


use Phore\MicroApp\App;
use Phore\MicroApp\Controller\Controller;
use Phore\MicroApp\Type\QueryParams;
use Phore\MicroApp\Type\Request;
use Phore\MicroApp\Type\Route;
use Phore\MicroApp\Type\RouteParams;
use RudlManager\Helper\Log;
use RudlManager\Mod\KSApp;


/**
 * Class LetsEncryptRenewController
 * @package RudlManager\Mod\LetsEncrypt
 * @property KSApp $app
 */
class LetsEncryptRenewController
{
    use Controller;


    protected $log;



    /**
     * @param $domain
     * @return bool
     */
    protected function isValidDomain($domain) :bool
    {
        $url = "http://$domain/.well-known/acme-challenge/verify-host-resolve";
        $sha1 = sha1(gethostname());
        $domainSha1 = file_get_contents($url);
        if ($sha1 != $domainSha1) {
            $this->log->log("Domain '{$domain}' is not valid: {$url} return invalid sha hostname: '{$domainSha1}'");
            return false;
        }
        return true;
    }

    protected function requestCert($serviceId, array $domains)
    {

        ks_exec("rm -R :workdir",  ["workdir" => LetsEncryptModule::LE_WORK_DIR]);

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
        return ["crtMeta" => $crtMeta, "crtData" => $crtData, "log" => $this->log->logs];
    }

    protected function cleanupDomainList(array $domainList) :array
    {
        $ret = [];
        foreach ($domainList as $domain) {
            $this->log->log("Verifying domain '{$domain}' is linked to cloudfront");
            if ( ! $this->isValidDomain($domain)) {
                $this->log->log("Warning: Domain '{$domain}' removed from domain list");
                continue;
            }
            $ret[] = $domain;
        }
        return $ret;
    }

    /**
     * @param $serviceId
     * @return string[]
     */
    protected function getDomainListForService($serviceId)
    {
        $cloudfront = $this->app->confFile["cloudfront"];
        $foundService = null;
        for ($i = 0; $i < count($cloudfront); $i++) {
            if ($cloudfront[$i]["service"] == $serviceId) {
                $foundService = $cloudfront[$i];
                break;
            }
        }

        if ($foundService == null) {
            throw new \InvalidArgumentException("Service '{$serviceId}' not found in config yml!");
        }

        return $foundService["domains"];
    }

    /**
     * Route: /api/v1/letsencrypt/renew/:serviceId?
     *
     * @param Request     $request
     * @param Route       $route
     * @param RouteParams $routeParams
     * @param QueryParams $GET
     */
    public function on_get(RouteParams $routeParams)
    {
        $this->log = new Log();
        $serviceId = $routeParams->get("serviceId", new \InvalidArgumentException("Service ID not found in route params."));
        $domainList = $this->getDomainListForService($serviceId);
        $domainList = $this->cleanupDomainList($domainList);
        return $this->requestCert($serviceId, $domainList);
    }

}