<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 12:08
 */

namespace RudlManager\Mod\Update;


use RudlManager\Mod\KSApp;

interface ConfigDatabaseMapper
{

    public static function Update(KSApp $app);

    public static function Startup(KSApp $app);

}