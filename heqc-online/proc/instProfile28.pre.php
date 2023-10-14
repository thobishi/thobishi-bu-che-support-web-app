<?php 
$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
$this->setValueInTable("institutional_profile", "institution_ref", $inst, "last_updated_date", date('Y-m-d'));

?>
