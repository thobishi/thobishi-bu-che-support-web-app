<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "accForm15_v2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Programme design</span>";

$user_id = $this->currentUserID;
$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

$prov_type = $this->checkAppPrivPubl($app_id);
$userIsAdmin = $this->checkIfAdmin($app_id, $user_id);

if (($prov_type == 2) && ($userIsAdmin)) {

// 	Vaidation Should be done on the button
	$this->formOnSubmit = "return checkBusy(this);";
	$currDate = date('Y-m-d');

	$critNumber = "7";
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
