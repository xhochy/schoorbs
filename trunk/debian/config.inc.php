<?php
/** Schoorbs's Debianised default master config file
Please do NOT edit and read about how the configuration works in the README.Debian
**/

    $server = preg_replace('/:.*/', "", $_SERVER['HTTP_HOST']);
    $file = '/etc/schoorbs/config-'.strtolower($server).'.php';

    if (!file_exists($file)) {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not found";
    }

    require_once($file);

