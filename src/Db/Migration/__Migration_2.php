<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 17:04
 */

namespace RudlManager\Db\Migration;


use OttoDB\Migration\Migration;
use OttoDB\OttoDb;

class __Migration_2 implements Migration
{

    public function getVersion(): int
    {
        return 2;
    }

    public function up(OttoDb $db)
    {
        // TODO: Implement up() method.
    }

    public function down(OttoDb $db)
    {
        // TODO: Implement down() method.
    }

    public function getPredecessor(): Migration
    {
        return new __Migration_1();
    }
}