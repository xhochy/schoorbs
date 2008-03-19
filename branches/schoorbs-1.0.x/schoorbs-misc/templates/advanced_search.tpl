<h3>{get_vocab text="advanced_search"}</h3>
<form method="get" action="search.php">
	<div class="advanced_search">
		{get_vocab text="search_for"} <input type="text" size="25" name="search_str" /><br />
		{get_vocab text="from"}
		{genDateSelector prefix="" day=$day month=$month year=$year}
		<br /><input type="submit" value="{get_vocab text="search_button"}" />
	</div>
</form>
	