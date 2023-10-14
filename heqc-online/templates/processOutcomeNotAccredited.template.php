<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "processOutcomeNotAccredited";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Outcome> Not Accredited</span>";

$this->formHidden["DELETE_RECORD"] = ""; // Allow delete of uploaded documents

$this->formOnSubmit = "return checkFrm();";

$this->scriptHead = <<<SCRIPTHEAD
	function checkManageOverride(obj){
		if (!(obj.checked == true)) {
			alert('Please indicate that management has approved multiple representations for this programme.');
			return false;
		}else{
			moveto('stay');
		}
	}
SCRIPTHEAD;

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
	if (document.defaultFrm.MOVETO.value == 'next')  {
	if (document.defaultFrm.FLD_lkp_proceedings_ref.value != 3 || document.defaultFrm.manage_override.value == 2){
				if (!valRadioRequired(document.defaultFrm.FLD_representation_ind,'Please indicate whether there is a representation or not before continuing.'))		{return false;}
				if (document.defaultFrm.FLD_representation_ind[1].checked == true){
					if (!valDocRequired(document.defaultFrm.FLD_representation_doc,'Please upload the representation before continuing.'))		{return false;}
					if (!valDateRequired(document.defaultFrm.FLD_representation_submission_date,'Please enter date that the representation was submitted before continuing.'))		{return false;}
				}
			}			
	  	}
		return true;
	}
SCRIPTTAIL;
?>
