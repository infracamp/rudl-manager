<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 17:47
 */

namespace RudlManager\Mod\LetsEncrypt;


use OttoDB\OttoDb;
use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;
use Phore\MicroApp\Type\Route;
use Phore\MicroApp\Type\RouteParams;

class LetsEncryptModule implements AppModule
{

    const CHALLENGE_ROOT_DIR = "/tmp/letsencrypt-www";
    const LE_WORK_DIR = "/tmp/letsencrypt-work";


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
        $app->acl->addRule(aclRule()->route("/.well-known/acme-challenge/*")->ALLOW());

        if ( ! file_exists(self::CHALLENGE_ROOT_DIR))
            mkdir(self::CHALLENGE_ROOT_DIR, 0777, true);

        if ( ! file_exists(self::LE_WORK_DIR))
            mkdir(self::LE_WORK_DIR, 0777, true);

        $app->router->delegate("/api/v1/letsencrypt/renew/:serviceId?", LetsEncryptRenewController::class);

        $app->router->get("/.well-known/acme-challenge/verify-host-resolve", function () {
            echo sha1(gethostname());
            exit;
        });

        $app->router->get("/.well-known/acme-challenge/:param", function (RouteParams $routeParams) {
            $file = $routeParams->get("param");
            if ( ! preg_match("/^[0-9A-Za-z\-\_]+$/", $file))
                throw new \InvalidArgumentException("Invalid letsencrypt challenge filename: '$file'");
            $filename = self::CHALLENGE_ROOT_DIR . "/.well-known/acme-challenge/" . $file;
            if ( ! file_exists($filename))
                throw new \InvalidArgumentException("Acme challenge not found: $filename");
            echo file_get_contents($filename);
            exit;
        });
    }



}