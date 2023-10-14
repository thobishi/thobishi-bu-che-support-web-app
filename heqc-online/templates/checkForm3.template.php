<?php
$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

$provider = strtolower($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id"), "priv_publ"));

if ($provider == "1") {
	$this->skipThisFlow ();
} else {
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body		= "checkForm3";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";
	

	$this->scriptHead .= "function checkSendMessage(obj){\n";
	$this->scriptHead .= "	if (!(obj.checked == true)) {\n";
	$this->scriptHead .= "		alert('Please check the send message checkbox');\n";
	$this->scriptHead .= "	}else{\n";
	$this->scriptHead .= "		moveto('stay');\n";
	$this->scriptHead .= "	}\n";
	$this->scriptHead .= "}\n";

}
?>
