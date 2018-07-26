<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 6/12/18
 * Time: 1:22 AM
 */

namespace App;

use Phore\MicroApp\Handler\JsonExceptionHandler;
use Phore\MicroApp\Handler\JsonResponseHandler;
use RudlManager\Helper\Log;
use RudlManager\Mod\CloudFront\CloudFrontModule;
use RudlManager\Mod\DockerApi\DockerApiMod;
use RudlManager\Mod\KSApp;
use RudlManager\Mod\LetsEncrypt\LetsEncryptModule;
use RudlManager\Mod\Setup\SetupModule;
use RudlManager\Mod\Update\UpdateModule;

require __DIR__ . "/../vendor/autoload.php";


$app = new KSApp();
$app->acl->addRule(aclRule()->route("/")->ALLOW());
$app->acl->addRule(aclRule()->route("/hooks/*")->ALLOW());
$app->acl->addRule(aclRule()->route("/api/*")->ALLOW());

$app->activateExceptionErrorHandlers();
$app->setResponseHandler((new JsonResponseHandler())->addFilter(function (array $data) {
    $data["log"] = Log::Get()->logs;
    return $data;
}));
$app->setOnExceptionHandler((new JsonExceptionHandler())->addFilter(function (array $data) {
    $data["log"] = Log::Get()->logs;
    return $data;
}));

// Add Modules below
$app->addModule(new SetupModule());
$app->addModule(new UpdateModule());
$app->addModule(new LetsEncryptModule());
$app->addModule(new CloudFrontModule());
$app->addModule(new DockerApiMod());


$app->serve();