<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 12:08
 */

namespace RudlManager\Mod\Update;


interface ConfigDatabaseMapper
{

    public function update(array $config);

}