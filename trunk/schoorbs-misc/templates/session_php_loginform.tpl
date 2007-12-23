<p>{get_vocab text="please_login"}</p>
<form method="post" action="{$action_url}">
  <table>
    <tr>
      <td align="right">{get_vocab text="user_name"}</td>
      <td><input type="text" name="NewUserName" /></td>
    </tr>
    <tr>
      <td align="right">{get_vocab text="user_password"}</td>
      <td><input type="password" name="NewUserPassword" /></td>
    </tr>
  </table>
  <div class="default_class">
  	<input type="hidden" name="TargetURL" value="{$TargetURL}" /><br />
  	<input type="hidden" name="Action" value="SetName" /><br />
  	<input type="submit" value="{get_vocab text="login"}" /><br />
  </div>
</form>
</body>
</html>
