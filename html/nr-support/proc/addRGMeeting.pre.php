<?php
	$activeNrIds = $this->listActiveNR_ids();

	$this->formFields['nr_national_review_id']->fieldValue = $activeNrIds;

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
