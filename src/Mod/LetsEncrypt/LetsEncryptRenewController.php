<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.07.18
 * Time: 08:11
 */

namespace RudlManager\Mod\LetsEncrypt;


use OttoDB\OttoDb;
use Phore\MicroApp\App;
use Phore\MicroApp\Controller\Controller;
use Phore\MicroApp\Type\QueryParams;
use Phore\MicroApp\Type\Request;
use Phore\MicroApp\Type\Route;
use Phore\MicroApp\Type\RouteParams;
use RudlManager\Db\CloudFrontDomain;
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
     * @param CloudFrontDomain $domain
     * @return bool
     */
    protected function isValidDomain($domain) :bool
    {
        $url = "http://{$domain->domain}/.well-known/acme-challenge/verify-host-resolve";
        $sha1 = sha1(gethostname());
        try {
            $domainSha1 = file_get_contents($url);
        } catch (\ErrorException $e) {
            $domainSha1 = null;
        }
        if ($sha1 != $domainSha1) {
            $this->log->log("CloudFrontDomain '{$domain->domain}' is not valid: {$url} return invalid sha hostname: '{$domainSha1}'");
            return false;
        }
        return true;
    }

    /**
     * @param CloudFrontDomain[] $domains
     * @return array
     * @throws \Exception
     */
    protected function requestCert($domains)
    {
        $domainNames = [];
        foreach ($domains as $curDomain) {
            $domainNames[] = $curDomain->domain;
        }

        phore_exec("rm -R :workdir",  ["workdir" => LetsEncryptModule::LE_WORK_DIR]);

        phore_exec(
            "certbot certonly --work-dir :workdir --logs-dir :workdir --config-dir :workdir --webroot -w :webroot --register-unsafely-without-email --agree-tos -d :domains",
            [
                "webroot" => LetsEncryptModule::CHALLENGE_ROOT_DIR,
                "workdir" => LetsEncryptModule::LE_WORK_DIR,
                "domains" => implode (",", $domainNames)
            ]);

        $crtData = file_get_contents(LetsEncryptModule::LE_WORK_DIR . "/live/{$domains[0]->domain}/fullchain.pem");
        $crtData .= file_get_contents(LetsEncryptModule::LE_WORK_DIR . "/live/{$domains[0]->domain}/privkey.pem");

        $crtMeta = openssl_x509_parse($crtData);
        if ($crtMeta === false) {
            throw new \Exception("Invalid x509 cert data." . openssl_error_string());
        }
        return ["crtMeta" => $crtMeta, "crtData" => $crtData, "log" => $this->log->logs];
    }

    /**
     * @param CloudFrontDomain[] $domainList
     * @return CloudFrontDomain[]
     */
    protected function cleanupDomainList($domainList)
    {
        $ret = [];
        foreach ($domainList as $domain) {
            $this->log->log("Verifying domain '{$domain->domain}' is linked to cloudfront");
            if ( ! $this->isValidDomain($domain)) {
                $this->log->log("Warning: CloudFrontDomain '{$domain->domain}' removed from domain list");
                continue;
            }
            $ret[] = $domain;
        }
        if (count($ret) === 0) {
            throw new \InvalidArgumentException("Error: No domains to request or renew certificates for...");
        }
        return $ret;
    }

    /**
     * @param $serviceId
     * @return CloudFrontDomain[]
     */
    protected function getDomainListForService($serviceId)
    {
        $domains = $this->app->db->query("SELECT * FROM CloudFrontDomain WHERE serviceId = :sid", ["sid" => $serviceId])
            ->all(CloudFrontDomain::class);

        if (count($domains) === 0) {
            throw new \InvalidArgumentException("Service '{$serviceId}' has no domains!");
        }

        return $domains;
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
        $domains = $this->getDomainListForService($serviceId);
        $domains = $this->cleanupDomainList($domains);
        return $this->requestCert($domains);
    }

}