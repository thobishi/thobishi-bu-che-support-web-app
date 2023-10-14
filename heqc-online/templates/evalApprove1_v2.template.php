<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalApprove1_v2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Approve evaluator report</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (typeof obj.FLD_condition_confirm_ind != "undefined"){
				if (!valNumberRequired(obj.FLD_condition_confirm_ind,'You may not continue if the confirmation of the evaluation of conditions has not been done. Please click on the icon in the Report link column.'))	{return false};
			}
			if (!valCheckboxRequired(obj.readyForRecomm,'You need to indicate whether the evaluation of conditions has been checked and confirmed before continuing.'))	{return false};
		}
		return true;
	}
SCRIPTTAIL;

?>
