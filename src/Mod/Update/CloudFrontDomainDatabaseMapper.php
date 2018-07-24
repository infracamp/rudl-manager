<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 17.07.18
 * Time: 12:41
 */

namespace RudlManager\Mod\Update;



use Phore\Dba\PhoreDba;
use RudlManager\Db\CloudFrontDomain;

class CloudFrontDomainDatabaseMapper implements ConfigDatabaseMapper
{

    /**
     * @var PhoreDba
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
     * @param PhoreDba $db
     * @param string $serviceId
     */
    public function __construct(PhoreDba $db, $serviceId)
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
        $this->db->query("SELECT domain FROM CloudFrontDomain WHERE serviceId = ?", [$serviceId])
            ->each(function (array $row) {
                $curDomain = CloudFrontDomain::Load($row["domain"]);
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
