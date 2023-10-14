<?php
	echo '<div class= "alert alert-info">';
	echo '<strong>Choose the user to you would like to assign this process by selecting his/her email address from the dropdown below.</strong>';
	echo '</div>';
	$active_processes_id = $this->dbTableInfoArray['active_processes']->dbTableCurrentID;
	$this->showField("user_ref");
?>