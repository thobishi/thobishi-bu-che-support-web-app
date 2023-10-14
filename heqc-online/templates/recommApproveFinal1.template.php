<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "recommApproveFinal1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Search for user to appoint</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!document.defaultFrm.finalRecommApproval.checked){
				alert('You need to indicate whether the Directorate recommendation has passed final approval before continuing.');
				return false;
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
