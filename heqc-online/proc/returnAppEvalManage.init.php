<?php
	$usr_setting = ($this->readTFV("InstitutionType") == 1)?("usr_eval_manage_priv"):("usr_eval_manage_pub");
	$this->returnAppToProcess(112, $usr_setting);
?>