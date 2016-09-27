<?php
namespace zfMaintenanceMode;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use zfMaintenanceMode\Options;

use Zend\Console\Request as ConsoleRequest;

/**
 * Class Module
 * @package zfMaintenanceMode
 */
class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface
{
    /**
     * Attach maintenance functions during bootstrap.
     * 
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $eventManager   = $event->getApplication()->getEventManager();

        /* @var Options\MaintenanceOptionsInterface $options */
        $options = $serviceManager->get(Options\ModuleOptions::class);

        if ($options->isEnabled() && !$event->getRequest() instanceof ConsoleRequest) {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'isAllowed'), 10);
            $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'addBanner'), 10);
        }
    }

    /**
     * Function is attached if maintenance mode module is active and displays a banner indicating so.
     * 
     * @param MvcEvent $event
     */
    public function addBanner(MvcEvent $event)
    {
        /* save and clear child views from template */
        $view = $event->getViewModel();
        $children = $view->getChildren();
        $view->clearChildren();

        /* use maintenance banner template */
        $wrapper = new ViewModel();
        $wrapper->setTemplate('layout/banner');
        $wrapper->addChild($children[0], 'content');

        /* add children back */
        $view->addChild($wrapper, 'content');
        $event->setViewModel($view);
    }

    /**
     * If maintenance mode is active, checks to see if we are allowed to continue.
     * If not the maintenance (503) error page is displayed.
     *
     * @param MvcEvent $event
     * @return bool|\Zend\Stdlib\ResponseInterface
     */
    public function isAllowed(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        /* @var Options\MaintenanceOptionsInterface $options */
        $options = $serviceManager->get(Options\ModuleOptions::class);

        /* check if IP address is allowed */
        $ip = $_SERVER['REMOTE_ADDR'];
        $allowedAddresses = $options->getAllowedHosts();
        if (in_array($ip,  $allowedAddresses)) {
            return true;
        }

        /* if we get this far, then we are maintenance mode and access is denied */
        $view = new ViewModel();
        $view->setTemplate('layout/maintenance');
        $viewRenderer = $event->getApplication()->getServiceManager()->get('ViewRenderer');
        $html = $viewRenderer->render($view);

        /* send response with 503 headers */
        $response = $event->getResponse();
        $response->setStatusCode(503);
        $response->setContent($html);
        $event->stopPropagation();
        return $response;
    }

    /**
     * @inheritdoc
     */
    public function getAutoloaderConfig()
    {
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
}