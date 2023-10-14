<?php

if (isset($_POST['adminproc']) && ($_POST['adminproc'] > 0)) {
	$this->setValueInTable ('active_processes', 'active_processes_id', $_POST['adminproc'], 'user_ref', $this->currentUserID);
	$this->writeLogInfo(100, "Application taken back by administrator", var_export($_POST, true), false);
}

?>