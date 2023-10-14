<?php 

$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "chgPassForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>User Information</span>";

$this->scriptHead .= "function checkPass(pass, confirm){\n";
$this->scriptHead .= "	if ((!(pass.value > '')) || (!(confirm.value > ''))){\n";
$this->scriptHead .= "		alert('Please enter the new password in both fields.');\n";
$this->scriptHead .= "		pass.focus();\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	if (pass.value != confirm.value){\n";
$this->scriptHead .= "		alert('The passwords do not match.');\n";
$this->scriptHead .= "		pass.value = '';\n";
$this->scriptHead .= "		confirm.value = '';\n";
$this->scriptHead .= "		pass.focus();\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}else{\n";
$this->scriptHead .= "		moveto('stay');\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";
?>
