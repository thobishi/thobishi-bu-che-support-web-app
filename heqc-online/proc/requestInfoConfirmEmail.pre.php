<?php 
	//check HEI priv/pub - send to relevant project admin
	//email both institutional user and admin, as well as create active process for administrator

	$appRequestID = $this->dbTableInfoArray["appTable_requests"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("appTable_requests", "appTable_requests_id", $appRequestID, "application_ref");
	$priv_publ = $this->checkAppPrivPubl($app_id);

	switch($priv_publ) {
		case 1 :	$to_projAdm = $this->getDBsettingsValue("usr_project_admin_priv");
					break;
		case 2 :	$to_projAdm = $this->getDBsettingsValue("usr_project_admin_pub");
					break;
	}

	$recipientMsg = $this->getTextContent("requestInfoConfirmEmail", "Response to request for additional information received");
	$senderMsg = $this->getTextContent("requestInfoConfirmEmail", "Response to request for additional information");

	$this->misMail($to_projAdm, "Response to request for additional information received", $recipientMsg);
	$this->misMail($this->currentUserID, "Response to request for additional information", $senderMsg);?>
