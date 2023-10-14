<?php
	$groupId = $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID;
	$selectedUserArr = readPost('userSelected');
	$selectedProcessArr = readPost('selectedProcess');
	if(!empty($selectedUserArr)){
		$this->saveUserGroups($selectedUserArr, $groupId);
	}
	if(!empty($selectedProcessArr)){
		$this->saveGroupProcess($selectedProcessArr, $groupId);
	}
 ?>
