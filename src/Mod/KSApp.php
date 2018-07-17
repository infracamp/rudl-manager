<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 16:26
 */

namespace RudlManager\Mod;


use OttoDB\OttoDb;
use Phore\MicroApp\App;
use RudlManager\Docker\DockerCmd;
use RudlManager\Repo\GitRepo;

/**
 * Class KSApp
 *
 * @package KH\Plugin
 * @property-read GitRepo $confRepo
 * @property-read array $confFile
 * @property-read OttoDb $db
 * @property-read DockerCmd $dockerCmd
 */
class KSApp extends App
{

}