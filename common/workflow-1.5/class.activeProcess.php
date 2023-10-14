<?php
/*
	20070714: Diederik
	File to Control the Active Processes
	
	- All the existing function that called the active_Processes was 
	  kept and should use this class
*/


class activeProcess {
	private $dbConn, $activeProcessID, $apData;

	function __construct ($conn, $apID=false, $userID=false) {
		$this->dbConn = $conn;

		if ($apID) {
			$this->setActiveProcessID ($apID, $userID);
		} else {
			$this->activeProcessID = false;
		}
	}

	public function clear () {
		$this->activeProcessID = false;
		unset($this->apData);
	}

	public function update () {
		$this->writeActiveProcess ();
	}
	
	public function setActiveProcessID ($apID, $userID=false) {
		$ret = false;
		
		if ($this->activeProcessID != $apID) {
			$this->activeProcessID = false;
			if ($this->apData = $this->readActiveProcess ($apID, $userID) ) {
				$this->activeProcessID = $apID;
				$ret = true;
			}
		}

		return $ret;
	}

	public function setProcess ($id) {
		return $this->setData ('processes_ref', $id);
	}

	public function setWorkflow ($id) {
		return $this->setData ('work_flow_ref', $id);
	}

	public function setWorkflowSettings ($val) {
		return $this->setData ('workflow_settings', $val);
	}

	public function setUser ($id) {
		return $this->setData ('user_ref', $id);
	}
	
	public function setDueDate ($date) {
		return $this->setData ('due_date', $date);
	}

	public function setExpiryDate ($date) {
		return $this->setData ('expiry_date', $date);
	}
	

	public function getProcess ($altID=false) {
		$ap = $altID ? $altID : $this->activeProcessID;
		$data = $this->getActiveProcess ($ap);

		return (isset($data["processes_ref"])?($data["processes_ref"]):(false));
	}

	public function setCompleted () {
		if ($this->activeProcessID) {
			$this->apData["status"] = 1;
			$this->writeActiveProcess ();
		} else {
			return false;
		}

		return true;
	}	

	public function getData () {
		if ($this->activeProcessID) {
			return $this->apData;
		} else {
			return false;
		}
	}

	private function setData ($var, $val) {
		if (!$this->activeProcessID) return false;

		$this->apData[$var] = $val;
		
	}
	
	private function getActiveProcess ($apID) {
		if ( ($apID == $this->activeProcessID) && ($this->activeProcessID) ) {
			return $this->apData;
		} else {
			return $this->readActiveProcess ($apID);
		}
	}
	
	private function readActiveProcess ($apID, $userID=false) {
		if (!$apID) return false;

		$ret = false;

		$SQLuser = ($userID)?(" AND user_ref = ".$userID):("");
		
		$SQL = "SELECT * FROM active_processes WHERE ".
		 " status = 0".
		 " AND active_date <= now()".
		 " AND active_processes_id = ".$apID.
		 $SQLuser;

		if ($rs = $this->dbConn->query($SQL)) {

			if ($row = $rs->fetch_assoc()) {
        			$ret = $row;
			}
			$rs->close();
		}

		return $ret;
	}

	private function writeActiveProcess () {
		if (!$this->activeProcessID) return false;

		$SQL = "UPDATE active_processes SET ".
		"processes_ref = '".$this->apData["processes_ref"]."', ".
		"work_flow_ref = '".$this->apData["work_flow_ref"]."', ".
		"user_ref = '".$this->apData["user_ref"]."', ".
		"workflow_settings = '".$this->apData["workflow_settings"]."', ".
		"status = '".$this->apData["status"]."', ".
		"active_date = '".$this->apData["active_date"]."', ".
		"due_date = '".$this->apData["due_date"]."', ".
		"expiry_date = '".$this->apData["expiry_date"]."', ".
		"last_updated = now() ".
		"WHERE active_processes_id = ".$this->activeProcessID;

		if (!$this->dbConn->query($SQL)) {
			$ret = false;
			die ("ERROR: (AP) can not update...");
		}

		return true;
	}
	
	
}
?>
