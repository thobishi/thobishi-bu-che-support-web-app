<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteApplic1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Schedule site visit</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
				if (!valSelectRequired(obj.FLD_institution_ref,'Please select the institution for which a site visit or site visits must be scheduled.'))	{return false;}
				if (!valTextRequired(obj.FLD_site_application_no,'Please enter the application number for this site visit application.'))	{return false;}
		}
		return true;
	}
SCRIPTTAIL;
?>
