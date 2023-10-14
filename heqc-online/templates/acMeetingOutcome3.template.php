<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "acMeetingOutcome3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting> Result of outcome</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.HEQC_meeting_ind);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valCheckboxRequired(obj,'The application needs to be marked as ready to be assigned to a HEQC Meeting before continuing.'))	{return false};
	  	};
		return true;;
	};

SCRIPTTAIL;
?>
