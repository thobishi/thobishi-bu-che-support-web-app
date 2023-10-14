<?
$this->securityLevel = 0;

$this->bodyMenuNavigation = "";
$this->title		= "CHE Project Register";
$this->bodyHeader	= "formHead";
$this->body			= "welcome";
$this->bodyFooter	= "";

$this->formHidden["CMD"] = "LOGIN";
if ($this->flowID == 1) {
	$this->formHidden["MOVETO"] = "next";
} else {
	/* If we are NOT on the first loging page, we want to sign on without
	   without changing any settings.  We would like to return to thte same
	   page, but logged in... */
	$this->formHidden["MOVETO"] = "stay";
}

$this->scriptTail .= "document.all.oct_username.focus();\n";

$this->destroyUserSession();
?>
