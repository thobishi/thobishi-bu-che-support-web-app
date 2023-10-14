<?php	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	// $contact_email = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"contact_email");
	// $head_email = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"head_email");
	// $ccArr = array();
	// array_push($ccArr,$contact_email);
	// array_push($ccArr,$head_email);
	// $user_id = Settings::get('currentUserID');
	// $message = $this->getTextContent ("ser_submit", "SERReportComplete");
	// $this->misMail($user_id , "SER Report Submission Complete", $message,$ccArr);
	// $this->db->setValueInTable("nr_programmes", "id", $prog_id, "date_submitted", date('Y-m-d'));
	$this->db->setValueInTable('nr_programmes','id',$prog_id,'siteVisit_completed','1');
	$this->db->setValueInTable('nr_programmes','id',$prog_id,'siteVisit_date_submitted',date('Y-m-d'));
?>