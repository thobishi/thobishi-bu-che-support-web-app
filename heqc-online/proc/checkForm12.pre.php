<?php 
$to = $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("current_saqa_user_id"), "email");
$message = $this->getTextContent ("checkForm11", "privateProvProgPendingSAQA");
$this->misMailByName($to, "Programme registration on the NQF", $message);
?>
