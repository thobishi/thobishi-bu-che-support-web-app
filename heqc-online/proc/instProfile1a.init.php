<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	
	$SQL = "SELECT * FROM `institutional_profile` WHERE institution_ref=".$inst;
	$RS = mysqli_query($this->getDataBaseConnection(), $SQL);
	$i_id = 0;
	if ($RS && ($row = mysqli_fetch_array($RS))) {
		$i_id = $row["institution_ref"];
		$this->setFormDBinfo("institutional_profile", "institution_ref", $i_id);
	}
?>
