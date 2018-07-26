<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 12:09
 */

namespace RudlManager\Mod\Update;



use Phore\Dba\PhoreDba;
use Phore\FileSystem\PhoreFile;
use RudlManager\Db\Stack;
use RudlManager\Helper\IdDiffTool;

use RudlManager\Mod\KSApp;

class StackConfigDatabaseMapper implements ConfigDatabaseMapper
{
    /**
     * @var PhoreDba
     */
    private $db;

    /**
     * @var PhoreFile
     */
    private $configFile;

    /**
     * @var KSApp
     */
    private $app;

    public function __construct(PhoreDba $db, PhoreFile $configFile, KSApp $app)
    {
        $this->db = $db;
        $this->configFile = $configFile;
        $this->app = $app;
    }


    public function doUpdate(array $config)
    {
        $oldIds = [];
        $this->db->query("SELECT * FROM Stack WHERE source='STATIC'")->each(function(array $row) use (&$oldIds) {
            $oldIds[$row["stackName"]] = $row;
        });

        $newIds = [];
        foreach ($config["stacks"] as $key => $data) {
            $data["stackConfig"] = $this->configFile->withSubPath($data["config"])->assertFile()->get_contents();
            $data["stackName"] = $key;
            $newIds[$key] = $data;
        }

        $updater = new IdDiffTool();
        $updater
            ->onNew([$this,     "newElement"])
            ->onDelete([$this,  "deletedElement"])
            ->onModified([$this,    "modifiedElement"])
            ->onUnmodified([$this,  "unmodifiedElement"]);

        $updater->process($newIds, $oldIds);

    }

    /**
     * Called once for every new element
     *
     * @param $key
     * @param $data
     *
     * @return mixed
     */
    public function newElement($key, $data)
    {
        //$stack = Stack::Load($data);
        $stack = new Stack();
        $stack->stackName = $key;
        $stack->source = "STATIC";
        $stack->stackConfig = $data["config"];

        logInfo("Insert stack: $key");
        $this->db->insert($stack);
    }

    /**
     * Called once for each modified element
     *
     * @param       $key
     * @param       $oldData
     * @param       $newData
     * @param array $changedKeys
     *
     * @return mixed
     */
    public function modifiedElement(
        $key,
        $oldData,
        $newData,
        array $changedKeys
    ) {
        $stack = Stack::Load(["stackName"=>$key]);
        $stack->stackName = $key;
        $stack->stackConfig = $newData["stackConfig"];


        //$this->app->dockerCmd->stackDeploy($stack->stackName, $stack->stackConfig);

        logInfo("Update stack: $key");
        $this->db->update($stack);
    }

    public function unmodifiedElement($key, $data)
    {
    }

    public function deletedElement($key, $oldData)
    {
        $stack = Stack::Load(["stackName"=>$key]);
        logInfo("Delete stack: $key");
        //$this->app->dockerCmd->stackRm($stack->stackName);
        $this->db->delete($stack);
    }


    public static function Update(KSApp $app)
    {
        $p = new self($app->db, $app->confFile, $app);
        $p->doUpdate($app->confFile->get_yaml());
    }

    public static function Startup(KSApp $app)
    {
        $app->db->query("SELECT stackName FROM Stack WHERE source='STATIC'")->each(function(array $row) use ($app){
            $stack = Stack::Load($row["stackName"]);
            $app->dockerCmd->stackDeploy($stack->stackName, $stack->stackConfig);
        });
    }

}