<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	$inst_type = $this->getValueFromTable("HEInstitution","HEI_id",$inst_id,"priv_publ");
	$ref = $this->getValueFromTable("Institutions_application","application_id",$app_id,"CHE_reference_code");
	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

	// 2015-06-10 Robin
	//  Bypass payment for new applications for public, agricultural and paid applications
	//if ($inst_type == 2 || (strpos($ref, "PN"))){
	//	$subject = "Checklisting-public";
	//	$message = $this->getTextContent ("generic", "returnApplication");


		if(	$app_version == 5)
		{
			$this->changeProcessAndUser(221, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);
	
		}else{
			$this->changeProcessAndUser(7, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);	
		}
		
	
	//} else {
		//$subject = "Payment-private";
	//	$message = $this->getTextContent ("generic", "returnApplication");

	//	$this->changeProcessAndUser(12, $this->getDBsettingsValue("usr_registry_payment"), $subject, $message);
	//}
?>
