<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "evaluatorForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluators > Evaluator</span>";

//$this->formHidden["DELETE_RECORD"] = "";

$this->scriptHead .= "function checkNewEmplValue() {\n";
$this->scriptHead .= "	if (document.all.new_employer.checked == false) {\n";
$this->scriptHead .= "		document.all.FLD_employer.value = '';\n";
$this->scriptHead .= "	}else {\n";
$this->scriptHead .= "		document.all.FLD_employer_ref.selectedIndex = 0;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";

array_push($this->scriptFile, "js/TreeMenu.js");

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function selectAll() {\n";
$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_Specialisations[]'].length;\n";
$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
$this->scriptHead .= "		document.defaultFrm.elements['FLDS_Specialisations[]'].options[i].selected = true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

$this->scriptTail = <<< SCRIPTTAIL

	function checkFrm(obj) {
		var contactCellNo = document.defaultFrm.FLD_Mobile_Number;
		var contactNo = document.defaultFrm.FLD_Work_Number;
		if ((obj.VIEW.value != -1)) {
			if (!valSelectRequired(obj.FLD_Title_ref,'Please enter the title of the user.')) {return false};
			if (!valTextRequired(obj.FLD_Surname,'Please enter the surname of the user.')) {return false};
			if (!valTextRequired(obj.FLD_Names,'Please enter the firstname of the user.')) {return false};
			if (!valTextRequired(obj.FLD_E_mail,'Please enter the email address of the user.')) {return false};
			if (!valEmailFormat(obj.FLD_E_mail,'Please enter a valid email address.')) {return false};
			if (contactNo.value == '' && contactCellNo.value == '') {
				alert('Please enter your work telephone number or cell phone number.');
				contactNo.focus();
				return false;
			}
			if (contactNo.value > ''){
				if (!valTelNo(contactNo)) {return false};
			}
			if (contactCellNo.value > ''){
				if (!valCellNo(contactCellNo)) {return false};	
			}
		}
		selectAll();
		return true;
	}
SCRIPTTAIL;
?>
