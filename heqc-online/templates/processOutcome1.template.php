<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "processOutcome1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Outcome> Result of outcome</span>";

$this->formHidden["DELETE_RECORD"] = ""; // Allow delete of uploaded documents

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valRadioRequired(document.defaultFrm.FLD_deferral_ind,'Please indicate whether deferral documents are expected or not before continuing.'))		{return false;}
			if (document.defaultFrm.FLD_deferral_ind[1].checked == true){
				if (!valDocRequired(document.defaultFrm.FLD_deferral_doc,'Please upload the deferral before continuing.'))		{return false;}
			}
	  	}
		return true;
	}
SCRIPTTAIL;
?>
