<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "heqcOutcomeApproval1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>HEQC Meeting Outcome> Approval</span>";

/*$this->formOnSubmit = "return checkFrm(document.defaultFrm.FLD_heqc_outcome_approved_ind);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {;
			if (!valCheckboxRequired(obj,'The HEQC meeting outcome and minutes need to be marked as approved before continuing.'))	{return false};
	  	};
		return true;;
	};
SCRIPTTAIL;*/

?>
