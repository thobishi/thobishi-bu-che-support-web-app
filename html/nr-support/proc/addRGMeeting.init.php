<?php
	
	$meeting_id = $this->dbTableInfoArray["rg_meetings"]->dbTableCurrentID;
	
	$progSelectedArr = readPost('progSelected');
	$selectedMemberArr = readPost('memSelected');
	if(!empty($progSelectedArr)){
		$this->saveMeetingProg($progSelectedArr, $meeting_id, "rg_meeting");
	}
		
	if(!empty($selectedMemberArr)){
		$this->saveMeetingMembers($selectedMemberArr, $meeting_id, "rg_meeting");
	}
 ?>
