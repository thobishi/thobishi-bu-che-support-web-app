<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td class="special1" colspan=2>
	<br>
<?
	global $login_message;
	echo $login_message;
?>
	<br>
	<br>
	<br>
	Please supply your login information.
	<br>
	<br>
</td>
</tr><tr>
<td align=right><b>Username:</b></td>
<td><?php echo $this->showField("oct_username") ?></td>
</tr><tr>
<td align=right><b>Password:</b></td>
<td><?php echo $this->showField("oct_passwd") ?> <input type="submit" class="btn" name="login" value="Login" onclick="moveto('next');"></td>
</tr>
</table>
<br><br>
