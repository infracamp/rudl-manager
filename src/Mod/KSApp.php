<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 16:26
 */

namespace RudlManager\Mod;


use KH\Docker\DockerCmd;
use KH\Repo\GitRepo;
use OttoDB\OttoDb;
use Phore\MicroApp\App;

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