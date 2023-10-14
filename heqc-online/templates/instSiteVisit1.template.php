<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteVisit1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Schedule site visit</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valDateRequired(obj.FLD_final_date_visit,'Please enter the date for the site visit to take place.'))	{return false;}
			if (!valSelectRequired(obj.FLD_lkp_relocate_ref,'Please indicate whether programmes must be re-located or added to this site on approval of the site.'))	{return false;}
			if (obj.appTotal.value == 0){
				var add = document.getElementsByName('addApplic[]');
				if (!valCheckboxArrayRequired(add,'Please select at least one application for the site visit.')) {return false;}
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
