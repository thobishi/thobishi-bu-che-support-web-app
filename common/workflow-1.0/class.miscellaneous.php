<?php

class miscellaneous extends grid {

	function miscellaneous () {
	}

	function populatePublicHolidays () {
		$this->public_holidays = array();
		$SQL = "SELECT holiday_date FROM `lkp_public_holidays` WHERE holiday_date >= '".date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")))."'";
		
		$conn = $this->getDatabaseConnection();
		$RS = mysqli_query($conn, $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
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


/*
This function makes the summary of expired/due processes
*/
function makeSumProcTable(){
        $conn = $this->getDatabaseConnection();
	$SQL2 = "SELECT * FROM active_processes WHERE status=0 AND (due_date <> \"1000-01-01\") AND (due_date < NOW()) AND (expiry_date <> \"1000-01-01\") AND (expiry_date > NOW())";
	$rs2 = mysqli_query($conn, $SQL2);
	$SQL3 = "SELECT * FROM active_processes WHERE status=0 AND (expiry_date <> \"1000-01-01\") AND (expiry_date < NOW())";
	$rs3 = mysqli_query($conn, $SQL3);
	if ((mysqli_num_rows($rs2) > 0) || (mysqli_num_rows($rs3))){
	?>
	<br>
	<span class="specialb">
	Note that the following processes are <span class="due">overdue</span> (<?php echo mysqli_num_rows($rs2)?>) / <span class="expiry">expired</span> (<?php echo mysqli_num_rows($rs3)?>).<br>
	<a href='javascript:goto(17)'>Please click here to view them</a>
	</span>
	<br>
	<?php 
	}
}


	/* 2004-05-07
	   Diederik
	   Function to show a list of active proccesses.
	*/
function showActiveProcesses () {
?>
<table width="95%" border=0 align="center" cellpadding="3" cellspacing="3"><tr>
<td class="oncolourb" align="center">Process</td>
<td class="oncolourb" align="center">Reference</td>
<td class="oncolourb" align="center">Last Updated</td>
</tr>
<?php 
	$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id and user_id = ? AND status = 0 AND active_date <= now() ORDER BY last_updated DESC";
	$conn = $this->getDatabaseConnection();
	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->currentUserID);
	$sm->execute();
	$rs = $sm->get_result();
	
	//$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_array ($rs)) {
			$desc = $this->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
			$dueStyle = "";
			if ( ($row["due_date"]!="1000-01-01") && ($row["due_date"]<=date("Y-m-d")) ) {
				$dueStyle = "CLASS=due";
			}
			if ( ($row["expiry_date"]!="1000-01-01") && ($row["expiry_date"]<=date("Y-m-d")) ) {
				$dueStyle = "CLASS=expiry";
			}
?>
<tr class='onblue'>
<td><a <?php echo $dueStyle?> href="?ID=<?php echo $row["active_processes_id"]?>"><?php echo $desc?></a></td>
<td align="center">
<?php 	$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
//print_r($this->parseOtherWorkFlowProcess($row["active_processes_id"]));
		$flag = true;
		foreach ($arr AS $k=>$v)
		{
		$HEQCref = "";
//Reference number only displayed if it is an application
			if ($k == "Institutions_application")
			{
				//$flag = false;
				$HEQCref = $this->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, "CHE_reference_code");
			}

/*				if (($row["processes_ref"] == 5))
				{
					$descFieldNameArr = $this->getValueFromTable ("processes", "processes_id", $row["processes_ref"], "desc_fields");
					$descFieldName  = explode ("|", $descFieldNameArr);
					$HEQCref .= " (".$this->table_field_info($row["active_processes_id"], $descFieldName[0]).")";
				}
*/
/*
	Edited: Rebecca & Robin 14/11/2006______________________________________
	The if statement below displays the relevant value on the active
	processes page (in Reference column). It traverses
	$descFieldName array until a value is found, which is displayed.
*/
				if (($HEQCref == ""))			//if NO che_reference exists, do...
				{
					$descFieldNameArr = $this->getValueFromTable ("processes", "processes_id", $row["processes_ref"], "desc_fields");
					$descFieldName  = explode ("|", $descFieldNameArr);
					foreach ($descFieldName as $value)
					{
					   $HEQCref = $this->table_field_info($row["active_processes_id"], $value);
					   if ($HEQCref != "")
					   {
					   	$flag = false;
					   	break;
					   }
					}
				}
				echo $HEQCref;
				break;
		}
		if ($flag) {
			echo "&nbsp;";
		}
?></td>
<!-- BUG: <td><a href="?goto=6&AP=<?php echo $row["active_processes_id"]?>">View</a></td> -->
<td align="center"><?php echo $row["last_updated"]?></td>
</tr>
<?php 
		}
	}
	if (mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
	if (mysqli_num_rows($rs) < 1) {
		echo '<tr class="onblue"><td colspan="3" align=center>There are currently no active processes</td></tr>';
	}
?>
</table>
<?php 
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
                $conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);
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
			echo '<input type="hidden" name="'.$fake.'" value="'.$this->getValueFromTable($table, $keyFLD, $keyVal, $field).'">';
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
        $conn = $this->getDatabaseConnection();
	$wkfrs = mysqli_query($conn, $wkfSql);
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
			WHERE (sec_user_ref = ?)
			AND (sec_group_ref = ?)
sql;
                        $conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $userid, $groupid);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query ($conn, $SQL);
			if (mysqli_num_rows($rs) > 0) {
				$ret = true;
			}
		}

		return ($ret);
	}
	
// END of Class
}

?>
