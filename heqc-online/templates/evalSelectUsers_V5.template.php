<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "evalSelectUsers_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Change user > Change user</span>";

/*
if($_POST["fav_language"]=='ContinueApplication')
{

	$proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
    
	
	$today = date("Y-m-d");
	$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "screened_date", $today);

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator1", "Percent completion - screening");


	$usr_eval = $this->getDBsettingsValue("usr_eval_appoint_accred");


	//$this->changeProcessAndUser(106,$usr_eval, $subject, $message);
	
$this->changeProcessAndUser(106, $_POST["user_ref"], $subject, $message)

}else{

	
	
	$subject = "Checklisting-public";
	$message = "Checklisting-public";
	
	$this->changeProcessAndUser(221, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);
}


		



?>
