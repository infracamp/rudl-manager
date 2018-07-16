<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.07.18
 * Time: 08:01
 */

namespace RudlManager\Mod\DockerApi;


use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;
use Phore\MicroApp\Type\RouteParams;
use RudlManager\Docker\DockerCmd;

class DockerApiMod implements AppModule
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
        $app->router->get("/hooks/docker-ps", function () {
            $docker = new DockerCmd();
            return $docker->dockerExec("ps", []);

        });

        $app->router->get("/hooks/stacks/list", function () use ($app) {
            return $app->dockerCmd->getStacks();
        });

        $app->router->get("/hooks/stack/:name", function (RouteParams $routeParams) use ($app) {
            return $app->dockerCmd->getStackServices($routeParams->get("name"));
        });

        $app->router->get("/hooks/service/list", function (RouteParams $routeParams) use ($app) {
            return $app->dockerCmd->getServices();
        });
    }
}