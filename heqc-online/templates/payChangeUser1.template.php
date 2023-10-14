<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "payChangeUser1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment> Change user</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
	  	}
		return true;
	}
SCRIPTTAIL;

?>
