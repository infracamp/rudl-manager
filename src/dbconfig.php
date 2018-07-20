<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 15:30
 */

namespace RudlManager;


use OttoDB\Migration\MigrationKernel;
use OttoDB\Migration\MigrationManager;
use OttoDB\Migration\SqliteMigrationRegistry;
use OttoDB\OttoDb;
use RudlManager\Db\Migration\__Migration_1;

MigrationKernel::AddOnMigration(function (OttoDb $ottoDb) {
    $manager = new MigrationManager(new SqliteMigrationRegistry());

    // Change this to the highest Migration:
    $manager->upgrade($ottoDb, new __Migration_1());
});