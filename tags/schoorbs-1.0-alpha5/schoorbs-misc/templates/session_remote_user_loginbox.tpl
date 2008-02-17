    <td class="banner" style="background-color: #c0e0ff; text-align: center">
        {get_vocab text="you_are"} {$user}<br />
        <a href="#self">{get_vocab text="unknown_user"}</a><br />
        {if $user_list_link neq ""}<a href="{$user_list_link}">{get_vocab text="user_list"}</a><br />{/if}
        {if $logout_link neq ""}<a href="{$logout_link}">{get_vocab text="logoff"}</a><br />{/if}
    </td>
