<h3>Reset Password</h3>
<p>
	To reset your password, enter the e-mail address associated with your National Reviews Online profile, then click "Reset password".
	<br />
	Your current password will be replaced with a new system-generated password and e-mailed to you automatically.
</p>
<?php
	if (isset($_POST["email"])) {
		$emailValue = $_POST["email"];
		$passwd = $this->makePassword(8,4);
		$message = "";
		
		$SQL = "UPDATE `users` SET password = PASSWORD(:passwd) WHERE `email` = :emailValue";
		$rs = $this->db->query($SQL, compact('passwd', 'emailValue'));

		if($rs->rowCount() > 0){
			echo '<div class="alert alert-success">Your password has been reset successfully. <br />Your login details will be e-mailed to the e-mail address that is associated with your National Reviews Online profile (<b>' . $emailValue . '</b>) shortly.</div>';
			$to = $emailValue;
			$subject = "Password Confirmation";
			$varArr["USERNAME"] = $this->db->getValueFromTable("users", "email", $to, "name") . " " . $this->db->getValueFromTable("users", "email", $to, "surname");
			$varArr["PASS"] = $passwd;
			$message = $this->getTextContent("forgotPassword", "PassReq", $varArr);
			$this->Email->misMailByName($to, $subject, $message, "", false);

	}else{
		$this->showBootstrapField("email", "Email address");
	?>
		<input class="btn" type="button" value="Reset Password" onClick="moveto('stay');" />
		<br /><br />
	<?php
		echo '<div class="alert alert-error">Your e-mail address was not found in the database.<br>Please make sure you have typed it correctly and try again.</div>';
	}
}else{
	$this->showBootstrapField('email', 'Email address');
	?>
	<input class="btn" type="button" value="Reset Password" onClick="moveto('stay');" />
	<?php
}
?>
	<br /><br />
	<div class="alert">
		<span class="specialb">Has your e-mail address changed?</span> If you have forgotten your password and no longer use the e-mail address associated with your National Reviews Online profile, please send an e-mail to <b> nr-online@che.ac.za </b>.
	</div>