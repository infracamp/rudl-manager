<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 16:26
 */

namespace RudlManager\Mod;



use Phore\Dba\PhoreDba;

use Phore\FileSystem\PhoreFile;
use Phore\MicroApp\App;
use RudlManager\Docker\DockerCmd;
use RudlManager\Repo\GitRepo;

/**
 * Class KSApp
 *
 * @package KH\Plugin
 * @property-read GitRepo $confRepo
 * @property-read PhoreFile $confFile
 * @property-read PhoreDba $db
 * @property-read DockerCmd $dockerCmd
 */
class KSApp extends App
{

}