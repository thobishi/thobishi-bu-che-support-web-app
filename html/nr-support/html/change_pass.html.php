<table>
	<tr>
		<td width="30%" align="right"><b>New Password:</b></td>
		<td width="70%"><?php $this->showField("password")?></td>
	</tr>
	<tr>
		<td width="30%" align="right"><b>Confirm Password:</b></td>
		<td width="70%"><?php $this->showField("password_confirm") ?></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="button" class="btn" value="Change Password" onClick="checkPass(document.defaultFrm.password, document.defaultFrm.password_confirm);"></td>
	</tr>
</table>
<?php
	if(isset($_POST["password"]) && ($_POST["password"] > "")){
		$pass = $_POST["password"];
		$SQL = "UPDATE `users` " . 
					 "SET password=PASSWORD(:pass)" . 
					 "WHERE user_id = " . Settings::get('currentUserID');
		$this->db->query($SQL, compact('pass'));
		echo '<script>goto('.__HOMEPAGE.');</script>';
	}
?>