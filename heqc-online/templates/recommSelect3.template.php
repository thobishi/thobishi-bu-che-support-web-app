<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "recommSelect3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Set access date</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= <<<SCRIPTTAIL
	function checkFrm(obj) {
		var anyChecked = 0;
		if (obj.MOVETO.value == 'next') {
			for(i=0; i<document.defaultFrm.elements.length;i++){
			    if ((document.defaultFrm.elements[i].checked) && (document.defaultFrm.elements[i].name.match('FLD_lop_status_confirm')) && (document.defaultFrm.elements[i].value == 1)){
				    anyChecked++;
			    }
		  	}
			if (anyChecked == 0){
			    alert('The recommendation users needs to accept in order to continue');
			    obj.MOVETO.value = '';
			    return false;
			}
			if ((document.defaultFrm.FLD_recomm_access_end_date.value == '1000-01-01') || (document.defaultFrm.FLD_recomm_access_end_date.value == '')) {
				alert('Please enter a deadline date for access to this application in the Directorate Recommendation portal.');
	    		return false;
			}
		}
		return true;
	}
SCRIPTTAIL;

?>
