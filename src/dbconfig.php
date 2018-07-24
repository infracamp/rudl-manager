<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 15:30
 */

namespace RudlManager;


use Phore\Dba\PhoreDba;
use Phore\DbaMigrations\MigrationKernel;
use Phore\DbaMigrations\MigrationManager;
use Phore\DbaMigrations\Registry\SqliteMigrationRegistry;
use RudlManager\Db\Migration\__Migration_1;

MigrationKernel::AddOnMigration(function (PhoreDba $dba) {
    $manager = new MigrationManager(new SqliteMigrationRegistry());

    // Change this to the highest Migration:
    $manager->upgrade($dba, new __Migration_1());
});