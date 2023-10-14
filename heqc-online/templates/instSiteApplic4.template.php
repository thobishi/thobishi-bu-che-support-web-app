<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteApplic4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Schedule site visit</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (obj.sites_valid_ind.value == 0){
				alert('Please edit each site and capture the required information before continuing.');	
				return false;
			}
		}
		return true;
	}
SCRIPTTAIL;
?>
