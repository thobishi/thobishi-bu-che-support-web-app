<?php
//require_once ('class.handleDocs.php');
/**
* Controls the logic of the flow of templates
* @author Diederik de Roos <diederik@octoplus.co.za>
* @global $GLOBALS['logicSettings'] The Status and Settings of the Logic calss
*
*/

class flowLogic extends handleDocs {
	var $logicSettings, $logicVars;

	function __construct() {
		$this->pageForm();

		$this->logicSettings = array();
		$this->logicSettings["GOTO"] = array();
		$this->logicSettings["GOSUB"] = array();
		$this->logicVars = Array();
	}
	
	function flowLogic() {
		self::__construct();
	}

	function logicToString() {
		$str = var_export($this->logicSettings, true);
		return ($str);
	}

	function logicFromString($str) {
		$evalStr = '$this->logicSettings = '.$str.';';
		$this->mis_eval_pre(__LINE__, __FILE__);
		eval($evalStr);
		$this->mis_eval_post($evalStr);
	}

	function dropCurrentProcess() {
		$this->completeActiveProcesses ();
		$this->clearWorkflowSettings ();
		/* Ek en Louwtjie het die volgende lyn terug gesit,
		   maar iemand het dit op 'n staium uitgehaal.  Dus wou
		   iemand een of ander tyd nie die DROP na home laat gaan
		   het nie
		*/
		$this->startFlow (__HOMEPAGE);
	}

	function execLogic($workFlowID, $direction="N") {
		$done = false;
		while (!$done) {
			$rs = $this->wrk_getWorkFlowRS($workFlowID);
			if (!$rs) {
				return ($rs);
			}
			$row = mysqli_fetch_array ($rs);
			switch ($row["workFlowType_ref"]) {
				case 1:					// TEMPLATE
												// It should be impossible to be here on first entry
					$done = true;
					break;
				case 2:					// EXIT
					$workFlowID = NULL;
					$done = true;
					break;
				case 3:					// GOTO
					$workFlowID = $this->logicGoto($workFlowID, $row["command"]);
					$direction = "N";
					break;
				case 4:					// GOSUB
					$workFlowID = $this->logicGoSub($workFlowID, $row["command"]);
					$direction = "N";
					break;
				case 5:					// RETURN
					$workFlowID = $this->logicReturn($workFlowID, $row["command"]);
					break;
				case 6:					// LABEL
					$workFlowID = $this->logicCheckLabel($workFlowID, $row["command"], $direction);
					break;
				case 7:					// IF THEN GOTO
					$workFlowID = $this->logicIfGoto($workFlowID, $row["condition"], $row["command"], $direction);
					break;
				case 11:				// PROGgramming
					$workFlowID = $this->logicProc($workFlowID, $row["template"], $direction);
					break;
				case 12:					// IF THEN GOSUB
					$workFlowID = $this->logicIfGoSub($workFlowID, $row["condition"], $row["command"], $direction);
					break;
				case 13:					// DROP
					$this->dropCurrentProcess ();
					$workFlowID = NULL;
					$done = true;
					break;
				default: 				// SOMETHING IS WRONG AS WE DO NOT KNOW WHAT WE HAVE
					$workFlowID = NULL;
					$done = true;
					break;
			}
		}
		return ($workFlowID);
	}

	function logicGoto($workFlowID, $label) {
                $conn = $this->getDatabaseConnection();
		$SQL = "SELECT * FROM work_flows WHERE workFlowType_ref = 6 AND command = ? LIMIT 0,1";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $label);
		$sm->execute();
		$rs = $sm->get_result();
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$row = mysqli_fetch_array($rs);

		$this->logicPushGoto($label, $workFlowID);
		return ($row["work_flows_id"]);
	}

	function logicGoSub($workFlowID, $label) {
		$this->logicPushGoSub($label, $workFlowID);
		$workFlowID = $this->logicGoto($workFlowID, $label);
		return ($workFlowID);
	}

	function logicReturn() {
		$last = end($this->logicSettings["GOSUB"]);
		$workFlowID = $last[1];
		$this->logicPopGoSub();
		$workFlowID = $this->logicNext($workFlowID, "N");
		return ($workFlowID);
	}

	function logicIfGoSub($workFlowID, $condition, $label, $direction) {
		if ($direction == "P") {
			$workFlowID = $this->logicNext($workFlowID, $direction);
		}	else {
			$evalStr = "return (($condition)?(true):(false));";
			$this->mis_eval_pre(__LINE__, __FILE__);
			$evalRes = eval($evalStr);
			$this->mis_eval_post($evalStr);
			if ($evalRes) {
				$workFlowID = $this->logicGoSub($workFlowID, $label);
			} else {
				$workFlowID = $this->logicNext($workFlowID, $direction);
			}
		}
		return ($workFlowID);
	}

	function logicIfGoto($workFlowID, $condition, $label, $direction) {
		if ($direction == "P") {
			$workFlowID = $this->logicNext($workFlowID, $direction);
		}	else {
			$evalStr = "return (($condition)?(true):(false));";
			$this->mis_eval_pre(__LINE__, __FILE__);
			$evalRes = eval($evalStr);
			$this->mis_eval_post($evalStr);
			if ($evalRes) {
				$workFlowID = $this->logicGoto($workFlowID, $label);
			} else {
				$workFlowID = $this->logicNext($workFlowID, $direction);
			}
		}
		return ($workFlowID);
	}

	function logicProc($workFlowID, $template, $direction) {
		if ($direction == "N") {
			$this->runInit($template);
			$this->updateActiveProcesses();
		}

		$workFlowID = $this->logicNext($workFlowID, $direction);
		return ($workFlowID);
	}

	function logicNext($workFlowID, $direction) {
		$workFlowID = $this->wrk_getNextWorkFlow($workFlowID, $direction);
		return ($workFlowID);
	}

	function logicCheckLabel($workFlowID, $label, $direction) {
		if ($direction == "P") {
			$last = end($this->logicSettings["GOTO"]);

			// when ge move back and there are just them we have missed by moveing
			// over them, we should ignore those funny just.  This could cause bugs
			while ($last[0] != $label AND (count($this->logicSettings["GOTO"])>0) ) {
				$this->logicPopGoto();
				$last = end($this->logicSettings["GOTO"]);
			}
			if ($last[0] == $label) {
				$workFlowID = $last[1];
				$this->logicPopGoto();
			}
		}
		$workFlowID = $this->logicNext($workFlowID, $direction);
		return ($workFlowID);
	}

	function logicPushGoto($label, $workFlowID) {
		array_push($this->logicSettings["GOTO"], array ($label, $workFlowID));
		$this->logicSaveState();
	}

	function logicPushGosub($label, $workFlowID) {
		array_push($this->logicSettings["GOSUB"], array ($label, $workFlowID));
		$this->logicSaveState();
	}

	function logicPopGoto() {
		array_pop ($this->logicSettings["GOTO"]);
		$this->logicSaveState();
	}

	function logicPopGoSub() {
		array_pop ($this->logicSettings["GOSUB"]);
		$this->logicPopGoto();	// PopGoto will also save
	}

	function logicSaveState() {
		$this->workFlow_settings["LOGIC_SET"] = $this->logicToString();
	}

// the end
}

?>
