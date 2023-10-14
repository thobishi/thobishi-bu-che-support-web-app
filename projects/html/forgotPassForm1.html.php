<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<span class="specialh">Forgot Password</span>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td colspan="2">Make sure your e-mail address is correct before pressing the 'Send Password' button.</td>
</tr><tr>
	<td colspan="2">Remember that your password will be changed and e-mailed to you by the system.</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><?
if (isset($_POST["email"])) {
	$message = "";
	$passwd = $this->makePassword(8,4);
	$SQL = "UPDATE `users` SET passwd=PASSWORD('".$passwd."') WHERE email='".$_POST["email"]."'";
	$rs = mysqli_query($SQL);
	$num = mysqli_affected_rows();
	if ($num > 0) {
	?>
<tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="center">

	<?
		echo "<span class='info'>Your password has been changed successfully<br>Your new password was sent to your e-mail address.</span>";
		$to = $_POST["email"];
		$subject = "Password Confirmation";
		$varArr["USERNAME"] = $this->getValueFromTable("users", "email", $to, "name")." ".$this->getValueFromTable("users", "email", $to, "surname");
		$varArr["PASS"] = $passwd;
		$message = $this->getTextContent ("forgotPassForm1", "PassReq", $varArr);
		$this->misMailByName ($to, $subject, $message, "", false);

	}else {
	?>
<tr>
	<td width="30%" align="right"><b>E-mail address:</b></td>
	<td width="70%"><?php echo $this->showField("email") ?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><input class="btn" type="button" value="Send Password" onClick="moveto('stay');"></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="center">
	<?
		echo "<span class='expiry'>Your e-mail address was not found in the database.<br>Please make sure you have typed it correctly and try again.</font>";
	}
}else{
	?>
<tr>
	<td width="30%" align="right"><b>E-mail address:</b></td>
	<td width="70%"><?php echo $this->showField("email")?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><input class="btn" type="button" value="Send Password" onClick="moveto('stay');"></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="center">
	<?

}
?>
	</td>
</tr></table>
<br><br>
</td></tr></table>
