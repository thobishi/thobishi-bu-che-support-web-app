<?php 
$private_public = ($this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"), "priv_publ") == 1)?($this->getDBsettingsValue("current_doe_teachers_edu_user_id")):($this->getDBsettingsValue("current_doe_teachers_edu_user_id"));
$to = $this->getValueFromTable("users", "user_id", $private_public, "email");
$message = $this->getTextContent ("checkForm15", "PrivateProvTeacherEduProg");
$this->misMailByName($to, "Compliance with the Norms and Standards for Educators 2000", $message);
?>