{if $user neq ""}
        <div id="schoorbs-loginbox-username">{get_vocab text="you_are"} {$user}</div>
        <form method="post" action="administration.php">
            <div id="schoorbs-loginbox-button">
	        <input type="hidden" name="TargetURL" value="{$TargetURL|escape:"htmlall"}" />
	        <input type="hidden" name="Action" value="SetName" />
	        <input type="hidden" name="NewUserName" value="" />
	        <input type="hidden" name="NewUserPassword" value="" />
	        <input type="submit" value="{get_vocab text="logoff"}" />
{else}
        <div id="schoorbs-loginbox-username">{get_vocab text="unknown_user"}</div>
        <form method="post" action="administration.php">
            <div id="schoorbs-loginbox-button">
            <input type="hidden" name="TargetURL" value="{$TargetURL|escape:"htmlall"}" />
            <input type="hidden" name="Action" value="QueryName" />
            <input type="submit" value="{get_vocab text="login"}" />
{/if}
            </div>
        </form>
        {if $user_list_link neq ""}<a href="{$user_list_link}">{get_vocab text="user_list"}</a>{/if}
