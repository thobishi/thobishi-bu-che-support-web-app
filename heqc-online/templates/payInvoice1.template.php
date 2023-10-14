<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "payInvoice1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment> Calculate invoice amount</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valNumberRequired(document.defaultFrm.FLD_proceeding_fee,"Please enter a valid amount for the proceeding fee")) {return false};
	  	}
		//document.defaultFrm.FLD_invoice_total.value = parseInt(document.defaultFrm.FLD_proceeding_fee.value);
		return true;
	}
SCRIPTTAIL;

?>
