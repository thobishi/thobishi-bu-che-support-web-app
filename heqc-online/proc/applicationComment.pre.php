<?php 
	// The following is to return to the previous process without losing information
	if (isset($this->workFlow_settings["PREV_WORKFLOW"]) && ($this->workFlow_settings["PREV_WORKFLOW"] > "")) {
		$prev_arr = explode ("|", $this->workFlow_settings["PREV_WORKFLOW"]);
		$this->createAction ("cancel", "Back to Application form", "href", "javascript:moveto(".$prev_arr[1].");", "ico_cancel.gif");
	}
?>