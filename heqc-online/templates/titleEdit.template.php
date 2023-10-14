<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "titleEdit";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Titles</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valTextRequired(document.defaultFrm.FLD_new_title,'Please enter the new programme name before saving.'))		{return false;}
			if (!valTextRequired(document.defaultFrm.FLD_reason,'Please enter a reason for changing the programme name.'))		{return false;}
			return true;
		}
	}
SCRIPTTAIL;
?>
