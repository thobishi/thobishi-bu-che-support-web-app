<?php
	$screeningID = $this->dbTableInfoArray["screening"]->dbTableCurrentID;
	if(readPost("FLD_signoff_checklisting_ind") >""){
		$this->db->setValueInTable("screening", "screening_id", $screeningID, "date_screening_signed", date("Y-m-d"));
	}
 ?>
