<?php 
$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
$message = $this->getTextContent ("checkForm6", "supportingDocsNotRecieved");
$this->misMailByName($to, "Supporting Documentation", $message);

?>
