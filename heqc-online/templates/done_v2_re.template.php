<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "done_v2";
$this->bodyFooter	= "formFoot";

$this->setFormDBinfo("Institutions_application", "application_id");

$this->createField("CHE_reference_code", "TEXT");

$this->formOnSubmit = " return checkSenate ();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkSenate () {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (parseInt(printed) == 1) {\n";
$this->scriptTail .= "			return true;\n";
$this->scriptTail .= "		}else {\n";
$this->scriptTail .= "			alert('Please print the application before submitting.');\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n\n";


$user_id = $this->currentUserID;
$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

$prov_type = $this->checkAppPrivPubl($app_id);
$userIsAdmin = $this->checkIfAdmin($app_id, $user_id);

if (($prov_type == 2) && ($userIsAdmin)) {

// 	Vaidation Should be done on the button
	$this->formOnSubmit = "return checkBusy(this);";
	$currDate = date('Y-m-d');

	$critNumber = "final";
	$ynLkp = "FLD_".$critNumber."_registrarDeclaration_lkp";
	$ynLkpInt = $ynLkp."[j]";

	$signedField = "PWA_".$critNumber."_registrarDeclaration_signed";
	$dateField = "FLD_".$critNumber."_registrarDeclaration_date";

	$this->scriptTail .= <<<TXT
	function checkBusy(obj) {
		if (document.defaultFrm.declaration_OK==null || document.defaultFrm.$ynLkp==null ) return true;
		if (document.defaultFrm.declaration_OK.value=='OK') return true;

		for (j=0; j<obj.$ynLkp.length; j++) {
			if (obj.$ynLkpInt.checked) {
				checkedRadio = obj.$ynLkpInt.value;
			}
		}
		if (checkedRadio == '2') {
			alert('Select the "Submit Declaration" button in order to verify your declaration.');
			return false;
		}
		return true;
	}

	function checkFrm(obj) {
		for (j=0; j<obj.$ynLkp.length; j++) {
			if (obj.$ynLkpInt.checked) {
				checkedRadio = obj.$ynLkpInt.value;
			}
		}
		if (checkedRadio == '1') {
			alert('Select "Yes" in order to verify your declaration.');
			return false;
		}
		if ((checkedRadio == '2') && (obj.$signedField.value == '')) {
			alert('Enter your HEQC-online password in order to validate your declaration.');
			obj.$signedField.focus();
			return false;
		}
		if ((checkedRadio == '2') && (obj.$dateField.value == '1970-01-01')) {
			alert('Please enter the date.');
			obj.$dateField.focus();
			return false;
		}
		return true;
	}

TXT;

}

?>
