<?php 
if ($this->getValueFromTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager") == 0) {
	$this->formActions["sendback"]->actionMayShow = false;
}
?>