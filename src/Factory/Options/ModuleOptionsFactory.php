<?php
namespace zfMaintenanceMode\Factory\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use zfMaintenanceMode\Options;

class ModuleOptionsFactory implements AbstractFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function canCreate(ContainerInterface $container, $requestName)
    {
        return class_exists($requestName);
    }

    /**
     * @inheritdoc
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $services, $name, $requestName)
    {
        return $this->canCreate($services, $requestName);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        return new Options\ModuleOptions(isset($config['zfMaintenanceMode']) ? $config['zfMaintenanceMode'] : array());
    }
}