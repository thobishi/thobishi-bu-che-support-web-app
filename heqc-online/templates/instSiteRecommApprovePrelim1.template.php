<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteRecommApprovePrelim1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Site Directorate Recommendation> Preliminary approval</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == '_changeSiteRecomm1ToCollegue') {
			if (document.defaultFrm.FLD_recomm_complete_ind.value == 0){
				alert('You may not continue if the user doing the directorate recommendation has not indicated that the recommendation is complete. Please review the recommendation and indicate that it has been approved.');
				return false;
			}
			if (!document.defaultFrm.prelimRecommApproval.checked){
				alert('You need to indicate whether the Directorate recommendation has passed preliminary approval before continuing.');
				return false;
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
