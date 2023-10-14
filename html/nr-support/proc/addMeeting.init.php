<?php
	
	$meeting_id = $this->dbTableInfoArray["nr_meetings"]->dbTableCurrentID;
	
	$progSelectedArr = readPost('progSelected');
	$selectedMemberArr = readPost('memSelected');
	if(!empty($progSelectedArr)){
		$this->saveMeetingProg($progSelectedArr, $meeting_id);
	}
		
	if(!empty($selectedMemberArr)){
		$this->saveMeetingMembers($selectedMemberArr, $meeting_id);
	}
 ?>
