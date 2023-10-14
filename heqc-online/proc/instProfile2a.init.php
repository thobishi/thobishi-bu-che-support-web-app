<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");

	$SQL = "SELECT * FROM `institutional_profile_sites` WHERE main_site=1 AND institution_ref=".$inst;
	$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
	$mid = 0;
	if ($RS && ($row = mysqli_fetch_array($RS))) {
		$mid = $row["institutional_profile_sites_id"];
		$this->setFormDBinfo("institutional_profile_sites", "institutional_profile_sites_id", $mid);
	}
?>