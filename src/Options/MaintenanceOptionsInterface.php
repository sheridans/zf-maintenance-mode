<?php
namespace zfMaintenanceMode\Options;

interface MaintenanceOptionsInterface 
{
    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled($enabled);

    /**
     * @return array
     */
    public function getAllowedHosts();

    /**
     * @param array $array list of allowed hosts
     * @return $this
     */
    public function setAllowedHosts($array);
}