<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 16.07.18
 * Time: 15:59
 */

namespace RudlManager\Mod\Update;


use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;

class UpdateModule implements AppModule {

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
        $app->router->get("/api/v1/update", function () use ($app) {
            $app->confRepo->gitPull();
            $app->triggerEvent("conf-update");
            return ["success"=>"true"];
        });

        $app->onEvent("conf-update", [CloudFrontServiceDatabaseMapper::class, "Run"]);
        $app->onEvent("conf-update", [StackConfigDatabaseMapper::class, "Run"]);
    }

}
