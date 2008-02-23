<?php
/**
 * Load configuration into a Singleton class
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Configuration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @todo Move in future versions into Schoorbs core
 */

## The class ##
 
/**
 * Wrap the Configuratation into a Singleton/Static class
 *  
 * @package Schoorbs
 * @subpackage Configuration
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
class Configuration
{

	/**
	 * Database backend (one of mysqli, mysql, pgsql)
	 * @var string
	 */
	public static $sDatabaseBackend;
	
	/**
	 * Server on which the Database is running
	 * @var string
	 */
	public static $sDatabaseHost;
	
	/**
	 * The name of the database to use
	 * @var string
	 */
	public static $sDatabaseName;
	
	/**
	 * The username to login into the Database
	 * @var string
	 */
	public static $sDatabaseUser;
	
	/**
	 * The password to login into the Database
	 * @var string
	 */
	public static $sDatabasePassword;
	
	/**
	 * Prefix for table names in the Database. 
	 * 
	 * This will allow multiple installations where only one database is available.
	 * @var string
	 */
	public static $sDatabaseTablePrefix;
	
	/**
	 * Load the configuration and store it in static variables
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sFile string The configuration file
	 * @todo only done Database settings yet, continue...
	 */
	public static function load($sFile)
	{
		// Parse it!
		require $sFile;
		
		self::$sDatabaseBackend = $dbsys;
		self::$sDatabaseHost = $db_host;
		self::$sDatabaseName = $db_database;
		self::$sDatabaseUser = $db_login;
		self::$sDatabasePassword = $db_password;
		self::$sDatabaseTablePrefix = $db_tbl_prefix;
		// We are not supporting peristent connections anymore!
		
		// TODO: only done Database settings yet, continue...
	}
}

/** Load & Parse the configuration now */
Configuration::load(dirname(__FILE__).'/../config.inc.php');
