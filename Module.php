<?php
namespace ZingersCrossed;

use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $logger = $e->getApplication()->getServiceManager()->get('logger');
        if ($logger instanceof \Zend\Log\Logger) {
            $eventManager = $e->getApplication()->getEventManager();
            $eventManager->attach('finish', array($this, 'checkWrites'), -1000);
        }
    }

    public function checkWrites(\Zend\Mvc\MvcEvent $e)
    {
        $logger = $e->getApplication()->getServiceManager()->get('logger');
        if ($logger instanceof \Zend\Log\Logger) {
            foreach ($logger->getWriters() as $writer) {
                if($writer instanceof \ZingersCrossed\ZingersCrossedWriter) {
                    $writer->checkWrites($logger->getWriters());
                }
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
