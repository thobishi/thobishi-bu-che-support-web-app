<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "programToCancellations_list";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programmes</span>";

$this->scriptTail .= <<<SCRIPTAIL
	function clearFields(obj) {
		clearForm(obj.search_institution);
		clearForm(obj.search_HEQCref);
		clearForm(obj.search_progname);
		return true;
	}
	
SCRIPTAIL;
?>
