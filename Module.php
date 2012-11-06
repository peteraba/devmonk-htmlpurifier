<?php

namespace DevmonkHtmlpurifier;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use DevmonkHtmlpurifier\View\Helper\Purify;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface,
    ViewHelperProviderInterface,
    ServiceProviderInterface
{
    const SERVICE_NAME            = 'purifier';
    const CONFIG_KEY_HTMLPURIFIER = 'devmonkhtmlpurifier';
    const CONFIG_KEY_CONFIG       = 'config';
    const HTMLPURIFIER_PREFIX     = 'HTMLPURIFIER_PREFIX';

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader'       => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    'HTMLPurifier' => __DIR__ . '/src/HTMLPurifier/library/HTMLPurifier',
                    __NAMESPACE__  => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public static function setConstants()
    {
        if (!defined(self::HTMLPURIFIER_PREFIX)) {
            define(self::HTMLPURIFIER_PREFIX, __DIR__ . '/src/HTMLPurifier/library');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        self::setConstants();
        
        $app    = $e->getApplication();
        $config = $app->getServiceManager()->get('Config');

        $purifierConfig = \HTMLPurifier_Config::createDefault();
        if (!empty($config[self::CONFIG_KEY_HTMLPURIFIER][self::CONFIG_KEY_CONFIG])) {
            foreach ($config[self::CONFIG_KEY_HTMLPURIFIER][self::CONFIG_KEY_CONFIG] as $configKey => $configValue) {
                $purifierConfig->set($configKey, $configValue);
            }
        }
        $purifier = new \HTMLPurifier($purifierConfig);

        $app->getServiceManager()->setService(self::SERVICE_NAME, $purifier);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getViewHelperConfig()
    {
        $purifyClosure = function ($sm) {
            $locator = $sm->getServiceLocator();
            return new Purify($locator->get(self::SERVICE_NAME));
        };
        return array(
            'factories' => array(
                'purify' => $purifyClosure,
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
            'aliases'   => array(),
            'factories' => array(
                'TwigEnvironment' => 'DevmonkHtmlpurifier\Service\EnvironmentFactory',
            )
        );
    }
}
