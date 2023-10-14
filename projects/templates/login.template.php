<?

$this->securityLevel = 0;

$this->title		= "CHE Project Register Login Page";
$this->bodyHeader	= "formHead";
$this->body		= "login";
$this->bodyFooter	= "formFoot";

$this->formHidden["CMD"] = "LOGIN";

$this->createInput ("oct_username", "", "", "30");
$this->createInput ("oct_passwd", "PASSWORD", "", "10");

$this->createAction ("register", "Register a Username", "href", "?goto=3");
$this->destroyUserSession();
?>
