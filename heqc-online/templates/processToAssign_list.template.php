<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "processToAssign_list";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Assign processes to user</span>";

$this->scriptTail = <<<SCRIPTTAIL
	function clearFields(obj) {
		clearForm(obj.search_setting_description);
		clearForm(obj.search_username);
		return true;
	}
SCRIPTTAIL;
?>
