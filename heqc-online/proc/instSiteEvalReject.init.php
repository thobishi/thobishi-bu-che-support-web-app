<?php 
	// Send application back to the Manage evaluators user.
	$flow = $this->getValueFromTable("work_flows", "template", "instSiteApplicPanel9", "work_flows_id");

	$subject = "Site evaluator reports not approved: ";
	$message = $this->getTextContent("instSiteEvalApprove3", "Site Evaluator Report Not Approved");
	$new_user = $this->getValueFromTable("settings", "s_key", "usr_site_panel", "s_value");

	$this->changeProcessAndUser(176, $new_user, $subject, $message, $flow);
	
?>