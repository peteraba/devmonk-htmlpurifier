<?php

namespace DevmonkHtmlpurifier\Twig;

use DevmonkHtmlpurifier\Module;
use Twig_Filter_Function;
use Zend\ServiceManager\ServiceLocatorInterface;

class Filter extends Twig_Filter_Function
{
    /** @var ServiceLocatorInterface */
    protected static $serviceLocator;

    public function __construct()
    {
        parent::__construct('DevmonkHtmlpurifier\Twig\Filter::purify', array('is_safe' => array('all')));
    }

    public static function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        static::$serviceLocator = $serviceLocator;
    }

    public static function purify($dirtyHtml)
    {
        return static::$serviceLocator->get(Module::SERVICE_NAME)->purify($dirtyHtml);
    }
}
