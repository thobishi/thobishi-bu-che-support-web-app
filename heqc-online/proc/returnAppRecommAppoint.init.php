<?php
	//$this->returnAppToProcess(159, 'usr_recomm_appoint');

	$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
		if ($current_process_status == 0):
			
			$new_user = $_POST["user_ref"];//$this->getValueFromTable("settings", "s_key", $user_setting, "s_value");
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			$message = $this->getTextContent ("generic", "returnApplication");
			$this->misMailByName($to, "Application returned", $message);
			$id = $this->addActiveProcesses (159, $new_user);
			$this->completeActiveProcesses();
		endif;
	
?>