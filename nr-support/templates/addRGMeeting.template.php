<?php
	$this->title			= "CHE National Reviews";
	$this->bodyHeader		= "formHead";
	$this->body				= "addRGMeeting";
	$this->bodyFooter		= "formFoot";
	$this->NavigationBar	= array('Admin', 'Manage RC Meetings','Add new meeting');
	$this->formHidden["DELETE_RECORD"] = "";
	$this->formOnSubmit = "return selectAll()";
?>