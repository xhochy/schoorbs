#!/usr/bin/env php
<?php
// Load the english translation
require dirname(__FILE__).'/../schoorbs-includes/lang/lang.en.php';
// Don't translate the charset helper, this is no string ;-)
unset($vocab['charset']);
$aEnglish = $vocab;
$vocab = null;

// Load the other translation
$sInFile = $_SERVER['argv'][1];
require $sInFile;

foreach ($aEnglish as $sKey=>$sId) {
	echo "msgid \"$sId\"\n";
	echo "msgstr \"${vocab[$sKey]}\"\n\n";
}
