<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 02.07.18
 * Time: 12:27
 */

namespace RudlManager\Docker;


class DockerCmd
{

    public function dockerExec ($command, array $args, $truncate = false)
    {
        $truncateString = "--no-trunc";
        if ($truncate) {
            $truncateString = "";
        }
        $retArr = ks_exec("sudo docker $command --format '{{json . }}' $truncateString", $args, true);
        $ret = json_decode($json = "[" . implode(",", $retArr) ."]", true);
        if ($ret === null)
            throw new \Exception("Cannot json decode: $json");
        return $ret;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getStacks () {
        $ret = $this->dockerExec("stack ls", [], true);
        return $ret;
    }

    /**
     * @param $stackName
     * @return mixed
     * @throws \Exception
     */
    public function getStackServices ($stackName) {

        $ret = $this->dockerExec("stack services ?", [$stackName], true);
        return $ret;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getServices () {
        $ret = $this->dockerExec("service ls ", [], true);
        return $ret;
    }
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getServiceProcessList ($serviceId) {
        $ret = $this->dockerExec("service ps ?", [$serviceId]);
        return $ret;
    }


    /**
     * @param $serviceId
     * @return mixed
     * @throws \Exception
     */
    public function getServiceLogs ($serviceId) {
        $ret = ks_exec("sudo docker service logs ? ", [$serviceId], false);
        return $ret;
    }


}