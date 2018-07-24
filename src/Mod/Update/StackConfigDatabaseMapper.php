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
use RudlManager\Helper\IdDiffToolProcessor;

class StackConfigDatabaseMapper implements ConfigDatabaseMapper, IdDiffToolProcessor
{
    /**
     * @var PhoreDba
     */
    private $db;

    /**
     * @var PhoreFile
     */
    private $configFile;

    public function __construct(PhoreDba $db, PhoreFile $configFile)
    {
        $this->db = $db;
        $this->configFile = $configFile;
    }


    public function update(array $config)
    {
        $oldIds = [];
        $this->db->query("SELECT * FROM Stack WHERE source='STATIC'")->each(function(array $row) use (&$oldIds) {
            $oldIds[$row["stackName"]] = $row;
        });

        $newIds = [];
        foreach ($config["stack"] as $key => $data) {
            $data["stackConfig"] = $this->configFile->withSubPath($data["config"])->assertFile()->get_contents();
            $data["stackName"] = $key;
            $newIds[$key] = $data;
        }

        $updater = new IdDiffTool();
        $updater
            ->onNew([$this, "newElement"])
            ->onDelete([$this, "deletedElement"])
            ->onModified([$this, "modifiedElement"])
            ->onUnmodified([$this, "unmodifiedElement"]);
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
    private function newElement($key, $data)
    {
        $stack = Stack::Cast($data);
        $stack->stackName = $key;
        $stack->source = "STATIC";
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
    private function modifiedElement(
        $key,
        $oldData,
        $newData,
        array $changedKeys
    ) {
        $stack = Stack::Load(["stackName"=>$key]);
        $stack->stackName = $key;

    }

    private function unmodifiedElement($key, $data)
    {
    }

    private function deletedElement($key, $oldData)
    {
        $stack = Stack::Load(["stackName"=>$key]);
        $this->db->delete($stack);
    }
}