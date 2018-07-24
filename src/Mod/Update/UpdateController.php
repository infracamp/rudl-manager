<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 16.07.18
 * Time: 16:01
 */

namespace RudlManager\Mod\Update;


use Phore\MicroApp\Controller\Controller;
use RudlManager\Db\CloudFrontDomain;
use RudlManager\Db\CloudFrontService;
use RudlManager\Mod\KSApp;

/**
 * Class UpdateController
 * @package RudlManager\Mod\Update
 * @property KSApp $app
 */
class UpdateController {
    use Controller;


    /**
     * @return array
     */
    public function on_get() :array
    {
        $this->app->confRepo->gitPull();
        return ["success"=>true, "msg"=>"clone of config-directory successful"];
    }


    protected function updateCloudFrontServices()
    {
        $cloudfront = $this->app->confFile->get_yaml()["cloudfront"];
        $db = $this->app->db;

        $savedServices = [];
        $db->query("SELECT * FROM CloudFrontService")->each(function(array $row) use(&$savedServices) {
            $curService = CloudFrontService::Cast($row);
            $savedServices[$curService->serviceId] = $curService;
        });

        foreach ($cloudfront as $service) {
            $serviceId = $service["service"];

            if (isset($savedServices[$serviceId])) {
                unset($savedServices[$serviceId]);

            } else {
                $cloudfrontService = new CloudFrontService();
                $cloudfrontService->serviceId = $serviceId;
                $db->insert($cloudfrontService);
            }

            $savedDomains = [];
            $db->query("SELECT * FROM CloudFrontDomain WHERE serviceId = :serviceId", ["serviceId" => $serviceId])
                ->each(function (array $row) use (&$savedDomains) {
                    $curDomain = CloudFrontDomain::Cast($row);
                    $savedDomains[$curDomain->domain] = $curDomain;
                }
            );

            foreach ($service["domains"] as $curDomain) {
                if (isset($savedDomains[$curDomain])) {
                    unset ($savedDomains[$curDomain]);

                } else {
                    $domain = new CloudFrontDomain();
                    $domain->domain = $curDomain;
                    $domain->serviceId = $serviceId;
                    $db->insert($domain);
                }
            }

            // delete orphaned domains
            foreach ($savedDomains as $domain => $obj) {
                $db->delete($obj);
            }
        }

        // delete orphaned services
        foreach ($savedServices as $serviceId => $obj) {
            $db->delete($obj);
        }

    }
}
