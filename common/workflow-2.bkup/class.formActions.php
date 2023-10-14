<?php

class formActions {
	var $actionName, $actionType, $actionDesc, $actionDest, $actionClass;
	var $actionMayShow;
	var $actionImg;
	var $target, $title;

	public function __construct ($name) {
		$this->actionInit ();
		$this->actionName = $name;
	}

	function actionInit () {
		$this->actionType = "button";
		$this->actionClass = "panel";
		$this->actionDesc = "";
		$this->actionDest = "";
		$this->actionImg = "";
		$this->actionMayShow = true;
		$this->target = "";
		$this->title = "";
	}

}
