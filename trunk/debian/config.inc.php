<?php
/** Schoorbs's Debianised default master config file
Please do NOT edit and read about how the configuration works in the README.Debian
**/

/**
 * Find the appropriate configuration directory.
 * (This code is taken from Drupal, due to GPL this is legal, thanks folks)
 *
 * Try finding a matching configuration directory by stripping the website's
 * hostname from left to right and pathname from right to left. The first
 * configuration file found will be used; the remaining will ignored. If no
 * configuration file is found, return a default value '$confdir/default'.
 *
 * Example for a fictitious site installed at
 * http://www.drupal.org:8080/mysite/test/ the 'settings.php' is searched in
 * the following directories:
 *
 * (default: $confdir = '/etc/schoorbs')
 *
 *  1. $confdir/config-8080.www.drupal.org.mysite.test.php
 *  2. $confdir/config-www.drupal.org.mysite.test.php
 *  3. $confdir/config-drupal.org.mysite.test.php
 *  4. $confdir/config-org.mysite.test.php
 *
 *  5. $confdir/config-8080.www.drupal.org.mysite.php
 *  6. $confdir/config-www.drupal.org.mysite.php
 *  7. $confdir/config-drupal.org.mysite.php
 *  8. $confdir/config-org.mysite.php
 *
 *  9. $confdir/config-8080.www.drupal.org.php
 * 10. $confdir/config-www.drupal.org.php
 * 11. $confdir/config-drupal.org.php
 * 12. $confdir/config-org.php
 *
 * 13. $confdir/config-default.php
 *
 */
function conf_path() {
  $confdir = '/etc/schoorbs';
  
  $uri = explode('/', $_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['SCRIPT_FILENAME']);
  $server = explode('.', implode('.', array_reverse(explode(':', rtrim($_SERVER['HTTP_HOST'], '.')))));
  
  for ($i = count($uri) - 1; $i > 0; $i--) {
    for ($j = count($server); $j > 0; $j--) {
      $file = implode('.', array_slice($server, -$j)) . implode('.', array_slice($uri, 0, $i));
      if (file_exists("$confdir/config-${file}.php")) {
        return "$confdir/config-${file}.php";
      }
    }
  }
  return "$confdir/config-default.php";
}

require_once(conf_path());

