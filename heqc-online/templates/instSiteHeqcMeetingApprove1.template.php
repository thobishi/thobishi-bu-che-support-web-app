<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteHeqcMeetingApprove1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>HEQC Meeting > Assign to meeting </span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.FLD_heqc_meeting_ready_ind);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.checked == true) {;
			document.defaultFrm.FLD_application_status_ref.value = 5; // Ready for HEQC Meeting
		}else {;
			document.defaultFrm.FLD_application_status_ref.value = 3; // Been through AC Meeting
		};
		if (document.defaultFrm.MOVETO.value == 'next')  {;
			if (!valCheckboxRequired(obj,'The site application needs to be marked as approved and ready to be assigned to a HEQC Meeting  before continuing.'))	{return false};
	  	};
		return true;;
	};
SCRIPTTAIL;
?>
