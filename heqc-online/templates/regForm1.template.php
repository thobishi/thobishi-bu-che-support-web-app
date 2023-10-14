<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "regForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>User Information</span>";

$this->formOnSubmit = "return checkAreaCode(this);";

$this->scriptHead .= "function checkNewInstValue() {\n";
$this->scriptHead .= "	if (document.defaultFrm.new_inst.checked == false) {\n";
$this->scriptHead .= "		document.defaultFrm.FLD_institution_name.value = '';\n";
$this->scriptHead .= "	}else {\n";
$this->scriptHead .= "		document.defaultFrm.FLD_institution_ref.selectedIndex = 0;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "function checkNewInstChecked() {\n";
$this->scriptHead .= "	if (document.defaultFrm.new_inst.checked) {\n";
$this->scriptHead .= "		document.defaultFrm.new_inst.checked = false;\n";
$this->scriptHead .= "		document.defaultFrm.FLD_institution_name.value = '';\n";
$this->scriptHead .= "		showHide(document.defaultFrm.FLD_institution_name);\n";
$this->scriptHead .= "		checkNewInstValue();\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= <<<SCRIPTHEAD
	function checkAreaCode(obj) {
		var contactCellNo = document.defaultFrm.FLD_contact_cell_nr;
		var contactNo = document.defaultFrm.FLD_contact_nr;
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!valTextRequired(obj.FLD_surname,'Please enter your Surname.')) {return false};
			if (!valTextRequired(obj.FLD_name,'Please enter your Name.')) {return false};
			if (!valSelectRequired(obj.FLD_title_ref,'Please select a title.')) {return false};
			if (!valTextRequired(obj.FLD_email,'Please enter your e-mail address.')) {return false};	
			if (!valEmailFormat(obj.FLD_email,'The e-mail address entered seems to be invalid.')) {return false};
			if (document.defaultFrm.FLD_password.value == '') {
				alert('Please enter a password');
				document.defaultFrm.MOVETO.value = '';
				document.defaultFrm.FLD_password.focus();
				return false;
			}
			if (document.defaultFrm.passwd2.value == '') {
				alert('Please retype password');
				document.defaultFrm.MOVETO.value = '';
				document.defaultFrm.passwd2.focus();
				return false;
			}
			if (document.defaultFrm.FLD_password.value != document.defaultFrm.passwd2.value) {
				alert('Please re-enter your password. It does not match the re-typed password.');
				document.defaultFrm.MOVETO.value = '';
				document.defaultFrm.passwd2.value = '';
				document.defaultFrm.FLD_password.value = '';
				document.defaultFrm.FLD_password.focus();
				return false;
			}
			if (contactNo.value == '' && contactCellNo.value == '') {
				alert('Please enter your contact telephone number or cell phone number.');
				contactNo.focus();
				return false;
			}
			if (contactNo.value > ''){
				if (!valTelNo(contactNo)) {return false};
			}
			if (contactCellNo.value > ''){
				if (!valCellNo(contactCellNo)) {return false};	
			}
			if (document.defaultFrm.new_inst.checked) {
				if (document.defaultFrm.FLD_institution_name.value == '') {
					alert('Please enter your new institution\'s name');
					document.defaultFrm.MOVETO.value = '';
					return false;
				}
			}else{
				if (document.defaultFrm.FLD_institution_ref.options[document.defaultFrm.FLD_institution_ref.selectedIndex].value == 0) {
					alert('Please select an institution from the drop-down list or tick \'Other\' to supply an institution not listed');
					document.defaultFrm.MOVETO.value = '';
					return false;
				}
			}
			return true;
		}
	}
SCRIPTHEAD;
?>
