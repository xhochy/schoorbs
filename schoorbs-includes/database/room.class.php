<?php
/**
 * Room represented as a class, abstracted from the database.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Room represented as a class.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class Room {
	
	/// static functions ///
	
	/**
	 * Create a new room in a specific area
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $oArea Area
	 * @param $sName string
	 * @param $sDescription string
	 * @param $nCapacity int
	 * @return Room
	 */
	public static function create($oArea, $sName, $sDescription, $nCapacity)
	{
		if (self::getByName($oArea, $sName) !== null) {
			throw new Exception('Room with name "'.$sName
				.'" already exists in this area.');
		}
		
		$oRoom = new Room();
		$oRoom->setArea($oArea);
		$oRoom->setName($sName);
		$oRoom->setDescription($sDescription);
		$oRoom->setCapacity($nCapacity);
		$oRoom->commit();
		
		return $oRoom;
	}
	
	/**
	 * Create a single Room object from an Creole ResultSet
	 *
	 * Remark: This function will not call ResultSet->next()
	 *
	 * @param $oResult ResultSet
	 * @return Room
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function fetchRoom($oResult)
	{
		$oRoom = new Room();
		$oRoom->nId = $oResult->getInt('id');
		$oRoom->sName = $oResult->getString('room_name');
		$oRoom->sDescription = $oResult->getString('description');
		$oRoom->nCapacity = $oResult->getInt('capacity');
		$oRoom->oArea = Area::getById($oResult->getInt('area_id'));
		$oRoom->sAdminMail = $oResult->getString('room_admin_email');
		return $oRoom;
	}
	
	/**
	 * Get an room by its name and its area.
	 *
	 * @param $oArea Area
	 * @param $sName string
	 * @return Room
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getByName($oArea, $sName)
	{
		$oDB = SchoorbsDB::getInstance();
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('room').' WHERE area_id = ? AND room_name = ?');
		$oStatement->setInt(1, $oArea->getId());
		$oStatement->setString(2, $sName);
		$oResult = $oStatement->executeQuery();
		if ($oResult->next()) {
			return self::fetchRoom($oResult);
		} else {
			return null;
		}
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
	 * If a field was modified, this varibale will be set to true.
	 *
	 * @var bool
	 */
	private $bChanged = false;
	
	/**
	 * The name of the room
	 *
	 * @var string
	 */
	private $sName = '';
	
	/**
	 * The e-mail-address of the room administrator.
	 *
	 * @var string
	 */
	private $sAdminEmail = '';
	
	/**
	 * The Area this room is located
	 *
	 * @var Area
	 */
	private $oArea = null;
	
	/**
	 * The description of this room
	 * 
	 * @var string
	 */
	private $sDescription = '';
	
	/**
	 * The capacity of this room, -1 means "not set"
	 *
	 * @var int
	 */
	private $nCapacity = -1;
	
	/// instance functions ///

	/** 
	 * Create an empty room object.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	private function __construct()
	{
		$this->oDB = SchoorbsDB::getInstance();
	}
	
	/**
	 * Destruct the object.
	 *
	 * If there were changes, commit them to the database.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	function __destruct() 
	{
		if (($this->nId == -1) || ($this->bChanged == true)) {
			$this->commit();
		}
	}
	
	/**
	 * Set the name of this room to a new value.
	 *
	 * Attention: This will not be automatically submitted to the database, 
	 * if you want to save the changes directly, you have to call commit().
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sName string
	 */
	public function setName($sName)
	{
		$this->bChanged = true;
		$this->sName = $sName;
	}
	
	/**
	 * Set the e-mail-address of the room administrator
	 *
	 * Attention: This will not be automatically submitted to the database, 
	 * if you want to save the changes directly, you have to call commit().
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sAdminMail string
	 */
	public function setAdminEmail($sAdminMail)
	{
		$this->bChanged = true;
		$this->sAdminEmail = $sAdminMail;
	}
	
	/**
	 * Set the area of this room
	 *
	 * Attention: This will not be automatically submitted to the database, 
	 * if you want to save the changes directly, you have to call commit().
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $oArea Area
	 */
	public function setArea($oArea)
	{
		$this->bChanged = true;
		$this->oArea = $oArea;
	}
	
	/**
	 * Set the description of this room
	 *
	 * Attention: This will not be automatically submitted to the database, 
	 * if you want to save the changes directly, you have to call commit().
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sDescription string
	 */
	public function setDescription($sDescription)
	{
		$this->bChanged = true;
		$this->sDescription = $sDescription;
	}
	
	/**
	 * Set the capacity of this room
	 *
	 * Attention: This will not be automatically submitted to the database, 
	 * if you want to save the changes directly, you have to call commit().
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nCapacity int
	 */
	public function setCapacity($nCapacity)
	{
		$this->bChanged = true;
		$this->nCapacity = $nCapacity;
	}
	
	
	/**
	 * Saves the object in the database.
	 *
	 * If this is a new obejct we will run an insert-query and get the created 
	 * Id, otherwise we will run an update-query.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function commit()
	{
		if ($this->nId == -1) {
			// new object, so we will insert it as a new row
			$oIdgen = $this->oDB->getConnection()->getIdGenerator();
			// prepare the INSERT startement which will be the same in both cases
			$oStatement = $this->oDB->getConnection()->prepareStatement(
				'INSERT INTO '.$this->oDB->getTableName('room').' (room_name, '
				.'area_id, description, capacity, room_admin_email) VALUES (?,'
				.' ?, ?, ?, ?)'
			);
			$oStatement->setString(1, $this->sName);
			$oStatement->setInt(2, $this->oArea->getId());
			$oStatement->setString(3, $this->sDescription);
			$oStatement->setInt(4, $this->nCapacity);
			$oStatement->setString(5, $this->sAdminEmail);
			// do we get id before or after performing insert?
			if($oIdgen->isBeforeInsert()) {
				$this->nId = $oIdgen->getId($this->oDB->getTableName('room')
					.'_id_seq');
				// now add that ID to SQL and perform INSERT
			   	$oStatement->executeUpdate();
			} else { // isAfterInsert()
			   // first perform INSERT
			   $oStatement->executeUpdate();
			   $this->nId = $oIdgen->getId();
			}
		} else {
			// already existing object
			$oStatement = $this->oDB->getConnection()->prepareStatement(
				'UPDATE '.$this->oDB->getTableName('area').' SET room_name = ? '
				.'AND area_id = ? AND description = ? AND capacity = ? AND '
				.'room_admin_email = ? WHERE id = ?'
			);
			$oStatement->setString(1, $this->sName);
			$oStatement->setInt(2, $this->oArea->getId());
			$oStatement->setString(3, $this->sDescription);
			$oStatement->setInt(4, $this->nCapacity);
			$oStatement->setString(5, $this->sAdminEmail);
			$oStatement->setInt(6, $this->nId);
		}
		// We have commited all current changes, so there are no changes left in 
		// this object.
		$this->bChanged = false;
	}
}
