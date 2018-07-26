<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 16:11
 */

namespace RudlManager\Mod\Setup;


use Phore\Dba\PhoreDba;
use Phore\DbaMigrations\MigrationKernel;
use Phore\Di\Container\Producer\DiService;
use Phore\Di\Container\Producer\DiValue;
use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;
use Phore\MicroApp\Type\RouteParams;
use RudlManager\Docker\DockerCmd;
use RudlManager\Helper\Log;
use RudlManager\Mod\KSApp;
use RudlManager\Repo\GitRepo;

class SetupModule implements AppModule
{
    const SQLITE_DBFILE = "sqlite:" . CONF_STORAGE_PATH . "/meta.db3";

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
        if ( ! $app instanceof KSApp)
            return;

        $app->define("confRepo", new DiValue($repo = new GitRepo(CONF_REPO_TARGET)));

        $repo->setOrigin(CONF_REPO_URI);
        if (CONF_REPO_SSH_PRIVATEKEY !== "")
            $repo->setAuthSshPrivateKey(CONF_REPO_SSH_PRIVATEKEY);

        $app->define("confFile", new DiService(function () {
            return phore_file(CONF_REPO_TARGET . CONF_REPO_CONF_FILE)->assertFile()->assertReadable();
        }));

        $app->define("db", new DiService(function () {
            return PhoreDba::InitDSN(self::SQLITE_DBFILE);
        }));

        $app->define("dockerCmd", new DiService(function (){
            return new DockerCmd();
        }));

        $app->router->get("/hooks/container-start", function () use ($app, $repo) {
            logInfo("/hooks/container-start called.");
            MigrationKernel::RunMigrations($app->db);

            if ( ! $repo->isCloned()) {
                logInfo("cloning repository");
                $repo->gitClone();
            }
            logInfo("trigger event: conf-startup");
            $app->triggerEvent("conf-startup");

            logInfo("pulling changes");
            $repo->gitPull();

            logInfo("trigger event: conf-update");
            $app->triggerEvent("conf-update");

            return ["success"=>true, "msg"=>"clone of config-directory successful", "log" => Log::Get()->logs];
        });

        if ($repo->isCloned())
            return;
        $app->serve();
        exit;
    }
}