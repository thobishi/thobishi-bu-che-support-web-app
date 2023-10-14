<?php

class Miscellaneous extends grid {

	public function __construct($flowID) {
		parent::__construct($flowID);
	}

	function populatePublicHolidays () {
		$this->public_holidays = array();
		$SQL = "SELECT holiday_date FROM `lkp_public_holidays` WHERE holiday_date >= :holidayDate";
		$holidayDate = date("Y-m-d", strtotime('-1 month'));
		$rs = $this->db->query($SQL, compact('holidayDate'));
		if (!$rs) {
			return;
		}
		
		while ($row = $rs->fetch()) {
			array_push($this->public_holidays, $row["holiday_date"]);
		}
	}
	
	/*
	Louwtjie: 2004-04-15
	mutation of the addcslashes($text, $escape_string) function. It escapes all of the predefined characters like \n, \t etc.
	*/
	function newGenerationAddcslashes ($str) {
		$str = addslashes($str);
		$str = addcslashes($str, "\r\n\t\$\"");
		return $str;
	}

	/*
	Louwtjie: 2005-07-22
	function to insert a array_element into an existing array at specified point
	*/
	function array_insert_item ($array, $item, $position) {
		$first_array = array_slice($array, 0, $position);
		$last_array = array_slice($array, $position);

		if ( is_array($item) ) {
			$first_array = array_merge ($first_array, $item);
		}else {
			array_push($first_array, $item);
		}
		return (array_merge ( $first_array, $last_array) );
	}

	/**
	* Louwtjie du Toit
	* 2004-07-08
	* function to convert mysql dates (yyyy-mm-dd) to human readable (10 July 2004)
	*/
	function convertDateForEmail ($date) {
		$newDate = mktime(0, 0, 0, substr($date,6,2), substr($date,9,2), substr($date,0,4));
		$newDate = date("j F Y", $newDate);
		return $newDate;
	}


	/*
	* Louwtjie: 2004-04-30
	* function to print any variable for debugging in a nice format
	*/
	function printVars($var="") {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}


	public function getNumberDueProcesses() {
		$SQL2 = "SELECT count(*) as `count` FROM active_processes WHERE status=0 AND (due_date <> \"1970-01-01\") AND (due_date < NOW()) AND (expiry_date <> \"1970-01-01\") AND (expiry_date > NOW())";
		$overdue = $this->db->query($SQL2)->fetch();
		$SQL3 = "SELECT count(*) as `count` FROM active_processes WHERE status=0 AND (expiry_date <> \"1970-01-01\") AND (expiry_date < NOW())";
		$expired = $this->db->query($SQL3)->fetch();

		$overdue = $overdue['count'];
		$expired = $expired['count'];
		return compact('overdue', 'expired');
	}

	/*
	This function makes the summary of expired/due processes
	*/
	function makeSumProcTable($echo = true, $class = "specialb", $viewProcess = 17){
		$summary = $this->getNumberDueProcesses();

		$output = '';
		if($summary['overdue'] > 0 || $summary['expired'] > 0) {
			$output .= '<br>';
			$output .= '<span class="' . $class . '">';
			$output .= 'Note that the following processes are';
			$output .= '<span class="due">overdue</span> ' . $summary['overdue'];
			$output .= ' / <span class="expiry">expired</span>' . $summary['expired'] . '<br>';
			$output .= '<a href="javascript:goto(' . $viewProcess . ')">Please click here to view them</a>';
		}

		if($echo) {
			echo $output;
		}

		return $output;
	}
	
	/*
		2013-06-03
		Get list of processes
	*/
	
	public function getProcess($orderBy = 'active_processes.last_updated', $processRef = null, $active=0){
		$params = array('userID' => Settings::get('currentUserID'));
		$SQL = "SELECT * FROM 
			active_processes 
			left join processes on (active_processes.processes_ref = processes.processes_id)
			left join users on (active_processes.user_ref = users.user_id)
				WHERE 
					users.user_id = :userID ";

		if (!empty($processRef)) {
			$SQL .= " AND processes_ref = :processRef";
			$params['processRef'] = $processRef;
		}
		
		if($active < 2){
			$SQL .= " AND active_processes.active_date <= now() AND active_processes.status = :active ";
			$params['active'] = $active;
		}
		else{
			$SQL .= " AND (active_processes.status = 0 OR active_processes.status = 1)";
		}

		$SQL .= " ORDER BY $orderBy DESC;";
		
		$rs = $this->db->query($SQL, $params);

		return $rs->fetchAll();
	}


	public function getActiveProcess($orderBy = 'active_processes.last_updated', $processRef = null) {
		$params = array('userID' => Settings::get('currentUserID'));
		$SQL = "SELECT * FROM 
			active_processes 
			left join processes on (active_processes.processes_ref = processes.processes_id)
			left join users on (active_processes.user_ref = users.user_id)
				WHERE 
					users.user_id = :userID 
					AND active_processes.status = 0 
					AND active_processes.active_date <= now()";

		if (!empty($processRef)) {
			$SQL .= " AND processes_ref = :processRef";
			$params['processRef'] = $processRef;
		}

		$SQL .= " ORDER BY $orderBy DESC";
		$rs = $this->db->query($SQL, $params);

		return $rs->fetchAll();
	}

	/* 2004-05-07
	   Diederik
	   Function to show a list of active proccesses.
	*/
	function showProcesses () {
		$processes = $this->getProcess();
		echo $this->element('processes', compact('processes'));
	}


	/* Louwtjie:
	 * 2005-03-02
	 * function to explode an array on a character and explode it again on another character.
	 * To get the following effect:
	 	 VAR: field1__2|field2__3
		 1st EXPLODE: array[0]=field1__2
		 							array[1]=field2__3
		 2nd EXPLODE: array[0]=> array[0]=field1
		 												 array[1]=2
									array[1]=> array[0]=field2
		 												 array[1]=3
	*/
	function doubleExplode ($var, $seperator1="|", $seperator2="__") {
		$tmp = explode ($seperator1, $var);
		$tmp2 = array();
		foreach ($tmp AS $value) {
			array_push($tmp2, explode ($seperator2, $value));
		}

		return $tmp2;
	}

	//function to return the filesize and time to download of a single file or array of files.
	function getFileSize($f) {
		if ( (!is_array($f)) && ($f>"") ) {
			$f = array($f);
		}
		$ret = array();
		$fs = 0;
		$tm = "";
		if (is_array($f)) {
			foreach ($f AS $val) {
				if (is_array($val)) {
					$fs += filesize($val[0]);
				}else{
					$fs += filesize($val);
				}
			}
			$met = "KB";
			if ($fs > 0) {
				$s = round(($fs/1024),0);
				if ($s >= 1000) {
					$s = round(($s/1024),1);
					$met = "MB";
				}
			}
			$t = round(($fs/1024/3/60),0);
			if ($t < 1) {
				$tm = "< 1min";
			} else {
				$tm = "~ ".round($t,2)." min";
			}
			$h = $s.$met;
			array_push($ret, $h);
			array_push($ret, $tm);
		}
		return $ret;
	}

	function createTableFileUpload ($name="", $actual_fld_name="", $table="", $keyFLD="", $keyVal="") {
		if ($actual_fld_name > "") {
			$this->makeLinkTableFileUpload($actual_fld_name, $name, $table, $keyFLD, $keyVal);
		}
	}

	function makeLinkTableFileUpload($field, $fake_field="", $table="", $keyFLD="", $keyVal=""){
		$table = ($table > "")?($table):($this->dbTableCurrent);
		$keyFLD = ($keyFLD > "")?($keyFLD):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField);
		$keyVal = ($keyVal > "")?($keyVal):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
		$SQL = "SELECT ".$field." FROM ".$table." WHERE ".$keyFLD." = '".$keyVal."'";
//echo $SQL.'<br>';
		$rs = mysqli_query($SQL);
		if ($rs && (mysqli_num_rows($rs) > 0)){
			$row = mysqli_fetch_array($rs);
			$doc = new octoDoc ($row[0]);
			if ($doc->isDoc()){
				if ($fake_field > "") {
					$this->createHiddenFileUploadInput ($field, $fake_field, $table, $keyFLD, $keyVal);
				}else {
					$this->showField ($field);
				}
				echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
				echo "<tr>";
				echo "<td class='oncolourb' width='40%'>File: </td>";

				echo "<td width='60%'><a href='".$doc->url()."' target='_blank'>".$doc->getFilename()."</a></td>";
				echo "</tr>";
				if (! $this->view ) {
					echo "<tr>";
					echo "<td class='oncolourb'>First Uploaded: </td>";
					echo "<td>".$doc->getDateCreated()."</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='oncolourb'>Last Uploaded: </td>";
				echo "<td>".$doc->getDateUpdated()."</td>";
				echo "</tr>";
				if (! $this->view ) {
					echo "<tr>";
					echo "<td class='oncolourb'>Upload File: </td>";
					$fld = ($fake_field > "")?($fake_field):("FLD_".$field);
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",".$doc->getDocID().",\"".$this->safeJS($fld)."\",\"\");'>Click here to select the file that you need to upload</a></td>";
					echo "<tr>";
					echo "<td class='oncolourb'>Delete File: </td>";
					echo "<td><a href='javascript:document.defaultFrm.".$fld.".value=0;document.defaultFrm.DELETE_RECORD.value = \"documents|document_id|".$doc->getDocID()."\";moveto(\"stay\");'>Click here to delete the uploaded file.</a></td>";
					echo "</tr>";
				}
				echo "</tr></table>";
			}else{
			//we have a new record, with no documents linked to it yet
				if ($fake_field > "") {
					$this->createHiddenFileUploadInput ($field, $fake_field, $table, $keyFLD, $keyVal);
				}else {
					$this->showField ($field);
				}
				echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
				echo "<tr>";
				echo "<td class='oncolourb' width='40%'>File: </td>";
				echo "<td width='60%'>N/A</td>";
				echo "</tr>";
				if (! $this->view ) {
					echo "<tr>";
					echo "<td class='oncolourb'>First Upload: </td>";
					echo "<td>N/A</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Last Uploaded: </td>";
					echo "<td>N/A</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Upload File: </td>";
					$fld = ($fake_field > "")?($fake_field):("FLD_".$field);
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",0,\"".$this->safeJS($fld)."\",\"\");'>Click here to select the file that you need to upload</a></td>";
				}
				echo "</tr>";
				echo "</table>";
			}
		}
	}


	function createHiddenFileUploadInput ($field, $fake="", $table="", $keyFLD="", $keyVal="") {
		if ($fake > "") {
			echo '<input type="hidden" name="'.$fake.'" value="'.$this->db->getValueFromTable($table, $keyFLD, $keyVal, $field).'">';
		}
	}


function getTemplateTableAndKey($template){
	$tmpl_array = array();

	$wkfSql = <<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;
	$wkfrs = mysqli_query($wkfSql);
	if ($wkfrow = mysqli_fetch_array($wkfrs)){
		$tmpl_array[$template]["dbTableName"] = $wkfrow["template_dbTableName"];
		$tmpl_array[$template]["dbTableKeyField"] = $wkfrow["template_dbTableKeyField"];
	}

	return $tmpl_array;
}

	function safeJS ($fld) {
		return (str_replace("$", "%24", $fld));
	}

	function scriptGetForm ($table, $id, $moveto) {
		global $heqcEncrypt;
		$script = 'javascript:alert("You are currently in the report view.");';

		if ($this->view != 1) {
			$chRec = $heqcEncrypt->encrypt("$table|$id");
			$script = "javascript:getForm(\"$chRec\", \"$moveto\");";
		}

		return ($script);
	}

	function is_userPartOfGroup($groupid,$userid){
		$ret = false;

		if ($groupid==0) {
			$ret = true;
		} else {
			$SQL = <<<sql
			SELECT * FROM sec_UserGroups
			WHERE (sec_user_ref = $userid)
			AND (sec_group_ref = $groupid)
sql;

			$rs = mysqli_query ($SQL);
			if (mysqli_num_rows($rs) > 0) {
				$ret = true;
			}
		}

		return ($ret);
	}
	
	function getUserFullName($UserID){
		$name = $this->db->getValueFromTable("users", "user_id", $UserID, "name");
		$surname = $this->db->getValueFromTable("users", "user_id", $UserID, "surname");
		$fullName = $name .' '. $surname;
		return $fullName;
	}
	
// END of Class
}
