<?php
	echo $this->element('filters/' . Settings::get('template'), $_POST);
	$detailArr = $this->getUsersDetails($_POST, Settings::get('template'));
	
	if(!empty($detailArr)){
		echo $this->element('manageUsers',compact('detailArr'));
	}else{
		echo "No users found";
	}
?>
