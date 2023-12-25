**TODO**

* Write AbstractUtils to wrap only methods that can be redefined based on the
  used php framework

Before starting it's greatly recommended to enabled these PHP extensions to improve
UUID generation performances as ramsey/uuid prescribe

* ext-gmp
* ext-bcmath

Add the following key to you environment file, this must point out to the configuration
file for the library
```env
MERCURE_CONFIG_PATH=/path/to/config.php
```

Here is a default template for the configuration file, it must be a file that returns
an associative array

```php
<?php
return [
    'utils' => '\Namespace\To\UtilClass'
    'jwt' => [
        'algo' => 'HS256',
        'maxAge' => 3600,
        'leeway' => 10,
        'secret' => 'secret'
    ]   
]
```

You can either use the default utils class, or a custom one. If you implement your own
utils class it must extends \JGuillaumesio\PHPMercureHub\Utils\AbstractUtils implements \JGuillaumesio\PHPMercureHub\Utils\UtilsInterface