<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 12:09
 */

namespace RudlManager\Mod\Update;


use OttoDB\OttoDb;
use RudlManager\Db\Stack;
use RudlManager\Helper\IdDiffTool;
use RudlManager\Helper\IdDiffToolProcessor;

class StackConfigDatabaseMapper implements ConfigDatabaseMapper, IdDiffToolProcessor
{
    private $db;

    public function __construct(OttoDb $db)
    {
        $this->db = $db;

    }


    public function update(array $config)
    {
        $oldIds = [];
        $this->db->query("SELECT * FROM Stack")->each(function(array $row) use (&$oldIds) {
            $oldIds[$row["stackName"]] = $row;
        });

        $newIds = [];
        foreach ($config["stack"] as $key => $data) {

        }

        $updater = new IdDiffTool($this);
        $updater->process();



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
    public function modifiedElement(
        $key,
        $oldData,
        $newData,
        array $changedKeys
    ) {
        $stack = Stack::Cast($this->db->load(Stack::class, ["stackName"=>$key]));
        $stack->stackName = $key;

    }

    public function unmodifiedElement($key, $data)
    {
    }

    public function deletedElement($key, $oldData)
    {
        $stack = Stack::Cast($this->db->load(Stack::class, ["stackName"=>$key]));
        $this->db->delete($stack);
    }
}