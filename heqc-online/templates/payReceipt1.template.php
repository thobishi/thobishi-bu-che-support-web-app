<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "payReceipt1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment> Calculate invoice amount</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
	  	}
		return true;
	}
SCRIPTTAIL;

?>
