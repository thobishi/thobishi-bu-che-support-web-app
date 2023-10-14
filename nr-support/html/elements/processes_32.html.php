<?php
	$currentUserID = Settings::get('currentUserID');
	$restrictionsArr = $this->getMeetingRestriction($currentUserID);

	$meetingDetails = $this->getMeetingDetails("rg_meeting_members","rg_meeting_id", "rg_meeting_members.user_id","rgc_access_start_date <= CURDATE() AND rgc_access_end_date  >= CURDATE()", $currentUserID, array(), "", "rg_meeting", array_filter($restrictionsArr[$currentUserID]['progIdArr']));

	echo $this->element('rc_member_data', compact('meetingDetails'));
?>