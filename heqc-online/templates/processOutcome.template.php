<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "processOutcome";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Outcome> Result of outcome</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valCheckboxRequired(document.defaultFrm.FLD_decision_approved_ind,'Please confirm that the outcome is correct before continuing.'))	{return false;}
			if (!valDocRequired(document.defaultFrm.FLD_decision_doc,'Please upload the decision letter to the institution before continuing.'))		{return false;}
			if (document.defaultFrm.FLD_condition_prior_due_date != undefined)
			{
				if (!valDateRequired(document.defaultFrm.FLD_condition_prior_due_date,'Please enter the prior to commencement due date before continuing.'))		{return false;}
			}
			if (document.defaultFrm.FLD_condition_short_due_date != undefined)
			{
				if (!valDateRequired(document.defaultFrm.FLD_condition_short_due_date,'Please enter the short term due date before continuing.'))		{return false;}
			}
			if (document.defaultFrm.FLD_condition_long_due_date != undefined)
			{
				if (!valDateRequired(document.defaultFrm.FLD_condition_long_due_date,'Please enter the long term due date before continuing.'))		{return false;}
			}
			if ((document.defaultFrm.FLD_heqc_board_decision_ref.value == 3 && document.defaultFrm.FLD_lkp_proceedings_ref.value != 3) || document.defaultFrm.FLD_heqc_board_decision_ref.value == 4){
				if (!valDateRequired(document.defaultFrm.FLD_heqc_decision_due_date,'Please enter the due date before continuing.'))		{return false;}
			}
	  	}
		return true;
	}
SCRIPTTAIL;

?>
