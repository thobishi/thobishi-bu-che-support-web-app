<?php
	$this->formFields["programme_ref"]->fieldValue = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->formFields["active_user_ref"]->fieldValue = Settings::get('currentUserID');
?>