<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 16.07.18
 * Time: 16:01
 */

namespace RudlManager\Mod\Update;


use Phore\MicroApp\Controller\Controller;
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

        $databaseNMapper = new CloudFrontServiceDatabaseMapper($db);
        $databaseNMapper->update($cloudfront);
        return $this;
    }
}
