<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "siteACOutcomeApproval1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting Outcome> Approval</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.FLD_ac_outcome_approved_ind);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {;
			if (!valCheckboxRequired(obj,'The AC meeting outcome and minutes need to be marked as approved before continuing.'))	{return false};
	  	};
		return true;;
	};
SCRIPTTAIL;

?>
