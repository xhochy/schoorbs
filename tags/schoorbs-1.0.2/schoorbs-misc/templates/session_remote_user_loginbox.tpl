    <td class="banner" style="background-color: #c0e0ff; text-align: center">
        {get_vocab text="you_are"} {$user}<br />
        {if $logout_link neq ""}<a href="{$logout_link}">{get_vocab text="logoff"}</a><br />{/if}
    </td>
