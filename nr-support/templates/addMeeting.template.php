<?php
	$this->title			= "CHE National Reviews";
	$this->bodyHeader		= "formHead";
	$this->body				= "addMeeting";
	$this->bodyFooter		= "formFoot";
	$this->NavigationBar	= array('Admin', 'Manage Meetings','Add new meeting');
	$this->formHidden["DELETE_RECORD"] = "";
	$this->formOnSubmit = "return selectAll()";	
?>