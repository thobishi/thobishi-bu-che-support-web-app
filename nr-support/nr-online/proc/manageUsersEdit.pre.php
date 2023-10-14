<?php
	
	$user_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
	$progSelectedArr = readPost('progSelected');
	$nr_restrictedArr = readPost('nr_restricted');
	$progAssignedArr = readPost('assigned');
	$nr_AssignedArr = readPost('nrc_assigned');
		
	if(!empty($progSelectedArr)){
		$this->saveMeetingRestriction($progSelectedArr, $user_id);
	}
	if(!empty($progAssignedArr)){
		$this->saveMeetingProgAssignment($progAssignedArr, $user_id);
	}
		
	if(!empty($nr_restrictedArr)){
		$this->saveMeetingRestriction($nr_restrictedArr, $user_id, 'nr_meeting_programmes_restrictions');
	}
	if(!empty($nr_AssignedArr)){
		$this->saveMeetingProgAssignment($nr_AssignedArr, $user_id, 'nr_meeting_programmes_assignment');
	}	
 ?>
