<?php 
	$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}
?>