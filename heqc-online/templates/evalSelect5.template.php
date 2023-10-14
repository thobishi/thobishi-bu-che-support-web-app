<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalSelect5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Upload evaluator report</span>";

$this->formHidden["DELETE_RECORD"] = "";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= <<<SCRIPTAIL
	function checkFrm(obj) {
		var anyChecked = 0;
		if (obj.MOVETO.value == 'next')  {
			if (typeof obj.FLD_condition_complete_ind != "undefined"){
				if (!valNumberRequired(obj.FLD_condition_complete_ind,'You may not continue if the user doing the evaluation has not indicated that the evaluation is complete. Please review the evaluation and indicate that it has been approved.'))	{return false};
			}
			if (!valCheckboxRequired(obj.readyForApproval,'The application needs to be marked as ready for management approval before continuing.'))	{return false};
		}
		return true;
	}
SCRIPTAIL;
?>
