<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "chgPassForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>User Information</span>";

$this->setFormDBinfo("users", "user_id");

$this->createInput("password", "PASSWORD", "", 16);
$this->createInput("password_confirm", "PASSWORD", "", 16);

$this->scriptHead .= "function checkPass(pass, confirm){\n";
$this->scriptHead .= "	if ((!(pass.value > '')) || (!(confirm.value > ''))){\n";
$this->scriptHead .= "		alert('Password field is empty');\n";
$this->scriptHead .= "		pass.focus();\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	if (pass.value != confirm.value){\n";
$this->scriptHead .= "		alert('Password and Confirm password does not match');\n";
$this->scriptHead .= "		pass.value = '';\n";
$this->scriptHead .= "		confirm.value = '';\n";
$this->scriptHead .= "		pass.focus();\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}else{\n";
$this->scriptHead .= "		moveto('stay');\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";
?>
