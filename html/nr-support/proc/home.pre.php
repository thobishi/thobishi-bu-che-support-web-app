<?php
	if(isset($_POST["oct_username"]) && ($_POST["oct_passwd"] > "")){
		$pass = $_POST["oct_passwd"];
		$this->checkUserLogin(Settings::get('currentUserID'), $pass);
	}
?>