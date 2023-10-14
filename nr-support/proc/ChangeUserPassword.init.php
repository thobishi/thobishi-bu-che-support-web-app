<?php
	
	$newPassword = readPost('newPassword');
	$user_id = $this->dbTableInfoArray['users']->dbTableCurrentID;
	$SQL = "SELECT * FROM ".$this->userTable." WHERE UPPER(".$this->usernameField.") = UPPER(:user) AND ".$this->passwordField." = PASSWORD(:pass)";
	
	if($newPassword > ""){
		$sql = "UPDATE `users` SET password = PASSWORD2 (:newPassword) WHERE user_id = :user_id";
		$rs = $this->db->query($sql, compact('newPassword', 'user_id'));
	}
 ?>
