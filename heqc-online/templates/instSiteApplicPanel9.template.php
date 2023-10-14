<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteApplicPanel9";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Upload site visit reports</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (obj.sites_valid_ind.value == 0){
				alert('Please upload the site visit report for each site before continuing.');	
				return false;
			}
			if (!valCheckboxRequired(document.defaultFrm.FLD_eval_complete_ind,'The application needs to be marked as ready for management approval before continuing.'))	{return false;}
		}
		return true;
	}
SCRIPTTAIL;
?>
