<?php
        
        $message = $this->getTextContent ("generic", "returnApplication");
        //  $this->changeProcessAndUser(106 ,  $new_user, "Application returned ", $message);

            $new_user = $_POST["user_ref"];//$this->getValueFromTable("settings", "s_key", $user_setting, "s_value");
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			//$message = $this->getTextContent ("generic", "returnApplication");
			$this->misMailByName($to, "Application returned", $message);
			$id = $this->addActiveProcesses (106, $new_user);
			$this->completeActiveProcesses();
		
?>
