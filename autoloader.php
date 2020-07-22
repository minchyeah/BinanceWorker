<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

/**
 * Load class by namespace.
 * @param string $name
 * @return boolean
 */
function myautoloader($name)
{
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $name);
    // search from root directory
    $class_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $class_path . '.php';
    if (empty($class_file) || !is_file($class_file)) {
        // search from applications directory
        $class_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Applications' .DIRECTORY_SEPARATOR. $class_path . '.php';
    }
    if (is_file($class_file)) {
        require_once($class_file);
        if (class_exists($name, false)) {
            return true;
        }
    }
    return false;
}

spl_autoload_register('myautoloader');
