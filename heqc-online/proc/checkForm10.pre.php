<?php 
$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
$app_version = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "app_version");
switch($app_version) {
	case 1 :	$message = $this->getTextContent ("checkForm9", "supportingDocsIncomplete");
				break;
	default: // version 2, 3, 4
				$message = $this->getTextContent ("checkForm9", "supportingDocsIncomplete_v2");
				break;
}
$this->misMailByName($to, "Supporting Documentation", $message);
?>
