<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "payInvoice3";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment> Calculate invoice amount</span>";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valDateRequired(document.defaultFrm.FLD_date_payment,"Please enter the payment date")) {return false};
			if (!valNumberRequired(document.defaultFrm.FLD_payment_total,"Please enter a valid amount for the payment total")) {return false};
			if (parseInt(document.defaultFrm.FLD_payment_total.value) > parseInt(document.defaultFrm.FLD_invoice_total.value)){
				alert('Payment total must be less than or equal to invoice total: ' + document.defaultFrm.FLD_invoice_total.value);
				return false;
			}
		}
		document.defaultFrm.FLD_received_confirmation.value = 0;
		if (parseInt(document.defaultFrm.FLD_payment_total.value) == parseInt(document.defaultFrm.FLD_invoice_total.value)){
			document.defaultFrm.FLD_received_confirmation.value = 1;
		}
		return true;
	}
SCRIPTTAIL;

?>
