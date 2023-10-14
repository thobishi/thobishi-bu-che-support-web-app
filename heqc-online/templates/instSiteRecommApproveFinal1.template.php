<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteRecommApproveFinal1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Search for user to appoint</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.readyForACMeeting);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {;
			if (!obj.checked) {;
			    alert('The application needs to be marked as approved and ready to be assigned to an AC Meeting  before continuing.');;
		        return false;;
		    };
	  	};
		return true;;
	};
SCRIPTTAIL;
?>
