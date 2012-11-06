# DevmonkHtmlpurifier

DevmonkHtmlpurifier is a module that integrates the [HTMLPurifier](http://htmlpurifier.org/) filter library with
[Zend Framework 2](http://framework.zend.com) and optionally the [Twig](http://twig.sensiolabs.org) templating engine.


## Installation

 1. Add `"devmonk/htmlpurifier": "dev-master"` to your `composer.json` file and run `php composer.phar update`.
 2. Add `DevmonkHtmlpurifier` to your `config/application.config.php` file under the `modules` key.


## Configuration

DevmonkHtmlpurifier has sane defaults out of the box but offers optional configuration via the `devmonk-htmlpurifier` configuration key.

    `config` - passed directly to the HTMLPurifier class.
             - Added `Cache.SerializerPath` and set the default cache folder to data/cache/htmlpurifier
               Active by default.


## Documentation

### View Helpers

DevmonkHtmlpurifier adds a view helper called 'purify' that will use the htmlpurifier with the set options.

### Twig Filters

DevmonkHtmlpurifier adds a filter called 'purify' for twig templates if zf-commons/zfc-twig is installed

### Namespaces

The module supports [namespaces](http://twig.sensiolabs.org/doc/api.html#built-in-loaders) which can be configured using the `namespaces` configuration key:

    'zfctwig' => array(
        'namespaces' => array(
            'admin'     => __DIR__ . '/../views/admin',
            'frontend'  => __DIR__ . '/../views/frontend',
        ),
    ),

When using a namespace the views will only be resolved to the specified namespace folder and not fallback to the View Manager resolver


## Known issues

DevmonkHtmlpurifier does not support using multiple purifiers at the moment.