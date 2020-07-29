<?php

/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-4)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md for more informations.
 *
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'DragonBe\\Vies\\';

    // base directory for the namespace prefix
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relativeClass = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
