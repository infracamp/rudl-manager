<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 17.07.18
 * Time: 12:30
 */

namespace RudlManager\Mod\Update;



use Phore\Dba\PhoreDba;
use RudlManager\Db\CloudFrontService;

class CloudFrontServiceDatabaseMapper implements ConfigDatabaseMapper
{
    /**
     * @var PhoreDba
     */
    private $db = null;

    /**
     * @var CloudFrontService[]
     */
    private $orphanedServices = [];


    /**
     * CloudFrontServiceDatabaseMapper constructor.
     * @param PhoreDba $db
     */
    public function __construct(PhoreDba $db)
    {
        $this->db = $db;
    }

    /**
     * @param array $config
     * @return $this
     * @throws \Exception
     */
    public function update(array $config)
    {
        $this->loadSavedServices();

        foreach ($config as $serviceId => $serviceConfig) {
            if (isset ($this->orphanedServices[$serviceId])) {
                $curService = $this->orphanedServices[$serviceId];
                unset ($this->orphanedServices[$serviceId]);

                $curService->cert_type = $serviceConfig["cert_type"];
                $curService->auto_upgrade_ssl = $serviceConfig["auto_upgrade_ssl"];
                $this->db->update($curService);

            } else {
                $curService = new CloudFrontService();
                $curService->serviceId = $serviceId;
                $curService->cert_type = $serviceConfig["cert_type"];
                $curService->auto_upgrade_ssl = $serviceConfig["auto_upgrade_ssl"];
                $this->db->insert($curService);
            }

            $domainMapper = new CloudFrontDomainDatabaseMapper($this->db, $serviceId);
            $domainMapper->update($serviceConfig["domains"]);
        }

        $this->deleteOrphanedServices();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function loadSavedServices()
    {
        $this->db->query("SELECT * FROM CloudFrontService")->each(function(array $row) {
            $curService = CloudFrontService::Cast($row);
            $this->orphanedServices[$curService->serviceId] = $curService;
        });
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function deleteOrphanedServices()
    {
        foreach ($this->orphanedServices as $service => $obj) {
            $this->db->delete($obj);
        }
        return $this;
    }

}