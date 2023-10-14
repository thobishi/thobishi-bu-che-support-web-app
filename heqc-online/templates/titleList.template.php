<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "titleList";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Titles</span>";

$this->scriptTail .= <<<SCRIPTAIL
	function clearFields(obj) {
		clearForm(obj.search_institution);
		clearForm(obj.search_HEQCref);
		clearForm(obj.search_progname);
		return true;
	}
SCRIPTAIL;
?>
