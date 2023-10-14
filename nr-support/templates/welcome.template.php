<?php
Settings::set('securityLevel', 0);

$this->bodyMenuNavigation = "";
$this->title		= "CHE National Reviews Login Page";
$this->bodyHeader	= "formHead";
$this->body			= "welcome";
$this->bodyFooter	= "";

$this->formHidden["CMD"] = "LOGIN";
if (Settings::get('flowID') == 1) {
	$this->formHidden["MOVETO"] = "next";
} else {
	/* If we are NOT on the first loging page, we want to sign on without
	   without changing any settings.  We would like to return to thte same
	   page, but logged in... */
	$this->formHidden["MOVETO"] = "stay";
}

//$this->createInput ("username", "", "", "30");
//$this->createInput ("passwd", "PASSWORD", "", "10");

//$this->createAction ("inst", "Accreditation Information", "href", "?goto=14", "ico_info.gif");
//$this->createAction ("register", "Register a Username", "href", "?goto=3", "ico_register.gif");

// 2010-01-07 Robin: replace document.all
//$this->scriptTail .= "document.all.oct_username.focus();\n";
$this->scriptTail .= "document.defaultFrm.oct_username.focus();\n";

$this->destroyUserSession();

?>