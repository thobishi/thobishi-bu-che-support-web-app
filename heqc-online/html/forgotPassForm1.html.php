<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<span class="specialb">Reset Password:</span>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td colspan="2">
	To reset your password, enter the e-mail address associated with your HEQC-online profile, then click "Reset password".
	<br>
	Your current password will be replaced with a new system-generated password and e-mailed to you automatically.
</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><?php
if (isset($_POST["email"])) {
	$message = "";
	$passwd = $this->makePassword(8,4);
	$SQL = "UPDATE `users` SET password=PASSWORD2('".$passwd."') WHERE email='".$_POST["email"]."'";
	$conn = $this->getDatabaseConnection();
	$rs = mysqli_query($conn, $SQL);
	$num = mysqli_affected_rows($conn);
	if ($num > 0) {
	?>
<tr>
	<td colspan="2" align="center">

	<?php
		echo "<font color='blue'>Your password has been reset successfully.<br>Your login details will be e-mailed to the e-mail address that is associated with your HEQC-online profile (<b>".readPOST('email')."</b>) shortly.</font>";
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
	<td width="70%"><?php $this->showField("email") ?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><input class="btn" type="button" value="Reset Password" onClick="moveto('stay');"></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="center">
	<?php
		echo "<font color='red'>Your e-mail address was not found in the database.<br>Please make sure you have typed it correctly and try again.</font>";
                    }
            }else{
	?>
<tr>
	<td width="30%" align="right"><b>E-mail address:</b></td>
	<td width="70%"><?php $this->showField("email")?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td><input class="btn" type="button" value="Reset Password" onClick="moveto('stay');"></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="center">
	<?php

}
?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<span class="specialb">Has your e-mail address changed?</span> If you have forgotten your password and no longer use the e-mail address associated with your HEQC-online profile, please send an e-mail to <b> <?php echo $this->getValueFromTable("users", "user_id", $this->getValueFromTable("settings", "s_key", "usr_user_administration", "s_value"), "email")?> </b>.
	</td>
</tr>
</table>
<br><br>
</td></tr></table>
