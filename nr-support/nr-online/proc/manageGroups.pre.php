<?php
	// $this->pr($this);
	// $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID = "NEW";
	$this->clearWorkflowSettings ();
	// $groupId = $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID;
	// $selectedUserArr = readPost('userSelected');
	// $selectedProcessArr = readPost('selectedProcess');
	// if(!empty($selectedUserArr)){
		// $this->saveUserGroups($selectedUserArr, $groupId);
	// }
	
	// if(!empty($selectedProcessArr)){
		// $this->saveGroupProcess($selectedProcessArr, $groupId);
	// }
	$isAdministrator = $this->sec_partOfGroup(1);
	if(!$isAdministrator){
		echo '<script>';
		echo "$('#generalActions').hide();";
		echo '</script>';
		
	}
 ?>
