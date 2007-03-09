    <td class="banner" style="background-color: #c0e0ff" align="center">
{if $user neq ""}
        <a href="{$search_string|escape:"html"}" title="{get_vocab text="show_my_entries"}">{get_vocab text="you_are"} {$user}</a><br />
        <form method="post" action="admin.php">
            <div>
	        <input type="hidden" name="TargetURL" value="{$TargetURL|escape:"htmlall"}" />
	        <input type="hidden" name="Action" value="SetName" />
	        <input type="hidden" name="NewUserName" value="" />
	        <input type="hidden" name="NewUserPassword" value="" />
	        <input type="submit" value="{get_vocab text="logoff"}" />
{else}
        <a href="#self">{get_vocab text="unknown_user"}</a><br />
        <form method="post" action="admin.php">
            <div>
            <input type="hidden" name="TargetURL" value="{$TargetURL|escape:"htmlall"}" />
            <input type="hidden" name="Action" value="QueryName" />
            <input type="submit" value="{get_vocab text="login"}" />
{/if}
            </div>
        </form>
        {if $user_list_link neq ""}<br /><a href="{$user_list_link}">{get_vocab text="user_list"}</a><br />{/if}
    </td>
