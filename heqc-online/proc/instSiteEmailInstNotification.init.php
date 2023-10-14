<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

	$c_email = readPost('contact');
	if (is_array($c_email) && count($c_email) > 0){
		$message = readPost('institution_site_visit_notification');
		$cc_usr_id = $this->getDBsettingsValue('usr_site_panel');
		$cc = $this->getValueFromTable("users", "user_id", $cc_usr_id, "email");

		// Get all attachments for each site visit
		$files = array();
		$doc_arr = $this->getSiteProcAttachments($site_proc_id);
		foreach ($doc_arr AS $doc_id => $title){
				$doc_url = $this->getValueFromTable("documents", "document_id", $doc_id,"document_url");
				$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");
				array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
		}
		
		foreach($c_email as $e){
			$to = $e;
			$subject = "Notification of site visit";
			$this->misMailByName ($to, $subject, $message, $cc, true ,$files);
		}
	}
?>
