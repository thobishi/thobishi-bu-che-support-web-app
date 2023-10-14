<?php 
/*	$appl_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$SQL = "SELECT * FROM lkp_sites WHERE application_ref=".$appl_ref;
	$RS = mysqli_query($SQL);
	while ($row = mysqli_fetch_array($RS)) {
		$cSQL = "SELECT * FROM siteVisit WHERE application_ref = ".$appl_ref." and site_ref=".$row["sites_ref"];
		$cRS = mysqli_query($cSQL);
		if (mysqli_num_rows($cRS) == 0){
			$insSQL = "INSERT INTO siteVisit (application_ref, site_ref) VALUES ('".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."', '".$row["sites_ref"]."')";
			$insRS = mysqli_query($insSQL);
			//BUG SPAWN: rember to change user here.
			if ($this->getValueFromTable("HEInstitution","HEI_id",$this->getValueFromTable("Institutions_application","application_id",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"institution_id"),"priv_publ") != 1) $tmpUser = "user_priv_site_visit";
			$tmpUserRef = $this->functionSettings('@IF($this->readTFV("InstitutionType") == 1,user_priv_site_visit,user_pub_site_visit)');
			$this->addActiveProcesses (25, $tmpUserRef, 0, 0, false, $this->makeWorkFlowStringFromCurrent ("siteVisit", "siteVisit_id", mysqli_insert_id()) );
			$message = $this->getTextContent ("evalReportSumScreenForm9", "SitevisitInform");
			$this->misMail($tmpUserRef, "Site visit decision.", $message);
		}
	}
*/
?>
