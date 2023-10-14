<?php 
$this->securityLevel = 0;

$this->bodyMenuNavigation = "";
$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "welcome";
$this->bodyFooter	= "";

$this->formHidden["CMD"] = "LOGIN";
if ($this->flowID == 1) {
	$this->formHidden["MOVETO"] = "next";
} else {
	/* If we are NOT on the first login page, we want to sign on without
	   without changing any settings.  We would like to return to the same
	   page, but logged in... */
	$this->formHidden["MOVETO"] = "stay";
}

$this->scriptTail .= "document.all.oct_username.focus();\n";

$this->destroyUserSession();
?>
