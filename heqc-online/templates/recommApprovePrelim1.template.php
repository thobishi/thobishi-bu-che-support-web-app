<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "recommApprovePrelim1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Preliminary approval</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.readyForACMeeting);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.readyForACMeeting.checked == true ) {
			// 2012-04-17 Robin: Change flag to proceedings i/o applications table
				//document.defaultFrm.FLD_application_status.value = 1;
				document.defaultFrm.FLD_application_status_ref.value = 1;
			}else {
				//document.defaultFrm.FLD_application_status.value = 4;
				document.defaultFrm.FLD_application_status_ref.value = 4;
		}
		if (document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == '_changeDirRecomm1ToCollegue')  {
			if (document.defaultFrm.FLD_recomm_complete_ind.value == 0){
				alert('You may not continue if the user doing the directorate recommendation has not indicated that the recommendation is complete. Please review the recommendation and indicate that it has been approved.');
				return false;
			}
			if (!obj.checked) {
			    alert('The application needs to be marked as approved and ready to be assigned to an AC Meeting  before continuing.');
		        return false;
		    }
	  	}		
		if (document.defaultFrm.MOVETO.value == '_returnAppRecommAppoint')  {
			document.defaultFrm.FLD_application_status_ref.value = 4;
	  	}

		return true;
	}

	//if (document.defaultFrm.FLD_application_status.value == 1) {
	if (document.defaultFrm.FLD_application_status_ref.value == 1) {
		document.defaultFrm.readyForACMeeting.checked=true;
	}

	//if (document.defaultFrm.FLD_application_status.value == 4) {
	if (document.defaultFrm.FLD_application_status_ref.value == 4) {
		document.defaultFrm.readyForACMeeting.checked=false;
	}
	
SCRIPTTAIL;
?>
