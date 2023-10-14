<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "regForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>User Information</span>";

$this->formOnSubmit = "return checkUpload();";

$this->scriptHead .= <<<VALIDATE
	function checkUpload() {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (document.defaultFrm.FLD_registration_doc.value == 0) {
				alert('Please upload your completed HEQC Online User - Institutional Application form. Your application cannot be approved without this information.');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			return true;
		}
	}

VALIDATE;
?>
