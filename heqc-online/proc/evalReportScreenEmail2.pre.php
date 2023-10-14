<?php 
$to = $this->getValueFromTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID,"Persnr_ref");
if ($this->formFields["evalReport_accept"]->fieldValue){
	$message = $this->getTextContent ("evalReportScreenEmail1", "Eval_Report_accept");
}else{
	$message = $this->getTextContent ("evalReportScreenEmail1", "Eval_Report_reject");
	$user = $this->getValueFromTable("users", "email",$this->getValueFromTable("Eval_Auditors", "Persnr",$to,"E_mail") ,"user_id");
	$this->addActiveProcesses(30,$user);
}
$this->misEvalMail($to, "Evaluator report recieved", $message, $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_finance"), "email"));

//$message = $this->getTextContent ("evalReportScreenEmail1", "Eval_Report_accept");
?>
