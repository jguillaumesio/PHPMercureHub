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
    'utils' => UtilClass::class
]
```