<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "recommApproveInter1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Intermediate Approval</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.interRecommApproval);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {

		if (document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == '_changeDirRecomm2ToCollegue')  {
			if (!obj.checked) {
			    alert('The application needs to be marked as approved before continuing.');
		        return false;
		    }
	  	}		

		return true;
	}

SCRIPTTAIL;
?>
