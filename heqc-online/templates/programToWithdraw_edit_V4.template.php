<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "programToWithdraw_edit_V4";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Withdrawals</span>";

	$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		//if (document.defaultFrm.MOVETO.value == 'next'  || document.defaultFrm.MOVETO.value == '_label_ChecklistConfirm_V5')  {
		//	if (!valCheckboxRequired(document.defaultFrm.chk_withdraw,'Please confirm whether you want to  withdraw this programme.')){return false;}
		//	if (!valTextRequired(document.defaultFrm.FLD_reason,'Please enter a reason for changing the programme name.'))		{return false;}			
		//}	

		var flag = false;

		if ( document.defaultFrm.MOVETO.value == 'next' )  {
         
			if (document.defaultFrm.FLD_reason.value == "0" || document.defaultFrm.FLD_reason.value == ""){
				alert("Please enter a reason for changing the programme name.");
				document.defaultFrm.MOVETO.value = "";
				return false;
			}
				
			
			if (document.defaultFrm.FLD_reason_doc.value == "0" || document.defaultFrm.FLD_reason_doc.value == ""){
				alert("Please upload the Document as evidence for the programme withdrawal");
				document.defaultFrm.MOVETO.value = "";
				return false;
			}

			  if (document.defaultFrm.FLD_chk_withdraw.checked) {
				  flag = true;
			  }
			  
			  if (flag == false) {
				alert('Please confirm whether you want to  withdraw this programme.');
				  document.defaultFrm.MOVETO.value  = '';
				 return false;
			  }
			  
		  }
		 
		
		return true;
	}
SCRIPTTAIL;
?>
