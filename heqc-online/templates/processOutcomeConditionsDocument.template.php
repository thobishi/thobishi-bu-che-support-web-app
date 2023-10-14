<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "processOutcomeConditionsDocument";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Conditions Document> Identify type of condition addressed</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			var chk = document.getElementsByName('cond_term[]');
			if (!valCheckboxArrayRequired(chk,'Please check the condition terms that the conditions document addresses before proceeding.'))		{return false;}
		}
		return true;
	}
SCRIPTTAIL;

?>
