<?php
$this->title		= "Conditions for Applications";
$this->bodyHeader	= "formHead";
$this->body			= "reportConditions";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Application Conditions</span>";

$this->scriptTail .= <<<SCRIPTAIL
	function clearFields(obj) {
		clearForm(obj.search_institution);
		clearForm(obj.search_HEQCref);
		clearForm(obj.search_progname);
		clearForm(obj.search_heqc_decision);
		clearForm(obj.mode_delivery);		
		return true;
	}
SCRIPTAIL;

?>
