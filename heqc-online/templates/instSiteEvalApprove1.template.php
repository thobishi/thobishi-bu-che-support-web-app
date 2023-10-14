<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteEvalApprove1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Approve evaluator report for site visit</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!valCheckboxRequired(obj.readyForRecomm,'You need to indicate whether the application is ready for the Directorate recommendation before continuing.'))	{return false};
		}
		return true;
	}
SCRIPTTAIL;

?>
