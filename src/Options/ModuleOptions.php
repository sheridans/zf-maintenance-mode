<?php
namespace zfMaintenanceMode\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements MaintenanceOptionsInterface
{
    /**
     * @inheritdoc
     */
    protected $__strictMode__ = false;

    /**
     * @var bool
     */
    protected $enabled = false;

    /**
     * @var array
     */
    protected $allowed_hosts = array(
        '127.0.0.1',
        '::1',
    );
    
    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     * @return ModuleOptions
     */
    public function setEnabled($enabled = true)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedHosts()
    {
        return $this->allowed_hosts;
    }

    /**
     * @param array $allowed_hosts
     * @return ModuleOptions
     */
    public function setAllowedHosts($allowed_hosts)
    {
        $this->allowed_hosts = $allowed_hosts;
        return $this;
    }
    
    
    
}