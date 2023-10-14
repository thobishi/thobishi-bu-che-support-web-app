<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm14";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Programme design</span>";

$this->formOnSubmit = "return checkDocumentsSelected(6, ".$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ").");";

$this->formHidden["DELETE_RECORD"] = "";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkNumeric(obj) {\n";
$this->scriptTail .= "	var ValidChars = '0123456789.';\n";
$this->scriptTail .= "	var IsNumber=true;\n";
$this->scriptTail .= "	var Char;\n";
$this->scriptTail .= "	for (i = 0; i < obj.value.length && IsNumber == true; i++) {\n";
$this->scriptTail .= "		Char = obj.value.charAt(i);\n";
$this->scriptTail .= "		if (ValidChars.indexOf(Char) == -1) {\n";
$this->scriptTail .= "			IsNumber = false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	if (! (IsNumber) ) {\n";
$this->scriptTail .= "		alert('Only numeric values are accepted.');\n";
$this->scriptTail .= "		obj.focus();\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "}\n\n";

?>
