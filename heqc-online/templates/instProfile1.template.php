<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "instProfile1";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

$this->formOnSubmit = "return checkInstForm ();";

$provider = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");

	$this->scriptTail .= "\n\n";
	$this->scriptTail .= "function checkSelects() {\n";
if ($provider == 1) {
// 2010-01-13 Robin - replace document.all for Firefox.
//	$this->scriptTail .= "	var obj = document.all;\n";
//	$this->scriptTail .= "	if ((obj.FLD_institutional_type.options[obj.FLD_institutional_type.selectedIndex].value < 4) && (obj.FLD_institutional_type.options[obj.FLD_institutional_type.selectedIndex].value > 0)) {\n";
//	$this->scriptTail .= "		alert('You can only select Private Institution');\n";
//	$this->scriptTail .= "		obj.FLD_institutional_type.selectedIndex = 2;\n";
	$this->scriptTail .= "  var obj = document.defaultFrm.elements['FLD_institutional_type'];\n";
	$this->scriptTail .= "	if ((obj.options[obj.selectedIndex].value < 4) && (obj.options[obj.selectedIndex].value > 0)) {\n";
	$this->scriptTail .= "		alert('You can only select Private Institution');\n";
	$this->scriptTail .= "		obj.selectedIndex = 2;\n";
	$this->scriptTail .= "	}\n";
}
	$this->scriptTail .= "}\n";
	$this->scriptTail .= "checkSelects();\n";
$this->scriptTail .= "\n\n";
$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkInstForm () {\n";
//$this->scriptTail .= "	var obj = document.all;\n";
$this->scriptTail .= "  var obj = document.defaultFrm.elements['FLD_institutional_type'];\n";
//$this->scriptTail .= "	if (obj.FLD_institutional_type.options[obj.FLD_institutional_type.selectedIndex].value == 0) {\n";
$this->scriptTail .= "	if (obj.options[obj.selectedIndex].value == 0) {\n";
$this->scriptTail .= "		alert('Please select institution type');\n";
$this->scriptTail .= "		obj.focus();\n";
//$this->scriptTail .= "		obj.FLD_institutional_type.focus();\n";
$this->scriptTail .= "		return false;\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";
?>
