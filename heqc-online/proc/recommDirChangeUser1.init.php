<?php

$subject = "";
$message = "";
//if ($_POST["user_ref"] != $this->currentUserID) {
	switch ($this->flowID){
	case 160:
	case 161:
		$message = $this->getTextContent("recommDirApproveTemplate", "distributeRecommDirInternal");
		$subject = "HEQC Directorate Recommendation approval";
		break;
	case 179:
	case 180:
		$message = $this->getTextContent("recommSiteApproveTemplate", "distributeRecommDirInternal");
		$subject = "HEQC Site Directorate Recommendation approval";
		break;	
	}
//}

switch ($this->flowID){
case 160: // Application preliminary approval
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$new_proc = 161;
	
	// 2011-07-04 Robin: Putting this here so that the date is reset when passing the application on.  At any earlier stage the user can cancel.  If the user 
	// cancels then the date should still be the same before the user clicked on 'Pass on to intermediate approver.'
	$today = date("Y-m-d");
	$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "recomm_access_end_date", $today);

	break;
case 161: // Application intermediate approval
	$new_proc = 162;
	break;
case 179: // Site preliminary approval
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

	$new_proc = 180;
	// 2012-05-08 Robin: Putting this here so that the date is reset when passing the application on.  At any earlier stage the user can cancel.  If the user 
	// cancels then the date should still be the same before the user clicked on 'Pass on to intermediate approver.'
	$today = date("Y-m-d");
	$this->setValueInTable("inst_site_app_proceedings", "inst_site_app_proc_id", $site_proc_id, "recomm_access_end_date", $today);

	break;
case 180: // Site intermediate approval
	$new_proc = 181;
	break;
}

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)

?>
