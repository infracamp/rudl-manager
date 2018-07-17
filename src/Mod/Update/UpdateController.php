<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 16.07.18
 * Time: 16:01
 */

namespace RudlManager\Mod\Update;


use Phore\MicroApp\Controller\Controller;
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
        $cloudfront = $this->app->confFile["cloudfront"];
        $db = $this->app->db;

        $savedServices = [];
        $db->query("SELECT * FROM CloudFrontService")->each(function(array $row) use(&$savedServices) {
            $curService = CloudFrontService::Cast($row);
            $savedServices[$curService->serviceId] = $curService->serviceId;
        });

        $requiredServices = [];
        foreach ($cloudfront as $service) {
            $serviceId = $service["service"];
            if (isset($savedServices[$serviceId])) {
                // update existing


                unset($savedServices[$serviceId]);

            } else {
                // create new

            }

        }

    }
}
