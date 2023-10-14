<?php
	$currentUserID = Settings::get('currentUserID');
	$restrictionsArr = $this->getMeetingRestriction($currentUserID, 'nr_meeting_programmes_restrictions');
	// echo $this->element('filters/' . Settings::get('template'), $_POST);
	$meetingDetails = $this->getMeetingDetails("nr_meeting_members","nr_meeting_id", "nr_meeting_members.user_id","nrc_access_start_date <= CURDATE() AND nrc_access_end_date  >= CURDATE()", $currentUserID,array(),"","",array_filter($restrictionsArr[$currentUserID]['progIdArr']));
	
	echo $this->element('nrc_member_data', compact('meetingDetails'));
?>