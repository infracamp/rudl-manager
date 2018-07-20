<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 17.07.18
 * Time: 12:41
 */

namespace RudlManager\Mod\Update;


use OttoDB\OttoDb;
use RudlManager\Db\CloudFrontDomain;

class CloudFrontDomainDatabaseMapper implements ConfigDatabaseMapper
{

    /**
     * @var OttoDb
     */
    private $db = null;

    /**
     * @var string
     */
    private $serviceId = null;

    /**
     * @var CloudFrontDomain[]
     */
    private $orphanedDomains = [];


    /**
     * CloudFrontDomainDatabaseMapper constructor.
     * @param OttoDb $db
     * @param string $serviceId
     */
    public function __construct(OttoDb $db, $serviceId)
    {
        $this->db = $db;
        $this->serviceId = $serviceId;
    }

    /**
     * @param array $config
     * @return $this
     * @throws \Exception
     */
    public function update(array $config)
    {
        $this->loadSavedDomains();

        foreach ($config as $domain) {
            if (isset ($this->orphanedDomains[$domain])) {
                $curDomain = $this->orphanedDomains[$domain];
                unset($this->orphanedDomains[$domain]);

                // nothing to update...

            } else {
                $curDomain = new CloudFrontDomain();
                $curDomain->domain = $domain;
                $curDomain->serviceId = $this->serviceId;
                $this->db->insert($curDomain);
            }
        }

        $this->deleteOrphanedDomains();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function loadSavedDomains()
    {
        $serviceId = $this->serviceId;
        $this->db->query("SELECT * FROM CloudFrontDomain WHERE serviceId = :serviceId", ["serviceId" => $serviceId])
            ->each(function (array $row) {
                $curDomain = CloudFrontDomain::Cast($row);
                $this->orphanedDomains[$curDomain->domain] = $curDomain;
            }
        );
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function deleteOrphanedDomains()
    {
        foreach ($this->orphanedDomains as $domain => $obj) {
            $this->db->delete($obj);
        }
        return $this;
    }

}
