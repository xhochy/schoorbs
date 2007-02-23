#!/usr/bin/php
<?php
/**
 * Generates automatically the sourcecode documentation of Schoorbs
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org> 
 */
 
$aDirs = array('.','auth','db','includes','schoorbs-includes',
	'schoorbs-includes/input','schoorbs-includes/rest-plugins',
	'session');
$aFiles = array();

foreach($aDirs as $sDir)
	foreach(glob($sDir.'/*.php') as $sFile)
		$aFiles[] = $sFile;

system('phpdoc -s on -dc "Schoorbs" -ti "Schoorbs Sourcecode Documentation" -t schoorbs-doc/ -f '.implode(',',$aFiles));
?>
