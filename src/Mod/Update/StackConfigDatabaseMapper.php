<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 12:09
 */

namespace RudlManager\Mod\Update;


use OttoDB\OttoDb;

class StackConfigDatabaseMapper implements ConfigDatabaseMapper
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


    }
}