<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 15:24
 */

namespace RudlManager\Db\Migration;


use OttoDB\Migration\InitialMigration;
use OttoDB\Migration\Migration;
use OttoDB\OttoDb;

class __Migration_1 implements Migration
{

    public function getVersion(): int
    {
        return 1;
    }

    public function up(OttoDb $db)
    {
        $db->query('CREATE TABLE Domain (
            domain TEXT PRIMARY KEY,
            serviceId TEXT
        )');

        $db->query('CREATE TABLE Service (
            serviceId TEXT PRIMARY KEY,
            source TEXT,
            cert_data BLOB,
            cert_valid_from TEXT,
            cert_valid_till TEXT
            cert_status TEXT,
            cert_error_msg BLOB
        )');
    }

    public function down(OttoDb $db)
    {
        $db->query("DROP TABLE Domain;");
        $db->query("DROP TABLE Service;");
    }

    public function getPredecessor(): Migration
    {
        return new InitialMigration();
    }
}