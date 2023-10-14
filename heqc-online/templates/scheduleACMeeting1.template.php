<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "scheduleACMeeting1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting > Schedule</span>";

//$this->createAction ("next", "Next", "submit", "", "ico_next.gif");



$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL

	function checkFrm(obj) {
		
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!valTextRequired(obj.FLD_ac_start_date,'Please enter the ac start date.')) {return false};
			if (!valTextRequired(obj.FLD_ac_to_date,'Please enter the ac end date.')) {return false};
			if (!valTextRequired(obj.FLD_ac_meeting_venue,'Please enter the ac meeting venue.')) {return false};
								}
		return true;
	}
SCRIPTTAIL;



?>