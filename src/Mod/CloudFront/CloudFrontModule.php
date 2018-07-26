<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 10:28
 */

namespace RudlManager\Mod\CloudFront;



use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;
use RudlManager\Db\CloudFrontService;
use RudlManager\Mod\KSApp;

class CloudFrontModule implements AppModule
{

    /**
     * Called just after adding this to a app by calling
     * `$app->addModule(new SomeModule());`
     *
     * Here is the right place to add Routes, etc.
     *
     * @param App $app
     *
     * @return mixed
     */
    public function register(App $app)
    {
        $app->router->get("/api/v1/cloudfront/get_config", function (KSApp $app) {
            $sql = "SELECT s.serviceId as sid, d.domain as dom 
                            FROM CloudFrontService AS s
                            LEFT JOIN CloudFrontDomain  AS d
                              ON s.serviceId=d.serviceId 
                            ORDER BY s.serviceId, domain";
            $ret = [
                "services" => []
            ];
            $app->db->query($sql)->each(function (array $row) use (&$ret) {
                if (changed($sid = $row["sid"])) {
                    $s = CloudFrontService::Load($sid);
                    $s->domains = [];
                    $ret["services"][] = $s;
                }

                if (changed($domain = $row["dom"])) {
                    $s->domains[] = $domain;
                }

            });
            return $ret;
        });
    }
}