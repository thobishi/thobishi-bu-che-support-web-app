<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "programToCancellations_edit";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Withdrawals</span>";

	$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valCheckboxRequired(document.defaultFrm.withdrawConfirm,'Please confirm whether you want to  withdraw this programme.')){return false;}
			if (!valTextRequired(document.defaultFrm.FLD_reason,'Please enter a reason for changing the programme name.'))		{return false;}			
		}	
		if(document.defaultFrm.FLD_reason != '' && document.defaultFrm.withdrawConfirm.checked && document.defaultFrm.processName.value != ''){
			var  decision = confirm("Please Note that the following process associated with this programme will be closed: " + document.defaultFrm.processName.value);
			if(decision == false){
				return false;				
			}
			
		}
		
		return true;
	}
SCRIPTTAIL;
?>
