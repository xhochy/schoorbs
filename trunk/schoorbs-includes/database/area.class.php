<?php
/**
 * Area represented as a class, abstracted from the database.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/**
 * Area represented as a class.
 *
 * An area consists of 0+ rooms.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class Area {
	
	/// static functions ///

	/**
	 * Create a new area
	 *
	 * @param $sName string
	 * @param $sAdminMail string
	 * @return Area
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function create($sName, $sAdminMail = '')
	{
		if (self::getByName($sName) !== null) {
			throw new Exception('Area with name "'.$sName.'" already exists.');
		}
		$oArea = new Area();
		$oArea->setName($sName);
		$oArea->setAdminEmail($sAdminMail);
		$oArea->commit();
		
		return $oArea;
	}

	/**
	 * Get an area by its name.
	 *
	 * @param $sName string
	 * @return Area
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getByName($sName)
	{
		$oDB = SchoorbsDB::getInstance();
		// Example query:
		//   SELECT * FROM schoorbs_area WHERE area_name = 'Area1'
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('area').' WHERE area_name = ?');
		$oStatement->setString(1, $sName);
		$oResult = $oStatement->executeQuery();
		if ($oResult->next()) {
			return self::fetchArea($oResult);
		} else {
			return null;
		}
	}
	
	/**
	 * Create a single Area object from an Creole ResultSet
	 *
	 * Remark: This function will not call ResultSet->next()
	 *
	 * @param $oResult ResultSet
	 * @return Area
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function fetchArea($oResult)
	{
		$oArea = new Area();
		$oArea->nId = $oResult->getInt('id');
		$oArea->sName = $oResult->getString('area_name');
		$oArea->sAdminEmail = $oResult->getString('area_admin_email');
		return $oArea;
	}
	
	/**
	 * Get an area by its id.
	 *
	 * @param $nId int
	 * @return Area
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getById($nId)
	{
		$oDB = SchoorbsDB::getInstance();
		// Example query:
		//   SELECT * FROM schoorbs_area WHERE id = 3
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('area').' WHERE id = ?');
		$oStatement->setInt(1, $nId);
		$oResult = $oStatement->executeQuery();
		if ($oResult->next()) {
			return self::fetchArea($oResult);
		} else {
			return null;
		}
	}
	
	/**
	 * Get all available areas.
	 *
	 * If no area exists, an empty array will be returned.
	 * 
	 * @return array
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getAreas()
	{
		$aAreas = array();
		$oDB = SchoorbsDB::getInstance();
		// Example query:
		//   SELECT * FROM schoorbs_area
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('area'));
		$oResult = $oStatement->executeQuery();
		while ($oResult->next()) {
			$aAreas[] = self::fetchArea($oResult);
		}
		
		return $aAreas;
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
	 * The name of the area
	 *
	 * @var string
	 */
	private $sName = '';
	
	/**
	 * The e-mail-address of the area administrator.
	 *
	 * @var string
	 */
	private $sAdminEmail = '';
	
	/// instance functions ///

	/** 
	 * Create an empty area object.
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
	 * Set the name of this area to a new value.
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
	 * Set the e-mail-address of the area administrator
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
			//
			// Example query:
			//   INSERT INTO schoorbs_area (area_name, area_admin_email) VALUES
			//   ('Area1', 'mail@example.com')
			$oStatement = $this->oDB->getConnection()->prepareStatement(
				'INSERT INTO '.$this->oDB->getTableName('area').' (area_name, '
				.'area_admin_email) VALUES (?, ?)'
			);
			$oStatement->setString(1, $this->sName);
			$oStatement->setString(2, $this->sAdminEmail);
			// do we get id before or after performing insert?
			if($oIdgen->isBeforeInsert()) {
			   $this->nId = $oIdgen->getId($this->oDB->getTableName('area').'_id_seq');
			   // now add that ID to SQL and perform INSERT
			   $oStatement->executeUpdate();
			} else { // isAfterInsert()
			   // first perform INSERT
			   $oStatement->executeUpdate();
			   $this->nId = $oIdgen->getId();
			}
		} else {
			// Update an already existing object
			//
			// Example query:
			//   UPDATE schoorbs_area SET area_name = 'Area2' AND 
			//   area_admin_email = 'mail@example.org' WHERE id = 2
			$oStatement = $this->oDB->getConnection()->prepareStatement(
				'UPDATE '.$this->oDB->getTableName('area').' SET area_name = ? '
				.'AND area_admin_email = ? WHERE id = ?'
			);
			$oStatement->setString(1, $this->sName);
			$oStatement->setString(2, $this->sAdminEmail);
			$oStatement->setInt(3, $this->nId);
		}
		// We have commited all current changes, so there are no changes left in 
		// this object.
		$this->bChanged = false;
	}
	
	/**
	 * Return the database-Id of this area.
	 *
	 * This is the primary key in the database, our unique identifier for areas.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return int
	 */
	public function getId()
	{
		return $this->nId;
	}
	
	/**
	 * Return the name of this area.
	 *
	 * This is the main string, which will be displayed to the user.
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public function getName()
	{
		return $this->sName;
	}
}
