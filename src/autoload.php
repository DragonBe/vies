<?php

/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 *
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */
spl_autoload_register(function ($class): void {

    $prefix = 'DragonBe\Vies\\';
    if (0 !== strpos($class, $prefix)) {
        return;
    }

    $file = __DIR__
        .DIRECTORY_SEPARATOR
        .'Vies'
        .str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)))
        .'.php';
    if (! is_readable($file)) {
        return;
    }

    require $file;
});
