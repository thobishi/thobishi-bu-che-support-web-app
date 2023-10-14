<?php 
	if ($this->getValueFromTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager") == 0) {
		$this->formActions["sendback"]->actionMayShow = false;
	}


	if ($this->getValueFromTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager") == 1) {
		$this->formActions["send_report"]->actionMayShow = true;
	}else {
		$this->formActions["send_report"]->actionMayShow = false;
	}
	
	$this->formFields["send_report_back"]->fieldValue = 0;
	$this->showField("send_report_back");
?>
