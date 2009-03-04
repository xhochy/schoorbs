<?php
/**
 * Repetition represented as a class, abstracted from the database.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/**
 * Repetition represented as a class.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class Repeat {

	/// static functions ///
	
	/**
	 * Get a repetion by its id.
	 *
	 * @param $nId int
	 * @return Repeat
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getById($nId) {
		$oDB = SchoorbsDB::getInstance();
		// Example Query:
		//   SELECT * FROM schoorbs_entry WHERE id = 5
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('repeat').' WHERE id = ?');
		$oStatement->setInt(1, $nId);
		$oResult = $oStatement->executeQuery();
		if ($oResult->next()) {
			return self::fetchRepeat($oResult);
		} else {
			return null;
		}
	}
	
	/**
	 * Create a single Repeat object from an Creole ResultSet
	 *
	 * Remark: This function will not call ResultSet->next()
	 *
	 * @param $oResult ResultSet
	 * @return Repeat
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function fetchRepeat($oResult) {
		$oRepetition = new Repeat();
		$oRepetition->nId = $oResult->getInt('id');
		$oRepetition->sCreateBy = $oResult->getString('create_by');
		
		/**
		 * @todo In future, we may use a Date class for the times
		 */
		$oRepetition->nStartTime = $oResult->getInt('start_time');
		$oRepetition->nEndTime = $oResult->getInt('end_time');
		$oRepetition->nTimestamp = intval($oResult->getTimestamp('timestamp', 'U'));
		$oRepetition->oRoom = Room::getById($oResult->getInt('room_id'));
		$oRepetition->sType = $oResult->getString('type');
		$oRepetition->sName = $oResult->getString('name');
		$oRepetition->sDescription = $oResult->getString('description');
		$oRepetition->nRepNumWeeks = $oResult->getInt('rep_num_weeks');
		$oRepetition->nRepType = $oResult->getInt('rep_type');
		$oRepetition->sRepOpt = $oResult->getString('rep_opt');
		$oRepetition->nEndDate = $oResult->getInt('end_date');

		return $oRepetition;
	}
	
	/// instance variables ///
	
	/**
	 * The database we are connected to.
	 *
	 * @var SchoorbsDB
	 */
	private $oDB;
	
	/**
	 * The id (primary key) in the database.
	 *
	 * If this field is = -1 then this object was newly created and never 
	 * checked into the database.
	 * 
	 * @var int
	 */
	private $nId = -1;
	
	/**
	 * The time, where the first entry starts.
	 *
	 * @var int
	 */
	private $nStartTime = 0;
	
	/** 
	 * The time where the first entry ends.
	 *
     	 * @var int
         */
    	private $nEndTime = 0;
    	
    	/** 
	 * The time where this repetition ends.
	 *
     	 * @var int
         */
    	private $nEndDate = 0;
    	
    	/** 
	 * The type of repetition
	 *
	 * 1: daily
	 * 2: weekly
	 * 3: monthly
	 * 4: yearly
	 * 5: monthly, corresponding day
	 * 6: n-weekly
	 *
     	 * @var int
         */
    	private $nRepType = 0;
    	
    	/** 
	 * How many weeks we will repeat this
	 *
     	 * @var int
         */
    	private $nRepNumWeeks = 0;
    	
    	/**
	 * The room in which this entry will take place.
	 *
	 * @var Room
	 */
	private $oRoom = null;
	
	/**
	 * entry_type?
	 *
	 * @var int
	 */
	private $nEntryType = 0;
	
	/**
	 * Place to store specific options for a repeat type
	 *
	 * (n-)weekly repeating: Used to store the days of repeating
	 *
	 * @var string
	 */
	private $sRepOpt = 0;
	
	/**
	 * True, if this entry was changed, so that we need to commit it to the
	 * database.
	 * 
	 * @var bool
	 */
	private $bChanged = false;
	
	/**
    	 * The category of this entry.
    	 *
    	 * This should be a letter out of 'A'..'Z'
    	 *
    	 * @var string
    	 */
    	private $sType = 'I';
    	
    	/**
    	 * This timestamps identifies the time where this entry was changed the last
    	 * time. On every UPDATE-query the database-system should update this 
    	 * timestamp automatoically. For exmaple in MySQL this is done by setting 
    	 * attribute of this column to 'ON UPDATE CURRENT_TIMESTAMP'
    	 *
    	 * Attention: This variable should be considered as read-only.
    	 *
    	 * @var int
    	 */
    	private $nTimestamp = 0;
    	
    	/**
    	 * The creator of this entry.
    	 *
    	 * @var string
    	 */
    	private $sCreateBy = '';
    	
    	/**
    	 * The name/title of this entry.
    	 *
    	 * @var string
    	 */
    	private $sName = '';
    	
    	/**
    	 * A long description of this entry.
    	 *
    	 * @var string
    	 */
    	private $sDescription = '';
    	
    	/// instance functions ///
    	
    	/** 
	 * Create an empty entry object.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	private function __construct() {
		$this->oDB = SchoorbsDB::getInstance();
	}
	
	/**
	 * Destruct the object.
	 *
	 * If there were changes, commit them to the database.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	function __destruct() {
		if (($this->nId == -1) || ($this->bChanged == true)) {
			$this->commit();
		}
	}
	
	/**
	 * Return the type of repetition.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getRepType() {
		return $this->nRepType;
	}
	
	/**
	 * Return the unique identifier
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getId() {
		return $this->nId;
	}
	
	/**
	 * Return the date where this repetition ends.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getEndDate() {
		return $this->nEndDate;
	}
	
	/**
	 * Return the number of weeks when repeated n-weekly
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getRepNumWeeks() {
		return $this->nRepNumWeeks;
	}
	
	/**
	 * Format rep_opt nicely when using (n-)weekly repeat
	 *
	 * The returned string is already translated.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getRepeatDayString() {
		$sResult = '';
		for ($i = 0; $i < 7; $i++) {
			$nDayNum = ($i + $GLOBALS['weekstarts']) % 7;
			if ($this->sRepOpt[$nDayNum]) {
				$sResult .= Lang::_(strftime('%A', mktime(0,0,0,1,2+$nDayNum,2000))).' ';
			}
		}
		return $sReuslt;
	}
}

