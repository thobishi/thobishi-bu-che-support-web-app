<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteApplic3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Schedule site visit</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valSelectRequired(obj.FLD_lkp_site_proceedings_ref,'Please select the type of proceedings and the sites to be visited.'))	{return false;}
			if (obj.sites_valid_ind.value == 0){
				var add = document.getElementsByName('site_id[]');
				if (!valCheckboxArrayRequired(add,'Please select at least one site for a site visit.')) {return false;}
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
