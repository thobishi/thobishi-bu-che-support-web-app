<?php 
$message = $this->getTextContent ("siteVisit2", "sitevisit confirmation");
$subject = "Letter of appointment";

$to = $this->getValueFromTable("Eval_Auditors", "Persnr", $_POST["eval_id"], "E_mail");
$this->misMailByName ($to, $subject, $message);
?>