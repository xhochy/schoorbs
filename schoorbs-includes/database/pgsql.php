<?php
/**
 * Simple PHP database support for PostgreSQL.
 * 
 * Include this file after defining the following variables:<br />
 *   $db_host = The hostname of the database server<br />
 *   $db_login = The username to use when connecting to the database<br />
 *   $db_password = The database account password<br />
 *   $db_database = The database name.<br />
 * Including this file connects you to the database, or exits on error.
 * 
 * @author jflarvoire, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/DB/PostgreSQL
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Includes ##

/** The Configuration file */
require_once dirname(__FILE__).'/../../config.inc.php';

## Functions ##

/**
 * Free a results handle. You need not call this if you call sql_row
 * until the row returns 0, since sql_row frees the results
 * handle when you finish reading the rows.
 * 
 * @param PostgreSQL-Result $r
 */
function sql_free ($r)
{
	pg_free_result($r);
}

/**
 * Execute a non-SELECT SQL command (insert/update/delete).
 * Returns the number of tuples affected if OK (a number >= 0).
 * Returns -1 on error; use sql_error to get the error message.
 * 
 * @param string $sql The SQL-Query
 * @return int The number of affected rows 
 */
function sql_command ($sql)
{
	global $db_c;
	
	$r = @pg_query($db_c, $sql);
	if (!$r) return -1;
	$n = pg_affected_rows($r);
	pg_free_result($r);
	return $n;
}

/**
 * Execute an SQL query which should return a single non-negative number value.
 * This is a lightweight alternative to sql_query, good for use with count(*)
 * and similar queries. It returns -1 on error or if the query did not return
 * exactly one value, so error checking is somewhat limited.
 * It also returns -1 if the query returns a single NULL value, such as from
 * a MIN or MAX aggregate function applied over no rows.
 * 
 * @param string $sql The SQL Query
 * @return mixed The first column of the first row
 */
function sql_query1 ($sql)
{
	global $db_c;
	
	$r = @pg_query($db_c, $sql);
	if(! $r) return -1;
	if(pg_num_rows($r) != 1 
		|| pg_num_fields($r) != 1
		|| ($result = pg_fetch_result($r, 0, 0)) == "") { 
		$result = -1;
	}
	pg_free_result($r);
	return $result;
}

/**
 * Execute an SQL query. Returns a database-dependent result handle,
 * which should be passed back to sql_row or sql_row_keyed to get the results.
 * Returns 0 on error; use sql_error to get the error message.
 * 
 * @param string $sql The SQL Query
 * @return PostgreSQL-Result-Handle
 */
function sql_query ($sql)
{
	global $db_c;
	
	$r = @pg_query($db_c, $sql);
	
	return $r;
}

/**
 * Return a row from a result. The first row is 0.
 * The row is returned as an array with index 0=first column, etc.
 * When called with i >= number of rows in the result, cleans up from
 * the query and returns 0.
 * 
 * <code>
 * // Typical usage:
 *for($i = 0; $data = sql_row($result, $i); $i++)
 *{
 *      // ...
 *} 	
 * </code>
 * 
 * @param PostgreSQL-Result-Handle $r
 * @param int $i The number of the row to fetch
 * @return array
 */
function sql_row ($r, $i)
{
	if ($i >= pg_num_rows($r))
	{
		pg_free_result($r);
		return 0;
	}
	return pg_fetch_row($r, $i);
}

/**
 * Return the number of rows returned by a result handle from sql_query.
 * 
 * @param PostgreSQL-Result-Handle $r
 * @return int
 */
function sql_count ($r)
{
	return pg_num_rows($r);
}

/**
 * Return the value of an autoincrement field from the last insert.
 * For PostgreSQL, this must be a SERIAL type field.
 * 
 * @param string $table
 * @param string $field
 * @return int a new ID
 */
function sql_insert_id($table, $field)
{
	$seq_name = $table . "_" . $field . "_seq";
	return sql_query1("SELECT last_value FROM $seq_name");
}

/**
 * Return the text of the last error message.
 * 
 * @return string The error message
 */
function sql_error()
{
	global $db_c;
	return pg_last_error($db_c);
}

/**
 * Begin a transaction, if the database supports it. This is used to
 * improve PostgreSQL performance for multiple insert/delete/updates.
 * There is no rollback support, since MySQL doesn't support it in 
 * older versions.
 */
function sql_begin()
{
	sql_command("BEGIN");
}

/**
 * Commit (end) a transaction.
 * 
 * @see sql_begin()
 */
function sql_commit()
{
	sql_command("COMMIT");
}

/**
 * Acquire a mutual-exclusion lock on the named table. For portability:
 * This will not lock out SELECTs.
 * It may lock out DELETE/UPDATE/INSERT or not, depending on the implementation.
 * It will lock out other callers of this routine with the same name argument.
 * It may timeout in 20 seconds and return 0, or may wait forever.
 * It returns 1 when the lock has been acquired.
 * Caller must release the lock with sql_mutex_unlock().
 * Caller must not have more than one mutex at any time.
 * 
 * Do not mix this with sql_begin()/sql_end() calls.
 * 
 * In PostgreSQL, the EXCLUSIVE mode lock excludes all but SELECT.
 * It does not timeout, but waits forever for the lock.
 * 
 * @param string $name The name of the table which should be locked
 * @return int 1, if successful
 */
function sql_mutex_lock($name)
{
	global $sql_mutex_shutdown_registered, $sql_mutex_unlock_name;
	if (sql_command("BEGIN") < 0
		|| sql_command("LOCK TABLE $name IN EXCLUSIVE MODE") < 0) return 0;
	$sql_mutex_unlock_name = $name;
	if (empty($sql_mutex_shutdown_registered))
	{
		register_shutdown_function("sql_mutex_cleanup");
		$sql_mutex_shutdown_registered = 1;
	}
	return 1;
}

/**
 * Release a mutual-exclusion lock on the named table. See sql_mutex_lock.
 * In PostgreSQL, all locks are released by closing the transaction; there
 * is no other way.
 * 
 * @param string $name The name of the table which should be unlocked
 */
function sql_mutex_unlock($name)
{
	global $sql_mutex_unlock_name;
	sql_command("COMMIT");
	$sql_mutex_unlock_name = "";
}

/**
 * Shutdown function to clean up a forgotten lock. For internal use only.
 */
function sql_mutex_cleanup()
{
	global $sql_mutex_shutdown_registered, $sql_mutex_unlock_name;
	if (!empty($sql_mutex_unlock_name))
	{
		sql_command("ABORT");
		$sql_mutex_unlock_name = "";
	}
}

// 
/**
 * Generate non-standard SQL for LIMIT clauses.
 * 
 * @param int $count the number of rows which will be fetched
 * @param int $offset
 * @return string String that could be used in a SQL-Query 
 */
function sql_syntax_limit($count, $offset)
{
	return " LIMIT $count OFFSET $offset ";
}

/**
 * Generate non-standard SQL to output a TIMESTAMP as a Unix-time:
 * 
 * @param string $fieldname The name of the field containing the date information
 * @return string String that could be used in a SQL-Query
 */
function sql_syntax_timestamp_to_unix($fieldname)
{
	return " DATE_PART('epoch', $fieldname) ";
}

/**
 * Generate non-standard SQL to match a string anywhere in a field's value
 * in a case insensitive manner. $s is the un-escaped/un-slashed string.
 * In PostgreSQL, we can do case insensitive regexp with ~*, but not case
 * insensitive LIKE matching.
 * Quotemeta escapes everything we need except for single quotes.
 * 
 * @param string $fieldname
 * @param string $s
 * @return string
 */
function sql_syntax_caseless_contains($fieldname, $s)
{
	$s = quotemeta($s);
	$s = str_replace("'", "''", $s);
	return " $fieldname ~* '$s' ";
}

/**
 * Escapes an SQL parameter
 * 
 * @param string $sArg
 * @return string
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function sql_escape_arg($sArg)
{
	return pg_escape_string($sArg);
}

/**
 * Establish a database connection.
 * On connection error, the message will be output without a proper HTML
 * header. There is no way I can see around this; if track_errors isn't on
 * there seems to be no way to supress the automatic error message output and
 * still be able to access the error text.
 * 
 * This function is called automatically when this file is included!
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return PostgreSQL-Connection-Handler
 * @param string $db_login The username for the PostgreSQL Database
 * @param string $db_password The password for the PostgreSQL Database
 * @param string $db_database The database which should be used
 * @param string $db_host The host on which PostgreSQL runs
 */
function sql_connect($db_login, $db_password, $db_database, $db_host = '')
{
	$conninfo = (empty($db_host) ? "" : "host=$db_host ")
		. "dbname=$db_database user=$db_login password=$db_password";
	if(empty($db_nopersist) || $db_nopersist == true)
		$db_c = @pg_pconnect($conninfo);
	else
		$db_c = @pg_connect($conninfo);
	
	if(!$db_c) fatal_error(true,get_vocab("failed_connect_db"));
	
	return $db_c;
}

/**
 * Autmatically connects to the database, when this file is included
 */
$db_c = sql_connect($db_login, $db_password, $db_database, $db_host);
