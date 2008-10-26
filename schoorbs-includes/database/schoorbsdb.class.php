<?php
/**
 * Database abstraction layer using Creole
 *
 * We using only Creole at the moment because its "partner" Propel doesn't 
 * support currently prefixed tables.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/// Common includes ///

@ini_set('include_path', dirname(__FILE__).PATH_SEPARATOR.ini_get('include_path'));
require_once dirname(__FILE__).'/creole/Creole.php';

/// ORM mappings ///

/** The area class */
require_once dirname(__FILE__).'/area.class.php';
/** The room class */
require_once dirname(__FILE__).'/room.class.php';
/** The entry class */
require_once dirname(__FILE__).'/entry.class.php';
/** The repeat class */
require_once dirname(__FILE__).'/repeat.class.php';

/// Main database class ///

/**
 * The database abstraction class for Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class SchoorbsDB {

	/// static variables ///

	/** The Instance to use. */
	private static $db = null;
	
	/// static functions ///
	
	/**
	 * Get the singleton instance for this class.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return SchoorbsDB
	 */
	public static function getInstance() {
		// If the object does not yet exist, create a new instance of it.
		if (self::$db == null) {
			// Build up the DSN parameters
			$aDSN = array();
			// Get the variables via the GLOBALS array, so that we do not need
			// to import them as local variables.
			$aDSN['phptype'] = $GLOBALS['dbsys'];
			$aDSN['hostspec'] = $GLOBALS['db_host'];
			$aDSN['username'] = $GLOBALS['db_login'];
			$aDSN['password'] = $GLOBALS['db_password'];
			$aDSN['database'] = $GLOBALS['db_database'];
			// create DB instance
			self::$db = new SchoorbsDB($aDSN, $GLOBALS['db_tbl_prefix']);
		}
		
		return self::$db;
	}
	
	/// instance variables ///

	/** 
	 * The Creole connection
	 *
	 * @var Creole
	 */
	private $oConnection;
	
	/**
	 * The table prefix
	 *
	 * @var string
	 */
	private $sPrefix;
	
	/// instance functions ///

	/**
	 * The constructor for the SchoorbsDB, should be called only once.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $aDSN array The connection parameters
	 * @param $sPrefix string
	 */
	private function __construct($aDSN, $sPrefix = '') {
		$this->oConnection = Creole::getConnection($aDSN, Creole::COMPAT_ASSOC_LOWER);
		$this->sPrefix = $sPrefix;
	}
	
	/**
	 * Close the database connection when cleaning up
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	function __destruct() {
		$this->oConnection->close();
	}
	
	/**
	 * Get the Creole connection.
	 *
	 * @return Creole
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function getConnection() {
		return $this->oConnection;
	}
	
	/**
	 * Prefix the table name
	 *
	 * In future here we could add more complex commands, maybe views or aliases.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 * @param $sTableName string
	 */
	public function getTableName($sTable) {
		return $this->sPrefix.$sTable;
	}
	
	/**
	 * Change the prefix for the tables in the database
	 *
	 * This is only used for the unit tests
	 * 
	 * @param $sPrefix string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function setPrefix($sPrefix) {
		$this->sPrefix = $sPrefix;
	}
}
