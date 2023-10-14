<?php 
require_once ("_systems/contract/contract.php");

	$today = date('Y-m-d');
	
	// 2009-05-28: Robin
	// Get the list of contracts that must expire i.e. have passed their end date and have a status of 'Active'.
	
	$sql = <<<SELEXPIRE
		SELECT distinct che_supervisor_user_ref
		FROM d_consultant_agreements
		WHERE status = 1
		AND end_date < now()
SELEXPIRE;
	
	$rs = mysqli_query($sql);

	// If there are some contracts that have expired.
	if ($rs && mysqli_num_rows($rs) > 0){
		while ($row = mysqli_fetch_array($rs)){

			$mgr = $row["che_supervisor_user_ref"];
			if ($mgr > 0){  // Notify manager if contract has a manager
				// Email each contract manager his/her list of contracts that have expired.
				$a_var['mgr'] = $mgr;
				$message = $this->getTextContent ("expireContract", "notificationExpiredContract",$a_var);
				$this->misMail($mgr, "Expired contracts", $message);
			}
		}

		// Get a list of valid administrators and notify them of expired contracts.

		$a_adm = $this->getUsersForGroup(1);

		foreach ($a_adm as $adm){
			$a_var['adm'] = $adm;
			$message = $this->getTextContent ("expireContract", "administrationExpiredContract",$a_var);
			$this->misMail($adm, "Notification of expired contracts", $message);
		}


		$usql = <<<UPDEXPIRE
			UPDATE d_consultant_agreements
			SET status = 2
			WHERE status = 1
			AND end_date < now()
UPDEXPIRE;
		$errorMail = false;
		mysqli_query($usql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $usql."  --> ".mysqli_error(), $errorMail);

	}

?>