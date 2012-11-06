<?php

namespace DevmonkHtmlpurifier\Twig;

use DevmonkHtmlpurifier\Module;
use Twig_Filter_Function;
use Zend\ServiceManager\ServiceLocatorInterface;

class Filter extends Twig_Filter_Function
{
    /** @var ServiceLocatorInterface */
    protected static $serviceLocator;

    /**
     * Constructor for Twig Filter
     */
    public function __construct()
    {
        parent::__construct('DevmonkHtmlpurifier\Twig\Filter::purify', array('is_safe' => array('all')));
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public static function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        static::$serviceLocator = $serviceLocator;
    }

    /**
     * Has to be static call because of twig
     *
     * @param string $dirtyHtml
     *
     * @return string|null
     */
    public static function purify($dirtyHtml)
    {
        $service = static::getService();

        if (!is_object($service) || !$service instanceof \HTMLPurifier) {
            return null;
        }

        return $service->purify($dirtyHtml);
    }

    /**
     * Has to be static call so it can be called from purify
     *
     * @return \HTMLPurifier|null
     */
    protected static function getService()
    {
        if (null == static::$serviceLocator) {
            return null;
        }

        return static::$serviceLocator->get(Module::SERVICE_NAME);
    }
}
