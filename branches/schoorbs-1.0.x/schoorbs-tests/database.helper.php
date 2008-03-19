<?php
/**
 * Helper functions/class for Database Testing
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Database
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 *
 * @ignore
 */
define('SCHOORBS_NOGUI', true);

## The class ##
 
/**
 * Helper class for database interaction
 *  
 * @package Schoorbs-Test
 * @subpackage Database
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
class DatabaseHelper
{
	/**
	 * Set all $tbl_*-Globals to be Test/Devel-prefixed
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function flavourTblGlobals()
	{
		global $tbl_room, $tbl_entry, $tbl_repeat, $tbl_room;
		$sPrefix = TestConfiguration::$sDatabaseTablePrefix;
		
		$tbl_area   = $sPrefix.'area';
		$tbl_entry  = $sPrefix.'entry';
		$tbl_repeat = $sPrefix.'repeat';
		$tbl_room   = $sPrefix.'room';
	}

	/**
	 * Add an area to the database
	 *
	 * @param $sName string
	 * @return int The id of the created area
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function addArea($sName)
	{
		$sTable = TestConfiguration::$sDatabaseTablePrefix.'area';
		$nResult = sql_command(sprintf(
			'INSERT INTO %s (area_name) VALUES (\'%s\')', 
			$sTable, sql_escape_arg($sName)
		));
		if ($nResult == -1) throw new Exception('Couldn\'t create area.');
	    return sql_insert_id($sTable, 'id');
	}
	
	/**
	 * Add a room to the database
	 *
	 * @param $nArea int
	 * @param $sName string
	 * @param $sDescription string
	 * @param $nCapacity int
	 * @return int The id of the created room
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function addRoom($nArea, $sName, $sDescription, $nCapacity)
	{
		$sTable = TestConfiguration::$sDatabaseTablePrefix.'room';
		$nResult = sql_command(sprintf(
			'INSERT INTO %s (room_name, area_id, description, capacity)'
			.' VALUES (\'%s\', %d, \'%s\', %d)', 
		    $sTable, sql_escape_arg($sName), $nArea, sql_escape_arg($sDescription), 
		    $nCapacity
		));
		if ($nResult == -1) throw new Exception('Couldn\'t create room.');
	    return sql_insert_id($sTable, 'id');
	}
	
	/**
	 * Remove all tables used for database tests
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function removeTestTables()
	{
		$sPrefix = TestConfiguration::$sDatabaseTablePrefix;
		if (sql_command('DROP TABLE IF EXISTS '.$sPrefix.'area') == -1) {
			throw new Exception('Couldn\'t delete table '.$sPrefix.'area');
		}
		if (sql_command('DROP TABLE IF EXISTS '.$sPrefix.'entry') == -1) {
			throw new Exception('Couldn\'t delete table '.$sPrefix.'entry');
		}
		if (sql_command('DROP TABLE IF EXISTS '.$sPrefix.'repeat') == -1) {
			throw new Exception('Couldn\'t delete table '.$sPrefix.'repeat');
		}
		if (sql_command('DROP TABLE IF EXISTS '.$sPrefix.'room') == -1) {
			throw new Exception('Couldn\'t delete table '.$sPrefix.'room');
		}
	}
	
	/**
	 * Create all tables used for database tests
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function createTestTables()
	{
		$sPrefix = TestConfiguration::$sDatabaseTablePrefix;
		$sBackend = Configuration::$sDatabaseBackend;
		if (($sBackend == 'mysqli') || ($sBackend == 'mysql')) {
			$sQuery = <<<QUERY
CREATE TABLE <prefix>area
(
  id               int NOT NULL auto_increment,
  area_name        varchar(30),
  area_admin_email text,

  PRIMARY KEY (id)
);

CREATE TABLE <prefix>room
(
  id               int NOT NULL auto_increment,
  area_id          int DEFAULT '0' NOT NULL,
  room_name        varchar(25) DEFAULT '' NOT NULL,
  description      varchar(60),
  capacity         int DEFAULT '0' NOT NULL,
  room_admin_email text,

  PRIMARY KEY (id)
);

CREATE TABLE <prefix>entry
(
  id          int NOT NULL auto_increment,
  start_time  int DEFAULT '0' NOT NULL,
  end_time    int DEFAULT '0' NOT NULL,
  entry_type  int DEFAULT '0' NOT NULL,
  repeat_id   int DEFAULT '0' NOT NULL,
  room_id     int DEFAULT '1' NOT NULL,
  timestamp   timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,

  PRIMARY KEY (id),
  KEY idxStartTime (start_time),
  KEY idxEndTime   (end_time)
);

CREATE TABLE <prefix>repeat
(
  id          int NOT NULL auto_increment,
  start_time  int DEFAULT '0' NOT NULL,
  end_time    int DEFAULT '0' NOT NULL,
  rep_type    int DEFAULT '0' NOT NULL,
  end_date    int DEFAULT '0' NOT NULL,
  rep_opt     varchar(32) DEFAULT '' NOT NULL,
  room_id     int DEFAULT '1' NOT NULL,
  timestamp   timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,
  rep_num_weeks smallint NULL,
  
  PRIMARY KEY (id)
);
QUERY;
		} elseif ($sBackend == 'pgsql') {
			$sQuery = <<<QUERY
CREATE TABLE <prefix>area
(
  id                serial primary key,
  area_name         varchar(30),
  area_admin_email  text
);

CREATE TABLE <prefix>room
(
  id                serial primary key,
  area_id           int DEFAULT 0 NOT NULL,
  room_name         varchar(25) DEFAULT '' NOT NULL,
  description       varchar(60),
  capacity          int DEFAULT 0 NOT NULL,
  room_admin_email  text
);

CREATE TABLE <prefix>entry
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  entry_type  int DEFAULT 0 NOT NULL,
  repeat_id   int DEFAULT 0 NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text
);
create index idxStartTime on <prefix>entry(start_time);
create index idxEndTime on <prefix>entry(end_time);

CREATE TABLE <prefix>repeat
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  rep_type    int DEFAULT 0 NOT NULL,
  end_date    int DEFAULT 0 NOT NULL,
  rep_opt     varchar(32) DEFAULT '' NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,
  rep_num_weeks smallint DEFAULT NULL NULL
);
QUERY;
		}
		
		$sQuery = str_replace('<prefix>', $sPrefix, $sQuery);
		$sQueries = explode(';', $sQuery);
		foreach ($sQueries as $sQuery) {
			if (empty($sQuery)) continue;
			if (sql_command($sQuery) == -1) {
				throw new Exception('Couldn\'t create Test Tables.');
			}
		}
	}
}
