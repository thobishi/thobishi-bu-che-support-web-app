<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "reportOutstandingPayments";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment reports > Outstanding payments</span>";


$this->scriptTail .= <<<SCRIPTAIL
	function clearFields(obj) {
		//clearForm(obj.search_institution);
		return true;
	}
SCRIPTAIL;

?>
