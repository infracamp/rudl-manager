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
            $ret = [];
            foreach ($app->confFile["cloudfront"] as $curService) {
                $ret[] = $curService;
            }
            return $ret;
        });
    }
}