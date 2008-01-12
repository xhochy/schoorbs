<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
    <title>{$mrbs_company|strip_tags} - Schoorbs</title>
    <link rel="stylesheet" type="text/css" href="schoorbs-misc/style/style.css" />
    <link rel="home" href="index.php" />

    <script type="text/javascript" src="schoorbs-misc/js/jquery.pack.js"></script>
    <script type="text/javascript" src="schoorbs-misc/js/schoorbs.js"></script>
    <!-- compliance patch for microsoft browsers -->
    <!--[if lt IE 7]>
    <script type="text/javascript">
        IE7_PNG_SUFFIX = ".png";
    </script>
    <script src="schoorbs-misc/js/ie7/ie7-standard-p.js" type="text/javascript"></script>
    <![endif]-->
</head>
<body>
	<div id="menu_logo">
		<br />
		<a class="headerLink" style="color: #55555;" href="index.php" rel="home">{$mrbs_company}</a>
		<br /><br />
		<small class="vcard"><a class="url" href="http://schoorbs.xhochy.com">Powered by <span class="fn org">Schoorbs</span></a></small>
	</div>
	<div id="menu">
		{if $logonbox neq ""}
			<table width="120" style="float: right;">
	    	<tr>
	    		{$logonbox}
	    	</tr>
	    	</table>
    	{/if}
		<br />
		<a class="menu_link" href="help.php"><img src="schoorbs-misc/gfx/help.png" class="menu_icon" alt="{get_vocab text="help"}" />{get_vocab text="help"}</a>
		&nbsp;|&nbsp;
		<!-- Report link deactivated due to deactivated report-page -->
		<!--
		<a class="menu_link" href="report.php"><img src="schoorbs-misc/gfx/report.png" class="menu_icon" alt="{get_vocab text="report}" />{get_vocab text="report}</a>
		&nbsp;|&nbsp;
		-->
		<a class="menu_link" href="admin.php"><img src="schoorbs-misc/gfx/admin.png" class="menu_icon" alt="{get_vocab text="admin"}" />{get_vocab text="admin"}</a>
		&nbsp;|&nbsp;
		<form action="day.php" method="get">
        	<div id="menu_selector">
        		<img src="schoorbs-misc/gfx/view.png" class="menu_icon" alt="{get_vocab text="search"}" />
           		{genDateSelector prefix=$prefix day=$Day month=$Month year=$Year}
	   			{if $Area neq ""}
	     			<input type="hidden" name="area" value="{$Area}" />
	   			{/if}
           		<script type="text/javascript">
           			ChangeOptionDays(''); // Note: The 2nd arg must match the first in the call to genDateSelector above.
           		</script>
           		<input type="submit" value="{get_vocab text="viewday"}" />
           </div>
		</form>
		&nbsp;&nbsp;
		<!-- Search deactivated due to deactivated search page -->
		<!--<form method="get" action="search.php">
        	<div id="menu_searchbox">
        		<a class="menu_link" style="font-size: 14px;" href="search.php?advanced=1">
        			<img src="schoorbs-misc/gfx/find.png" class="menu_icon" alt="{get_vocab text="search"}" />
        			{get_vocab text="search"}
        		</a>
           		<input type="text" name="search_str" value="{$search_str}" size="10" />
           		<input type="hidden" name="day" value="{$Day}" />
           		<input type="hidden" name="month" value="{$Month}" />
           		<input type="hidden" name="year" value="{$Year}" />
           		{$pview}
        	</div>
    	</form>-->
    	<br />
    	&nbsp;
	</div>
	<hr />
