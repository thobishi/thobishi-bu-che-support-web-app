<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteRecommApproveInter1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Intermediate Approval</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == '_changeSiteRecomm2ToCollegue') {
			if (!document.defaultFrm.interRecommApproval.checked){
				alert('You need to indicate whether the Directorate recommendation has passed intermediate approval before continuing.');
				return false;
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
