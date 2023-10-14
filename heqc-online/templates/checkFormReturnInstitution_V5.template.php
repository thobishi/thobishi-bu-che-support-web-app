<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "checkFormReturnInstitution_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Checklist</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
	

		if ( document.defaultFrm.MOVETO.value == 'next' )  {
         
			if (document.defaultFrm.FLD_resubmission_due_date.value == null || document.defaultFrm.FLD_resubmission_due_date.value == ''){
				alert("Please enter a resubmission Due Date.");
				document.defaultFrm.MOVETO.value = "";
				return false;
			}
				
		
			  
		  }
		 
		
		return true;
	}
SCRIPTTAIL;
?>
