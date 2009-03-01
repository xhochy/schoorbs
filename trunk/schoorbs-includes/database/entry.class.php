<?php
/**
 * Entry represented as a class, abstracted from the database.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/**
 * Entry represented as a class.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class Entry {

	/// static functions ///

	/**
	 * Get all entries between start- and endtime in a specific room.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $oRoom Room
	 * @param $nStartTime int
	 * @param $nEndTime int
	 * @return array
	 */
	public function getBetween($oRoom, $nStartTime, $nEndTime) {
		$aEntries = array();
		$oDB = SchoorbsDB::getInstance();
		// Example query:
		//   SELECT * FROM schoorbs_entry WHERE room_id = 5 AND start_time
		//   <= 1567890567 AND end_time > 1667890567
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('entry').' WHERE room_id = ? AND start_time <= '
			.'? AND end_time > ?');
		$oStatement->setInt(1, $oRoom->getId());
		$oStatement->setInt(2, $nEndTime);
		$oStatement->setInt(3, $nStartTime);
		
		$oResult = $oStatement->executeQuery();
		while ($oResult->next()) {
			$aEntries[] = self::fetchEntry($oResult);
		}
		return $aEntries;
	}
	
	/**
	 * Get an entry by its id.
	 *
	 * @param $nId int
	 * @return Entry
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getById($nId) {
		$oDB = SchoorbsDB::getInstance();
		// Example Query:
		//   SELECT * FROM schoorbs_entry WHERE id = 5
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('entry').' WHERE id = ?');
		$oStatement->setInt(1, $nId);
		$oResult = $oStatement->executeQuery();
		if ($oResult->next()) {
			return self::fetchEntry($oResult);
		} else {
			return null;
		}
	}
	
	/**
	 * Create a single Entry object from an Creole ResultSet
	 *
	 * Remark: This function will not call ResultSet->next()
	 *
	 * @param $oResult ResultSet
	 * @return Entry
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function fetchEntry($oResult) {
		$oEntry = new Entry();
		$oEntry->nId = $oResult->getInt('id');
		$oEntry->oRoom = Room::getById($oResult->getInt('room_id'));
		$oEntry->nEntryType = $oResult->getInt('entry_type');
		$oEntry->nRepeatId = $oResult->getInt('repeat_id');
		$oEntry->sCreateBy = $oResult->getString('create_by');
		$oEntry->sName = $oResult->getString('name');
		$oEntry->sType = $oResult->getString('type');
		$oEntry->sDescription = $oResult->getString('description');

		/**
		 * @todo In future, we may use a Date class for the times
		 */
		$oEntry->nStartTime = $oResult->getInt('start_time');
		$oEntry->nEndTime = $oResult->getInt('end_time');
		$oEntry->nTimestamp = intval($oResult->getTimestamp('timestamp', 'U'));

		return $oEntry;
	}
	
	/**
	 * Search in all text-based columns for an occurence of $sText
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sText string The string we are searching for
	 * @return Array
	 */
	public static function simpleSearch($sText) {
		$oDB = SchoorbsDB::getInstance();
		
		// Example query:
		//   SELECT * FROM entry WHERE name LIKE '%test%' OR
		//     description LIKE '%test%' OR created_by LIKE '%test%'
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('entry').' WHERE name LIKE ? OR '
			.' description LIKE ? OR create_by LIKE ?');
			
		// Add % for searching to things likes $sText.'sss' too
		$oStatement->setString(1, '%'.$sText.'%');
		$oStatement->setString(2, '%'.$sText.'%');
		$oStatement->setString(3, '%'.$sText.'%');
		
		$aEntries = array();
		$oResult = $oStatement->executeQuery();
		var_dump($oStatement);
		while ($oResult->next()) {
			$aEntries[] = self::fetchEntry($oResult);
		}
		return $aEntries;
	}
	
	public static function advancedSearch($sText, $sCreatedBy, $oRoom, $sType) {
		$oDB = SchoorbsDB::getInstance();
		
		// Example query:
		//   SELECT * FROM entry WHERE name LIKE '%test%' OR
		//     description LIKE '%test%' OR created_by LIKE '%test%'
		$sQuery = 'SELECT * FROM '.$oDB->getTableName('entry')
			.' WHERE (name LIKE ? OR description LIKE ?) AND '
			.' create_by LIKE ? AND type LIKE ?';
		if ($oRoom != null) $sQuery.= ' AND room_id = ?';
		$oStatement = $oDB->getConnection()->prepareStatement($sQuery);
			
		// Add % for searching to things likes $sText.'sss' too
		$oStatement->setString(1, '%'.$sText.'%');
		$oStatement->setString(2, '%'.$sText.'%');
		$oStatement->setString(3, '%'.$sCreatedBy.'%');
		$oStatement->setString(4, '%'.$sType.'%');
		if ($oRoom != null) $oStatement->setInt(5, $oRoom->getId());
		
		
		$aEntries = array();
		$oResult = $oStatement->executeQuery();
		while ($oResult->next()) {
			$aEntries[] = self::fetchEntry($oResult);
		}
		return $aEntries;
	}
	
	/**
	 * Provides a wrapper to determinate if we are using periods or simple timestamps
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return bool
	 */
	public static function perioded() {
		return $GLOBALS['enable_periods'] == true;
	}
	
	/**
	 * Format a time fitting for the current period setting
	 *
	 * Only senseful if periods are enabled.
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nTime int
	 */
	public static function formatTimePeriodString($nTime) {
		global $periods;
		
		$aTime = getdate($nTime);
    		$nPnum = $aTime['minutes'];
		if($nPnum < 0 ) $nPnum = 0;
		if($nPnum >= count($periods) - 1) $nPnum = count($periods) - 1;
		return $periods[$nPnum];
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
	 * This is a readonly property since the id is generated with the 
	 * creation of the database row for this entry and will never be 
	 * changed, so that not depend on any other property this booking 
	 * could uniquely be referenced by its id.
	 * 
	 * @var int
	 * @see Entry::getId()
	 */
	private $nId = -1;
	
	/**
	 * The room in which this entry will take place.
	 *
	 * @var Room
	 * @see Entry::getRoom()
	 * @see Entry::setRoom()
	 */
	private $oRoom = null;
	
	/**
	 * The time, where this entry starts.
	 *
	 * @var int
	 * @see Entry::getStartTime()
	 * @see Entry::setStartTime()
	 */
	private $nStartTime = 0;
	
	/** 
	 * The time where this entry ends.
	 *
     	 * @var int
     	 * @see Entry::getEndTime()
     	 * @see Entry::setEndTime()
         */
    	private $nEndTime = 0;
    
    	/**
	 * entry_type?
	 *
	 * @var int
	 * @see Entry::getEntryType()
	 * @see Entry::setEntryType()
	 */
	private $nEntryType = 0;
	
	/**
	 * True, if this entry was changed, so that we need to commit it to the
	 * database.
	 * 
	 * @var bool
	 */
	private $bChanged = false;
    
    	/**
    	 * The id of the repeation information.
    	 *
    	 * 0 means that this is a single, stand-alone entry which will be not 
    	 * repeated.
    	 *
    	 * @var int
    	 * @see Entry::$oRepetition
    	 */
    	private $nRepeatId = 0;
    	
    	/**
    	 * The repetition information.
    	 *
    	 * This is only fetched on demand. On single entries this stays on null.
    	 *
    	 * @var Repeat
    	 * @see Entry::getRepetition()
    	 * @see Entry::isRepeated()
    	 */
    	private $oRepetition = null;
    
    	/**
    	 * The creator of this entry.
    	 *
    	 * @var string
    	 * @see Entry::getCreateBy()
    	 * @see Entry::setCreateBy()
    	 */
    	private $sCreateBy = '';
    
    	/**
    	 * This timestamps identifies the time where this entry was changed the last
    	 * time. On every UPDATE-query the database-system should update this 
    	 * timestamp automatoically. For exmaple in MySQL this is done by setting 
    	 * attribute of this column to 'ON UPDATE CURRENT_TIMESTAMP'
    	 *
    	 * Attention: This variable should be considered as read-only.
    	 *
    	 * @var int
    	 * @see Entry::getTimestamp()
    	 */
    	private $nTimestamp = 0;
    	
    	/**
    	 * The name/title of this entry.
    	 *
    	 * @var string
    	 * @see Entry::getName()
    	 * @see Entry::setName()
    	 */
    	private $sName = '';
    	
    	/**
    	 * The category of this entry.
    	 *
    	 * This should be a letter out of 'A'..'Z'
    	 *
    	 * @var string
    	 * @see Entry::setType()
    	 * @see Entry::getType()
    	 */
    	private $sType = 'I';
    
    	/**
    	 * A long description of this entry.
    	 *
    	 * @var string
    	 * @see Entry::getDescription()
    	 * @see Entry::setDescription()
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
	 * Return the starttime of this entry
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int Unix Timestamp
	 */
	public function getStartTime() {
		return $this->nStartTime;
	}
	
	/**
	 * Return the starttime of this entry
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int Unix Timestamp
	 */
	public function getEndTime() {
		return $this->nEndTime;
	}
	
	/**
	 * Return the unique identifier of this entry.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getId() {
		return $this->nId;
	}
	
	/**
	 * Return the detailed description of this entry.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getDescription() {
		return $this->sDescription;
	}
	
	/**
	 * Return the name/title of this entry.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getName() {
		return $this->sName;
	}
	
	/**
	 * Return the name of the creator of this entry.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getCreateBy() {
		return $this->sCreateBy;
	}
	
	/**
	 * Return the type/category of this entry. This is only one character 
	 * between 'A'..'Z'.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getType() {
		return $this->sType;
	}
	
	/**
	 * Return the type/category of this entry. This function returns the
	 * whole string which was configured.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getTypeLong() {
		if ($this->sType == 'I') {
			return Lang::_('Internal');
		} else if ($this->sType == 'E') {
			return Lang::_('External');
		} else {
			return $GLOBALS['typel'][$this->sType];
		}
	}
	
	/**
	 * Return the repetion information associated to this entry.
	 *
	 * Will return null, if this entry is never repeated
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return Repeat
	 */
	public function getRepetition() {
		if ($this->isRepeated()) {
			if ($this->oRepetition == null) {
				$this->oRepetition = Repeat::getById($this->nRepeatId);
			}
			return $this->oRepetition;
		} else {
			return null;
		}
	}
	
	/**
	 * Determinate if this entry is repeated at another time
	 *
	 * At the moment an entry is repeated if its RepeatId is not zero.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return bool
	 */
	public function isRepeated() {
		return ($this->nRepeatId != 0);
	}
	
	/**
	 * Return the type of repeating as a nice string
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getRepetitionString() {
		if ($this->isRepeated()) {
			$nRepType = $this->getRepetition()->getRepType();
			if ($nRepType == 1) {
				return Lang::_('Daily');
			} else if ($nRepType == 2) {
				return Lang::_('Weekly');
			} else if ($nRepType == 3) {
				return Lang::_('Monthly');
			} else if ($nRepType == 4) {
				return Lang::_('Yearly');
			} else if ($nRepType == 5) {
				return Lang::_('Monthly, corresponding day');
			} else {
				// $nRepType == 6
				return Lang::_('n-Weekly');
			}
		} else {
			return Lang::_('None');
		}
	}
	
	
	/**
	 * Return the room to which this entry belongs.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return Room
	 */
	public function getRoom() {
		return $this->oRoom;
	}
	
	/**
	 * Return the time of the last modification
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getTimestamp() {
		return $this->nTimestamp;
	}
	
	/**
	 * Return the periodString of the startTime of this booking.
	 * 
	 * Only senseful if periods are enabled.
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function getStartPeriodString() {
		return self::formatTimePeriodString($this->nStartTime);
	}
	
	/**
	 * Return the periodString of the endTime this booking.
	 * 
	 * Only senseful if periods are enabled.
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function getEndPeriodString() {
		return self::formatTimePeriodString($this->nEndTime);
	}	 
	
	/**
	 * Return the duration of this entry as nice formatted string.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getDurationString() {
		$nDuration = $this->nEndTime - $this->nStartTime;
		if (self::perioded()) {
			$nStartPeriod = intval(date('i', $this->nStartTime));
			$nMaxPeriods = count($GLOBALS['periods']);
			$nDuration /= 60;
		    	if (($nDuration >= $nMaxPeriods) || ($nStartPeriod == 0)) {
				if (($nStartPeriod == 0) && ($nDuration == $nMaxPeriods)) {
					$sUnits = Lang::_('days');
					$nDuration = 1;
					return sprintf('%d %s', $nDuration, $sUnits);
				}

				$nDuration /= 60;
				if (($nDuration >= 24) && is_int($nDuration)) {
					$nDuration /= 24;
					$sUnits = Lang::_('days');
					return sprintf('%d %s', $nDuration, $sUnits);
				} else {
					$nDuration *= 60;
					$nDuration = ($nDuration % $nMaxPeriods) + floor($nDuration/(24*60)) * $nMaxPeriods;
					$sUnits = Lang::_('periods');
					return sprintf('%d %s', $nDuration, $sUnits);
				}
			} else { 
				$sUnits = get_vocab('periods');
			}
		} else {
			if ($nDuration >= 60) {
				$nDuration /= 60;
				if ($nDuration >= 60) {
					$nDuration /= 60;
					if (($nDuration >= 24) && ($nDuration % 24 == 0)) {
						$nDuration /= 24;
						if (($nDuration >= 7) && ($nDuration % 7 == 0)) {
							$nDuration /= 7;
							if (($nDuration >= 52) && ($nDuration % 52 == 0)) {
								$nDuration  /= 52;
								$sUnits = Lang::_('years');
							} else {
								$sUnits = Lang::_('weeks');
							}
						} else {
							$sUnits = Lang::_('days');
						}
					} else {
						$sUnits = Lang::_('hours');
					}
				} else {
					$sUnits = Lang::_('minutes');
				}
			} else {
				$sUnits = Lang::_('seconds');
			}
		}
		
		return sprintf('%d %s', $nDuration, $sUnits);
	}
	
	/**
	 * Get the starting period of this entry.
	 *
	 * Returns an integer from 0..(count(periods)-1), if 
	 * the value is higher than count(periods)-1 then
	 * count(periods)-1 is returned.
	 *
	 * @return int
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function getStartPeriod() {
		$aTime = getdate($this->nStartTime);
    		$nPnum = $aTime['minutes'];
		if($nPnum >= count($periods) - 1) $nPnum = count($periods) - 1;
		return $nPnum;
	}
}
