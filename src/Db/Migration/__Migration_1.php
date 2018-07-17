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

        $db->query('CREATE TABLE CloudFrontService (
            serviceId TEXT PRIMARY KEY,
            source TEXT CHECK (source IN (\'STATIC\', \'DYNAMIC\')) NOT NULL,
            
            cert_type TEXT CHECK(cert_type IN (\'LETSENCRYPT\', \'NONE\', \'STATIC\')) NOT NULL,
            auto_upgrade_ssl INTEGER NOT NULL DEFAULT \'0\',
            
            cert_data BLOB,
            cert_valid_from TEXT,
            cert_valid_till TEXT,
            cert_status TEXT,
            cert_error_msg TEXT
        )');

        $db->query('CREATE TABLE CloudFrontDomain (
            domain TEXT PRIMARY KEY,
            serviceId TEXT NOT NULL,
            FOREIGN KEY (serviceId) REFERENCES CloudFrontDomain(serviceId) 
              ON UPDATE CASCADE
              ON DELETE CASCADE
        )');

        $db->query('CREATE TABLE Stack (
            stackName TEXT PRIMARY KEY,
            source TEXT CHECK (source IN (\'STATIC\', \'DYNAMIC\')) NOT NULL,
            stackConfig TEXT,
            updateKey TEXT
        )');

        $db->query('CREATE TABLE Service (
            serviceName TEXT PRIMARY KEY,
            source TEXT CHECK (source IN (\'STATIC\', \'DYNAMIC\')) NOT NULL,
            serviceConfig TEXT,
            updateKey TEXT
        )');

        $db->query('CREATE TABLE Log (
            logId TEXT PRIMARY KEY,
            logDate TEXT NOT NULL,
            logType TEXT NOT NULL,
            logSystem TEXT,
            severity INTEGER,
            logMessage TEXT       
        )');
    }

    public function down(OttoDb $db)
    {
        $db->query("DROP TABLE CloudFrontDomain;");
        $db->query("DROP TABLE CloudFrontService;");
    }

    public function getPredecessor(): Migration
    {
        return new InitialMigration();
    }
}