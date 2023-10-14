<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "accForm1_v4";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

$this->formHidden["FLD_user_ref"] = $this->currentUserID;
$this->formHidden["DELETE_RECORD"] = "";
$this->formOnSubmit = "return checkFrm(this);";



$this->scriptTail .= <<<CHECKFORM
	function checkFrm(obj) {
		var flag = false;
		if (obj.MOVETO.value == 'next' ) {
		
            if ((obj.terms_conditionsv4.checked)) {
                flag = true;
            }
			
			if (flag == false) {
				alert('Kindly indicate that you have read, understood, and will comply with the requirements indicated in points 2 to 11 by clicking the checkbox.');
				obj.MOVETO.value = '';
				return false;
			}
			
		}
		return true;
	}
CHECKFORM;

?>