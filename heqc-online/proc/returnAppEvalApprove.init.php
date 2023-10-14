<?php
	//$usr_setting = ($this->readTFV("proceedingType") == 4) ? "usr_manager_conditions" : (($this->readTFV("InstitutionType") == 1)?("usr_manager_priv"):("usr_manager_pub"));
	//$this->returnAppToProcess(163, $usr_setting);

	/*$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
	if ($current_process_status == 0):
		$//$this->getValueFromTable("settings", "s_key", $user_setting, "s_value");
		$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
		$message = $this->getTextContent ("generic", $email_text);
		$this->misMailByName($to, "Application returned", $message);
		$id = $this->addActiveProcesses (163, $new_user);
		$this->completeActiveProcesses();
	endif;

*/
	$new_user = $_POST["user_ref"];

	$message = $this->getTextContent ("generic", "returnApplication");

	$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
		
		$this->misMailByName($to, "Application returned", $message);
		$id = $this->addActiveProcesses (163, $new_user);
		$this->completeActiveProcesses();
   // $this->changeProcessAndUser(163 ,  $new_user, "Application returned ", $message);
?>
