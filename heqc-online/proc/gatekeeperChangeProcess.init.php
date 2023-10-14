<?php 
	$app_id  = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	if (isset($_POST["cancelSubmissionFlag"]) && ($_POST["cancelSubmissionFlag"] == 1)) {
		$this->returnAppToInstBeforePayment($app_id);
	}

?>