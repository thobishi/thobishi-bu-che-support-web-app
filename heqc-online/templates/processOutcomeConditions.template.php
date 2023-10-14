<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "processOutcomeConditions";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Outcome> Result of outcome</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valDocRequired(document.defaultFrm.FLD_condition_doc,'Please upload the compliance with conditions document received from the institution.'))		{return false;}
			if (!valDateRequired(document.defaultFrm.FLD_conditions_submission_date,'Please enter the date that the compliance document was received before continuing.'))		{return false;}
	  	}
		return true;
	}
SCRIPTTAIL;

?>
