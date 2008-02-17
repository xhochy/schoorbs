<h3>{get_vocab text="about_mrbs"}</h3>
<p>
	<a href="http://code.google.com/p/schoorbs">{get_vocab text="mrbs"}</a> - {$mrbs_version}
	<br />{get_vocab text="database"} {$sql_version}
	<br />{get_vocab text="system"} {$php_uname}
	<br />PHP: {$phpversion}
</p>
<h3>{get_vocab text="help"}</h3>
<p>
	{get_vocab text="please_contact"}
	<a href="mailto:{$schoorbs_admin_email|escape:"hex"}">{$schoorbs_admin}</a> 
	{get_vocab text="for_any_questions"}
</p>