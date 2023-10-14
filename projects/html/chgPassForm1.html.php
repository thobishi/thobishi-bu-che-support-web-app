<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td width="30%" align="right"><b>New Password:</b></td>
		<td width="70%"><?php echo $this->showField("password")?></td>
	</tr>
	<tr>
		<td width="30%" align="right"><b>Confirm Password:</b></td>
		<td width="70%"><?php echo $this->showField("password_confirm") ?></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="button" class="btn" value="Change Password" onClick="checkPass(document.defaultFrm.password, document.defaultFrm.password_confirm);"></td>
	</tr>
</table>

<?
	if (isset($_POST["password"]) && ($_POST["password"] > "")) {
		$SQL = "UPDATE `users` SET passwd=PASSWORD('".$_POST["password"]."') WHERE user_id=".$this->currentUserID;
		$rs = mysqli_query($SQL);
		$num = mysqli_affected_rows();
		if ($num > 0) {
			echo "<span class='info'><br>Your password has been changed successfully. Please click 'Close'.</span>";
//			echo '<script>goto('.__HOMEPAGE.');</script>';
		}
	}

?>
<br><br>
</td></tr>
</table>
