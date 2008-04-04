<?php
/**
 * Area represented as a class, abstracted from the database.
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
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
		if (self::getByName($sName)) {
			throw new Exception('Area with name "'.$sName.'" already exists.');
		}
		$oArea = new Area();
		$oArea->setName($sName);
		$oArea->setAdminEmail($sAdminMail);
		$oArea->commit();
		
		return $oArea->getId();
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
		$oStatement = $oDB->getConnection()->prepareStatement('SELECT * FROM '
			.$oDB->getTableName('area').' WHERE area_name = ?');
		$oStatement->setString(1, $sName);
		$oResult = $oStatement->executeQuery();
		return $oResult->next();
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
	public function setAdminEmail($sName)
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
			// already existing object
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
}
