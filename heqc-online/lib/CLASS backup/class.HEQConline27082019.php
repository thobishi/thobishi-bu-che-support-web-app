<?php
//require_once ('/var/www/html/common/workflow-1.0/class.workFlow.php');
//require_once ('/var/www/html/common/workflow-1.0/class.octoDoc.php');
//require_once ('/var/www/html/common/workflow-1.0/class.octoDocGen.php');

/**
 * application class specific to this application
 *
 * this class has non-genric functions specific to this workflow application.
 * @author Diederik de Roos, Louwtjie du Toit, Reyno vd Hooven
*/

/*
GRID FUNCTIONS
-------------
	gridDeleteRow
	gridInsertRow
	doPopulateGridFromTemplateTable
	createHTMLGridHeading
	createHTMLGridSQL
	createHTMLGridInsertWithLookup
	createHTMLGridInsertWithoutLookup
	createHTMLGridFields
	gridShow - Displays a grid with set rows specified in a lookup table. e.g. Institutional profile list of documents to upload
				for a specified criteria.  Number of rows in child table are expected to be the same as in the lookup (template)
				for that grid.  If they differ then the rows are inserted into the child table so that they match.
	gridShowRowByRow - Displays all rows of a child table. By default inserts a row if none. Optional parameters to allow for
						delete of rows or adding new rows.
	gridShowTableByRow
	gridDisplay
	gridDisplayPerTable
	displayFixedGrid

*/

class HEQConline extends workFlow {

	var $relativePath;
	var $domReady = '';
	/**
	 * default constructor
	 *
	 * this function calls the {@link workFlow} function.
	 * @author Diederik de Roos
	 * @param integer $flowID
	*/
	function __construct ($flowID) {
		$this->readPath ();
		$this->workFlow ($flowID);
		$this->populatePublicHolidays ();
		$this->populatePrivatePublicDocs ();
	}
	
	function HEQConline ($flowID) {
		self::__construct ();
	}

	function readPath () {
		global $path;
		// echo '<pre>';
		// print_r($path);
		// echo '</pre>';
		$this->relativePath = (isset($path))?($path):("");
	}

	function populatePublicHolidays () {
		$this->public_holidays = array();
		$SQL = "SELECT holiday_date FROM `lkp_public_holidays` WHERE holiday_date >= '".date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")))."'";
	//	$d = date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
	$conn = $this->getDatabaseConnection();
	//	$sm = $conn->prepare($SQL);
	//	$sm->bind_param("s", $d);
//$sm->execute();
		//$RS = $sm->get_result();
		
		$RS = mysqli_query($conn, $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			array_push($this->public_holidays, $row["holiday_date"]);
		}
		
	}

	function populatePrivatePublicDocs () {
		$this->private_docs = $this->public_docs = "";
		$SQL = "SELECT * FROM `lkp_application_docs`";
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			if (($row["private_public"] == 1) || ($row["private_public"] == 3)) {
				$this->private_docs .= $row["lkp_application_docs_fieldName"];
			}
			if (($row["private_public"] == 2) || ($row["private_public"] == 3)) {
				$this->public_docs .= $row["lkp_application_docs_fieldName"];
			}
		}
		
	}

	function createCHE_reference ($division, $institution, $program, $progType) {
		$conn = $this->getDatabaseConnection();
		$newNum = 1;

		// first find the last program
		$SQL = "SELECT max(progNo) FROM CHE_referenceNo ".
					 "WHERE division = '$division' ".
						 "AND institution = '$institution' ".
						 "AND program = '$program'";
                
            //    $sm = $conn->prepare($SQL);
		//$sm->bind_param("sss", $division, $institution, $program);
		//$sm->execute();
//$rs = $sm->get_result();
		
		$rs = mysqli_query($conn, $SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO CHE_referenceNo VALUES"."(NULL, '$division', '$institution', '$program', $newNum, '$progType')";
                
//$sm = $conn->prepare($SQL);
		//$sm->bind_param("sssss", $division, $institution, $program, $newNum, $progType);
		//$sm->execute();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

		// format the reference number
		$reference = sprintf("%s/%s/%s%03u%s", $division, $institution, $program, $newNum, $progType);
                
		return ($reference);
	}

	/*
	 * Louwtjie: 2004-08-11
	 * function to return the last hei_code and update the database with the new one.
	*/
	function getLastHEIcode($prov) {
                $conn = $this->getDatabaseConnection();
	$SQL = "SELECT * FROM `last_hei_code` WHERE public_private='".$prov."'";
	//	$sm = $conn->prepare($SQL);
//$sm->bind_param("s", $prov);
		//$sm->execute();
		//$rs = $sm->get_result();
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		
		$hei_code = "";
		if ($rs && ($row=mysqli_fetch_array($rs))) {
			$hei_code = $row["public_private"].sprintf("%03u", $row["hei_code_num"]);
		}
		$SQL = "UPDATE `last_hei_code` SET hei_code_num=(hei_code_num+1) WHERE public_private='".$prov."'";
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $prov);
		//$sm->execute();
		//$rs = $sm->get_result();
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		
		return $hei_code;
	}

	function createInstitution_reference ($org) {
                $conn = $this->getDatabaseConnection();
		$newNum = 1;
		$org_type = $org;
		// first find the last program
		$SQL = "SELECT max(orgNo) FROM Institution_referenceNo ".
					 "WHERE org_type = '$org' ";
		//$sm = $conn->prepare($SQL);
//$sm->bind_param("s", $org);
		//$sm->execute();
		//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO Institution_referenceNo VALUES".
					 "(NULL, '$org_type', '$newNum')";
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("ss", $org_type, $newNum);
		//$sm->execute();
//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

		// format the reference number
		$reference = sprintf("%s%03u", $org_type, $newNum);
                
		return ($reference);
	}

	/**
	 * deletes a line of the program in site visit process
	 * @author Louwtjie
	 * 30-03-2004
	 * @param string $table The MySQL table name which holds the program information.
	 * @param string $keyFld The key field of the MySQL table.
	 * @param mixed $keyFldValue The value of the key field.
	*/
	function deleteProgram($table, $keyFld, $keyFldValue){
		$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld."=".$keyFldValue;
		$conn = $this->getDatabaseConnection();
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $keyFldValue);
		//$sm->execute();
		//$rs = $sm->get_result();
		$rs = mysqli_query($conn, $SQL);
		
	}

	/*
	Louwtjie
	30-03-2004
	inserts a new program line in site visait process
	*/
	function saveProgram($table, $keyFld, $keyFldValue, $keyFld2="", $keyFldValue2=0){
		$fld2_key = ($keyFld2>"")?(", ".$keyFld2):("");
		$fld2_value = ($keyFldValue2>0)?(", '".$keyFldValue2."'"):("");

		$SQL ="INSERT INTO `".$table."` (".$keyFld.$fld2_key.")";
		$SQL .= " VALUES ('".$keyFldValue."'".$fld2_value.")";
		
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		
	}

	/**
	 * show the transport table for site visit process
	 * @author Louwtjie
	 * 30-03-2004
	 * @return string $content All HTML used to display the transport table
	*/
	function showTransport($siteVisit_id, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="", $numEntires=0, $evalsArr){
		$SQL = "SELECT * FROM `".$table."` WHERE siteVisit_ref=".$siteVisit_id;
		
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $siteVisit_id);
		//$sm->execute();
		//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$numAlready = mysqli_num_rows($rs);
		if ( !($numAlready == $numEntires) ) {
			for ($i=0; $i<$numEntires; $i++) {
				$this->saveProgram("siteVisit_transport", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "application_ref, site_ref", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "application_ref")."','".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref"));
			}
		}
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$count=0;
		$message = "";
		$content = "";
		$content .=  "<table cellpadding='2' cellspacing='2' align='center' border='1'>";
		$content .=  "<tr>";
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			foreach ($tableHeading AS $key=>$value) {
				$content .= '<td colspan="'.$value.'" align="center"><b>'.$key.'</b></td>';
			}
			$content .=  "</tr><tr>";
		}
		foreach ($fieldsArr AS $value) {
			$content .=  '<td>'.$value.'</td>';
			$count++;
		}
		$content .=  "</tr>";
		$array_keys = array_keys($fieldsArr);
		$cc = 0;
		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if (stristr($key, "time") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "Persnr_ref") > "") {
					$new = explode("|", $evalsArr[$cc]);
					$value1 = $new[0];
					$value2 = $new[1];
					$content .=  "<td><input size='".$sizeOfFld."' type='HIDDEN' ID='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value1."'>";
					$content .=  $value2."</td>";
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td><input size='".$sizeOfFld."' type='TEXT' ID='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
					}
				}
			}
			// louwtjie:  $content .= '<tr><td><input type="HIDDEN" name="GRID_save_'.$row[$keyFld].'" value="1"></td></tr>';
			$cc++;
		}
		$content .=  "</table>";
		
		return $content;
	}

	function generateTransportProgram ($siteVisit_id, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="", $numEntires=0, $evalsArr) {
		$SQL = "SELECT Persnr_ref, airfare_date, airfare_from, airfare_to, airfare_time, airfare_reference, shuttle_date, shuttle_from, shuttle_to, shuttle_time, shuttle_reference, car_hire_date, car_hire_reference FROM `".$table."` WHERE siteVisit_ref=".$siteVisit_id;
		//$conn = $this->getDatabaseConnection();
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $siteVisit_id);
		//$sm->execute();
		//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$content = "";
		$content .=  $this->makeTop($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		$content .=  "<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>";
		$content .=  "<tr>";
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			foreach ($tableHeading AS $key=>$value) {
				$content .= '<td colspan="'.$value.'" align="center"><b>'.$key.'</b></td>';
			}
			$content .=  "</tr><tr>";
		}
		foreach ($fieldsArr AS $value) {
			$content .=  '<td>'.$value.'</td>';
		}
		$content .=  "</tr>";
		$array_keys = array_keys($fieldsArr);
		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if ($key == 'Persnr_ref') $value = $this->getValueFromTable("Eval_Auditors", "Persnr", $value, "Names")." ".$this->getValueFromTable("Eval_Auditors", "Persnr", $value, "Surname");
				$content .=  '<td>'.$value.'</td>';
			}
			$content .=  "</tr>";
		}
		$content .=  "</table>";
		
		return $content;
	}

	/*
	 * Louwtjie
	 * 30-03-2004
	 * generate the site visit programme to send to institution
	*/

	function generateSiteProgram ($siteVisit_id, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="") {
		$count=0;
		$content = "";
		if ($report) {
			$content .=  $this->makeTop($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		}
		$content .=  "<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>";
		$content .=  "<tr>";

		foreach ($fieldsArr AS $value) {
			$content .=  '<td valign="top" class="oncolourb">'.$value.'</td>';
			$count++;
		}
		$content .=  "</tr>";
		$array_keys = array_keys($fieldsArr);
	$SQL = "SELECT ".implode(", ", array_keys($fieldsArr))."  FROM `".$table."` WHERE siteVisit_ref=".$siteVisit_id." ORDER BY ".$keyFld;
		$conn = $this->getDatabaseConnection();
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("ss", $siteVisit_id, $keyFld);
		//$sm->execute();
		//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				$content .=  '<td valign="top">'.$value.'</td>';
			}
			$content .=  "</tr>";
		}
		$content .=  "</table>";
		
		return $content;
	}

	/*
	Louwtjie
	30-03-2004
	show the program table for site visit process
	*/
	function showProgram($siteVisit_id, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading=""){
		$conn = $this->getDatabaseConnection();
		$count=0;
		$message = "";
		$content = "";
		if ($report) {
			$content .=  $this->makeTop($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		}
		$content .=  "<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>";
		$content .=  "<tr>";
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			foreach ($tableHeading AS $key=>$value) {
				$content .= '<td valign="top" class="oncolourb" colspan="'.$value.'" align="center"><b>'.$key.'</b></td>';
			}
			$content .=  "</tr><tr>";
		}
		foreach ($fieldsArr AS $value) {
			$content .=  '<td valign="top" class="oncolourb">'.$value.'</td>';
			$count++;
		}
		$content .=  "<td valign='top'>&nbsp;</td></tr>";
		$array_keys = array_keys($fieldsArr);
		$SQL = "SELECT * FROM `".$table."` WHERE siteVisit_ref=".$siteVisit_id;
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $siteVisit_id);
		//$sm->execute();
		//$rs = $sm->get_result();
				
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
			$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row for scheduling the site visit. <i>Note that you can have more than one row in which to plan activities of the site visit.</i>";

		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if (($value == '1000-01-01') || ($value == '00:00:00')) $value = '';
				if (stristr($key, "time") > "") {
					$content .=  '<td valign="top"><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td valign="top"><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "text") > "") {
					$content .=  "<td valign='top'><textarea size='".$sizeOfFld."' ID='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."'>".$value."</textarea></td>";
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td valign='top'><input size='".$sizeOfFld."' type='TEXT' ID='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
					}
				}
			}
			if (!$report) $content .=  "<td valign='top' align='left'><a href='javascript:changeID(\"".$row[$keyFld]."\");changeCMD(\"del\");moveto(\"stay\")'>Del</a></td>";
			$content .=  "</tr>";
		}
		if (!$report) {
			$content .=  "<tr>";
			$content .=  "<td valign='top' colspan='".($count)."'>".$message."</td><td align='left'><a href='javascript:changeCMD(\"new\");moveto(\"stay\")'>Add</a></td>";
			$content .=  "</tr>";
		}
		$content .= '<tr><td><input type="HIDDEN" ID="GRID_save_'.$row[$keyFld].'" name="GRID_save_'.$row[$keyFld].'" value="1"></td></tr>';
		$content .=  "</table>";
		if (!$report) {
			$content .=  "<input type='hidden' ID='cmd' name='cmd' value=''>";
			$content .=  "<input type='hidden' ID='id' name='id' value=''>";
		}
		
		return $content;
	}

	/*
	Louwtjie
	2004-03-31
	To return the dates for the sitevisit in the e-mail to institution but to print it also in the html pages.
	*/
	function getDatesForSiteVisit ($print="") {
		$SQL = "SELECT date_visit1, date_visit2, final_date_visit FROM siteVisit WHERE siteVisit_id=".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;
		$conn = $this->getDatabaseConnection();
//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
		//$sm->execute();
		//$RS = $sm->get_result();
		
		$RS = mysqli_query($conn, $SQL);
		$SEL = " CHECKED";
		if ($row = mysqli_fetch_array($RS)) {
			if ($print > "") {
				echo '<td colspan="2" align="center">';
				$date1 = '<input type="radio" name="FLD_final_date_visit" id="date1" value="'.$row["date_visit1"].'"';
				if ($row["final_date_visit"] == $row["date_visit1"]) {
					$date1 .= $SEL;
				}
				echo $date1.'>&nbsp;'.$row["date_visit1"];
				echo '<Br>';
				$date2 = '<input type="radio" name="FLD_final_date_visit" id="date2" value="'.$row["date_visit2"].'"';
				if ($row["final_date_visit"] == $row["date_visit2"]) {
					$date2 .= $SEL;
				}
				echo $date2.'>&nbsp;'.$row["date_visit2"].'</td>';
			}else {
				return $row["date_visit1"]."|".$row["date_visit2"];
			}
		}
		
	}

	/**
	* Function to create the academic sturcture table of the institutional profile
	* @author Louwtjie du Toit
	* @return mixed $content
	*/
	function createAcaStruct($tableKeyFld, $tableKeyVal, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading=""){
		$count=0;
		$message = "";
		$content = "";
		$content .=  "<table width='90%' cellpadding='2' cellspacing='2' align='center' border='0'>";
		$content .=  "<tr>";
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			foreach ($tableHeading AS $key=>$value) {
				$content .= '<td class="oncolourb" colspan="'.$value.'" align="center"><b>'.$key.'</b></td>';
			}
			$content .=  "<td>&nbsp;</td></tr><tr>";
		}
		foreach ($fieldsArr AS $value) {
			$content .=  '<td class="oncolour" align="center"><b>'.$value.'</b></td>';
			$count++;
		}
		$content .=  "<td>&nbsp;</td></tr>";
		$array_keys = array_keys($fieldsArr);
	$SQL = "SELECT * FROM `".$table."` WHERE ".$tableKeyFld."='".$tableKeyVal."'";
		$conn = $this->getDatabaseConnection();
		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $tableKeyVal);
		//$sm->execute();
		//$rs = $sm->get_result();
		
		$rs = mysqli_query($conn, $SQL);
		if (!mysqli_num_rows($rs)) {
			$message = "To start filling in the table, click on the 'Insert' link on the right of the table";
		}

		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if (stristr($key, "time") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td><input size='".$sizeOfFld."' type='TEXT' ID='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
					}
				}
			}
			if (!$report) $content .=  "<td align='left'><a href='javascript:changeID(\"".$row[$keyFld]."\");changeCMD(\"del\");moveto(\"stay\")'>Del</a></td>";
			$content .= '<tr><td><input type="HIDDEN" name="GRID_save_'.$row[$keyFld].'" value="1"></td></tr>';
			$content .=  "</tr>";
		}
		if (!$report) {
			$content .=  "<tr>";
			$content .=  "<td colspan='".($count)."'>".$message."</td><td align='left'><a href='javascript:changeCMD(\"new\");moveto(\"stay\")'>Insert</a></td>";
			$content .=  "</tr>";
		}
		$content .=  "</table>";
		
		return $content;
	}

	// 2007-08-05 Robin
	// Return an array of all evaluators attached to an application.
	// 2012-11-08 Updated to include extracting evaluators for proceedings
	function getSelectedEvaluatorsForApplication ($app_id, $where="", $flag="Accred", $arrkey="Persnr") {
		$eval_arr = array();
		$where_app = "";

		if ($where > ""){
			$where_app = " AND " . implode(" AND ", $where);
		}

		switch ($flag){
		case "Reaccred":
			$appl_whr = "AND evalReport.reaccreditation_application_ref=$app_id";
			break;
		case "Proceedings":
			$appl_whr = "AND evalReport.ia_proceedings_ref = $app_id";
			break;
		case "Accred":
		default:
			$appl_whr = "AND evalReport.application_ref=$app_id";
		}
		
		$SQL =<<<evalSQL
			SELECT Eval_Auditors.Persnr, CONCAT(Eval_Auditors.Surname,', ',Eval_Auditors.Names,' ') as Name, Eval_Auditors.Surname, Eval_Auditors.Names, Eval_Auditors.E_mail, evalReport.do_summary,
			evalReport.lop_isSent, evalReport.lop_isSent_date, Eval_Auditors.Work_Number, evalReport.evalReport_date_sent, Eval_Auditors.Title_ref, t.lkp_title_desc, evalReport.evalReport_doc,
			evalReport.evalReport_id, evalReport.application_sum_doc, Eval_Auditors.user_ref, evalReport.evalReport_date_completed, evalReport.view_by_other_eval_yn_ref,
			evalReport.application_ref, evalReport.eval_contract_doc, evalReport.ia_proceedings_ref
			FROM (Eval_Auditors, evalReport)
			LEFT JOIN lkp_title t ON t.lkp_title_id = Eval_Auditors.Title_ref
			WHERE evalReport.Persnr_ref=Eval_Auditors.Persnr
			$appl_whr
			$where_app
			ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names
evalSQL;
               
             //  echo $SQL;
             
                
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($row = mysqli_fetch_array($rs)) {
			// The same evaluator is assigned again if it is a deferral.  This is lost if Persnr is used as the second record replaces the first.
			// Thus one can pass the key that you want returned as the array reference
			$eval_arr[$row["$arrkey"]] = $row;
//echo "<br />" . $row['application_ref'] . ": " . $row["Persnr"] . ": " . $row['Name'] .": " . $row['lop_isSent_date'] .": " . $row['evalReport_date_sent'].": " . $row['evalReport_date_completed'] . ": " . $row['evalReport_doc'];
			}
                
                
                
		return $eval_arr;
	}

	/*
	2004-04-01
	Louwtjie
	Return a array of the evaluators that worked on a specific application.
	*/
	function getEvaluatorsPerApplication ($app_id) {
		$conn = $this->getDatabaseConnection();
		$eval_arr = array();
		$SQL = "SELECT Names, Surname FROM Eval_Auditors, evalReport WHERE do_sitevisit_checkbox=1 AND active=1 AND eval_site_visit_status_confirm=1 AND Persnr_ref=Persnr AND application_ref=".$app_id." ORDER BY Surname, Names";
		
	//	$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $app_id);
//$sm->execute();
//$rs = $sm->get_result();
		
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($row = mysqli_fetch_array($rs)) {
			array_push($eval_arr, $row["Surname"].", ".$row["Names"]);
		}
		
		return $eval_arr;
	}


	/*
	2004-04-01
	Louwtjie
	Return a array of the site visit letter to institution.
	*/
	function getSiteVisitValues ($table, $selectFld, $keyFld, $keyVal) {
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

		$site_arr = array();
		$SQL = "SELECT ".$selectFld." FROM siteVisit WHERE ".$keyFld."=".$keyVal;
		$rs = mysqli_query ($conn, $SQL);
		if ($row = mysqli_fetch_array($rs)) {
			$site_arr = explode("\n", $row[$selectFld]);
		}
		return $site_arr;
	}

	/*
	2004-04-01
	Louwtjie
	Return the site of delivery for a specific site visit.
	*/
	function getSiteOfDeliveryForSiteVisit ($app_id) {
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

		$SQL = "SELECT site_delivery FROM siteVisit WHERE application_ref=".$app_id;
		$rs = mysqli_query ($conn,$SQL);
		if ($row = mysqli_fetch_array($rs)) {
			return $row["site_delivery"];
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
	  Louwtjie: 2004-05-11
	  function to return the documents that should be checked in the screening (application) process.
	*/
	function returnApplicationDocs (&$docs, &$no_docs, &$doc_url, $application_id=0) {
		$institution_type = "";
		if ( !($application_id > 0) ) {
			$application_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
			$institution_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $application_id, "institution_id"), "priv_publ");
		}
		$numbers = array(1,2,3,4,5,6,7,8,9);
		$all_docs = array();

		$SQL = "SELECT lkp_application_docs_desc, lkp_application_docs_fieldName, private_public FROM lkp_application_docs";
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($row = mysqli_fetch_object($RS)) {
			$all_docs[$row->lkp_application_docs_fieldName] = $row->lkp_application_docs_desc."|".$row->private_public;
		}

		$rs = mysqli_list_fields($this->DBname, "Institutions_application", $this->getDatabaseConnection());
		$columns = mysqli_num_fields($rs);
		for ($i = 0; $i < $columns; $i++) {
			if ((in_array(substr(mysqli_field_name($rs, $i), 0, 1), $numbers)) && (mysqli_field_type($rs, $i)== "int") && (substr(mysqli_field_name($rs, $i),2,strlen(mysqli_field_name($rs, $i))) != "criteria")) {
				foreach ($all_docs AS $key=>$value) {
					if (($key == mysqli_field_name($rs, $i)) && (($institution_type == substr($value, (strpos($value, "|")+1), strlen($value))) || (substr($value, (strpos($value, "|")+1), strlen($value)) == 3))) {
						$fieldVal = $this->getValueFromTable("Institutions_application", "application_id", $application_id, mysqli_field_name($rs, $i));
						if (!(($this->getValueFromTable("Institutions_application", "application_id", $application_id, "NQF_ref") < 3) && (substr(mysqli_field_name($rs, $i), 0, 1) == 9))) {
							if (($fieldVal >= 2) && ($fieldVal < 4)) { //value 2 is if they answered yes
								$docs["DOCRADIO_".mysqli_field_name($rs, $i)] = substr($value, 0, strpos($value, "|"));
								$doc_fld_name = mysqli_field_name($rs, $i)."_doc";
								$doc_fld_url = "";
								$doc_fld_url = $this->getValueFromTable("Institutions_application", "application_id", $application_id, $doc_fld_name);
								$doc_url[mysqli_field_name($rs, $i)] = ($doc_fld_url > 0)?($doc_fld_url):("POSTED");
							}
							if (($fieldVal < 2) || ($fieldVal == 5)) {
								$SQL = "SELECT ".mysqli_field_name($rs, $i)."_whyNot FROM `Institutions_application` WHERE application_id=".$application_id;
								$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
								$why = ($fieldVal == 5)?("N/A: "):("");
								if ($RS && ($row=mysqli_fetch_row($RS))) {
									$why .= $row[0];
								}
								$no_docs[$key] = $why; //copies only the reason for not submitting documentation into array
							}
							if ($fieldVal == 4) {
								$why = "Same as institutional profile";
								$no_docs[$key] = $why; //copies only the reason for not submitting documentation into array
							}
						}
					}
				}
			}
		}
		ksort($doc_url);
		ksort($no_docs);
		ksort($docs);
		ksort($all_docs);
		
		return $all_docs;
	}

	/*
	 * Louwtjie: 2004-08-05
	 * function to check if there is still documents left to check in the supporting documentation page: checkform2a
	*/
	function retSuppDocsChecked ($app_id=0) {
		if (! ($app_id > 0) ) {
			$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		}
		$docs = $no_docs = $doc_url = array();
		$this->returnApplicationDocs($docs, $no_docs, $doc_url);
		$no_docs = array();
		$SQL = "SELECT documentation FROM screening WHERE application_ref=".$app_id;
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$documentation = "";
		if ($RS && ($row=mysqli_fetch_array($RS))) {
			$documentation = $row["documentation"];
		}
		$checkBoxArr = explode("|", $documentation);
		if (count($checkBoxArr) > 0) {
			foreach ($docs AS $key=>$value) {
				if (! (in_array(substr($key, 9, strlen($key)), $checkBoxArr)) ) {
					$no_docs[$key] = $value;
				}
			}
		}
		
		return $no_docs;
	}


	/*
	Louwtjie: 2004-05-17
	function to handle the choose of evaluators and managers.
	*/
	function chooseEvaluatorsManagers($evalArr) {
		$was_sent = array(); // array for which letters was already sent

		if (count($evalArr) > 0) {
			$SQL = "SELECT Persnr_ref, is_manager FROM `".$this->dbTableCurrent."` WHERE lop_isSent = 1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
			
			$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			while ($row = mysqli_fetch_array($RS)) {
				$man = "";
				if ($row["is_manager"]) {
					$man = "M";
				}
				array_push($was_sent, $man.$row["Persnr_ref"]);
			}

			foreach ($evalArr AS $key=>$value) {
				$mkey = -1;
				if (substr($value,0,1) == "M") {
					$value = substr($value,1,strlen($value));
					$mkey = $key;
				}
				if (!(in_array($value, $was_sent))) {
					$SQLman = ($mkey!=-1)?(", is_manager=1 "):("");
					$SQL = "UPDATE `".$this->dbTableCurrent."` SET lop_isSent = 1 ".$SQLman." WHERE Persnr_ref=".$value." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
					$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
					$message = $this->getTextContent ($this->template, "Letter of appointment");
					if ($this->getValueFromTable("Eval_Auditors", "Persnr", $value, "active")) {
						$this->misMailByName ("heqc@octoplus.co.za", "RE: Accreditation Reference number", "THIS EMAIL SHOULD HAVE NOT GONE OUT! THIS FUNCTION SHOULD NOT BE USED. function: chooseEvaluatorsManagers\n\n\n\n\n".$message);
						$this->misMailByName ($this->getValueFromTable("Eval_Auditors", "Persnr", $value, "E_mail"), "RE: Accreditation Reference number", $message, "", false);
					}
				}
			}

			$old_arr = array_diff($was_sent, $evalArr);
			foreach ($old_arr AS $value) {
				$SQL = "UPDATE `".$this->dbTableCurrent."` SET lop_isSent = 0 WHERE Persnr_ref=".$value." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
				$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			}
			
		}
	}

	/*
	Louwtjie: 2004-05-20:
	function to show the site visit report that the evaluators filled in.
	*/
	function showSiteVisitReport ($dis="") {
		echo $this->buildSiteVisitReportTable($dis);

		$app_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$site_ref = $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref");
		$SQL = "SELECT * FROM `siteVisit_report` WHERE site_ref='".$site_ref."' AND application_ref='".$app_ref."'";
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		echo '<script>';
		while ($row = mysqli_fetch_object($RS)) {
			echo '	obj = document.all;';//.commend|'.$row->siteVisit_report_areas_ref.';'."\n";
			echo '	for (i=0; i<obj.length; i++) {'."\n";
			echo '		if ((obj[i].name == "commend|'.$row->siteVisit_report_areas_ref.'") && (obj[i].value == "'.$row->commend.'")) {'."\n";
			echo '			obj[i].checked = true;'."\n";
			echo '		}'."\n";
			echo '		if ((obj[i].name == "documentation|'.$row->siteVisit_report_areas_ref.'") && (obj[i].value == "'.$row->documentation.'")) {'."\n";
			echo '			obj[i].checked = true;'."\n";
			echo '		}'."\n";
			echo '		if (obj[i].name == "comments|'.$row->siteVisit_report_areas_ref.'") {'."\n";
			echo '			obj[i].value = "'.$row->comments.'";'."\n";
			echo '		}'."\n";
			echo '	}'."\n";
		}
		echo '</script>';
		
	}

	/*
	Louwtjie: 2004-05-20:
	function to save the site visit report that the evaluators filled in.
	*/
	function saveSiteVisitReport ($reportArray) {
                $conn = $this->getDatabaseConnection();
		$commend = array();
		$documentation = array();
		$comments = array();
		$app_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$site_ref = $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref");
		foreach ($reportArray AS $key=>$value) {
			$id = 0;
			if (strstr($key, "commend") > "") {
				$ref = explode ("|", $key);
				$SQL = "SELECT * FROM siteVisit_report WHERE siteVisit_report_areas_ref='".$ref[1]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				$valueRS = mysqli_query($conn, $SQL);
				if ($valueRS && (mysqli_num_rows($valueRS) > 0) && ($rr = mysqli_fetch_object($valueRS))) {
					$SQL = "UPDATE `siteVisit_report` SET site_ref='".$site_ref."', application_ref='".$app_ref."', commend='".$value."' WHERE siteVisit_report_areas_ref='".$ref[1]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
					$RS = mysqli_query($conn, $SQL);
					$id = $rr->siteVisit_report_id;
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, commend, siteVisit_report_areas_ref) VALUES ('".$site_ref."', '".$app_ref."', '".$value."', '".$ref[1]."')";
					$RS = mysqli_query($conn, $SQL);
					$id = mysqli_insert_id($conn);
				}
				array_push($commend, $id);
			}
			if (strstr($key, "documentation") > "") {
				$ref = explode ("|", $key);
				foreach ($ref AS $key=>$val) {
					$ref[$key] = $val."|".$value;
				}
				array_push($documentation, $ref);
			}
			if (strstr($key, "comments") > "") {
				$ref = explode ("|", $key);
				foreach ($ref AS $key=>$val) {
					$ref[$key] = $val."|".$value;
				}
				array_push($comments, $ref);
			}
		}

		if (count($documentation) > 0) {
			foreach ($documentation AS $key=>$value) {
				$docArea = explode("|", $documentation[$key][1]);
				$SQLarea = "SELECT * FROM `siteVisit_report` WHERE siteVisit_report_areas_ref='".$docArea[0]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				$RSarea = mysqli_query($this->getDatabaseConnection(), $SQLarea);
				if ($RSarea && (mysqli_num_rows($RSarea) > 0)) {
					$SQL = "UPDATE `siteVisit_report` SET documentation='".$docArea[1]."'  WHERE siteVisit_report_areas_ref='".$docArea[0]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, siteVisit_report_areas_ref, documentation) VALUES ('".$site_ref."', '".$app_ref."', '".$docArea[0]."', '".$docArea[1]."')";
				}
				$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			}
		}

		if (count($comments) > 0) {
			foreach ($comments AS $value) {
				$qRef = explode("|", $value[1]);
				$com = explode("|", $value[0]);
				$SQLarea = "SELECT * FROM `siteVisit_report` WHERE siteVisit_report_areas_ref='".$qRef[0]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				$RSarea = mysqli_query($this->getDatabaseConnection(), $SQLarea);
				if ($RSarea && (mysqli_num_rows($RSarea) > 0)) {
					$SQL = "UPDATE `siteVisit_report` SET comments='".$com[1]."' WHERE application_ref='".$app_ref."' AND site_ref='".$site_ref."' AND siteVisit_report_areas_ref='".$qRef[0]."'";
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, siteVisit_report_areas_ref, comments) VALUES ('".$site_ref."', '".$app_ref."', '".$qRef[0]."', '".$com[1]."')";
				}
				$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			}
		}
		
	}

	/*
	Louwtjie: 2004-05-20:
	function to check if the site visit report is already filled in.
	*/
	function checkIfSiteVisitReportDone () {
		$ret = 0;
		$app_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$site_ref = $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID;
		$SQL = "SELECT * FROM `siteVisit_report` WHERE site_ref='".$site_ref."' AND application_ref='".$app_ref."'";
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($RS) > 0) {
			$ret = 1;
		}
		
		return $ret;
	}

	/*
	Louwtjie: 2004-05-20:
	function to build the site visit report that the evaluators should fill in.
	*/
	function buildSiteVisitReportTable ($dis="", $site_ref=0, $application_ref=0, $show_table_top=0) {
                
		$valueRS = "";
		if (($site_ref > 0) && ($application_ref > 0)) {
			$SQL = "SELECT * FROM siteVisit_report WHERE application_ref='".$application_ref."' AND site_ref='".$site_ref."' ORDER by siteVisit_report_id";
			$valueRS = mysqli_query($this->getDatabaseConnection(), $SQL);
		}
		if (($valueRS > "") && (mysqli_num_rows($valueRS) < 14)) {
			$arr = array();
			while ($valueRS && ($tmpRow=mysqli_fetch_array($valueRS))) {
				$arr[$tmpRow["siteVisit_report_areas_ref"]] = $tmpRow["commend"]."|".$tmpRow["documentation"]."|".$tmpRow["comments"];
			}
			$SQL = "DELETE FROM `siteVisit_report` WHERE application_ref='".$application_ref."' AND site_ref='".$site_ref."'";
			$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			for ($j=1; $j<14; $j++) {
				$SQL = "INSERT INTO `siteVisit_report` VALUES (NULL, '".$application_ref."', '".$site_ref."', '".$j."', ";
				if (isset($arr[$j]) && ($arr[$j] > "")) {
					$tmp = explode("|", $arr[$j]);
					$SQL .= "'".$tmp[0]."', '".$tmp[1]."', '".$tmp[2]."')";
				}else {
					$SQL .= "'0', '0', '')";
				}
 				$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			}
			$SQL = "SELECT * FROM siteVisit_report WHERE application_ref='".$application_ref."' AND site_ref='".$site_ref."' ORDER by siteVisit_report_id";
			$valueRS = mysqli_query($this->getDatabaseConnection(), $SQL);
		}
		$html = "";
		$SQL = "SELECT * FROM siteVisit_report_headings";
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$TDcount = mysqli_num_rows($RS);

		if ($show_table_top == 1) {
			$html .=  $this->makeTop($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		}

		$html .=  '<table border="1"><tr>';
		while ($row = mysqli_fetch_object($RS)) {
			$html .=  '<td valign="top" class="oncolourb"><b>'.$row->siteVisit_report_heading_desc.'</b></td>';
		}
		$html .=  '</tr>';
		$SQL = "SELECT * FROM siteVisit_report_areas";
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$heading = $subHeading = "";
		$count = 0;
		while ($row = mysqli_fetch_object($RS)) {
			if ($heading != $row->main_heading) {
				$html .=  '<tr>';
				$html .=  '<td valign="top" class="oncolourb" colspan="'.($TDcount).'"><b>';
				$html .= $row->main_heading;
				$html .=  '</b>';
				if (($row->sub_heading > "") && ($row->sub_heading != $subHeading)) {
					$html .= ' - '.$row->sub_heading;
				}
				$html .= '</td>';
				$html .= '</tr>';
			}
			$rr = "";
			if ($valueRS && (mysqli_num_rows($valueRS) > 0)) {
				if (! ($count > (mysqli_num_rows($valueRS)-1)) ) {
					mysqli_data_seek($valueRS, $count);
					$rr = mysqli_fetch_object($valueRS);
				}
			}
			$checkCommend1 = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->commend == 1))?("CHECKED"):("");
			$checkCommend2 = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->commend == 2))?("CHECKED"):("");
			$checkCommend3 = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->commend == 3))?("CHECKED"):("");
			$checkCommend4 = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->commend == 4))?("CHECKED"):("");
			$checkYes = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->documentation == 2))?("CHECKED"):("");
			$checkNo = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && ($rr->documentation == 1))?("CHECKED"):("");

			$commentINFRASTRUCTURE = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="INFRASTRUCTURE") && ($count==0)))?($rr->comments):("");
			$commentSTAFF = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="STAFF") && ($count==6)))?($rr->comments):("");
			$commentSTUDENTS = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="STUDENTS") && ($count==8)))?($rr->comments):("");
			$commentLEARNINGMATERIAL = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="LEARNING MATERIALS") && ($count==11)))?($rr->comments):("");
			$commentOTHER = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="OTHER") && ($count==12)))?($rr->comments):("");
			$commentCOMMENTS = (($row->siteVisit_report_areas_id == $rr->siteVisit_report_areas_ref) && (($row->main_heading=="COMMENTS") && ($count==13)))?($rr->comments):("");

			if ($count < 13) {
				$html .= '<tr>';
				$html .= '<td valign="top">'.$row->question.'</td>';
				$html .= '<td valign="top"><input type="radio" ID="commend|'.$row->siteVisit_report_areas_id.'" name="commend|'.$row->siteVisit_report_areas_id.'" value="1" '.$dis.' '.$checkCommend1.'></td>';
				$html .= '<td valign="top"><input type="radio" ID="commend|'.$row->siteVisit_report_areas_id.'" name="commend|'.$row->siteVisit_report_areas_id.'" value="2" '.$dis.' '.$checkCommend2.'></td>';
				$html .= '<td valign="top"><input type="radio" ID="commend|'.$row->siteVisit_report_areas_id.'"  name="commend|'.$row->siteVisit_report_areas_id.'" value="3" '.$dis.' '.$checkCommend3.'></td>';
				$html .= '<td valign="top"><input type="radio" ID="commend|'.$row->siteVisit_report_areas_id.'" name="commend|'.$row->siteVisit_report_areas_id.'" value="4" '.$dis.' '.$checkCommend4.'></td>';
				$html .= '<td valign="top"><input type="radio" ID="documentation|'.$row->siteVisit_report_areas_id.'" name="documentation|'.$row->siteVisit_report_areas_id.'" value="1" '.$dis.' '.$checkNo.'>No';
				$html .= '&nbsp;<input type="radio" ID="documentation|'.$row->siteVisit_report_areas_id.'" name="documentation|'.$row->siteVisit_report_areas_id.'" value="2" '.$dis.' '.$checkYes.'>Yes</td>';
//the following 5 rows are for the extra comments column that has been replaced by the last comments row.
//				if (($row->main_heading=="INFRASTRUCTURE") && ($count==0)) $html .= '<td valign="top" rowspan="6"><textarea rows="12" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentINFRASTRUCTURE.'</textarea></td>';
//				if (($row->main_heading=="STAFF") && ($count==6)) $html .= '<td valign="top" rowspan="2"><textarea rows="7" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentSTAFF.'</textarea></td>';
//				if (($row->main_heading=="STUDENTS") && ($count==8)) $html .= '<td valign="top" rowspan="3"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentSTUDENTS.'</textarea></td>';
//				if (($row->main_heading=="LEARNING MATERIALS") && ($count==11)) $html .= '<td valign="top" rowspan="1"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentLEARNINGMATERIAL.'</textarea></td>';
//				if (($row->main_heading=="OTHER") && ($count==12)) $html .= '<td valign="top" rowspan="1"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentOTHER.'</textarea></td>';
				$html .= '</tr>';
			}

			if ($count == 13) {
				$html .= '<tr><td colspan="6" valign="top" rowspan="1"><textarea style="width:100%" rows="10" ID="comments|'.$row->siteVisit_report_areas_id.'" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentCOMMENTS.'</textarea></td></tr>';
			}

			$count++;
			$heading = $row->main_heading;
			$subHeading = $row->sub_heading;
		}
		$html .= '</table>';
		
		return ($html);
	}

	/*
	Louwtjie: 2004-05-19
	function to draw the table at the top of the screen with institution and program information.
	*/
	function showInstitutionTableTop ($applicationID=0) {
		if ( !($applicationID > 0) ) {
			$applicationID = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		}
		$inst_id = $this->getValueFromTable("Institutions_application", "application_id",$applicationID, "institution_id");
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application___application_id=".$applicationID;

		$proc_id = '';
		$reacc_id = 0;
		$tmpSettingsReacc = '';
		if (isset($this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID)){
			$proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
			$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $proc_id,"lkp_proceedings_ref");
			$proc_desc = $this->getValueFromTable("lkp_proceedings", "lkp_proceedings_id", $proc_type, "lkp_proceedings_desc");
			if ($proc_type == 5){
				$reacc_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
				$tmpSettingsReacc = ($reacc_id > 0) ? "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reacc_id : "";
				$reacc_progname = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_id, "programme_name");
			}
		}
		
		echo '<table width="75%" border=0  cellpadding="2" cellspacing="2">';
		echo '<tr>';
		echo '	<td width="40%">&nbsp;</td>';
		echo '	<td>&nbsp;</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>INSTITUTION NAME:</b> </td>';
		echo '	<td valign="top" class="oncolour"><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$inst_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name").' - (view profile)</a></td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>PROVIDER TYPE:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->table_field_info($this->active_processes_id, "InstitutionType").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>PROGRAMME NAME:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->table_field_info($this->active_processes_id, "ProgrammeName").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>DELIVERY MODE:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $this->getValueFromTable("Institutions_application", "application_id", $applicationID,"mode_delivery"), "lkp_mode_of_delivery_desc").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>NQF Level:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->getValueFromTable("NQF_level", "NQF_id", $this->getValueFromTable("Institutions_application", "application_id", $applicationID,"NQF_ref"), "NQF_level").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>HEQC - Reference Number:</b></td>';
		echo '	<td valign="top" class="oncolour"><a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$applicationID.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->table_field_info($this->active_processes_id, "HEQC_ref").' - (view application form)</a></td>';
		echo '</tr>';
		if ($proc_id != '' && $proc_id > 0){
			echo '<tr>';
			echo '	<td valign="top" width="30%" align="right"><b>CURRENT PROCEEDINGS:</b></td>';
			switch($proc_type){
			case 5:
				echo '	<td valign="top" class="oncolour">' . $proc_desc . ': ' 
					. '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$reacc_id.'\', \''.base64_encode($tmpSettingsReacc).'\', \'\');">'.$reacc_progname.'</a>' 
					. "</td>";
				break;
			default:
				echo '	<td valign="top" class="oncolour">' . $proc_desc . '</td>';
				break;
			}
			echo '</tr>';
		}

		// Display previous proceedings if any in sequential order
		$psql =<<<PROCEEDINGS
			SELECT ia_proceedings_id, lkp_proceedings_desc
			FROM ia_proceedings, lkp_proceedings
			WHERE ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
			AND application_ref = $applicationID
			AND proceeding_status_ind = 1
			ORDER BY prev_ia_proceedings_ref
PROCEEDINGS;
                
		$prs = mysqli_query($this->getDatabaseConnection(), $psql);
		if ($prs){
			if (mysqli_num_rows($prs) > 0){
				echo '<tr>';
				echo '	<td valign="top" width="30%" align="right"></td>';
				echo '	<td valign="top" class="oncolour"><b>Previous proceedings for this application:</b></td>';
				echo '</tr>';				
	
				while ($prow = mysqli_fetch_array($prs)){
					$docs_arr = $this->getProceedingDocs($prow["ia_proceedings_id"], "application header");
					$docs = "";
					foreach($docs_arr as $d){
						$docs .= "<br />" . $d;
					}

					echo '<tr>';
					echo '	<td valign="top" width="30%" align="right">&nbsp;</td>';
					echo '	<td valign="top" class="oncolour">'.$prow["lkp_proceedings_desc"].$docs.'</td>';
					echo '</tr>';				
				}
			}
		}
		echo '</table>';
                
	}

	/**
	* Louwtjie du Toit
	* Date: 2004-0707
	* Function to display the general program info
	*/
	function showGeneralProgramInfo ($app_id) {
	
		$SQL = "SELECT * FROM `Institutions_application` WHERE application_id=".$app_id;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if ($RS && $row = mysqli_fetch_array($RS)) {
			echo '<tr>';
			echo '<td align="right"><b>Name:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["program_name"].'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Programme Type:</b></td>';
			echo '<td class="oncoloursoft">'.$this->getValueFromTable("lkp_prog_type", "lkp_prog_type_id", $row["prog_type"], "lkp_prog_type_desc").'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>CESM category:</b></td>';
			echo '<td class="oncoloursoft">'.$this->getValueFromTable("SpecialisationCESM_code1", "CESM_code1", $row["CESM_code1"], "Description").'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Minimum number of credits:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["num_credits"].'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Duration:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["expected_min_duration"].'</td>';
			echo '</tr>';
			/*
			<tr>';
			echo '<td align="right"><b>Articulation:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["articulation"].'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Entrance requirements:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["entrance_requirements"].'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Integrated assessment:</b> </td>';
			echo '<td class="oncoloursoft">'.$row["integrated_assessment"].'</td>';
			echo '</tr>
			*/
			echo '<tr>';
			echo '<td align="right"><b>Mode of delivery:</b> </td>';
			echo '<td class="oncoloursoft">'.$this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $this->getValueFromTable("institutional_profile", "institution_ref", $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID, "mode_delivery"), "lkp_mode_of_delivery_desc").'</td>';
			echo '</tr><tr>';
			echo '<td align="right"><b>Site of delivery:</b> </td>';
			$sql2 = "SELECT location FROM `institutional_profile_sites` WHERE institution_ref='".$row["institution_id"]."' AND main_site=1";
			$rs2 = mysqli_query($this->getDatabaseConnection(), $sql2);
			if ($rs2 && ($row2 = mysqli_fetch_array($rs2))) $site = $row2["location"];
			echo '<td class="oncoloursoft">'.$site.'</td>';
			echo '</tr>';
		}
		
	}

	/*
	This function makes the summary of expired/due processes
	*/
	function makeSumProcTable(){
                
		$SQL2 = "SELECT * FROM active_processes WHERE status=0 AND (due_date <> \"1000-01-01\") AND (due_date < NOW()) AND (expiry_date <> \"1000-01-01\") AND (expiry_date > NOW())";
		$rs2 = mysqli_query($this->getDatabaseConnection(), $SQL2);
		$SQL3 = "SELECT * FROM active_processes WHERE status=0 AND (expiry_date <> \"1000-01-01\") AND (expiry_date < NOW())";
		$rs3 = mysqli_query($this->getDatabaseConnection(), $SQL3);
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
	function showActiveProcesses ($order="last_updated") {
		$this->showField("sortorder");
		$cross = '<img src="images/dash_mark.gif">';
		$check = '<img src="images/check_mark.gif">';		
		$deleteclass = (!$this->sec_userInGroup("Institution")) ? "hidden" : "";
		if ($order == "p"){
			$order = "processes.processes_desc, active_processes.last_updated";
		} else {
			$order = "active_processes.last_updated";
		}	
		$applicprocesslist = "90,12,40,141,142,7,47,106,112,163,159,160,161,162,165,173,167,168,170,193,198,194,195,203";
		$asql =<<<APPL
			SELECT * 
			FROM active_processes, processes, users 
			WHERE processes_ref = processes_id  
			AND user_ref = user_id and user_id = {$this->currentUserID} 
			AND status = 0 
			AND active_date <= now() 
			AND processes_ref IN ({$applicprocesslist})
			ORDER BY $order DESC
APPL;
		$defaultapplic =<<<DEFAULT
			<table id="activeProcessTable" width="98%" border=0 align="center" cellpadding="3" cellspacing="3">
			<tr><td colspan="8" class="oncolourb">Application processes</td></tr>
			<tr>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='p';goto(2);">Process</a></td>
			<td class="oncolourb" align="center">Proceeding</td>
			<td class="oncolourb" align="center">Submission<br>date</td>
			<td class="oncolourb" align="center">Screening<br>date</td>
			<td class="oncolourb" align="center">Status</td>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='d';goto(2);">Last Updated</a></td>
			<td class="oncolourb $deleteclass" align="center">Delete</td>
DEFAULT;
                
		$ars = mysqli_query($this->getDatabaseConnection(), $asql);
		if (mysqli_num_rows($ars) > 0) {
			echo $defaultapplic;
			while ($arow = mysqli_fetch_array ($ars)) {
				$desc = $this->workflowDescription ($arow["active_processes_id"], $arow["processes_ref"]);
				$dueStyle = "";
				if ( ($arow["due_date"]!="1000-01-01") && ($arow["due_date"]<=date("Y-m-d")) ) {
					$dueStyle = "CLASS=due";
				}
				if ( ($arow["expiry_date"]!="1000-01-01") && ($arow["expiry_date"]<=date("Y-m-d")) ) {
					$dueStyle = "CLASS=expiry";
				}

				$arr = $this->parseOtherWorkFlowProcess($arow["active_processes_id"]);

				$app_id = "";
				$subm_date = "&nbsp;";
				$screen_date = "&nbsp;";
				$proc_id = "";
				$proc_type = "";
				$proc_desc = "&nbsp;";
				$proc_subm_date = "&nbsp;";
				$che_reference = "&nbsp;";
				$programme_name = "&nbsp;";
				$proc_screen_date = "&nbsp;";
				$status = "&nbsp;";
				if (isset($arr['ia_proceedings']) && $arr['ia_proceedings']->dbTableCurrentID > 0){
					$proc_id = $arr['ia_proceedings']->dbTableCurrentID;
					$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"lkp_proceedings_ref");
					$proc_desc = $this->getValueFromTable("lkp_proceedings","lkp_proceedings_id",$proc_type,"lkp_proceedings_desc");
					$proc_subm_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"submission_date");
					$proc_subm_date = ($proc_subm_date > '1000-01-01') ? $proc_subm_date : "&nbsp;";
					$proc_screen_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"screened_date");
					$proc_screen_date = ($proc_screen_date > '1000-01-01') ? $proc_screen_date : "&nbsp;";
					$evaluator_access_end_date = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "evaluator_access_end_date");
					$evaluator_access_end_date = ($evaluator_access_end_date > '1000-01-01') ? $evaluator_access_end_date : "&nbsp;";
				}

				if (isset($arr['Institutions_application']) && $arr['Institutions_application']->dbTableCurrentID > 0){
					$app_id = $arr['Institutions_application']->dbTableCurrentID;
					$subm_date = $this->getValueFromTable('Institutions_application', 'application_id', $app_id, "submission_date");
				}

				$HEQCref = "";

				if ($app_id > ""){
					switch ($arow["processes_ref"]){
					case 90:
					case 7:
					case 47:
						$resub = $this->getValueFromTable('screening', 'application_ref', $app_id, "comment_on_applicationForm");
						$HEQCref = "Submitted: " . $subm_date;
						if ($resub > ''){
							$HEQCref .= " (Re-submission)";
						}
						break;
					case 112:
						$HEQCref = 'Access until: ' . $evaluator_access_end_date;
						if ($proc_id > 0){
							$sql1 = "SELECT count(*) AS outstanding FROM evalReport WHERE ia_proceedings_ref = " . $proc_id . " AND evalReport_status_confirm = 1 AND evalReport_doc = 0";
							$rs1 = mysqli_query($this->getDatabaseConnection(), $sql1) OR die( $app_id);
							$row1 = mysqli_fetch_array($rs1);
							$HEQCref .= ($row1['outstanding'] > 0) ?  '<br />Reports: ' . $cross : '<br />Reports: ' . $check;
						}
						break;
					case 160: // Preliminary directorate recommendation
						if ($proc_id > 0){
							$recomm_ind = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "recomm_complete_ind");
							$HEQCref = ($recomm_ind == 1) ?  'Complete indicator: ' . $check : 'Complete indicator: ' . $cross;
						}
						break;
					case 165: // AC meeting and outcome
					case 173: // AC outcome approval
					case 167: // Ready for HEQC meeting
					case 168: // HEQC meeting
						if ($proc_id > 0){
							$ac_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "ac_meeting_ref");
							$heqc_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "heqc_meeting_ref");
							if ($ac_ref > 0){
								$HEQCref = " &nbsp;&nbsp;&nbsp;&nbsp;AC: " . $this->getValueFromTable('AC_Meeting', 'ac_id', $ac_ref, "ac_start_date");
							}
							if ($heqc_ref > 0){
								$HEQCref .= "<br/>HEQC: " . $this->getValueFromTable('HEQC_Meeting', 'heqc_id', $heqc_ref, "heqc_start_date");
							}
						}
						break;
					case 170:
						if ($proc_id > 0){
							$outcome = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "heqc_board_decision_ref");
							$heqc_meeting_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $proc_id, "heqc_meeting_ref");
							$heqc_meeting_date = $this->getValueFromTable('HEQC_Meeting', 'heqc_id', $heqc_meeting_ref, "heqc_start_date");
							if ($heqc_meeting_date > ''){
								$HEQCref = $this->getValueFromTable('lkp_desicion', 'lkp_id', $outcome, "lkp_title") . " (" . $heqc_meeting_date. ")";
							} else {
								$HEQCref = $this->getValueFromTable('lkp_desicion', 'lkp_id', $outcome, "lkp_title");
							}
						}
						break;
					default:
						$HEQCref = "-";
					}
				}

				if (isset($arr['payment'])){
					switch ($arow["processes_ref"]){
					case 12:
					case 40:
					case 141:
					case 142:
					case 198:
						$inv_date = '1000-01-01';
						$inv_amount = 0;
						$inv_paid = 0;
						$pay_id = $arr['payment']->dbTableCurrentID;
						$paysql =<<<SQL
							SELECT invoice_total, received_confirmation, invoice_sent, date_invoice
							FROM payment
							WHERE payment_id = $pay_id
SQL;
						$payrs = mysqli_query($this->getDatabaseConnection(), $paysql);
						if ($payrs){
							if (mysqli_num_rows($payrs) == 1){  // Only one row shouldbe returned
								$payrow = mysqli_fetch_array($payrs);
								$inv_date = $cross;
								if ($payrow['invoice_sent'] == 1) $inv_date = $check;
								if ($payrow['date_invoice'] > '1000-01-01') $inv_date = $check .' ' .$payrow['date_invoice'];
								$inv_amount = ($payrow['invoice_total'] > 0) ?  $payrow['invoice_total'] : $cross;
								$HEQCref = 'Sent: '.$inv_date . "<br/>" . 'Amount: ' . $inv_amount;
							}
						}
						break;
					}
				}

				$htmlrow =<<<AROW
					<tr class='onblue'>
						<td><a {$dueStyle} href="?ID={$arow["active_processes_id"]}">{$desc}</a></td>
						<td align="center">{$proc_desc}</td>
						<td align="center">{$subm_date}<br>{$proc_subm_date}</td>
						<td align="center">{$proc_screen_date}</td>
						<td align="center">{$HEQCref}</td>
						<td align="center">{$arow["last_updated"]}</td>
					</tr>
AROW;
				echo $htmlrow;
			}
			echo "</table>";
		}

		$SQL =<<<OTHER
			SELECT * 
			FROM active_processes, processes, users 
			WHERE processes_ref = processes_id  
			AND user_ref = user_id and user_id = $this->currentUserID
			AND status = 0 
			AND active_date <= now() 
			AND processes_ref NOT IN ({$applicprocesslist})
			ORDER BY $order DESC
OTHER;
		
		$default =<<<DEFAULT
			<table id="activeProcessTable" width="95%" border=0 align="center" cellpadding="3" cellspacing="3"><tr>
			<tr><td colspan="4" class="oncolourb">Active processes</td></tr>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='p';goto(2);">Process</a></td>
			<td class="oncolourb" align="center">Reference</td>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='d';goto(2);">Last Updated</a></td>
			<td class="oncolourb $deleteclass" align="center">Delete</td>
DEFAULT;
		echo $default;

		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
				<?php	
				$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
				$flag = true;

				$HEQCref = "";
					if (isset($arr['payment'])){
						switch ($row["processes_ref"]){
						case 12:
						case 40:
						case 141:
						case 142:
						case 198:
							$inv_date = '1000-01-01';
							$inv_amount = 0;
							$inv_paid = 0;
							$pay_id = $arr['payment']->dbTableCurrentID;
							$paysql =<<<SQL
								SELECT invoice_total, received_confirmation, invoice_sent, date_invoice
								FROM payment
								WHERE payment_id = $pay_id
SQL;
							$payrs = mysqli_query($this->getDatabaseConnection(), $paysql);
							if ($payrs){
								if (mysqli_num_rows($payrs) == 1){  // Only one row shouldbe returned
									$payrow = mysqli_fetch_array($payrs);
									$inv_date = $cross;
									if ($payrow['invoice_sent'] == 1) $inv_date = $check;
									if ($payrow['date_invoice'] > '1000-01-01') $inv_date = $check .' ' .$payrow['date_invoice'];
									$inv_amount = ($payrow['invoice_total'] > 0) ?  $payrow['invoice_total'] : $cross;
									$HEQCref = 'Sent: '.$inv_date . "<br/>" . 'Amount: ' . $inv_amount;
								}
							}
							break;
						}
					}
					if ($HEQCref > ""){
						//break;
						$flag = false;
					}

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

				if ($flag) {
					echo "&nbsp;";
				}
	?>	
				</td>
				<td align="center"><?php echo $row["last_updated"]?></td>
	<?php if ($this->sec_userInGroup("Institution")) {?>
				<td align="center"><input type="checkbox" id="checkboxhiddenActiveProcessId"  value = "<?php echo $row["active_processes_id"]; ?>"/></td>
	<?php }?>
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

	/* 2004-05-07
	   Diederik
   	   Function to show a list of active proccesses.
   	   2015-03-16 Robin - This is the previous showActiveProcesses.  If new one is working this code can
   	   be deleted.  It is not used anywhere.
	*/
	function showActiveProcesses1 ($order="last_updated") {
		$this->showField("sortorder");
		$cross = '<img src="images/dash_mark.gif">';
		$check = '<img src="images/check_mark.gif">';		
		$deleteclass = (!$this->sec_userInGroup("Institution")) ? "hidden" : "";
		
		$default =<<<DEFAULT
			<table id="activeProcessTable" width="95%" border=0 align="center" cellpadding="3" cellspacing="3"><tr>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='p';goto(2);">Process</a></td>
			<td class="oncolourb" align="center">Reference</td>
			<td class="oncolourb" align="center"><a href="javascript:document.defaultFrm.sortorder.value='d';goto(2);">Last Updated</a></td>
			<td class="oncolourb $deleteclass" align="center">Delete</td>
DEFAULT;
		echo $default;

		if ($order == "p"){
			$order = "processes.processes_desc, active_processes.last_updated";
		} else {
			$order = "active_processes.last_updated";
		}	
		$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id and user_id = ".$this->currentUserID." AND status = 0 AND active_date <= now() ORDER BY $order DESC";
                
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
				<?php	
				$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
				$flag = true;
				//foreach ($arr AS $k=>$v)
				//{
					$HEQCref = "";
					//Reference number only displayed if it is an application
					//if ($k == "Institutions_application"){
					if (isset($arr['Institutions_application'])){
						switch ($row["processes_ref"]){
						case 7:
							$resub = $this->getValueFromTable('screening', 'application_ref', $arr['Institutions_application']->dbTableCurrentID, "comment_on_applicationForm");
							$subm_date = $this->getValueFromTable('Institutions_application', 'application_id', $arr['Institutions_application']->dbTableCurrentID, "submission_date");
							$HEQCref = "Submitted: " . $subm_date;
							if ($resub > ''){
								$HEQCref .= " (Re-submission)";
							}
							break;
						case 160: // Preliminary directorate recommendation
							if (isset($arr['ia_proceedings'])){
								$recomm_ind = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "recomm_complete_ind");
								$HEQCref = ($recomm_ind == 1) ?  'Complete indicator: ' . $check : 'Complete indicator: ' . $cross;
							}
							break;
						case 165: // AC meeting and outcome
						case 173: // AC outcome approval
						case 167: // Ready for HEQC meeting
						case 168: // HEQC meeting
							if (isset($arr['ia_proceedings'])){
								$ac_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "ac_meeting_ref");
								$heqc_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "heqc_meeting_ref");
								if ($ac_ref > 0){
									$HEQCref = " &nbsp;&nbsp;&nbsp;&nbsp;AC: " . $this->getValueFromTable('AC_Meeting', 'ac_id', $ac_ref, "ac_start_date");
								}
								if ($heqc_ref > 0){
									$HEQCref .= "<br/>HEQC: " . $this->getValueFromTable('HEQC_Meeting', 'heqc_id', $heqc_ref, "heqc_start_date");
								}
							}
							break;
						case 170:
							$outcome = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "heqc_board_decision_ref");
							$heqc_meeting_ref = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "heqc_meeting_ref");
							$heqc_meeting_date = $this->getValueFromTable('HEQC_Meeting', 'heqc_id', $heqc_meeting_ref, "heqc_start_date");
							if ($heqc_meeting_date > ''){
								$HEQCref = $this->getValueFromTable('lkp_desicion', 'lkp_id', $outcome, "lkp_title") . " (" . $heqc_meeting_date. ")";
							} else {
								$HEQCref = $this->getValueFromTable('lkp_desicion', 'lkp_id', $outcome, "lkp_title");
							}
							break;
						case 106:
							if (isset($arr["ia_proceedings"])){
								$proc = $this->getValueFromTable('ia_proceedings', 'ia_proceedings_id', $arr['ia_proceedings']->dbTableCurrentID, "lkp_proceedings_ref");
								$HEQCref = $this->getValueFromTable('lkp_proceedings', 'lkp_proceedings_id', $proc, "lkp_proceedings_desc");						
							} 
							break;
						case 112:
							$HEQCref = 'Access until: ' . $this->getValueFromTable('Institutions_application', 'application_id', $arr['Institutions_application']->dbTableCurrentID, "evaluator_access_end_date");
							$sql1 = "SELECT count(*) AS outstanding FROM evalReport WHERE application_ref = " . $arr['Institutions_application']->dbTableCurrentID . " AND evalReport_status_confirm = 1 AND evalReport_doc = 0";
							$rs1 = mysqli_query($this->getDatabaseConnection(), $sql1);
							$row1 = mysqli_fetch_array($rs1);
							$HEQCref .= ($row1['outstanding'] > 0) ?  '<br />Reports: ' . $cross : '<br />Reports: ' . $check;
							break;
						default:
							$HEQCref = $this->getValueFromTable($arr['Institutions_application']->dbTableName, $arr['Institutions_application']->dbTableKeyField, $arr['Institutions_application']->dbTableCurrentID, "CHE_reference_code");
						}
					}
					if (isset($arr['payment'])){
						switch ($row["processes_ref"]){
						case 12:
						case 40:
						case 141:
						case 142:
						case 198:
							$inv_date = '1000-01-01';
							$inv_amount = 0;
							$inv_paid = 0;
							$pay_id = $arr['payment']->dbTableCurrentID;
							$paysql =<<<SQL
								SELECT invoice_total, received_confirmation, invoice_sent, date_invoice
								FROM payment
								WHERE payment_id = $pay_id
SQL;
							$payrs = mysqli_query($this->getDatabaseConnection(), $paysql);
							if ($payrs){
								if (mysqli_num_rows($payrs) == 1){  // Only one row shouldbe returned
									$payrow = mysqli_fetch_array($payrs);
									$inv_date = $cross;
									if ($payrow['invoice_sent'] == 1) $inv_date = $check;
									if ($payrow['date_invoice'] > '1000-01-01') $inv_date = $check .' ' .$payrow['date_invoice'];
									$inv_amount = ($payrow['invoice_total'] > 0) ?  $payrow['invoice_total'] : $cross;
									$HEQCref = 'Sent: '.$inv_date . "<br/>" . 'Amount: ' . $inv_amount;
								}
							}
							break;
						}
					}
					if ($HEQCref > ""){
						//break;
						$flag = false;
					}
				//}
				/*			
				if (($row["processes_ref"] == 5))
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

				if ($flag) {
					echo "&nbsp;";
				}
	?>	
				</td>
				<!-- BUG: <td><a href="?goto=6&AP=<?php//=$row["active_processes_id"]?>">View</a></td> -->
				<td align="center"><?php echo $row["last_updated"]?></td>
	<?php if ($this->sec_userInGroup("Institution")) {?>
				<td align="center"><input type="checkbox" id="checkboxhiddenActiveProcessId"  value = "<?php echo $row["active_processes_id"]; ?>"/></td>
	<?php }?>
				</tr>
	<?php
			}
		}
		if (mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
		if (mysqli_num_rows($rs) < 1) {
			echo '<tr class="onblue"><td colspan="3" align=center>There are currently no active processes</td></tr>';
		}
		mysqli_close($this->getDatabaseConnection());
	?>
	</table>
	<?php
	}
	function doPopulateGridFromTemplateTable ($parentTable, $parentTable_id, $childTable, $childKeyFld, $childRef, $fieldsArr, $lookup_template_table) {
                $conn=$this->getDatabaseConnection();
		$RS = mysqli_query($conn, "SELECT * FROM `".$lookup_template_table."`");
		$num_rows = mysqli_num_rows($RS);
		while ($RS && ($template_table_row=mysqli_fetch_array($RS))) {
			$init_RS = mysqli_query($conn, "INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
			$last_id = mysqli_insert_id($conn);
			foreach ($fieldsArr AS $initK=>$initV) {
				mysqli_query($conn, "UPDATE `".$childTable."`  SET ".$initK."='".$template_table_row[$initK]."' WHERE ".$childRef."=".$parentTable_id." AND ".$childKeyFld."='".$last_id."'");
			}
		}
		
	}

	/*
	 * Louwtjie: function to create the headings for the grid functions.
	*/
	function createHTMLGridHeading ($headingArr, $emptyCol=0) {
		echo "<tr>\n";
		foreach ($headingArr as $value){
			$style = "";
			if (stristr($value,":vertical")) {
				$value = substr($value, 0, strpos($value,":vertical"));
				$style = " filter: flipv fliph; writing-mode: tb-rl; text-align:left";
			}
			echo "<td style='".$style."' class='oncolourb' align='center'>\n";
			echo $value;
			echo "</td>\n";
		}
		if ($emptyCol > 0) echo "<td>&nbsp;</td>";
		echo "</tr>\n";
	}

	/*
	 * Louwtjie: function to create the main SQL for the grid functions.
	*/
	function createHTMLGridSQL ($table, $keyFLD, $unique_flds_array, $ordFLD="") {


		$main_SQL = "SELECT * FROM ".$table;
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				array_push ($andArr, $unique_flds_array[$i][0]."='".$unique_flds_array[$i][1]."'");
			}
			$main_SQL .= " WHERE ".implode (" AND ", $andArr);
		}
		if ($ordFLD > ""){
			$main_SQL .= " ORDER BY ".$ordFLD;
		} else {
			$main_SQL .= " ORDER BY ".$keyFLD;
		}


		$RS = mysqli_query ($this->getDatabaseConnection(), $main_SQL);
		return $RS;
	}

	/*
	 * Louwtjie: function to create the rows in database before displaying them on screen.
	*/
	function createHTMLGridInsertWithLookup ($table, $lkp_row_table, $lkp_row_id, $lkp_row_ref, $unique_flds_array, $lkp_row_where) {
		$lkp_array = array();
		$conn = $this->getDatabaseConnection();
		if ($lkp_row_ref > "") {
			$lkp_WHERE = "WHERE ".$lkp_row_ref." IS NULL";
			$lkp_WHERE .= ($lkp_row_where > "") ? " AND " . $lkp_row_where : "";

			$lkp_SQL = "SELECT ".$lkp_row_id.", ".$lkp_row_ref;
			if (count($unique_flds_array) > 0) {
				$andArr = array();
				$lkp_SQL .= " , ";
				for ($i=0; $i < count($unique_flds_array); $i++) {
					array_push ($andArr, $unique_flds_array[$i][0]);
				}
				$lkp_SQL .= implode (" , ", $andArr);
			}

			$lkp_SQL .= " FROM ".$lkp_row_table." LEFT JOIN ".$table." ON (".$lkp_row_id." = ".$lkp_row_ref;

			if (count($unique_flds_array) > 0) {
				$andArr = array();
				$lkp_SQL .= " AND ";
				for ($i=0; $i < count($unique_flds_array); $i++) {
					array_push ($andArr, $unique_flds_array[$i][0]."='".$unique_flds_array[$i][1]."'");
				}
				$lkp_SQL .= implode (" AND ", $andArr);
			}

			$lkp_SQL .= ") " . $lkp_WHERE;

			$lkp_RS = mysqli_query($conn, $lkp_SQL);
			while ($lkp_RS && ($row=mysqli_fetch_array($lkp_RS))) {
				array_push($lkp_array, $row[$lkp_row_id]);
			}
		}

		//If we have no previous rows in the database, insert the required number of rows with the unique fields filled in.
		for ($j=0; $j < count($lkp_array); $j++) {
			$SQL = "INSERT INTO `".$table."` (";
			if (count($unique_flds_array) > 0) {
				$andArr = array();
				for ($i=0; $i < count($unique_flds_array); $i++) {
					array_push ($andArr, $unique_flds_array[$i][0]);
				}
				$SQL .= implode (" , ", $andArr);
			}

			if (count($lkp_array) > 0) {
				$SQL .= ", ".$lkp_row_ref;
			}

			$SQL .= ") VALUES (";
			if (count($unique_flds_array) > 0) {
				$andArr = array();
				for ($i=0; $i < count($unique_flds_array); $i++) {
					array_push ($andArr, "'".$unique_flds_array[$i][1]."'");
				}
				$SQL .= implode (" , ", $andArr);
			}

			if (count($lkp_array) > 0) {
				$SQL .= ", '".$lkp_array[$j]."'";
			}

			$SQL .= ")";
			$errorMail = false;

			mysqli_query($conn, $SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL", $SQL."  --> ".mysqli_error($conn), $errorMail);
			$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
		}
		
	}

	/*
	 * Louwtjie: function to create the rows in database before displaying them on screen.
	*/
	function createHTMLGridInsertWithoutLookup ($table, $unique_flds_array) {
         
   
       
		$SQL = "";
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			$valArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				array_push ($andArr, $unique_flds_array[$i][0]);
				array_push ($valArr, "'".$unique_flds_array[$i][1]."'");
			}
			$fld = implode (" , ", $andArr);
			$val = implode (" , ", $valArr);

			$SQL = "INSERT INTO `".$table."` (" .$fld. ") VALUES (" .$val. ")";
		}
                $conn = $this->getDatabaseConnection();
		$errorMail = false;
		mysqli_query($conn, $SQL) or $errorMail = true;
		$this->writeLogInfo(10, "SQL", $SQL."  --> ".mysqli_error($conn), $errorMail);
		$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
		
	}

	/*
	 * Louwtjie: function to create the fields for the grid functions.
	 * NB NB This function is run per field i.e. if 3 fields then this fucntion is run once per field in the calling program.
	*/
	function createHTMLGridFields ($row, $table, $keyFLD, $fieldArr, $cols=40, $rows=5, &$fieldName, &$fieldValue, &$fieldType, &$fieldSize, &$fieldStatus, &$fld_lkp_desc, &$fld_lkp_key, &$fld_lkp_table, &$fld_lkp_condition, &$fld_lkp_order_by) {
                
		$html_field_arr = $this->doubleExplode ($fieldArr);
		$html_arr = array();

		//First we need all attributes of the individual fields.
		foreach ($html_field_arr AS $entity) {
		$arr_count = count($entity);

			/* Robin 20/8/2007
			// Replaced this with allowing multiple parameters.
			// For order by or for lkp_description we might want more than 1 value.
			$html_arr[$entity[0]] = $entity[1];
			*/

			// The first element of the array is the name of the field.  Extract it from the array.

			$var = array_shift($entity);

			// Process the rest of the fields depending on the type of field. some fields may have more than one element
			switch ($var){
			case "description_fld":
				$html_arr[$var] = "concat(".implode('," ",',$entity).")";
				break;
			default:
				$html_arr[$var] = $entity[0];
			}

		}

		//Here, we start building the rows, field by field.
		//the following is the bare minimum properties for a field.
		$fieldName = (isset($html_arr["name"]))?('GRID_'.$row[$keyFLD].'$'.$keyFLD.'$'.$html_arr["name"].'$'.$table):("");
		$fieldValue = (isset($html_arr["name"]))?($row[$html_arr["name"]]):("");
		$fieldType = (isset($html_arr["type"]))?($html_arr["type"]):("");
		$fieldSize = (isset($html_arr["size"]))?($html_arr["size"]):("");
		$fieldStatus = (isset($html_arr["status"]))?($html_arr["status"]):("");

		// 2012-08-25 Robin: Adding the ability to set a default for a hidden field if no value is set.
		if ($fieldType == 'hidden' && isset($html_arr["value"])){
			if ($fieldValue == 0 || $fieldValue == ''){
				$fieldValue = $html_arr["value"];
			}
		}
		
		//the following needs to be checked if it is a select or radio type field.

		$fld_lkp_desc = (isset($html_arr["description_fld"]))?($html_arr["description_fld"]):("");
		$fld_lkp_key = (isset($html_arr["fld_key"]))?($html_arr["fld_key"]):("");
		$fld_lkp_table = (isset($html_arr["lkp_table"]))?($html_arr["lkp_table"]):("");
		$fld_lkp_condition = (isset($html_arr["lkp_condition"]))?($html_arr["lkp_condition"]):("");
		$fld_lkp_order_by = (isset($html_arr["order_by"]))?($html_arr["order_by"]):("");

		//The following resizes a textarea in a grid.
		$fieldCols = (isset($html_arr["cols"]))?($html_arr["cols"]):$cols;
		$fieldRows = (isset($html_arr["rows"]))?($html_arr["rows"]):$rows;
		
		//we don't have solid rules for checkbox values so if it doesn't have a value, assign value 1 to the checkbox
		//check if the page is just viewed or needs to be saved.
		if ($fieldType == "checkbox") {
			if ($row[$html_arr["name"]] == 0) {
				$fieldValue = ( !($this->view == 1) )?(1):("No");
			}else {
				if ($this->view == 1) {
					$fieldValue = "Yes";
				}
			}
		}

		//create the field in memory
		$this->createInput ($fieldName, $fieldType, $fieldValue, $fieldSize, $fieldStatus);

		//set properties for field.
		$this->formFields[$fieldName]->fieldCols = $fieldCols;
		$this->formFields[$fieldName]->fieldRows = $fieldRows;

		//we don't have solid rules for checkbox values so if it has a value of 1 make it "checked"
		if (($fieldType == "checkbox") && ($row[$html_arr["name"]] == 1)) {
			$this->formFields[$fieldName]->fieldOptions = "checked";
		}

		//create the select or radio type field's options
		if (($fieldType == "select") || ($fieldType == "radio")) {
			$fld_lkp_SQL = "SELECT ".$fld_lkp_desc.", ".$fld_lkp_key." FROM ".$fld_lkp_table." WHERE ".$fld_lkp_condition." ORDER BY ".$fld_lkp_order_by."";

			$fld_lkp_RS = mysqli_query($this->getDatabaseConnection(), $fld_lkp_SQL);
			while ($fld_lkp_RS && ($fld_lkp_row=mysqli_fetch_array($fld_lkp_RS))) {
//zoology
				$this->formFields[$fieldName]->fieldValuesArray[$fld_lkp_row[$fld_lkp_key]] = $fld_lkp_row[$fld_lkp_desc];
			}

		}
		
		return $html_arr;
	}

	/*Louwtjie:
		Make Grid from Database
	*/
	function gridShow ($table, $key_fld, $unique_flds, $fields_arr, $html_table_headings_arr, $lkp_row_table="", $lkp_row_id="", $lkp_row_desc="", $lkp_row_ref="", $lkp_row_desc_col=1, $cols=40, $rows=5, $createFileUpload=false, $fileUpload_name="",  $lkp_row_where="") {
                
		$unique_flds_array = $this->doubleExplode ($unique_flds);

		//First of all, we need to select the rows out of the database to see whether it exists or not.
		//That's why we need the unique fields to uniquely identify the rows we're working with.
		$RS = $this->createHTMLGridSQL ($table, $lkp_row_ref, $unique_flds_array);

		//actual number of rows we have in DB
		$actual_rows = mysqli_num_rows($RS);

		$lookup_where = ($lkp_row_where > "") ? " WHERE " . $lkp_row_where : "";
		$lookup_sql = "SELECT ".$lkp_row_id." FROM ".$lkp_row_table . $lookup_where;

		$lookup_rows = mysqli_num_rows(mysqli_query($this->getDatabaseConnection(), $lookup_sql));

		//Now, we check to see if we've got any rows to work with. If there are rows, it means that we've already created the entries
		//in the database and now we need some values for them as well as to print them in our table.

		//first see if we're dealing with a fixed grid else adjust the number_rows accordingly.
		if ( (!($actual_rows > 0)) || ($actual_rows != $lookup_rows) ) {
			$this->createHTMLGridInsertWithLookup ($table, $lkp_row_table, $lkp_row_id, $lkp_row_ref, $unique_flds_array, $lkp_row_where);
		}

		//Here, we build the table header as described in the $html_table_headings_arr array.
		$this->createHTMLGridHeading ($html_table_headings_arr);

		$RS = $this->createHTMLGridSQL ($table, $lkp_row_ref, $unique_flds_array);
		//Now, we start building our table row for row.
		while ($row = mysqli_fetch_array($RS)) {
			$rowID = $row[$key_fld];
			echo "<tr>\n";
			for ($i=0; $i < count($fields_arr); $i++) {
				//check if we have a column with text from lookup table
				//if we are at the correct column, print the lookup text.
				$count = $i+1;
				if ($lkp_row_table > "") {
					if ($count == $lkp_row_desc_col) {
						$lkp_SQL = "SELECT ".$lkp_row_desc." FROM ".$lkp_row_table." WHERE ".$lkp_row_id."='".$row[$lkp_row_ref]."'";

						$lkp_rs = mysqli_query($this->getDatabaseConnection(), $lkp_SQL);
						if ($lkp_rs && ($lkp_row=mysqli_fetch_array($lkp_rs))) {
							echo "<td valign='top'>\n";
							echo $lkp_row[$lkp_row_desc]." ";
							echo "</td>\n";
						}
					}
				}

				$fieldName = "";
				$fieldValue = "";
				$fieldType = "";
				$fieldSize = "";
				$fieldStatus = "";
				$fld_lkp_desc = "";
				$fld_lkp_key = "";
				$fld_lkp_table = "";
				$fld_lkp_condition = "";
				$fld_lkp_order_by = "";

				$html_arr = $this->createHTMLGridFields ($row, $table, $key_fld, $fields_arr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);

				echo "<td valign='top'>\n";
				//print the field after all properties have been set.
				$this->showField($fieldName);

				//if this is the last field, print the hidden save field aswell
				if (($i == (count($fields_arr)-1)) && !($createFileUpload)) {
					echo "<input type='HIDDEN'  name='GRID_save_".$rowID."' value='1'>\n";
				}

				echo "</td>\n";
				//temporary solution for checkboxes to become unchecked.
				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->setValueInTable ($table, $key_fld, $row[$key_fld], $html_arr["name"], 0);
				}
			}
			//this is for when you want a file upload in your table
			if ($createFileUpload) {
				echo '<td valign="top">';
				$this->createTableFileUpload("GRID_".$row[$key_fld]."$".$key_fld."$".$fileUpload_name."$".$table, $fileUpload_name, $table, $key_fld, $row[$key_fld]);
				echo "<input type='HIDDEN'  name='GRID_save_".$rowID."' value='1'>\n";
				echo '</td>';
			}
		}
		
	}

	/*
	 * Louwtjie: new function for displaying grids that you can add rows by clicking on link.
	 * Robin 20/8/2007 - Made adding rows or deleting rows optional.
	*/
	function gridShowRowByRow ($table, $keyFLD, $unique_flds, $fieldArr, $headingArr, $cols=40, $rows=5, $add="", $del="",$dfltRow=1,$ordFLD="") {
		$unique_flds_array = $this->doubleExplode ($unique_flds);
		$fieldName = "";
		$fieldValue = "";
		$fieldType = "";
		$fieldSize = "";
		$fieldStatus = "";
		$fld_lkp_desc = "";
		$fld_lkp_key = "";
		$fld_lkp_table = "";
		$fld_lkp_condition = "";
		$fld_lkp_order_by = "";

		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);

		$num_rows = mysqli_num_rows($RS); 

		if ($dfltRow == 1){
			//we must have at least 1 row in the database:
			if (! ($num_rows > 0) ) {
				$this->createHTMLGridInsertWithoutLookup ($table, $unique_flds_array);
			}
		}
		$this->createHTMLGridHeading ($headingArr, 1);

		//we do the main query again, maybe we inserted some rows.
	
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array,$ordFLD);
		while ($RS && ($row=mysqli_fetch_array($RS, MYSQLI_ASSOC))) {
			$rowID = $row[$keyFLD];
			echo "<tr>\n";
			for ($i=0; $i < count($fieldArr); $i++) {
				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $fieldArr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);
//echo "<br/><br/>html_arr:";
//print_r($html_arr);

				echo "<td valign='top'>\n";
				//print the field after all properties have been set.
				$this->showField($fieldName);
//echo "<br/><br/>for:";
//print_r($this->formFields["$fieldName"]);
				//if this is the last field, print the hidden save field aswell
				if ($i == (count($fieldArr)-1)) {
					echo "<input type='HIDDEN'  name='GRID_".$rowID."_save_".$table."' value='1'>\n";
				}

				echo "</td>\n";
				//temporary solution for checkboxes to become unchecked.
				//2012-06-25 Robin: Was an error in this - corrected but not sure why one wants to reset here.
				// End up wiping out valid values on cancelview because no 'save' takes place. Thus values that were there are overwritten.
				// The assumption for this setup is that their is always a 'save' from the template which is not the case.  The data capture forms
				// have an option to cancel without saving and this then wipes out all your checkbox data.
				// No checkboxes using this function.  Rather use radio Yes/No.
				//if (($fieldType == "checkbox") && !($this->view == 1)) {
					//$this->setValueInTable ($table, $keyFLD, $row[$keyFLD], $html_arr["name"], 0);
				//}
			}

			// display link to delete a record if requested
			if ($del==true){
				echo "<td valign='top'>\n";
				echo "<a class='rowByRowDel ".($dfltRow == 0 ? 'deleteAll' : '')."' id='".$table."-".$row[$keyFLD]."-del' href='javascript:changeCMD(\"del|".$table."|".$keyFLD."|".$row[$keyFLD]."\");moveto(\"stay\")'>Del</a>";
				echo "</td>\n";
			}
			echo "</tr>\n";
		}

		// display link to add a new record if requested
		if ($add==true){
			$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row in which to supply the relevant information. <i>Note that you can add multiple rows</i>.";
			
			// Max of two extra unique fields can be inserted into a grid.
			$unique_pair_array = array();
			foreach($unique_flds_array as $uf){
			   
			  //  $unique_pair_array=implode('|',$uf))
				array_push($unique_pair_array, implode('|',$uf)) ;
			}
			$unique_fields_str = implode('|',$unique_pair_array);

			echo "<tr>";
			//echo "<td valign='top' colspan='".(count($headingArr))."'>".$message."</td><td align='left'><a class='rowByRowAdd' id='".$table."-add' href='javascript:changeCMD(\"new|".$table."|".$unique_flds_array[0][0]."|".$unique_flds_array[0][1]."\");moveto(\"stay\")' ref='".$unique_flds_array[0][0]."$".$unique_flds_array[0][1]."'>Add</a></td>";
			echo "<td valign='top' colspan='".(count($headingArr))."'>".$message."</td><td align='left'><a class='rowByRowAdd' id='".$table."-add' href='javascript:changeCMD(\"new|".$table."|".$unique_fields_str."\");moveto(\"stay\")' ref='".$unique_flds_array[0][0]."$".$unique_flds_array[0][1]."'>Add</a></td>";
			echo "</tr>";
		}
		if ($del==true){
			echo '<tr style="display:none;"><td><input name="GRID_deleted|'.$table.'|'.$keyFLD.'" id="'.$table.'-deleted" type="hidden"/></td></tr>';
		}
	}

	/*
	 * Louwtjie: new function for displaying grids that you can add rows by clicking on link (vertically).
	*/
	function gridShowTableByRow ($table, $keyFLD, $unique_flds, $fieldArr, $headingArr, $cols=40, $rows=5, $showButtons=false, $addRowText="") {
		$unique_flds_array = $this->doubleExplode ($unique_flds);
		$fieldName = "";
		$fieldValue = "";
		$fieldType = "";
		$fieldSize = "";
		$fieldStatus = "";
		$fld_lkp_desc = "";
		$fld_lkp_key = "";
		$fld_lkp_table = "";
		$fld_lkp_condition = "";
		$fld_lkp_order_by = "";

		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);

		//we must have at least 1 row in the database:
		if (! (mysqli_num_rows($RS) > 0) ) {
			$this->createHTMLGridInsertWithoutLookup ($table, $unique_flds_array);
		}

		$tableStyle = array("oncolourswitcha", "oncolourswitchb");
		$tableStyleCount = 0;

		$skipHead = 0;
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);
		echo "<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'><tr><td>";
		while ($RS && ($row=mysqli_fetch_array($RS, MYSQLI_BOTH))) {
			if ($tableStyleCount == 0) {
				$tableStyleCount = 1;
			}else	if ($tableStyleCount == 1) {
				$tableStyleCount = 0;
			}

			$count = 0;
			echo "	<table cellpadding='2' class='".$tableStyle[$tableStyleCount]."' width='100%'>\n";
			foreach ($fieldArr AS $value) {
				echo "		<tr>\n";
				echo "			<td width='33%' valign='top' align='right'><b>".$headingArr[$count]."</b></td>\n";
				echo "			<td valign='top'>";

				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $value, $cols, $rows, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);
				$this->showField ($fieldName);

				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->setValueInTable ($table, $key_fld, $row[$keyFLD], $html_arr["name"], 0);
				}

				echo "			</td>\n";
				echo "		</tr>\n";
				$count++;
			}
			echo '		<tr><td colspan=2><input type="HIDDEN" name="GRID_save_'.$table."_".$row[$keyFLD].'" value="1"></td></tr>';
// 2008-09-18 Robin
// Added $table to field name because if two grids on the same page. They could have same keys e.g. both record 1 in two different tables.
// This causes a conflict and the second grid is not updated.
//			echo "<input type='HIDDEN'  name='GRID_save_".$table."_".$rowID."' value='1'>\n";

			if ($showButtons) {
				echo "		<tr>";
				echo "		<td valign='top' colspan='2' align='right'><a class='visidel' href='javascript:changeCMD(\"del|".$table."|".$keyFLD."|".$row[$keyFLD]."\");moveto(\"stay\")'>&nbsp;Delete Entry&nbsp;</a></td>";
				echo "		</tr>";
			}
			echo "	</table>";
		}
		$addLinkText = ($addRowText>"")?(" - ".$addRowText):("");
		if ($showButtons) {
			echo  "<tr>";
			echo  "<td align='right'><a class='visiadd' href='javascript:changeCMD(\"new|".$table."|".$unique_flds_array[0][0]."|".$unique_flds_array[0][1]."\");moveto(\"stay\")'>&nbsp;Add".$addLinkText."&nbsp;</a></td>";
			echo  "</tr>";
		}
		echo  "</td></tr></table>";
	}

	/*
	Louwtjie
	20041027
	Display a Grid
	*/
	function gridDisplay($parentTable, $childTable, $childKeyFld, $childRef, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="", $where="", $select_array="", $populate_table_from_template=0, $lookup_template_table=""){
                
		$parentTable_id = $this->dbTableInfoArray[$parentTable]->dbTableCurrentID;
		$parentTable_keyField = $this->dbTableInfoArray[$parentTable]->dbTableKeyField;
		$count=0;

		if (($populate_table_from_template > 0) && ($lookup_template_table > "")) {
			$rows_rs = mysqli_num_rows(mysqli_query($this->getDatabaseConnection(), "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id));
			if (! $rows_rs ) {
				$this->doPopulateGridFromTemplateTable ($parentTable, $parentTable_id, $childTable, $childKeyFld, $childRef, $fieldsArr, $lookup_template_table);
			}
		}

		$message = "";
		$content = "";
		$content .=  "<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>";
		$content .=  "<tr>";
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			foreach ($tableHeading AS $key=>$value) {
				$content .= '<td valign="top" class="oncolourb" colspan="'.$value.'" align="center"><b>'.$key.'</b></td>';
			}
			$content .=  "</tr><tr>";
		}
		foreach ($fieldsArr AS $value) {
			$value = (is_array($value))?($value[0]):($value);
			$content .=  '<td valign="top" align="center" class="oncolourb">'.$value.'</td>';
			$count++;
		}
		$content .=  "<td valign='top'>&nbsp;</td></tr>";
		$array_keys = array_keys($fieldsArr);
		$SQL = "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id." ORDER BY ".$childKeyFld;
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row in which to supply the relevant information. <i>Note that you can add multiple rows</i>.";
		$field_size = $sizeOfFld;
		while ($row = mysqli_fetch_assoc($rs)) {
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {

				$name_of_field = 'GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable;
				$sizeOfFld = $field_size;

				if (($value == '1000-01-01') || ($value == '00:00:00')) $value = '';
				if (stristr($key, "timeFLD") > "") {
					$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
					$content .=  '<td valign="top" align="right"><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "dateFLD") > "") {
					$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
					$content .=  '<td valign="top" align="right"><input readonly size="'.$sizeOfFld.'" type="TEXT" ID="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'"name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "textFLD") > "") {
					$content .=  "<td valign='top' align='right'><textarea size='".$sizeOfFld."' ID='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'>".$value."</textarea></td>";
				}else if (stristr($key, "selectFLD") > "") {
					$content .=  "<td valign='top' align='right'>";
					$content .=  "<select ID='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'>";
					$content .=  "<option value='0'>- Select -</option>";
					$select_SQL = "SELECT ".$select_array[$key]["description_fld"].", ".$select_array[$key]["fld_key"]." FROM ".$select_array[$key]["lkp_table"]." WHERE ".$select_array[$key]["lkp_condition"]." ORDER BY ".$select_array[$key]["order_by"]."";
					$select_RS = mysqli_query($this->getDatabaseConnection(), $select_SQL);
					while ($select_RS && ($select_row=mysqli_fetch_array($select_RS))) {
						$selected = "";
						if ($value == $select_row[$select_array[$key]["fld_key"]]) {
							$selected = " selected";
						}
						$content .=  "<option value='".$select_row[$select_array[$key]["fld_key"]]."' ".$selected.">".$select_row[$select_array[$key]["description_fld"]]."</option>";
					}
					$content .=  "</select>";
					$content .=  "</td>";
				}else if (stristr($key, "radioFLD") > "") {
					$content .=  "<td valign='top' align='right'>";
					$select_SQL = "SELECT ".$select_array[$key]["description_fld"].", ".$select_array[$key]["fld_key"]." FROM ".$select_array[$key]["lkp_table"]." WHERE ".$select_array[$key]["lkp_condition"]." ORDER BY ".$select_array[$key]["order_by"]."";
					$select_RS = mysqli_query($this->getDatabaseConnection(), $select_SQL);
					while ($select_RS && ($select_row=mysqli_fetch_array($select_RS))) {
						$selected = "";
						if ($value == $select_row[$select_array[$key]["fld_key"]]) {
							$selected = " checked";
						}
						$content .=  "<input type='radio' ID='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$select_row[$select_array[$key]["fld_key"]]."' ".$selected.">";
						$content .=  $select_row[$select_array[$key]["description_fld"]]."&nbsp;";
					}
					$content .=  "</td>";
				}else if (stristr($key, "checkboxFLD") > "") {
					$content .=  "<td valign=top>";
					$content .=  "<input type='checkbox' value='1' ID='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'";
					if ($row[$key] == 1) $content .=  "checked";
					$content .=  ">";
					$SQL_del = "UPDATE ".$childTable." SET ".$key."=0 WHERE ".$childKeyFld."=".$row[$childKeyFld];
					$RS = mysqli_query($this->getDatabaseConnection(), $SQL_del);
				}else {
					if (in_array($key, $array_keys)) {
						$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
						$content .=  "<td valign='top' align='right'><input size='".$sizeOfFld."' type='TEXT' ID='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$value."'></td>";
					}
				}
			}
			$content .=  "<td valign='top' align='left'><a href='javascript:changeCMD(\"del|".$childTable."|".$childKeyFld."|".$row[$childKeyFld]."\");moveto(\"stay\")'>Del</a></td>";
			$content .=  "</tr>";
			//make the field for saving purposes. Look at workflow.class.php - checkSaveFieldsPost ()
			$content .= '<tr><td><input type="HIDDEN" name="GRID_save_'.$row[$childKeyFld].'" value="1"></td></tr>';
		}
		$content .=  "<tr>";
		$content .=  "<td valign='top' colspan='".($count)."'>".$message."</td><td align='left'><a href='javascript:changeCMD(\"new|".$childTable."|".$childRef."|".$parentTable_id."\");moveto(\"stay\")'>Add</a></td>";
		$content .=  "</tr>";
		$content .=  "</table>";
		
		return $content;
	}

	/*
	* This is the same function as above except for the colums and rows are inverted.
	* Diederik (20050123) The showButtons is to remove the Delete and Add
	*/
	function gridDisplayPerTable($parentTable, $childTable, $childKeyFld, $childRef, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="", $where="", $select_array="", $field_options="", $addRowText="", $add_initial_DB_row="", $showButtons=true){
                
		$parentTable_id = $this->dbTableInfoArray[$parentTable]->dbTableCurrentID;
		$parentTable_keyField = $this->dbTableInfoArray[$parentTable]->dbTableKeyField;
		if (($add_initial_DB_row) && !(mysqli_num_rows(mysqli_query($this->getDatabaseConnection(), "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id)))) {
			$init_RS = mysqli_query($this->getDatabaseConnection(), "INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
		}

		$SQL = "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id." ORDER BY ".$childKeyFld;

		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$array_keys = array_keys($fieldsArr);
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			$tmp = array_keys($tableHeading);
		}

		$content = "\n\n";
		$content .=  "<table width='95%' align='center' class='oncoloursoft' cellpadding='2' cellspacing='2' border='1'><tr><td>\n";

		$tableStyle = array("oncolourswitcha", "oncolourswitchb");
		$tableStyleCount = 0;

		$skipHead = 0;

		while ($row = mysqli_fetch_assoc($rs)) {
			if ($tableStyleCount == 0) {
				$tableStyleCount = 1;
			}else	if ($tableStyleCount == 1) {
				$tableStyleCount = 0;
			}
			$content .= "	<table cellpadding='2' class='".$tableStyle[$tableStyleCount]."' width='100%'>\n";
			$count = 0;

			foreach ($fieldsArr AS $key=>$value) {
				$content .= "		<tr>\n";

				if ($skipHead > 0) {
					$valueHeading = "";
					$skipHead--;
				} else {
					if (is_array($tableHeading) && (count($tableHeading) > 0)) {
						if ($count < count($tableHeading)) {
							$valueHeading = "			<td width='33%' valign='top' rowspan='".$tableHeading[$tmp[$count]]."' align='right'><b>".$tmp[$count]."</b></td>\n";
							if ($tableHeading[$tmp[$count]] > 1) {
								$skipHead = $tableHeading[$tmp[$count]] - 1;
							}
						}
					}else {
						$valueHeading = "			<td width='33%' valign='top' align='right'>";
						$valueHeading .= (is_array($value))?($value[0]):($value);
						$valueHeading .= "</td>\n";
					}
					$count++;
				}

//				$valueHeading = (is_array($value))?($value[0]):($value);
				$valueSize = (is_array($value) && (isset($value[1])))?($value[1]):($sizeOfFld);
//				$content .= "			<td valign='top' class='oncolourb'>".$valueHeading."</td>\n";
				$content .= $valueHeading;
				if (($value == '1000-01-01') || ($value == '00:00:00')) $value = '';
				if (!$this->view){
					if (stristr($key, "timeFLD") > "") {
						$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
						$content .=  '			<td valign="top" align="left"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$row[$key].'"><a href="javascript:showTime(\'GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\', \''.$row[$key].'\');">';
						$content .=  '<img src="images/icon_time.gif" border=0></a></td>'."\n";
					}else if (stristr($key, "dateFLD") > "") {
						$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
						$content .=  '			<td valign="top" align="left"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$row[$key].'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\');">';
						$content .=  '<img src="images/icon_calendar.gif" border=0></a></td>'."\n";
					}else if (stristr($key, "textFLD") > "") {
						$tt = (is_array($value) && (is_array($tableHeading)))?($value[0]):("");
						if ($tt > "") $tt .= "<br>";
						$content .=  "			<td valign='top' align='left'>".$tt."<textarea cols='70' rows='7' size='".$sizeOfFld."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'>".$row[$key]."</textarea></td>\n";
					}else if (stristr($key, "selectFLD") > "") {
						$content .=  "			<td valign='top' align='left'>";
						$options = "";
						$options = (is_array($field_options) && (isset($field_options[$key])))?($field_options[$key]):("");
						$content .=  "<select  name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' ".$options.">";
						$content .=  "<option value='0'>- Select -</option>";
						$select_SQL = "SELECT ".$select_array[$key]["description_fld"].", ".$select_array[$key]["fld_key"]." FROM ".$select_array[$key]["lkp_table"]." WHERE ".$select_array[$key]["lkp_condition"]." ORDER BY ".$select_array[$key]["order_by"]."";
						$select_RS = mysqli_query($this->getDatabaseConnection(), $select_SQL);
						while ($select_RS && ($select_row=mysqli_fetch_array($select_RS))) {
							$selected = "";
							if ($row[$key] == $select_row[$select_array[$key]["fld_key"]]) {
								$selected = " selected";
							}
							$content .=  "<option value='".$select_row[$select_array[$key]["fld_key"]]."' ".$selected.">".$select_row[$select_array[$key]["description_fld"]]."</option>";
						}
						$content .=  "</select>";
						$content .=  "</td>\n";
					}else if (stristr($key, "radioFLD") > "") {
						$content .=  "			<td valign='top' align='left'>";
						$select_SQL = "SELECT ".$select_array[$key]["description_fld"].", ".$select_array[$key]["fld_key"]." FROM ".$select_array[$key]["lkp_table"]." WHERE ".$select_array[$key]["lkp_condition"]." ORDER BY ".$select_array[$key]["order_by"]."";
						$select_RS = mysqli_query($this->getDatabaseConnection(), $select_SQL);
						while ($select_RS && ($select_row=mysqli_fetch_array($select_RS))) {
							$selected = "";
							if ($row[$key] == $select_row[$select_array[$key]["fld_key"]]) {
								$selected = " checked";
							}
							$content .=  "<input type='radio' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$select_row[$select_array[$key]["fld_key"]]."' ".$selected.">";
							$content .=  $select_row[$select_array[$key]["description_fld"]]."&nbsp;";
						}
						$content .=  "</td>\n";
					}else if (stristr($key, "checkboxFLD") > "") {
						$content .=  "			<td valign=top>";
						$content .=  "<input type='checkbox' value='1' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'";
						if ($row[$key] == 1) $content .=  "checked";
						$content .=  ">";
						$content .= (is_array($value))?($value[0]):($value);
						$content .= "\n";
						$SQL_del = "UPDATE ".$childTable." SET ".$key."=0 WHERE ".$childKeyFld."=".$row[$childKeyFld];
						$RS = mysqli_query($this->getDatabaseConnection(), $SQL_del);
					}else {
						if (in_array($key, $array_keys)) {
							$options = "";
							$options = (is_array($field_options) && (isset($field_options[$key])))?($field_options[$key]):("");
							$sizeOfFld = (is_array($fieldsArr[$key]) && (isset($fieldsArr[$key][1])))?($fieldsArr[$key][1]):($sizeOfFld);
							$tt = (is_array($value) && (is_array($tableHeading)))?($value[0]):("");
							if ($tt > "") $tt .= "<br>";
							$content .=  "			<td valign='top' align='left'>".$tt."<input size='".$valueSize."' type='TEXT' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$row[$key]."' ".$options."></td>\n";
						}
					}
				}else { // Display only
					if (in_array($key, $array_keys)) {
							$tt = (is_array($value) && (is_array($tableHeading)))?($value[0]):("");
							if ($tt > "") $tt .= "<br>";
							$content .=  "			<td valign='top' align='left'>".$tt." ".$row[$key]."</td>\n";
					}
				}
				$content .= "		</tr>\n";
			}
			if (!$this->view){
				//make the field for saving purposes. Look at workflow.class.php - checkSaveFieldsPost ()
				$content .= '<tr><td colspan=2><input type="HIDDEN" name="GRID_save_'.$row[$childKeyFld].'" value="1"></td></tr>';
			}
			if ($showButtons) {
				$content .= "		<tr>";
				$content .= "		<td valign='top' colspan='2' align='right'><a class='visidel' href='javascript:changeCMD(\"del|".$childTable."|".$childKeyFld."|".$row[$childKeyFld]."\");moveto(\"stay\")'>&nbsp;Delete Entry&nbsp;</a></td>";
				$content .= "		</tr>";
			}
			$content .= "	</table>\n<br>";
		}//while
		$addLinkText = ($addRowText>"")?(" - ".$addRowText):("");
		if ($showButtons) {
			$content .=  "<tr>";
			$content .=  "<td align='right'><a class='visiadd' href='javascript:changeCMD(\"new|".$childTable."|".$childRef."|".$parentTable_id."\");moveto(\"stay\")'>&nbsp;Add".$addLinkText."&nbsp;</a></td>";
			$content .=  "</tr>";
			$content .=  "</td></tr></table>";
		}
		$content .= "\n\n";
		
		return $content;
	}

	/* Louwtjie:
	 * 2005-03-02
	 * function to create a grid table with fixed number of rows and no lookup values at 1st column
	 * $table = db table to write to, $key_fld = table key field, $key_value = table key value,
	 	 $unique_flds = pipe seperated list of unique fields to identify each row in db (each unique field must be double underscored by its value e.g. fieldname__value),
		 $fields_arr = array of fields to be saved to db (per row),
		 $html_table_headings_arr = the headings for each column on html page
	*/
	function displayFixedGrid ($table, $key_fld, $unique_flds, $fields_arr, $html_table_headings_arr, $number_rows=6) {
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

		
		$unique_flds_array = $this->doubleExplode ($unique_flds);
                
		/*
		First of all, we need to select the rows out of the database to see whether it exists or not.
		That's why we need the unique fields to uniquely identify the rows we're working with.
		*/
		$main_SQL = "SELECT * FROM `".$table."` WHERE ";

		for ($i=0; $i < count($unique_flds_array); $i++) {
			$main_SQL .= $unique_flds_array[$i][0]."='".$unique_flds_array[$i][1]."'";
			if ( (count($unique_flds_array) > ($i+1)) ) {
				$main_SQL .= " AND ";
			}
		}

		$main_SQL .= " ORDER by ".$key_fld." LIMIT 0,".$number_rows;

		$RS = mysqli_query ($conn, $main_SQL);

		//actual number of rows we have in DB
		$actual_rows = mysqli_num_rows($RS);

		/*
		Now, we check to see if we've got any rows to work with. If there are rows, it means that we've already created the entries
		in the database and now we need some values for them as well as to print them in our table.
		*/
		if (($actual_rows != $number_rows) && ($number_rows > $actual_rows)) {
			/*
			If we have no previous rows in the database, insert the required number of rows with the unique fields filled in.
			*/
			for ($j=0; $j < ($number_rows - $actual_rows); $j++) {
				$SQL = "INSERT INTO `".$table."` (";
				for ($i=0; $i < count($unique_flds_array); $i++) {
					$SQL .= $unique_flds_array[$i][0];
					if ( (count($unique_flds_array) > ($i+1)) ) {
						$SQL .= ", ";
					}
				}
				$SQL .= ") VALUES ('";
				for ($i=0; $i < count($unique_flds_array); $i++) {
					$SQL .= $unique_flds_array[$i][1]."'";
					if ( (count($unique_flds_array) > ($i+1)) ) {
						$SQL .= ", '";
					}
				}
				$SQL .= ")";
				$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
			}
		}

		/*
		Here, we build the table header as described in the $html_table_headings_arr array.
		*/
		echo "<tr>\n";
		foreach ($html_table_headings_arr as $value){
			$style = "";
			if (stristr($value,":vertical")) {
				$value = substr($value, 0, strpos($value,":vertical"));
				$style = " filter: flipv fliph; writing-mode: tb-rl; text-align:left";
			}
			echo "<td style='".$style."' class='oncolourb' align='center'>\n";
			echo $value;
			echo "</td>\n";
		}
		echo "</tr>\n";

		/*
		Now, we start building our table row for row.
		*/
		$RS = mysqli_query($this->getDatabaseConnection(), $main_SQL);
		while ($row = mysqli_fetch_array($RS)) {
			$rowID = $row[$key_fld];
			echo "<tr>\n";
			foreach ($fields_arr AS $value) {
				$html_field_arr = $this->doubleExplode ($value);
				$html_arr = array();
				/*
				First we need all attributes of the individual fields.
				*/
				foreach ($html_field_arr AS $entity) {
					$html_arr[$entity[0]] = $entity[1];
				}
				$fieldName = "";
				$fieldValue = "";
				$fieldType = "";
				$fieldSize = "";
				/*
				Here, we start building the rows, field by field.
				*/
				foreach ($html_arr AS $key=>$val) {
					if ($key == "name") {
						$fieldValue = $row[$val];
						$fieldName = 'GRID_'.$row[$key_fld].'$'.$key_fld.'$'.$val.'$'.$table;
					}
					if ($key == "type") {
						$fieldType = $val;
					}
					if ($key == "size") {
						$fieldSize = $val;
					}
				}
				$this->createInput ($fieldName, $fieldType, $fieldValue, $fieldSize);
				echo "<td>\n";
				$this->showField($fieldName);
				echo "</td>\n";
			}
			echo "<td valign='top'><input type='HIDDEN'  name='GRID_save_".$rowID."' value='1'></td>\n";
			echo "</tr>\n";
		}
		
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

	/*
	*	Louwtjie: 2004-11-30
	*	function to calculate the site visit decision that the evaluators made in their last question.
	*/
	function showEvalDecisionResult ($app_id=0, $inst_id=0) {
                
		if (! ($app_id > 0) ) {
			$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		}
		if (! ($inst_id > 0) ) {
			$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
		}
		$recommend_arr = array();
		$SQL = "SELECT * FROM `lkp_eval_sitevisit_recommend` WHERE application_ref=".$app_id." AND institution_ref=".$inst_id;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			array_push($recommend_arr, $row["recommend"]);
		}
		$final = array('YES'=>'0', 'NO'=>'0');
		foreach ($recommend_arr AS $val) {
			if ($val == 'No') $final['NO'] += 1;
			if ($val == 'Yes') $final['YES'] += 1;
//			if ($val == 'Yes') $final['YES'] += $this->getDBsettingsValue("eval_sitevisit_recommendation_yes_value");
		}
/*		$ret = "No";
		if ($final["YES"] > $final["NO"]) {
			$ret = "Yes";
		}
		return $ret;
*/
                
		echo "Yes (".$final['YES'].") / No (".$final['NO'].")";
	}

	function showSiteHistoryList ($site_ref) {
                
		$html = "";
		$SQL = "SELECT site_visit, application_ref, final_date_visit FROM `siteVisit` WHERE final_date_visit > '1000-01-01' AND institution_ref=".$this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id")." AND site_ref=".$site_ref;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($RS) > 0) {
			echo '<table border="1" width="50%"><tr><td class="oncolour"><b>PROGRAMME</b></td><td class="oncolour"><b>DATE</b></td></tr>';
			while ($row = mysqli_fetch_object($RS)) {
				if (($row->site_visit == 'Yes') && ($row->final_date_visit != "1000-01-01")) {
					echo '<tr>';
					echo '<td><a href="javascript:changeRefs('.$row->application_ref.', '.$site_ref.');moveto('."'next'".');">'.$this->getValueFromTable("Institutions_application", "application_id", $row->application_ref, "program_name").'</a></td>';
					echo '<td><a href="javascript:changeRefs('.$row->application_ref.', '.$site_ref.');moveto('."'next'".');">'.$row->final_date_visit.'</a></td>';
					echo '</tr>';
				}
			}
			echo '</table>';
		}else {
			echo '<center><b>No previous site visits for this site</b></center>';
		}
		
	}

	/*
	 * Louwtjie: 2004-12-15
	 * function for displaying previous phone call comments regarding evaluator in sitevisit
	*/
	function showEvalPhoneComments ($table, $key, $fKey, $kVal, $fKVal, $field1, $field2) {
                
		$SQL = "SELECT * FROM `".$table."` WHERE ".$key."=".$kVal." AND ".$fKey."=".$fKVal;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($RS) > 0) {
			echo '<table width="85%" border=1 align="center" cellpadding="2" cellspacing="2"><tr>';
			echo '<td><b>Date:</b></td>';
			echo '<td><b>Comment:</b></td>';
			echo '</tr>';
			while ($RS && ($row=mysqli_fetch_array($RS))) {
				echo '<tr>';
				echo '<td>'.$row[$field1].'</td>';
				echo '<td>'.$row[$field2].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		
	}

	function generateAttendanceList ($ac_id) {
                
		$att_arr = array();
		$SQL = "SELECT * FROM `AC_Members`, `lnk_ACMembers_ACMeeting` WHERE ac_member_ref=ac_mem_id AND ac_mem_active=1 AND lnk_confirmed=1 AND ac_meeting_ref=".$ac_id;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$content = "<table>";
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$content .= "<tr><td>";
			$content .= $this->getValueFromTable("lkp_title", "lkp_title_id", $row["ac_mem_title_ref"], "lkp_title_desc")." ".$row["ac_mem_name"]." ".$row["ac_mem_surname"];
			$content .= "</td></tr>";
		}
		$content .= "</table>";
                
		return $content;
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

	function showAddressesACmembers ($ac_id=0) {
                
		$SQL = "SELECT * FROM `AC_Members`, `lnk_ACMembers_ACMeeting` WHERE ac_member_ref=ac_mem_id AND ac_mem_active=1 AND lnk_confirmed=1 AND ac_meeting_ref=".$ac_id;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$text = "\n\n";
		while ($RS && ($row=mysqli_fetch_array($RS))) {
				$text .= '<b>'.$this->getValueFromTable("lkp_title", "lkp_title_id", $row["ac_mem_title_ref"], "lkp_title_desc").' '.$row["ac_mem_name"].' '.$row["ac_mem_surname"].'</b>'."\n";
				$text .= ($row["ac_mem_postal"] > "")?($row["ac_mem_postal"]):($row["ac_mem_physical"]);
				$text .= "\n\n";
		}
		mysqli_close($close);
		return $text;
	}

	function showSiteVisitConfirmationPayment() {
                
		$institution = $this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
		$SQL = "SELECT application_id, HEI_name, program_name, CHE_reference_code, site_ref	FROM Institutions_application, HEInstitution, siteVisit WHERE site_visit_payed=0 AND site_visit='Yes' AND application_ref=application_id AND HEI_id=institution_id AND AC_Meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID." AND institution_id=".$institution." ORDER BY HEI_name,program_name";
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$input_count = 1;
		echo '<table>';
		while ($rs && ($row=mysqli_fetch_array($rs))) {
			echo '<tr><td>Institution:</td>';
			echo '<td>'.$row["HEI_name"].'</td>';
			echo '</tr><tr><td>Programme:</td>';
			echo '<td>'.$row["program_name"].'</td>';
			echo '</tr><tr><td>Reference number:</td>';
			echo '<td>'.$row["CHE_reference_code"].'</td>';
			echo '</tr><tr><td>Site:</td>';
			echo '<td>'.$this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $row["site_ref"], "location").'</td>';
			echo '</tr><tr>';
			echo '<td>Payed:</td>';
			echo '<td><input type="checkbox" name="siteVisit_payed_'.$input_count.'" value="site_ref|'.$row["site_ref"].'#application_ref|'.$row["application_id"].'"> <i>(Tick for yes)</i></td>';
			echo '</tr>';
			echo '<tr><td colspan="2">&nbsp;</td></tr>';
			$input_count++;
		}
		
		echo '</table>';
	}

	function createACMembersAttendanceList ($ac_id=0) {
                
		$SQL = "SELECT ac_member_ref, ac_mem_title_ref, ac_mem_name, ac_mem_surname FROM `lnk_ACMembers_ACMeeting`, `AC_Members` WHERE ac_member_ref=ac_mem_id AND lnk_confirmed=1 AND ac_meeting_ref=".$ac_id;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$i = 1;
		echo '<table>';
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$checked = "";
			$check_SQL = "SELECT * FROM `lnk_ACMembers_attend_meeting` WHERE ac_meeting_ref=".$ac_id." AND ac_member_ref=".$row["ac_member_ref"];
			$check_RS = mysqli_query($this->getDatabaseConnection(), $check_SQL);
			if (mysqli_num_rows($check_RS) > 0) $checked = " CHECKED";
			echo '<tr>';
			echo '<td>'.$this->getValueFromTable("lkp_title", "lkp_title_id", $row["ac_mem_title_ref"], "lkp_title_desc")." ".$row["ac_mem_name"]." ".$row["ac_mem_surname"].'</td>';
			echo '<td><input type="Checkbox" name="ac_member_'.$i.'" value="'.$row["ac_member_ref"].'" '.$checked.'></td>';
			echo '</tr>';
			$i++;
		}
		
		echo '</table>';
	}

	function showACMeetingAttendance () {
                
		$SQL = "SELECT  count(*), ac_meeting_ref, ac_start_date FROM  `AC_Meeting` ,  `lnk_ACMembers_ACMeeting` WHERE AC_Meeting.ac_id = lnk_ACMembers_ACMeeting.ac_meeting_ref GROUP BY ac_meeting_ref";
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		echo '<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>';
		echo '<td align="center"><b>AC Meeting Date</b></td>';
		echo '<td align="center"><b>Total confirmed</b></td>';
		echo '<td align="center"><b>Total attended</b></td>';
		echo '<td align="center"><b>% Attendance</b></td>';
		echo '</tr>';
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$att_SQL = "SELECT * FROM `lnk_ACMembers_attend_meeting` WHERE ac_meeting_ref=".$row["ac_meeting_ref"];
			$att_RS = mysqli_query($this->getDatabaseConnection(), $att_SQL);
			$num_att = mysqli_num_rows($att_RS);
			$percentage_att = (($num_att*100)/($row["count(*)"]));
			echo '<tr>';
			echo '<td align="center">'.$row["ac_start_date"].'</td>';
			echo '<td align="center">'.$row["count(*)"].'</td>';
			echo '<td align="center">'.$num_att.'</td>';
			echo '<td align="center">'.$percentage_att.'</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}

	function createEvalStatsPage() {
		echo '<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>';
		echo '<td><a href="javascript:statType(\'evalsPerEval\');moveto(\'next\');">Number of evaluations per Evaluator</a></td>';
		echo '</tr><tr>';
		echo '<td><a href="javascript:statType(\'evalPerCESM\');moveto(\'next\');">Number of evaluators per CESM category</a></td>';
		echo '</tr><tr>';
		echo '<td><a href="javascript:statType(\'evalPerRace\');moveto(\'next\');">Number of evaluators per Race, Gender and Disability</a></td>';
		echo '</tr><tr>';
		echo '<td><a href="javascript:statType(\'evalPerProvince\');moveto(\'next\');">Number of evaluators per Province, Full/Part-Time, Highest Qualification and Employer</a></td>';
		echo '</tr><tr>';
		echo '<td><a href="javascript:statType(\'evalPerExperience\');moveto(\'next\');">Number of evaluators per Experience</a></td>';
		echo '</tr></table>';
	}

	/*
		Louwjtie: 20050627
		Displays the information about application forms.
	*/
	function displayApplicationFormsPerInstitution ($select, $from, $where="1") {
                
		$sec_group = $this->getValueFromTable("sec_UserGroups", "sec_user_ref", $this->currentUserID, "sec_group_ref");
//		echo $sec_group;

		if ($sec_group > 2) {
			$where .= " AND institution_id=".$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
		}
		$SQL = "SELECT ".$select." FROM ".$from." WHERE ".$where;
//		echo $SQL;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($RS) > 0) {
			echo '<table border="1">';
			echo '<tr>';
			echo '<td><b>Programme name</b></td>';
			echo '<td><b>CHE Reference Number</b></td>';
			echo '<td><b>Programme Administrator</b></td>';
			echo '<td><b>Programme at User</b></td>';
			echo '<td><b>Last Updated</b></td>';
			echo '</tr><tr><td colspan="5">&nbsp;</td></tr>';
			while ($RS && ($row=mysqli_fetch_array($RS))) {
				//$at_user_wkf = $this->getValueFromTable ();
				echo '<tr>';
				echo '<td>'.$row["program_name"].'</td>';
				$ref = ($row["CHE_reference_code"] > "")?($row["CHE_reference_code"]):("none");
				echo '<td>'.$ref.'</td>';
				echo '<td>'.$this->getValueFromTable("users", "user_id", $row["user_ref"], "email").'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}else{

		}
		
	}

	/*
		Louwtjie:
		Displays all the statistics about the evaluators from the reports menu.
	*/
	function evaluatorStats($select, $table, $where, $where_split=" ", $group_by="", $table_head="", $col_head="", $order_by="", $no_rows_msg="", $leftjoin="") {
                
		$no_rows_msg = ($no_rows_msg > "") ? $no_rows_msg : "Insufficient data in system to run this report";
		$str_group_by = "";
		if (is_array($group_by) && (count($group_by) > 0)) {
			$str_group_by .= " GROUP BY ";
			$str_group_by .= implode(", ", $group_by);
		}
		$str_order_by = "";
		if (is_array($order_by) && (count($order_by) > 0)) {
			$str_order_by .= " ORDER BY ";
			$str_order_by .= implode(", ", $order_by);
		}
		// Set Order by to the same as Group By if no specific Order by is provided.
		if ($str_order_by == "" && $str_group_by <> ""){
			$str_order_by = " ORDER BY " . implode(", ", $group_by);;
		}

		$SQL  = "SELECT ".implode(", ", $select)." FROM ".implode(", ",$table);
		if ($leftjoin > ""){
			$SQL .= " LEFT JOIN " . $leftjoin;
		}
		if (is_array($where) && (count($where) > 0)){
			$SQL .= " WHERE ".implode($where_split, $where);
		}
		$SQL .= $str_group_by.$str_order_by;

		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		echo '<table class="lineunder" width="85%" align="center" cellpadding="2" cellspacing="2">';
		echo '<tr><td colspan="'.count($col_head).'">&nbsp;</td></tr>';
		echo '<tr><td colspan="'.count($col_head).'"><span class="loud">'.$table_head.'</span><hr></td></tr>';
		echo '<tr>';
		foreach ($col_head AS $val) {
			echo '<td class="lineunder"><b>'.$val.'</b></td>';
		}
		echo '</tr>';
		$total = 0;
		if (mysqli_num_rows($RS) > 0) {
			while ($RS && ($row=mysqli_fetch_array($RS))) {
				echo '<tr>';
				foreach ($select AS $key=>$val) {
					echo '<td class="lineunder">'.$row[$val].'</td>';
				}
				if (is_numeric($row[$val])) {
					$total += $row[$val];
				} else {
					$total = mysqli_num_rows($RS);
				}
				echo '</tr>';
			}
			echo '<tr><td><b>Total</b></td><td>'. $total. '</td></tr>';
		}else {
			if ($no_rows_msg > "") echo '<tr><td colspan="'.count($col_head).'" align="center">'.$no_rows_msg.'</td></tr>';
		}
		echo '<tr><td colspan="'.count($col_head).'">&nbsp;</td></tr></table>';
		
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
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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

	function showInstProfileUploadedDocs ($id=0) {
                
		$tables = mysqli_list_tables($this->DBname);
		$tables_arr = array();
		while ($tables && ($row_tables=mysqli_fetch_array($tables, MYSQLI_NUM))) {
			array_push($tables_arr, $row_tables[0]);
		}
		$file_tables = array();
		foreach ($tables_arr AS $value) {
			if (stristr($value, "institutional_profile_pol_budgets")) {
				array_push($file_tables, $value);
			}
		}
		$documents = array();
		foreach ($file_tables AS $value) {
			$SQL = "SELECT inst_uploadDoc FROM `".$value."` WHERE institution_ref=".$id;
			$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
			while ($RS && ($row=mysqli_fetch_array($RS, MYSQLI_NUM))) {
				array_push($documents, $row[0]);
			}
		}

		// POSIBLE BUG
		// $path = ($this->view)?("../"):("");

		echo '<table width="100%" border=1 align="center" cellpadding="2" cellspacing="2">';
		if (sizeof($documents) > 0) {
			echo '<tr><td class="oncolourb">The following is the list of documentation related to the institutional profile, submitted by the registrar:<br><br><ul>';
			foreach ($documents AS $value) {
				if ($value > 0) {
					$doc = new octoDoc ($value);
					if ($doc->isDoc()) {
						$fname = $doc->getFilename();
						if ($doc->getFileExist()) {
							$url = $doc->url();
							$docLink = '<a href="'.$url.'" target="_blank">'.$fname.'</a>';
						} else {
							$docLink = $fname;
						}
						
						echo "<li>\n\t{$docLink}\n</li>\n";
					}
				}
			}
		}else {
			echo '<tr><td class="oncolourb">The registrar has not submitted documentation related to the institutional profile as yet.<br><br><ul>';
		}
		
		echo '</ul></td></tr></table>';
	}

	function showMessageRequiredDocsPrivate() {
		echo "<b>For every item of required documentation in the list below, please indicate whether it differs from the general institution's policies. ";
		echo "<br>";
		echo "If it does, please, upload or send a copy by post to the following address:";
		echo "</b>";
		echo "<br><br>";
		echo "<center>";
		echo "Accreditation and Coordination Directorate, HEQC<br>";
		echo "Council on Higher Education<br>";
		echo "PO Box 13354, The Tramshed, 0126";
		echo "</center>";
	}

	function showMessageRequiredDocsPublic() {
		echo "<b>For every item of required documentation in the list below, please indicate whether it differs from the general institution's policies. ";
		echo "<br>";
		echo "If it does, please, upload or send a copy by post to the following address:";
		echo "</b>";
		echo "<br><br>";
		echo "<center>";
		echo "Accreditation and Coordination Directorate, HEQC<br>";
		echo "Council on Higher Education<br>";
		echo "PO BOX 13354, The Tramshed, 0126";
		echo "</center>";
	}

	/*
	* Louwtjie: 2005-02-08
	* function to create the documentation links on the action menu.
	*/
	function createEvalActions ($question=0) {
                
		$SQL = "SELECT * FROM `Institutions_application` WHERE application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);

		$columns = mysqli_num_fields($RS);

		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$i=0;
			$j=1;
			while ($i < mysqli_num_fields($RS)) {
				$meta = mysqli_fetch_field($RS, $i);
				if ((stristr($meta->name, $question."_")) && (stristr($meta->name, "_doc"))) {
					if (($row[$meta->name] > 0)) {
						$this->createAction ($meta->name, "Documentation - u".$this->getValueFromTable("documents", "document_id", $row[$meta->name], "document_url"), "href", "documents/".$this->getValueFromTable("documents", "document_id", $row[$meta->name], "document_url"), "ico_change.gif", "Application Documentation", "_blank");
					}
				}
				$i++;
			}
		}
                
	}
	/*
	* Louwtjie: 2005-02-08
	* function to create the evaluator report links on the action menu.
	*/
	function createEvalSummaryActions ($question=0) {
                
		$SQL = "SELECT * FROM evalReport WHERE application_ref =".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." and evalReport_status_confirm=1";
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname").",&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Initials");
				$tmpSettings = "DBINF_Institutions_application___application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
				$this->createAction ($row["Persnr_ref"], $name, "href", "javascript:winPrintEvalReportForm('Evaluation','".$row["evalReport_id"]."','".base64_encode($tmpSettings)."','')", "ico_change.gif", "Evaluator Report");
			}
		}
		
	}

	/* 	Louwtjie: 2005-04-26
			function to see wheather or not the email has been sent to the DoE (if teachers edu prog) or to the professional board (if professional prog)
	*/
	function checkEmail_DoE_Profboard () {
                
		$sent = false;
		$SQL = "SELECT * FROM `screening` WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND screening_id=".$this->dbTableInfoArray["screening"]->dbTableCurrentID;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			if (($row["email_sent"] == 1) && ($row["email_rcpt"] > "")) {
				$sent = true;
			}
		}
		
		return $sent;
	}

	/* 	Louwtjie: 2005-04-26
			function to show the email to the DoE (if teachers edu prog) or to the professional board (if professional prog)
	*/
	function showEmail_DoE_Profboard () {
		return "<b>This is the email</b>";
	}

	function generateSiteVisitInvoice () {
                
		$SQL = "SELECT * FROM `siteVisit` WHERE siteVisit_id=".$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);

		$html = $this->makeTop($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);

		$html .= "<table>";
		if ($RS && ($row=mysqli_fetch_object($RS))) {
			$html .= "<tr><td  align='right'>Site visit fee:</td><td>R ".$this->getDBsettingsValue("payment_site_fee")."</td></tr>";
			$html .= "<tr><td  align='right'>Additional fee for Extra site:</td><td>R ".$this->getDBsettingsValue("payment_additional_fee_siteVisit")."</td></tr>";
			$html .= "<tr><td  align='right'>Direct costs:</td><td>&nbsp;</td></tr><tr>";
			$html .= "<tr><td  align='right'>Travel:</td><td>R ".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "direct_travel_costs")."</td></tr>";
			$html .= "<tr><td  align='right'>Accommodation:</td><td>R ".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "direct_accomodation_costs")."</td></tr>";
			$html .= "<tr><td  align='right'>Subsistence:</td><td>R ".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "direct_subsistence_costs")."</td></tr>";
			$html .= "<tr><td  align='right' colspan='2'>&nbsp;</td></tr><tr><td align='right'><b>Total (plus VAT):</b></td><td>R ".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "total_costs")."</td></tr>";
		}
		$html .= "</table>";
                
		return $html;
	}

	function applicationProgressReport ($institution="", $process_number=5, $status="", $last_process=false, $is_CHE=false) {
                

		if ($institution > "") {
			if (! is_array($institution) ) {
				$institution = array($institution);
			}
		}else {
			$institution = array($this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
		}
		$searchArr = array();
		$sqlArr = array();
		$searchCrit = array();
		$iframeText = "";
		$post_array = array("HEI_id");
		$title_array = array("Institution");
		array_push($sqlArr, "HEI_id IN ('".implode("', '", $institution)."')");
		foreach ($institution AS $value) {
			array_push($searchCrit, $title_array[0] . ": " . $this->formFields["HEI_id"]->fieldValuesArray[$value]);
		}
		
		$conn= $this->getDatabaseConnection();

/* */
		$this->createSubmittedApplicationsTempTable($conn);
/***********************************************************************************/


/*$sql =<<<SQLselect
	CREATE TEMPORARY TABLE tmp_applic1 (
	  `application_id` int(11) NOT NULL,
	  `active_processes_id` int(11) NOT NULL,
	  `processes_ref` int(11) NOT NULL,
	  `work_flow_ref` int(11) NOT NULL,
	  `user_ref` int(11) NOT NULL,
	  `workflow_settings` text,
	  `status` int(11) NOT NULL,
	  `last_updated` datetime NOT NULL,
	  `active_date` date NOT NULL,
	  `due_date` date NOT NULL,
	  `expiry_date` date NOT NULL,
   KEY (`application_id`)
	)
SQLselect;

$conn= $this->getDatabaseConnection();


	$rs = mysqli_query($conn, $sql); // or die(mysqli_error($conn)); 
 
	$sql =<<<SQLinsert
		INSERT INTO tmp_applic1 (application_id, active_processes_id,
	  processes_ref,
	  work_flow_ref,
	  user_ref,
	  workflow_settings,
	  status,
	  last_updated,
	  active_date,
	  due_date,
	  expiry_date)
		SELECT (
				IF(InStr( a.workflow_settings, "application_id=" ) =0, 0,
					mid( a.workflow_settings,
						InStr(a.workflow_settings, "application_id=" ) +15,
						IF (Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "application_id=" ) +15) > 0,
				 			Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "application_id=" ) +15)
							-
							( InStr( a.workflow_settings, "application_id=" ) +15 ),
							Length(a.workflow_settings)
							-
							( InStr( a.workflow_settings, "application_id=" ) +15 ) + 1
						)
					)
				)
			) AS application_id, a.*
		FROM active_processes as a
SQLinsert;
  
//echo $sql;

//$conn= $this->getDatabaseConnection();

	$rs = mysqli_query($conn, $sql);

	$sql =<<<sSQL
		CREATE TEMPORARY TABLE tmp_submitted
		SELECT application_id, min(last_updated) as last_updated
		FROM tmp_applic1
		WHERE processes_ref not in (5,46,66,100)
		GROUP BY application_id
		ORDER BY application_id
sSQL;
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
/************************************************************************************/	
	
	
	
	
	

$SQL =<<<SQLselect
		SELECT
			HEInstitution.HEI_name,
			Institutions_application.institution_id,
			Institutions_application.program_name as Program,
			Institutions_application.application_id,
			IF(Institutions_application.CHE_reference_code='', "-- Not Submitted --", Institutions_application.CHE_reference_code) AS CHE_reference_code,
			tmp_ap.last_updated,
			CONCAT(name, ': ', users.email) as Process_User,
			tmp_ap.user_ref,
			processes.processes_desc as Process,
			tmp_ap.active_processes_id,
			lkp_process_status.lkp_process_status_desc,
			count(*) as Nr_Invoice,
			sum(payment.invoice_total) AS Invoice,
			sum(IF(payment.received_confirmation=1,payment.invoice_total,0)) AS Paid
		FROM (tmp_ap, processes, users)
		LEFT JOIN Institutions_application on Institutions_application.application_id = tmp_ap.application_id
		LEFT JOIN HEInstitution on HEI_id = Institutions_application.institution_id
		LEFT JOIN lkp_process_status on lkp_process_status_id = tmp_ap.status
		LEFT JOIN payment ON payment.application_ref = Institutions_application.application_id
		WHERE tmp_ap.processes_ref = processes.processes_id
			AND tmp_ap.user_ref = users.user_id
			AND Institutions_application.application_id is not null
SQLselect;

		$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
		$SQL = ($is_CHE)?($SQL):($SQL." AND processes_ref IN (5,46, 113)");//$SQL." AND (" . implode(" AND ", $sqlArr).")"
		$SQL .= "GROUP BY 
			HEInstitution.HEI_name,
			Institutions_application.institution_id,
			Institutions_application.program_name,
			Institutions_application.application_id,
			Institutions_application.CHE_reference_code,
			tmp_ap.last_updated,
			users.email,
			tmp_ap.user_ref,
			processes.processes_desc,
			tmp_ap.active_processes_id,
			lkp_process_status.lkp_process_status_desc
		ORDER by 						HEInstitution.HEI_name,
			Institutions_application.institution_id,
			Institutions_application.program_name,
			Institutions_application.application_id,
			Institutions_application.CHE_reference_code,
			tmp_ap.last_updated,
			users.email,
			tmp_ap.user_ref,
			processes.processes_desc,
			tmp_ap.active_processes_id,
			lkp_process_status.lkp_process_status_desc";

		/*
			$SQL = "SELECT Persnr, Names, Surname, Work_Number, E_mail FROM ".implode (", ", $tableArray)." WHERE 1 ";
			$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
			$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL):($SQL);
			$SQL = (count($searchArr) > 0)?($SQL." AND (".implode(" OR ", $searchArr).")"):($SQL);
			$SQL .= "ORDER BY number_evals, Surname,Names";
		*/
	//	echo $SQL;

			if ($rs = mysqli_query($conn, $SQL)) {

			/*
			$HEI_id = $institution[0]
			echo $HEI_id;

				$iframeText .= "Please note that each alternate coloured section represents one application.";
				$iframeText .= "Each column represents the following:";
				$iframeText .= "<ul>";
				$iframeText .= "<li><b>Institution</b> - clicking on this will bring up your institutional profile. It will be the same for every application</li>";
				$iframeText .= "<li><b>Programme</b></li>";
				$iframeText .= "<li><b>CHE ref no.</b></li>";
				$iframeText .= "<li><b>Institution</b></li>";
				$iframeText .= "<li><b>With user</b></li>";
				$iframeText .= "<li><b>Process</b></li>";
				$iframeText .= "<li><b>Institution</b></li>";
				$iframeText .= "<li><b>Date last accessed</b></li>";
				$iframeText .= "<li><b>Status</b></li>";
				$iframeText .= "<li><b>Amount due</b></li>";
				$iframeText .= "<li><b>Paid</b></li>";
				$iframeText .= "<li><b>Admin action</b></li>";
				$iframeText .= "<li><b>Accreditation status</b></li>";
				$iframeText .= "</ul>";
			*/
			    $iframeText .= "<table border='0' width='95%' align='center'>\n";
				$prevProgram = "";
				$bgColor = "#EAEFF5";
				$n=0;
				if (mysqli_num_rows($rs) > 0){
					$iframeText .= "<tr class='onblueb'><td colspan=\"7\"><b>Application Status Report for </b>". implode('',$searchCrit) ."</td><td colspan=\"4\" align=\"right\"><b>Total Rows: ".mysqli_num_rows($rs)."</b></td></tr>";
					$iframeText .= "<tr class='onblueb'><td colspan=\"11\">&nbsp;</td>";
					$iframeText .= "<tr class='onblueb'><td><b>Institution</b></td><td><b>Programme</b></td><td><b>CHE Ref No</b></td><td><b>User</b></td><td><b>Process</b></td><td><b>Date</b></td><td><b>Status</b></td><td><b>Amount</b></td><td><b>Paid</b></td><td><b>Admin Action</b></td></tr>\n";
				    while ($row = mysqli_fetch_array($rs)) {
						$admin_action = '&nbsp;';
						if ($row["application_id"]!= $prevProgram){
							$n+=1;
							$instadm = $this->getInstitutionAdministrator(0,$row['institution_id']);
						}
						$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#c0c0c0");
						$iframeText .= "<tr bgcolor='" . $bgColor . "'>\n";

						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"] ."&DBINF_ia_criteria_per_site___ia_criteria_per_site_id=1";

						$iframeText .= "<td valign='top'>".'<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["institution_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["HEI_name"]."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Program"] ."</td>\n";
						$iframeText .= "<td valign='top' nowrap>". '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"].'</a>' . "</td>\n";
						$iframeText .= "<td valign='top'>". $row["Process_User"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Process"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["last_updated"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["lkp_process_status_desc"] ."</td>\n";
		//				$iframeText .= "<td valign='top'>". $row["Nr_Invoice"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Invoice"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Paid"]."</td>\n";

// 2010-03-08 Robin - Replaced with check against actual administrator and not the person on the application record (one who started the application.
//						$admin_action = ((($this->currentUserID != $row['user_ref']) && ($row["Process"] == 'Accreditation Application Form') && ($this->currentUserID == $this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "user_ref")) && ($row["lkp_process_status_desc"] != 'complete'))?('<a href="javascript:adminTakeApp('.$row["active_processes_id"].');">Take back</a>'):('&nbsp;'));

						$admin_action = "&nbsp;";
						if ($this->currentUserID != $row['user_ref'] 						// current user doesn't have the active process - thus no need to take it back.
							&& ($row["Process"] == 'Accreditation Application Form')
							&& ($this->currentUserID == $instadm[0]) 						// current user is the administrator
							&& ($row["lkp_process_status_desc"] != 'complete')){ 	 		// process is still active
								$admin_action = '<a href="javascript:adminTakeApp('.$row["active_processes_id"].');">Take back</a>';
						}
						
						$iframeText .= "<td valign='top'>".$admin_action."</td></tr>\n";

						$prevProgram = $row["application_id"];
					}
				}else {
					$iframeText .= "<tr><td colspan='2' align='center'><b>No results found!</b></td></tr>\n";
				}
			    $iframeText .= "</table>\n";
			}
			echo $iframeText;
//		}
                
	}

	function reportReaccApplicProgress ($institution="", $is_CHE=false) {
        $conn = $this->getDatabaseConnection();
        $this->createReaccApplicTempTable($conn);
        if ($institution > "") {
			if (! is_array($institution) ) {
				$institution = array($institution);
			}
		}else {
			$institution = array($this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
		}

		$searchArr = array();
		$sqlArr = array();
		$searchCrit = array();
		$iframeText = "";
		$post_array = array("HEI_id");
		$title_array = array("Institution");
		array_push($sqlArr, "HEI_id IN ('".implode("', '", $institution)."')");
		foreach ($institution AS $value) {
			array_push($searchCrit, $title_array[0] . ": " . $this->formFields["HEI_id"]->fieldValuesArray[$value]);
		}

$SQL =<<<SQLselect
		SELECT
			HEInstitution.HEI_name,
			r.Institutions_application_reaccreditation_id,
			r.institution_ref,
			r.programme_name,
			r.referenceNumber,
			r.reacc_submission_date,
			IF(r.reacc_submission_date='1000-01-01', "-- Not Submitted --", r.reacc_submission_date) AS reacc_submission_date,
			tmp_reacc.user_ref,
			CONCAT(users.name, ': ', users.email) as Process_User,
			tmp_reacc.active_processes_id,
			tmp_reacc.processes_ref,
			tmp_reacc.last_updated,
			If(tmp_reacc.status = 1,'complete','active') as status
		FROM tmp_reacc
		LEFT JOIN Institutions_application_reaccreditation AS r ON r.Institutions_application_reaccreditation_id = tmp_reacc.reacc_applic_id
		LEFT JOIN HEInstitution on HEI_id = r.institution_ref
		LEFT JOIN users ON users.user_id = tmp_reacc.user_ref
		WHERE 1
SQLselect;

		$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
		$SQL = ($is_CHE)?($SQL):($SQL." AND tmp_reacc.processes_ref IN (130, 210)");//$SQL." AND (" . implode(" AND ", $sqlArr).")"
		$SQL .= " ORDER by HEInstitution.HEI_Name, r.programme_name, r.referenceNumber,
			tmp_reacc.last_updated";
		//echo $SQL;
			
			$rs = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
			//echo "<br/>mysqli_num_rows($rs) : ".mysqli_num_rows($rs);
			
			
			//if ($rs){
				$iframeText .= "<table border='0' width='95%' align='center'>\n";
				$prevProgram = "";
				$bgColor = "#EAEFF5";
				$n=0;
				if (mysqli_num_rows($rs) > 0){
					$iframeText .= "<tr class='onblueb'><td colspan=\"5\"><b>Re-accreditation Application Status Report for </b>". implode('',$searchCrit) ."</td><td colspan=\"3\" align=\"right\"><b>Total Rows: ".mysqli_num_rows($rs)."</b></td></tr>";
					$iframeText .= "<tr class='onblueb'><td colspan=\"8\">&nbsp;</td>";
					$iframeText .= "<tr class='onblueb'><td><b>Institution</b></td><td><b>Programme</b></td><td><b>CHE Ref No</b></td><td><b>Submission<br> Date</b></td><td><b>User</b></td><td><b>Date</b></td><td><b>Status</b></td><td><b>Admin Action</b></td></tr>\n";
				    while ($row = mysqli_fetch_array($rs)) {
						$apDate = $row['last_updated'];
						$apUser = $row['Process_User'];
						$apStatus = $row['status'];

						$admin_action = '&nbsp;';
						if ($row["Institutions_application_reaccreditation_id"]!= $prevProgram){
							$n+=1;
							$instadm = $this->getInstitutionAdministrator(0,$row['institution_ref']);
						}
						$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#c0c0c0");
						$iframeText .= "<tr bgcolor='" . $bgColor . "'>\n";

						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["institution_ref"]."&DBINF_institutional_profile___institution_ref=".$row["institution_ref"]."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$row["Institutions_application_reaccreditation_id"];

						$iframeText .= "<td valign='top'>".'<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["institution_ref"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["HEI_name"]."</a></td>\n";
						$iframeText .= "<td valign='top'>". $row["programme_name"] ."</td>\n";
						$iframeText .= "<td valign='top' nowrap>". '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>' . "</td>\n";
						$iframeText .= "<td valign='top'>". $row["reacc_submission_date"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $apUser ."</td>\n";
						$iframeText .= "<td valign='top'>". $apDate ."</td>\n";
						$iframeText .= "<td valign='top'>". $apStatus ."</td>\n";
						$admin_action = "&nbsp;";
						if ($this->currentUserID != $row['user_ref'] 						// current user doesn't have the active process - thus no need to take it back.
							&& (($row["processes_ref"] == 130)
							|| ($row["processes_ref"] == 210))
							&& ($this->currentUserID == $instadm[0]) 						// current user is the administrator
							&& ($row["status"] != 'complete')){ 	 		// process is still active
								$admin_action = '<a href="javascript:adminTakeApp('.$row["active_processes_id"].');">Take back</a>';
						}

						$iframeText .= "<td valign='top'>".$admin_action."</td></tr>\n";


						$prevProgram = $row["Institutions_application_reaccreditation_id"];
					}
				}else {
					$iframeText .= "<tr><td colspan='2' align='center'><b>No results found!</b></td></tr>\n";
				}
			    $iframeText .= "</table>\n";
			//}
			echo $iframeText;
	}







	function createSubmittedApplicationsTempTable($conn){
	
	
      // $conn = $this->getDatabaseConnection();
	$sql =<<<SQLselect
	CREATE TEMPORARY TABLE tmp_ap (
	  `application_id` int(11) NOT NULL AUTO_INCREMENT,
	  `active_processes_id` int(11) NOT NULL,
	  `processes_ref` int(11) NOT NULL,
	  `work_flow_ref` int(11) NOT NULL,
	  `user_ref` int(11) NOT NULL,
	  `workflow_settings` text,
	  `status` int(11) NOT NULL,
	  `last_updated` datetime NOT NULL,
	  `active_date` date NOT NULL,
	  `due_date` date NOT NULL,
	  `expiry_date` date NOT NULL,
   KEY  (`application_id`)
	)
SQLselect;


	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
 
	$sql =<<<SQLinsert
		INSERT INTO tmp_ap (application_id, active_processes_id,
	  processes_ref,
	  work_flow_ref,
	  user_ref,
	  workflow_settings,
	  status,
	  last_updated,
	  active_date,
	  due_date,
	  expiry_date)
		SELECT (
				IF(InStr( a.workflow_settings, "application_id=" ) =0, 0,
					mid( a.workflow_settings,
						InStr(a.workflow_settings, "application_id=" ) +15,
						IF (Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "application_id=" ) +15) > 0,
				 			Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "application_id=" ) +15)
							-
							( InStr( a.workflow_settings, "application_id=" ) +15 ),
							Length(a.workflow_settings)
							-
							( InStr( a.workflow_settings, "application_id=" ) +15 ) + 1
						)
					)
				)
			) AS application_id, a.*
		FROM active_processes as a
SQLinsert;

//echo $sql;

	$rs = mysqli_query($conn, $sql);

	$sql =<<<sSQL
		CREATE TEMPORARY TABLE tmp_submitted
		SELECT application_id, min(last_updated) as last_updated
		FROM tmp_ap
		WHERE processes_ref not in (5,46,66,100)
		GROUP BY application_id
		ORDER BY application_id
sSQL;
	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        
	}

	function createReaccApplicTempTable($conn){
	 
	$sql =<<<TMPREACC
	CREATE TEMPORARY TABLE tmp_reacc (
	  `reacc_applic_id` int(11) NOT NULL,
	  `active_processes_id` int(11) NOT NULL,
	  `processes_ref` int(11) NOT NULL,
	  `work_flow_ref` int(11) NOT NULL,
	  `user_ref` int(11) NOT NULL,
	  `workflow_settings` text,
	  `status` int(11) NOT NULL,
	  `last_updated` datetime NOT NULL,
	  `active_date` date NOT NULL,
	  `due_date` date NOT NULL,
	  `expiry_date` date NOT NULL,
   KEY  (`reacc_applic_id`)
	)
TMPREACC;


	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	$sql =<<<INSREACC
		INSERT INTO tmp_reacc
			SELECT tmp.*
			FROM (
					SELECT (
								IF(InStr( a.workflow_settings, "Institutions_application_reaccreditation_id=" ) =0, 0,
									mid( a.workflow_settings,
										InStr(a.workflow_settings, "Institutions_application_reaccreditation_id=" ) +44,
										IF (Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "Institutions_application_reaccreditation_id=" ) +43) > 0,
								 			Locate( "&", a.workflow_settings, InStr( a.workflow_settings, "Institutions_application_reaccreditation_id=" ) +43)
											-
											( InStr( a.workflow_settings, "Institutions_application_reaccreditation_id=" ) +44 ),
											Length(a.workflow_settings)
											-
											( InStr( a.workflow_settings, "Institutions_application_reaccreditation_id=" ) +43 ) + 1
										)
									)
								)
							) AS reacc_applic_id, a.*
					FROM active_processes as a
			) AS tmp
			WHERE tmp.reacc_applic_id IS NOT NULL 
INSREACC;

		$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
	}

	function getLocationOfActiveProcessesForApplication($app_id){
                
		$location = "";

		// Get location of active process / es
		$sSQL =<<<sSQL
			SELECT CONCAT(processes_desc,'-',u.name) as plocation
			FROM tmp_ap
			LEFT JOIN processes as p ON p.processes_id = processes_ref
			LEFT JOIN users AS u ON u.user_id = user_ref
			WHERE status = 0
			AND application_id = $app_id
sSQL;
			$rsSQL = mysqli_query($this->getDatabaseConnection(), $sSQL);

			$arr_location = array();
			while($srow = mysqli_fetch_array($rsSQL)){
				array_push($arr_location,$srow['plocation']);
			}
			$location .= implode(",<br>",$arr_location);
                
			return $location;
	}

	function reportSubmittedApplications($searchText='', $dateFrom='0',$dateTo='0', $searchFor='0', $reportType="", $reportParam="", $institution='0', $outcome="", $mode_delivery = '0'){	
                
	
		
		switch($outcome) {
			case "prov" :	$outcome = "1";
							break;
			case "provCond" :$outcome = "2";
							break;
			case "not" :	$outcome = "3";
							break;
			case "def" :	$outcome = "4";
							break;
			default : 		$outcome = "";
		}

		$priv_publ = "";
		$outcome_group = "";

		$pos = strrpos($reportParam, "_");

		if ($pos)
		{
		 	$outcome_group = substr($reportParam, $pos+1);
		 	$reportParam = substr($reportParam, (strlen($reportParam)-2)-$pos, $pos);
			//MAGIC NUMBERS BAD! but how else?
		}

			switch ($reportParam)
			{
				case "all" : $priv_publ = ""; break;
				case "private" : $priv_publ = "1"; break;
				case "public" : $priv_publ = "2"; break;
			}
        
            $conn=$this->getDatabaseConnection();

			$this->createSubmittedApplicationsTempTable($conn);

			$whereArr = array("1");

			$aSql =<<<aSql
				SELECT i.HEI_name, a.*
				FROM Institutions_application AS a
				LEFT JOIN HEInstitution as i ON HEI_id = a.institution_id
aSql;

			$whereArr = array("submission_date > '1000-01-01'","(a.CHE_reference_code > '')","a.institution_id NOT IN (1, 2)");

			if ($reportType == "accredited") { array_push($whereArr, "a.AC_desision > ''");}
			if ($reportType == "without") { array_push($whereArr, "a.AC_desision = '' and a.application_status != -1");}
			if ($reportType == "cancelled") { array_push($whereArr, "a.application_status = -1");}

			if ($searchText != ''){
				array_push($whereArr,"CHE_reference_code LIKE '%".$searchText."%' ");
			}

			if ($dateFrom != '0') {
				array_push($whereArr, 'submission_date >= "'.$dateFrom.'"');
			}

			if ($dateTo != '0') {
				array_push($whereArr, 'submission_date <= "'.$dateTo.'"');
			}

			if ($mode_delivery != '0') {
				array_push($whereArr, 'mode_delivery = "'. $mode_delivery .'"');
			}			
			


			if ($searchFor != '0') {
				array_push($whereArr, $this->getValueFromTable("lkp_search_for", "lkp_search_for_id", $searchFor, "lkp_search_for_sql")." ");
			}

			if ($institution != '0') {
				array_push($whereArr," institution_id = '".$institution."' ");
			}

			if ($outcome != '') {
				array_push($whereArr," a.AC_desision = '".$outcome."' ");
			}

			if ($priv_publ) {
				array_push($whereArr, 'priv_publ = "'.$priv_publ.'"');
			}

			if ($outcome_group) {
				array_push($whereArr, 'AC_desision = "'.$outcome_group.'"');
			}

			$aSql .= " WHERE ".implode(" AND ", $whereArr);
			$aSql .=  " ORDER BY submission_date, a.AC_Meeting_date";
			


			if ($aRs = mysqli_query($conn, $aSql))
			{
				$tot_rows = mysqli_num_rows($aRs);
				$n=0;

				$bgColor = "#EAEFF5";
				echo "
						<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
						<tr><td colspan='7' align='right'>Total: ".$tot_rows."</td></tr>";

				echo "
					<tr class='onblueb' align='center'>
						<td><b>Submission date</b></td>
						<td><b>Institution</b></td>
						<td><b>HEQC Reference No.</b></td>
						<td><b>Programme Name</b></td>
						<td><b>NQF</b></td>
						<td><b>CESM</b></td>
						<td><b>Mode of delivery</b></td>";
					echo ($reportType == "submitted") ?	"<td><b>Submitted to CHE</b></td>" : "";
					//2017-11-13 Richard: Removed Location and AC meeting and added Date recommended to SAQA for registration and SAQA Id
					//echo ($this->body == "reportSubmittedApplications") ? "<td><b>Location</b></td>" : "";
					//echo "			<td width='8%'><b>AC Meeting</b></td>
					echo "				<td><b>Status</b></td>
									<td><b>Date recommended to SAQA</b></td>
									<td><b>SAQA Id</b></td>
								</tr>";
				while ($row = mysqli_fetch_array($aRs))
				{

					$n += 1;
					$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");

					$status = "";
					if ($row["application_status"] == -1){
						//$status = "Cancelled";
						$status = $this->getValueFromTable("lkp_application_status", "lkp_application_status_id", $row["application_status"], "lkp_application_status_desc");
					}
					if ($row["withdrawn_ind"] == 1 ){
						$status .= " - withdrawn";
					}
					if ($row["AC_desision"] > 0){
						$status = $this->getValueFromTable("lkp_desicion", "lkp_id", $row["AC_desision"], "lkp_title");
					}
					$ref = $row["CHE_reference_code"];
					$nqf = $this->getValueFromTable("NQF_level","NQF_id",$row["NQF_ref"],"NQF_level");
					$cesm = $this->getValueFromTable("SpecialisationCESM_code1","CESM_code1",$row["CESM_code1"],"Description");

					$mode_deliveryValue = $this->getValueFromTable("lkp_mode_of_delivery","lkp_mode_of_delivery_id",$row["mode_delivery"],"lkp_mode_of_delivery_desc"); 
					
					//2017-11-13 Richard: Added Date recommended to SAQA for registration and SAQA Id
					$date_to_SAQA = ($row["date_recommended_to_SAQA"] > "1000-01-01") ? $row["date_recommended_to_SAQA"] : "&nbsp;";
					$SAQA_Id = $row["SAQA_id"];

					if ($this->body == "reportSubmittedApplications")
					{
						$location = $this->getLocationOfActiveProcessesForApplication($row["application_id"]);
						// needed for link
						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_id"];
						$ref = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"]."</a>";
					}

					$ac_meeting_date = ($row["AC_Meeting_date"] > "1000-01-01") ? $row["AC_Meeting_date"] : "&nbsp;";

					echo "<tr bgcolor='" . $bgColor . "'>
								<td>".$row["submission_date"]."</td>
								<td>".$row["HEI_name"]."</td>
								<td>".$ref."</td>
								<td>".$row["program_name"]."</td>
								<td>".$nqf."</td>
								<td>".$cesm."</td>
								<td>".$mode_deliveryValue."</td>";
					echo ($reportType == "submitted") ?	"<td>".$row["submission_date"]."</td>" : "";
					//2017-11-13 Richard: Removed Location and AC meeting and added Date recommended to SAQA for registration and SAQA Id
					//echo ($this->body == "reportSubmittedApplications") ? "<td>".$location."</td>" : "";
					//echo "		<td>".$ac_meeting_date."</td>
					echo "			<td>".$status."</td>
								<td>".$date_to_SAQA."</td>
								<td>".$SAQA_Id."</td>
							</tr>";
				}

				echo "</table>";
			}
			
	}

	function reportAuditTrail($CHE_code='', $institution_ref='', $reacc_ind=0){
                
		if (($CHE_code=="") && ($institution_ref=="")) {
			echo "<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>";
			echo "<tr class='onblue'><td class='onblueb' align='center'> - Please enter at least one search parameter. -</td></tr>";
			echo "</table>";
		}
		else {
			
			$whereArr = array("1");
			
			// Accreditation audit trail
			if ($reacc_ind == 0){
				$app_id = ($CHE_code) ? $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "application_id") : "";
				$app_field = "application_ref";
				$inst_id = ($institution_ref) ? $institution_ref : $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "institution_id");
			}
			
			// Re-accreditation audit trail
			if ($reacc_ind == 1){
				$app_id = ($CHE_code) ? $this->getValueFromTable("Institutions_application_reaccreditation", "referenceNumber", $CHE_code, "Institutions_application_reaccreditation_id") : "";
				$app_field = "reacc_application_ref";
				$inst_id = ($institution_ref) ? $institution_ref : $this->getValueFromTable("Institutions_application_reaccreditation", "referenceNumber", $CHE_code, "institution_ref");
			}

//if no inst_id found, then invalid ref_no must have been entered
			if ($inst_id != "")
			{
				$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");
				$aSql =<<<aSql
							SELECT DISTINCT $app_field,
							institution_ref,
							user_ref,
							process_desc,
							audit_subject,
							if (audit_subject='EMAIL' OR audit_subject='EMAIL NOT SENT' , audit_text,"") as audit_text,
							if (audit_subject='EMAIL' OR audit_subject='EMAIL NOT SENT' , workflow_audit_trail_id,"") as audit_id,
							DATE_FORMAT(date_updated,'%Y-%m-%d') as date_trim_updated, date_updated
							FROM `workflow_audit_trail`
aSql;

				//$whereArr = array("(a.CHE_reference_code > '')","a.institution_id NOT IN (1, 2)");
				$whereArr = array("$app_field > 0");

				if ($CHE_code != ''){
					array_push($whereArr," $app_field = '".$app_id."' ");
				}

				if (($institution_ref != '') && ($institution_ref != '0')){
					array_push($whereArr," institution_ref = '".$institution_ref."' ");
				}

				$aSql .= " WHERE ".implode(" AND ", $whereArr);
				$aSql .=  " ORDER BY $app_field, date_updated";

				echo "<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>";
				echo "<tr class='onblue'><td class='onblueb'>Institution:</td><td colspan='5'>".$inst_name."</td></tr>";
                                //echo "SQL : ".$aSql;
				if ($aRs = mysqli_query($this->getDatabaseConnection(), $aSql))
				{
					$tot_rows = mysqli_num_rows($aRs);
					$n=0;
					$bgColor = "#EAEFF5";
					$prev_app = "";
					$audit_subj = "";

					while ($row = mysqli_fetch_array($aRs))
					{
						$app = ($reacc_ind == 0) ? $row["application_ref"] : $row["reacc_application_ref"];
						$ref_no = ($reacc_ind == 0) ? $this->getValueFromTable("Institutions_application", "application_id", $app, "CHE_Reference_code") : $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $app, "referenceNumber");
						$bgColor = "#EAEFF5";

						if ($app != $prev_app)
						{
							echo "
								<tr class='onblueb' align='center' valign='top'>
								<td width='12%'><b>Application ref.</b></td>
								<td width='30%'><b>User</b></td>
								<td width='20%'><b>Process</b></td>";
							echo "<td width='12%'><b>Audit subject</b></td>
								<td width='10%'><b>Email text</b></td>
								<td width='7%'><b>Date updated</b></td>
							</tr>";
						}


						$message = ($row["audit_subject"] == "EMAIL" || $row["audit_subject"] == "EMAIL NOT SENT") ? '<a href="javascript:void window.open(\'pages/emailContent.php?audit_id='.$row['audit_id'].'\',\'\',\'width=400; height=300 top=100; left=100; resizable=1; scrollbars=1;center=no\');">Email text</a>' : "";
						switch ($row["audit_subject"])
						{
							case "EMAIL" :
									$audit_subj = 'Email sent';
									break;
							case "EMAIL NOT SENT" :
									$audit_subj = '<font color="red">Email not sent</font>';
									break;
							case "updateActiveProcesses" :
									$audit_subj = 'Process updated';
									break;
							case "changeActiveProcesses" :
									$audit_subj = 'User changed';
									break;
							default:
									$audit_subj = $row["audit_subject"];
									break;
						}


						$user_desc = $this->getValueFromTable("users", "user_id", $row["user_ref"], "name")." ".$this->getValueFromTable("users", "user_id", $row["user_ref"], "surname");

						echo "<tr bgcolor='" . $bgColor . "' valign='top'>
									<td>".$ref_no."</td>
									<td>".$user_desc."</td>
									<td>".$row["process_desc"]."</td>
									<td>".$audit_subj."</td>
									<td>".$message."</td>
									<td>".$row["date_trim_updated"]."</td>
								</tr>";

						$prev_app = ($reacc_ind == 0) ? $row["application_ref"] : $row["reacc_application_ref"];
					}//end while
				}//end if
			echo "</table>";
			}//end if
			else  {
				echo "<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>";
				echo "<tr class='onblue'><td class='onblueb' align='center'> - The reference number you have entered does not exist in the system -</td></tr>";
				echo "</table>";
			}
		}
		
	}

	//function for displaying ac meeting decisions for applications that did not go through the normal AC Meeting process.
	function acMeetingReportOutside () {
                

		$SQL =<<<SQL
				SELECT DISTINCT application_id, institution_id, program_name, CHE_reference_code, evalDocs_AC_Meeting, AC_desision, AC_conditions
				FROM `Institutions_application`, `evaluation_outside_system`, `evalReport`
				WHERE application_status=9 AND AC_Meeting_ref=0 AND application_id=evalReport.application_ref AND application_id=evaluation_outside_system.application_ref
SQL;
		$iframeText = "";
		$prevProgram = "";
		$n = 0;

		if ($RS = mysqli_query($this->getDatabaseConnection(), $SQL)) {
			$iframeText .=<<<iframeText
				<table border='1' width='95%' align='center'>
				<tr>
					<td><b>Institution</b></td>
					<td><b>Reference Number</b></td>
					<td><b>Programme Name</b></td>
					<td><b>Evaluators' Documentation</b></td>
					<td><b>Decisions</b></td>
					<td><b>Conditions</b></td>
				</tr>
iframeText;

			while ($row = mysqli_fetch_array($RS)) {

				if ($row['application_id'] != $prevProgram){
					$n+=1;
				}

				$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#c0c0c0");

				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_id"];

				$iframeText .= "<tr bgcolor='" . $bgColor . "'>\n";

				$iframeText .= "<td valign='top'>".'<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$this->getValueFromTable("Institutions_application", "application_id", $row["application_id"], "institution_id").'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name")."</td>\n";

				$iframeText .= "<td valign='top'>". $row["program_name"] ."</td>\n";

				$iframeText .= "<td valign='top' nowrap>". '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"].'</a>' . "</td>\n";

				$iframeText .= "<td valign='top'><a href='documents/".$this->getValueFromTable('documents', 'document_id', $row['evalDocs_AC_Meeting'], 'document_url')."' target='_blank'>".$this->getValueFromTable('documents', 'document_id', $row['evalDocs_AC_Meeting'], 'document_name')."</a></td>\n";

				$iframeText .= "<td valign='top'>".$this->getValueFromTable('lkp_desicion', 'lkp_id', $row['AC_desision'], 'lkp_title')."</td>\n";

				$iframeText .= "<td valign='top'>".$row['AC_conditions']."</td>\n";

				$iframeText .= "</tr>\n";

				$prevProgram = $row["application_id"];
			}

			$iframeText .=<<<iframeText
				</table>
iframeText;
		}
                
		echo $iframeText;
	}

	function showWelcomeAlertsForEditing () {
                
		$SQL = "SELECT * FROM `welcome_alerts` ORDER BY alert_date DESC ";
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
                //echo $SQL;
		echo '<table border="1" width="95%" align="center">';
		echo '<tr><td colspan="2" align="center"><b>Actions</b></td><td><b>Date</b></td><td><b>Alert Body</b></td></tr>';
		while ($RS && ($row = mysqli_fetch_array($RS))) {
			echo '<tr>';
			echo '<td align="right">[<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value=\'welcome_alerts|'.$row['alerts_id'].'\';moveto(\'_editWelcomeAlerts\');">Edit</a>]</td>';
			echo '<td>[<a href="javascript:document.defaultFrm.DELETE_RECORD.value = \'welcome_alerts|alerts_id|'.$row['alerts_id'].'\';moveto(\'stay\');">Delete</a>]</td>';
			echo '<td>'.substr($row['alert_date'], 0, 4).'-'.substr($row['alert_date'], 4, 2).'-'.substr($row['alert_date'], 6, 2).'</td>';
			echo '<td width="66%">'.$row['alert_body'].'</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}

	/***************************************************************************************************************
	$sel may be "main" for the main site, "additional" for any other sites except main and "all" for all sites.
	***************************************************************************************************************/
	function checkInstitutionalProfileContactInfo ($inst_id, $sel="all") {
                
		$flag = TRUE;

		$fields = array();
		
		$whr = "";
		if ($sel == "main"){
			$whr = " AND main_site = 1";
		}
		if ($sel == "additional"){
			$whr = " AND main_site != 1";
		}
		
		$SQL = "SELECT * FROM `institutional_profile_sites` WHERE `institution_ref`='".$inst_id."'" . $whr;

		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$i = 0;
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$fields[$i]['site'] = $row['site_name'];
			$fields[$i]['address'] = $row['address'];
			$fields[$i]['postal_address'] = $row['postal_address'];
			$fields[$i]['contact_nr'] = $row['contact_nr'];
			$fields[$i]['contact_fax_nr'] = $row['contact_fax_nr'];
			$i++;
		}

		foreach ($fields AS $site) {
			foreach ($site AS $key=>$value) {
				if (! ($value > '') ) {
					$flag = FALSE;
				}
			}
		}
                
		return $flag;
	}

        function checkInstitutionalProfileContactInfoHeads ($inst_id) {
                
		$flag = TRUE;

		$fields = array();

		$SQL = "SELECT * FROM `institutional_profile_contacts` WHERE `institution_ref`='". $inst_id."'";
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		$i = 0;
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$fields[$i]['contact_name'] = $row['contact_name'];
			$fields[$i]['contact_surname'] = $row['contact_surname'];
			$fields[$i]['contact_title_ref'] = $row['contact_title_ref'];
			$fields[$i]['contact_designation'] = $row['contact_designation'];
			$fields[$i]['contact_postal_address'] = $row['contact_postal_address'];
			$fields[$i]['contact_physical_address'] = $row['contact_physical_address'];
			$fields[$i]['contact_email'] = $row['contact_email'];
			$fields[$i]['contact_nr'] = $row['contact_nr'];
			$fields[$i]['contact_fax_nr'] = $row['contact_fax_nr'];
			$i++;
		}

		foreach ($fields AS $fld) {
			foreach ($fld AS $key=>$value) {
				if (! ($value > '') ) {
					$flag = FALSE;
				}
			}
		}
                
		return $flag;
	}

	function isProfileCurrent () {
		$insRef = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
		$lastUpdated = $this->getValueFromTable("institutional_profile","institution_ref", $insRef, "last_updated_date");
		if ($lastUpdated) {
			$dArr = strptime($lastUpdated, "%Y-%m-%d");
			$actualDate = mktime (0, 0, 0, $dArr["tm_mday"], $dArr["tm_mon"], 1900+$dArr["tm_year"]);
			if ($actualDate < strtotime('-1 year')) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}

	}


/*Used in email to give list of applications for specific AC meeting*/
	function returnListOfApplications($application_status, $ac_meeting=0, $display="plainText") {
                
		$apps_arr = array();
		$strListApps = "";
		
		$aSQL =<<<SQL
			SELECT a.*, p.ia_proceedings_id 
			FROM Institutions_application a, ia_proceedings p
			WHERE a.application_id = p.application_ref 
			AND p.ac_meeting_ref = $ac_meeting 
			ORDER BY institution_id
SQL;
//echo $aSQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $aSQL);
		while ($row = mysqli_fetch_array($rs)) {
			$apps_arr[$row['ia_proceedings_id']] = $row;
		}

		switch ($display) {
		case "plainText" : 	foreach($apps_arr as $e){
								$strListApps .= "-- ".$e["program_name"]." (".$e["CHE_reference_code"].") - ".$this->getValueFromTable("HEInstitution", "HEI_id", $e["institution_id"], "HEI_name");
								$strListApps .= "\n";
							}
							break;

		case "table" 	:	$strListApps .= "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
						   	$total = 0;
							foreach($apps_arr as $e){
								$strListApps .= "<tr valign='top'>";
								$strListApps .= "<td> ".$e["program_name"]."</td>";

								$strListApps .= "<td>".$e["CHE_reference_code"]."</td>";
								$strListApps .= "<td>".$this->getValueFromTable("HEInstitution", "HEI_id", $e["institution_id"], "HEI_name");
								$strListApps .= "</td></tr>";
								$total++;
							}
							$strListApps .= "<tr><td colspan='3' align='right'><a href='pages/applicationList.php?ac_ref=".base64_encode($ac_meeting)."' target='_blank'><b>Total applications: ".$total."</b></td></tr>";
							$strListApps .= "</table>";
							break;
		}
                
		return $strListApps;
	}

	function returnListOfHEQCApplications($application_status, $heqc_meeting=0, $display="plainText") {
                
		$apps_arr = array();
		$strListApps = "";

		$aSQL =<<<SQL
			SELECT a.*, p.ia_proceedings_id 
			FROM Institutions_application a, ia_proceedings p
			WHERE a.application_id = p.application_ref 
			AND p.heqc_meeting_ref = $heqc_meeting 
			ORDER BY institution_id
SQL;
//echo $aSQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $aSQL);
		while ($row = mysqli_fetch_array($rs)) {
			$apps_arr[$row["ia_proceedings_id"]] = $row;
		}

		switch ($display) {
		case "plainText" : 	foreach($apps_arr as $e){
								$strListApps .= "-- ".$e["program_name"]." (".$e["CHE_reference_code"].") - ".$this->getValueFromTable("HEInstitution", "HEI_id", $e["institution_id"], "HEI_name");
								$strListApps .= "\n";
							}
							break;

		case "table" 	:	$strListApps .= "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
						   	$total = 0;
							foreach($apps_arr as $e){
								$strListApps .= "<tr valign='top'>";
								$strListApps .= "<td> ".$e["program_name"]."</td>";
								$strListApps .= "<td>".$e["CHE_reference_code"]."</td>";
								$strListApps .= "<td>".$this->getValueFromTable("HEInstitution", "HEI_id", $e["institution_id"], "HEI_name");
								$strListApps .= "</td></tr>";
								$total++;
							}
							$strListApps .= "<tr><td colspan='3' align='right'><a href='pages/applicationHEQCList.php?heqc_ref=".base64_encode($heqc_meeting)."' target='_blank'><b>Total applications: ".$total."</b></td></tr>";
							$strListApps .= "</table>";
							break;
		}
                
		return $strListApps;
	}
	
	function returnListOfSiteApplications($ac_meeting_id=0, $display="plainText") {
                
		$apps_arr = array();
		$strListApps = "";
		
		$sSQL =<<<SITE
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND inst_site_app_proceedings.ac_meeting_ref= $ac_meeting_id
			ORDER BY lkp_site_proceedings.lkp_site_proceedings_desc, HEI_name
SITE;
//echo $sSQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sSQL);
		while ($row = mysqli_fetch_array($rs)) {
			$apps_arr[$row['inst_site_app_proc_id']] = $row;
		}

		switch ($display) {
		case "plainText" : 	foreach($apps_arr as $e){
								$strListApps .= "-- ".$e["lkp_site_proceedings_desc"]." (".$e["site_application_no"].") - ". $e["HEI_name"];
								$strListApps .= "\n";
							}
							break;
		}
		return $strListApps;
	}
	
		function returnListOfHEQCSiteApplications($heqc_meeting_id=0, $display="plainText") {

		$apps_arr = array();
		$strListApps = "";
		
		$sSQL =<<<SITE
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND inst_site_app_proceedings.heqc_meeting_ref= $heqc_meeting_id
			ORDER BY lkp_site_proceedings.lkp_site_proceedings_desc, HEI_name
SITE;
//echo $sSQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sSQL);
		while ($row = mysqli_fetch_array($rs)) {
			$apps_arr[$row['inst_site_app_proc_id']] = $row;
		}

		switch ($display) {
		case "plainText" : 	foreach($apps_arr as $e){
								$strListApps .= "-- ".$e["lkp_site_proceedings_desc"]." (".$e["site_application_no"].") - ". $e["HEI_name"];
								$strListApps .= "\n";
							}
							break;
		}
		
		return $strListApps;
	}
	
//displays the active AC meeting for managing AC meetings
function getACMeetingTableTop($ac_meeting_id) {
	$SQL = "SELECT * FROM AC_Meeting WHERE ac_id = ".$ac_meeting_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		echo "<tr class='oncolourb'><td colspan='2' align='center'>Current AC Meeting:</td></tr>";
		while ($row = mysqli_fetch_array($rs)) {
		//echo 'test';
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='20%'>Meeting date:</td>";
			echo "<td>".$row["ac_start_date"]."</td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb'>Meeting venue:</td>";
			echo "<td>".$row["ac_meeting_venue"]."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '<br>';
		//echo 'test';
	}
}

function getACMeetingTableTopwrite($ac_meeting_id) {

$SQL = "SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				ac.lkp_title AS ac_outcome,
				ac.outcome_reason_heading AS ac_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_desicion AS ac ON ac.lkp_id = ia_proceedings.ac_decision_ref 
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.ac_meeting_ref = 17
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, Institutions_application.program_name";

	//$SQL = "SELECT * FROM AC_Meeting WHERE ac_id = ".$ac_meeting_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		echo "<tr class='oncolourb'><td colspan='2' align='center'>for extortingCurrent AC Meeting:</td></tr>";
		while ($row = mysqli_fetch_array($rs)) {
		
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='20%'>Meeting date:</td>";
			echo "<td>".$row["ac_heading"]."</td>";
			
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb'>Meeting venue:</td>";
			echo "<td>".$row["rec_heading"]."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '<br>';
		echo 'test';
	}
}

//displays basic application information
function getApplicationInfoTableTop($app_id, $path="") {
	$SQL = "SELECT * FROM Institutions_application WHERE application_id = ".$app_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_array($rs);
		$inst_id = $row["institution_id"];
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application___application_id=".$app_id;
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		echo "<tr class='onblue'>";
		echo "<td class='oncolourb' width='20%'>Reference number:</td>";
		echo "<td>".'<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \''.$path.'\');">'.$row["CHE_reference_code"]."</a></td>";
		echo "</tr>";
		echo "<tr class='onblue'>";
		echo "<td class='oncolourb'>Programme name:</td>";
		echo "<td>".$row["program_name"]."</td>";
		echo "</tr>";
		echo '</table>';
		echo '<br>';
	}
}

// displays basic application information, viewable by institution.
// Includes CHE reference no., programme name, mode of delivery and which site of delivery you are entering information for

function getApplicationInfoTableTopForHEI_perSite($app_id, $site_id, $path="") {
	$SQL =<<< SQL
		SELECT * FROM institutional_profile_sites
		LEFT JOIN ia_criteria_per_site
		ON institutional_profile_sites_ref=institutional_profile_sites_id
		WHERE application_ref=$app_id
		AND ia_criteria_per_site_id=$site_id
SQL;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">';
		echo '<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			$ref_code = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code");
			$ref_no = ($ref_code == "") ? "<i>A reference number has not yet been generated for this application</i>" : $applicationLink.$ref_code."</a>";
			$programmeName = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");
			$mode_lkp = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
			$mode_delivery = $this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $mode_lkp, "lkp_mode_of_delivery_desc");
			$mode_delivery_other = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery_specify_char");
			$mode_delivery .= ($mode_lkp == "5") ? ": ".$mode_delivery_other : "";
			$site_name = $row['site_name']." - ".$row['location'];

			if ($this->view != 1) {
				$html =<<< MYHTML
					<tr class='onblue'>
					<td class='oncolourb' width='30%' valign='top'>CHE Reference No.:</td>
					<td valign="top">$ref_no</td>
					</tr>
					<tr class='onblue'>
					<td class='oncolourb' width='30%' valign='top'>Programme name:</td>
					<td valign="top">$programmeName</td>
					</tr>
					<tr class='onblue'>
					<td class='oncolourb' width='30%' valign='top'>Mode of delivery:</td>
					<td valign="top">$mode_delivery</td>
					</tr>
					<tr class='onblue'>
					<td class='oncolourb' width='30%' valign='top'>Information being entered for<br>(site of delivery):</td>
					<td valign="top">$site_name</td>
					</tr>
MYHTML;
			} else {
				$html =<<< MYHTML
					<tr class='onblue'>
					<td class='oncolourb' width='30%' valign='top'>Information for<br>(site of delivery):</td>
					<td valign="top">$site_name</td>
					</tr>
MYHTML;
			}
			echo $html;
		}
		echo '</table>';
		echo '<br>';
	}
}

// displays basic application information, viewable by institution.
// Includes CHE reference no., programme name, mode of delivery and sites of delivery for relevant programme
function getApplicationInfoTableTopForHEI_sites($app_id, $path="") {
	$SQL =<<<APPHEAD
		SELECT CHE_reference_code,  mode_delivery, mode_delivery_specify_char, institution_id, mode_delivery,
			program_name, submission_date
		FROM Institutions_application 
		WHERE application_id = $app_id
APPHEAD;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">';
		echo '<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			$ref_no = ($row["CHE_reference_code"] == "") ? "<i>A reference number has not yet been generated for this application</i>" : $row["CHE_reference_code"];
			$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name");
			$priv_publ_lkp = $this->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "priv_publ");
			$priv_publ = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $priv_publ_lkp, "lnk_priv_publ_desc");
			$dhet_reg = $this->getValueFromTable("institutional_profile", "institution_ref", $row["institution_id"], "dhet_registration_no");
//			$mode_lkp = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
			$mode_lkp = $row["mode_delivery"];
			$mode_delivery = $this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $mode_lkp, "lkp_mode_of_delivery_desc");
//			$mode_delivery_other = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery_specify_char");
			$mode_delivery_other = $row["mode_delivery_specify_char"];
			$mode_delivery .= ($mode_lkp == "5") ? ": ".$mode_delivery_other : "";
			$submission_date = ($row["submission_date"] > '1000-01-01') ? $row["submission_date"] : "Application has not been submitted.";
			
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>CHE Reference No.:</td>";
			echo "<td>".$applicationLink.$ref_no."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Institution name:</td>";
			echo "<td>".$inst_name."</td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Provider type:</td>";
			echo "<td>".$priv_publ."</td>";
			echo "</tr>";
			// Display the DHET registration number for private institutions only.
			if ($priv_publ_lkp == 1){
				echo "<tr class='onblue'>";
				echo "<td class='oncolourb' width='30%' valign='top'>DHET registration no.:</td>";
				echo "<td>".$dhet_reg."</td>";
				echo "</tr>";
			}
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Programme name:</td>";
			echo "<td>".$row["program_name"]."</td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Mode of delivery:</td>";
			echo "<td>".$mode_delivery."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Site(s) of delivery:</td>";
			echo "<td>".$this->getSitesOfDeliveryPerApplication($app_id)."</td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Date of submission:</td>";
			echo "<td>".$submission_date."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '<br>';
	}
}

//checks whether AC or HEQC meeting has passed yet
	function checkMeetingPassed($start_date) {
		$MeetingPassed = $start_date < date("Y-m-d");
		return $MeetingPassed;
	}

//Rebecca:: displays the entire list of documentation for a specific meeting
function displayMeetingDocs($ac_meeting_id){

	$last_meeting_id = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "is_last_meeting");
	$last_meeting_str = ($last_meeting_id != 0) ? "<i>".$this->getValueFromTable("AC_Meeting", "ac_id", $last_meeting_id, "ac_meeting_venue")."<br>".$this->getValueFromTable("AC_Meeting", "ac_id", $last_meeting_id, "ac_start_date")."</i>" : "<i>No previous meetings have been captured.</i>";

	$SQL = "SELECT * FROM `AC_Meeting` WHERE ac_id = ".$ac_meeting_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	while ($row = mysqli_fetch_array($rs)) {
		$agendaDoc = new octoDoc($row['agenda_doc']);
		$prevMinutes = new octoDoc($row['prev_minutes_doc']);
		$minutes = new octoDoc($row['minutes_doc']);
	}

	echo '<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">';

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Meeting agenda:</td>";
	echo "<td><a href='".$agendaDoc->url()."' target='_blank'>".$agendaDoc->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Minutes of this meeting:</td>";
	echo "<td><a href='".$minutes->url()."' target='_blank'>".$minutes->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Minutes of previous meeting:<br>".$last_meeting_str."</td>";
	echo "<td><a href='".$prevMinutes->url()."' target='_blank'>".$prevMinutes->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Applications assigned to meeting:</td>";
	echo "<td><i>Click on the \"Total\" link to view documentation for each application:</i><br><br>".$this->returnListOfApplications("2,3", $ac_meeting_id, "table")."<br></td>";
	echo "</tr>";

	echo "</table>";
}

//Rebecca:: displays the entire list of documentation for a specific meeting
function displayHEQCMeetingDocs($heqc_meeting_id){

	$last_meeting_id = $this->getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meeting_id, "is_last_meeting");
	$last_meeting_str = ($last_meeting_id != 0) ? "<i>".$this->getValueFromTable("HEQC_Meeting", "heqc_id", $last_meeting_id, "heqc_meeting_venue")."<br>".$this->getValueFromTable("HEQC_Meeting", "heqc_id", $last_meeting_id, "heqc_start_date")."</i>" : "<i>No previous meetings have been captured.</i>";

	$SQL = "SELECT * FROM `HEQC_Meeting` WHERE heqc_id = ".$heqc_meeting_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	while ($row = mysqli_fetch_array($rs)) {
		$agendaDoc = new octoDoc($row['ac_summary_doc']);
		$prevMinutes = new octoDoc($row['prev_minutes_doc']);
		$minutes = new octoDoc($row['minutes_doc']);
	}

	echo '<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">';

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>AC Meeting recommendation summary:</td>";
	echo "<td><a href='".$agendaDoc->url()."' target='_blank'>".$agendaDoc->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Minutes of this meeting:</td>";
	echo "<td><a href='".$minutes->url()."' target='_blank'>".$minutes->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Minutes of previous meeting:<br>".$last_meeting_str."</td>";
	echo "<td><a href='".$prevMinutes->url()."' target='_blank'>".$prevMinutes->getFilename()."</a></td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td class='oncolourb'>Applications assigned to meeting:</td>";
	echo "<td><i>Click on the \"Total\" link to view documentation for each application:</i><br><br>".$this->returnListOfHEQCApplications("5,6", $heqc_meeting_id, "table")."<br></td>";
	echo "</tr>";

	echo "</table>";
}

//Rebecca:: displays header for applications during the adding/editing outcomes process
function displayApplicationForOutcomes($application_id){
	echo '<table border=0 width="70%" cellpadding="2" cellspacing="2" align="center">';

	echo "<tr class='onblue'>";
	echo "<td align='right' class='onblueb'>HEQC reference number: </td>";
	echo "<td>".$this->getValueFromTable("Institutions_application", "application_id", $application_id, "CHE_reference_code")."</td>";
	echo "</tr>";

	echo "<tr class='onblue'>";
	echo "<td align='right' class='onblueb'>Programme name: </td>";
	echo "<td>".$this->getValueFromTable("Institutions_application", "application_id", $application_id, "program_name")."</td>";
	echo "</tr>";

	echo "<tr class='onblue'>";
	echo "<td align='right' class='onblueb'>Institution name: </td>";
	echo "<td>".$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $application_id, "institution_id"), "HEI_name")."</td>";
	echo "</tr>";

	echo "<tr class='onblue'>";
	echo "<td align='right' class='onblueb'>Mode of delivery: </td>";
	echo "<td>".$this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $this->getValueFromTable("Institutions_application", "application_id", $application_id, "mode_delivery"), "lkp_mode_of_delivery_desc")."</td>";
	echo "</tr>";

	echo "<tr class='onblue' valign='top'>";
	echo "<td align='right' class='onblueb'>Site(s) of delivery: </td>";

	//get an array of all the sites assigned to this application
	$sitesArr = array();
	$site_names = "";
	$SQL = "SELECT sites_ref FROM lkp_sites WHERE application_ref = ".$application_id;
	$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
	while ($row = mysqli_fetch_array($RS)) {
		array_push($sitesArr, $site_name = $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $row["sites_ref"], "site_name"));
	}
	foreach ($sitesArr as $key => $value) {
		$site_names .= $value."<br>";
	}

	echo "<td>".$site_names."</td>";
	echo "</tr>";

	echo "</table>";
}

//***Rebecca::18-02-2008 - display popuplated application form in RTF format

function displayPopulatedApplicationForm ($app_id=0, $type) {
/*
	  $whereArray = array("1");
	 // if ($heiID != 0) { array_push ($whereArray, "HEI_id = ".$heiID); }

//main reportAccreditedInstitutions SQL
	  $SQL  = "SELECT * FROM HEInstitution";
	  //$SQL .= " WHERE ".implode(' AND ', $whereArray);
	  $SQL .= " WHERE HEI_id IN (1, 2, 20,54)";
	  $SQL .= " ORDER BY HEI_name";

	  $RS = mysqli_query($this->getDatabaseConnection(), $SQL);


	//2008-03-19. Rebecca - taken out for now
*/
	  switch ($type) {
	  	case "html" :	$doc = new octoDocGen ("populatedAppForm", "app_id=".$app_id);
						$doc->url ("Download report as document");
						 break;
		}

}


//***Rebecca::14-11-2007 - functions for Accredited Institutions report - both html and docgen

function reportAccreditedInstitutions ($heiID=0, $type) {

	  $whereArray = array("1");
	  if ($heiID != 0) { array_push ($whereArray, "HEI_id = ".$heiID); }

//main reportAccreditedInstitutions SQL
	  $SQL  = "SELECT * FROM HEInstitution";
	  //$SQL .= " WHERE ".implode(' AND ', $whereArray);
	  $SQL .= " WHERE HEI_id IN (1, 2, 20,54)";
	  $SQL .= " ORDER BY HEI_name";

	  $RS = mysqli_query($this->getDatabaseConnection(), $SQL);

	  switch ($type) {
	  	case "html" :	$doc = new octoDocGen ("accreditedInstitutions", "hei_id=".$heiID);
						$doc->url ("Download report as document");
						  while ($row = mysqli_fetch_array($RS)) {
							$mainTable =<<< TXT
							<table border='0' cellpadding='2' cellspacing='2' width='95%' align='center'>
								<tr><td><u><b>INSTITUTION DETAILS</b></u></td></tr>
								<tr>
									<td>
TXT;
							echo $mainTable;
							echo HEQConline::getInstitutionDetails($row, $type);
							echo "</td></tr><tr><td><b>Additional sites of delivery:</b></td></tr><tr><td>";
						//site detail SQL
  						    $s_SQL = "SELECT * FROM institutional_profile_sites WHERE institution_ref=".$row["HEI_id"]." AND main_site != 1";
						    $s_RS = mysqli_query($this->getDatabaseConnection(), $s_SQL);
							HEQConline::getSitesOfDeliveryPerInstitution ($s_RS, $type);
							$mainTable =<<< TXT
								<br><br>
								</td></tr>
								<tr>
									<td><u><b>PROGRAMME DETAILS</b></u></td>
								</tr>
								<tr><td>
TXT;
							echo $mainTable;
						//programme detail SQL
							$p_SQL = "SELECT * FROM Institutions_application WHERE institution_id = ".$row["HEI_id"]." AND AC_desision IN (1,2)";
							$p_RS = mysqli_query($this->getDatabaseConnection(), $p_SQL);
							HEQConline::getProgrammeDetailsPerInstitution ($p_RS, $type);
							$mainTable =<<< TXT
								</td></tr>
							</table>
							<br><hr>
TXT;
						echo $mainTable;
						 }
						 break;
			case "docgen" : while ($row = mysqli_fetch_array($RS)) {
								echo "<table border='0' width='160%'>";
								echo "<tr><td><u><b>INSTITUTION DETAILS</b></u></td></tr>";
								echo "<tr><td>";
							//site detail SQL
								$s_SQL = "SELECT * FROM institutional_profile_sites WHERE institution_ref=".$row["HEI_id"]." AND main_site != 1";
								$s_RS = mysqli_query($this->getDatabaseConnection(), $s_SQL);
								echo HEQConline::getInstitutionDetails($row, $type);
								echo "</td></tr><tr><td><b>Additional sites of delivery:</b></td></tr><tr><td>";
								HEQConline::getSitesOfDeliveryPerInstitution ($s_RS, $type);
							$mainTable =<<< TXT
								<br /><br />
								</td></tr>
								<tr>
									<td><u><b>PROGRAMME DETAILS</b></u></td>
								</tr>
								<tr><td>
TXT;
							echo $mainTable;
						//programme detail SQL
							$p_SQL = "SELECT * FROM Institutions_application WHERE institution_id = ".$row["HEI_id"]." AND AC_desision IN (1,2)";
							$p_RS = mysqli_query($this->getDatabaseConnection(), $p_SQL);
							HEQConline::getProgrammeDetailsPerInstitution ($p_RS, $type);
							$mainTable =<<< TXT
								</td></tr>
							</table>
							<hr /><page />
TXT;
							echo $mainTable;
							}
							break;
		}
}

//20080313. Rebecca
//gets the lookup value of a field stored in template_field (i.e. Yes/No for lkp_yn fields)
function displayLkpValue($fieldID, $lkpValue) {
	$table = HEQCOnline::getValueFromTable("template_field", "template_field_id", $fieldID, "fieldSelectTable");
	$id = HEQCOnline::getValueFromTable("template_field", "template_field_id", $fieldID, "fieldSelectID");
	$desc = HEQCOnline::getValueFromTable("template_field", "template_field_id", $fieldID, "fieldSelectName");

	return HEQCOnline::getValueFromTable($table, $id, $lkpValue, $desc);
}

//function to display one field at a time - used when most of the criterion is hardcoded, but there are still some generic parts
//20080311. Rebecca
function displayFieldForPrintedReport($fieldTable, $fieldKey, $fieldKeyValue, $fieldName, $templateName) {
//app_id set to zero when debugging to stop it inserting blank tables
//20081003. Nontutu
$app_id = 0;
	$sql =<<<SQL
		SELECT *
		FROM template_field
		WHERE template_name='$templateName'
		AND fieldName='$fieldName'
SQL;
	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	$fieldTitle = "";
	$fieldType = "";
	$fieldval = HEQConline::getValueFromTable($fieldTable, $fieldKey, $fieldKeyValue, $fieldName);
	$evalRes = true;

	while ($row = mysqli_fetch_array($rs)) {
		$fieldTitle = $row["fieldTitle"];
		$fieldType = $row["fieldType"];
		$templateFieldID = $row["template_field_id"];
		$fieldCondition = $row['fieldValidationCondition'];
	}

	// Show field only if it meets condition
	if ($fieldCondition != ""){
		$fieldCondition = HEQConline::convertForDocGen($fieldCondition, '$app_id');
		$evalStr = "return (($fieldCondition)?(true):(false));";
		$evalRes = eval($evalStr);

	}

	switch ($fieldType) {
		case "TEXTAREA" :	$val = simple_text2html($fieldval, "docgen");
							$displayField = "<b>$fieldTitle</b><br />$val<br /><br />";
							break;
		case "FILE" : 	$docName = ($fieldval != 0) ? HEQConline::getValueFromTable("documents", "document_id", $fieldval, "document_name") : "Document not uploaded";
						$displayField = "<br /><b>* $fieldTitle </b>$docName";
						break;
		case "RADIO" :  $val = HEQConline::displayLkpValue($templateFieldID, $fieldval);
						$displayField = "<b>$fieldTitle</b> $val<br /><br />";
						break;
		case "SELECT" :  $val = HEQConline::displayLkpValue($templateFieldID, $fieldval);
						$displayField = "<b>$fieldTitle</b> $val<br /><br />";
						break;
		default:	$displayField = "<b>$fieldTitle</b> $fieldval<br /><br />";
					break;
	}

	return ($evalRes) ? $displayField : "";
}

function displayCriterion1($app_id) {

	$Q1_1 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_1_comment", "accForm3-1_v2");
	$Q1_2 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_2_comment", "accForm3-1_v2");
	$Q1_3 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_3_comment", "accForm3-1_v2");
	$Q1_6 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_4_comment_v2", "accForm3-1_v2");
	$Q1_7 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_5_comment", "accForm3-1_v2");
	$Q1_8 = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_6_comment", "accForm3-1_v2");
	$Q1_9_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_9_yn");
	$Q1_9 = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $Q1_9_id, "lkp_yn_desc");
	$Q1_10_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_11_yn");
	$Q1_10 = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $Q1_10_id, "lkp_yn_desc");

	$prepmaterials_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_prepmaterials_doc", "accForm3-1_v2");
	$budget_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_budget_doc", "accForm3-1_v2");
	$elective_modules_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_elective_modules_doc", "accForm3-1_v2");
	$contract_arragement_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_contract_arragement_doc", "accForm3-1_v2");
	$prepmaterials_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_prepmaterials_doc", "accForm3-1_v2");
	$saqa_submission_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_saqa_submission_doc", "accForm3-1_v2");
	$outline_courses_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_outline_courses_doc", "accForm3-1_v2");
	$additional_doc = HEQCOnline::displayFieldForPrintedReport("Institutions_application", "application_id", $app_id, "1_additional_doc", "accForm3-1_v2");

	$otherLearningActivities = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "learning_activities_other_text"), "docgen");
	$otherModeDelivery = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery_other_text"), "docgen");


	$text =<<< TEXT
	<br />
	<b>1) PROGRAMME DESIGN: (Criterion 1)</b>
	<br /><br />
	$Q1_1
	$Q1_2
	$Q1_3
	<b>1.4 Provide the names of the modules/courses which lead to the programme - and for each course, specify:</b>
	<table width="100%">
		<tr>
			<td><b>Module name</b></td>
			<td><b>NQF Level of the module</b></td>
			<td><b>Credits per module</b></td>
			<td><b>Compulsory/optional</b></td>
			<td><b>Year (1, 2, 3, 4)</b></td>
			<td><b>Total credits per year   </b></td>
		</tr>
	</table>

TEXT;

	$innerSQL =<<< SQL
		SELECT * FROM appTable_1_prog_structure
		WHERE application_ref = $app_id
SQL;
	$innerRS = mysqli_query($this->getDatabaseConnection(), $innerSQL);

	while ($innerRow = mysqli_fetch_array($innerRS)) {
		$moduleName = HEQCOnline::docgenBlank($innerRow['course_name']);
		$nqfLevel = HEQCOnline::docgenBlank($innerRow['nqf_level']);
		$credits = HEQCOnline::docgenBlank($innerRow['fund_credits']);
		$comp_optional = HEQCOnline::docgenBlank($innerRow['course_type']);
		$year = HEQCOnline::docgenBlank($innerRow['year']);
		$totalCredits = HEQCOnline::docgenBlank($innerRow['core_credits']);


		$text .=<<< TEXT
	<table>
		<tr>
			<td>$moduleName</td>
			<td>$nqfLevel</td>
			<td>$credits</td>
			<td>$comp_optional</td>
			<td>$year</td>
			<td>$totalCredits</td>
		</tr>
	 </table>
TEXT;

	}

	$text .=<<< TEXT

	<br />

	<b>1.5 LEARNING ACTIVITIES:
	<br />
	Complete the following table for the whole programme:</b>
	<table width="100%">
		<tr>
			<td><b>Contact (Y/N)</b></td>
			<td><b>Distance (Y/N)</b></td>
			<td><b>Other (Y/N)</b></td>
			<td><b>Types of learning activities</b></td>
			<td><b>% Learning time</b></td>
		</tr>
	</table>
TEXT;

	$innerSQL =<<< SQL
		SELECT * FROM appTable_1_prog_struct_breakdown
		LEFT JOIN lkp_prog_struct_breakdown
		ON lkp_prog_struct_breakdown_ref = lkp_prog_struct_breakdown_id
		WHERE application_ref = $app_id
SQL;
	$innerRS = mysqli_query($this->getDatabaseConnection(), $innerSQL);

	while ($innerRow = mysqli_fetch_array($innerRS)) {

		$contactBox = ($innerRow['contact_checkbox'] == 1) ? "Yes" : "No";
		$distanceBox = ($innerRow['distance_checkbox'] == 1) ? "Yes" : "No";
		$otherBox = ($innerRow['other_checkbox'] == 1) ? "Yes" : "No";
		$percentage_learning = $innerRow['percentage_learning'];
		$percentLearning = HEQCOnline::docgenBlank($percentage_learning);
		$learningTypes = $innerRow['lkp_prog_struct_breakdown_desc'];
		$otherModeDelivery = HEQCOnline::docgenBlank ($learningTypes);
		$otherLearningActivities = HEQCOnline::docgenBlank ($learningTypes);


	$text .=<<< TEXT
		<table>
			<tr>
				<td>$contactBox</td>
				<td>$distanceBox</td>
				<td>$otherBox </td>
				<td>$learningTypes</td>
				<td>$percentLearning</td>
			</tr>
		</table>
TEXT;
		}


$text .=<<< TEXT
<table>
    	<tr>
				<td colspan="5"><b>If you selected "Other" as the mode of delivery in the third column of the table above, please give a detailed explanation in the box below:</b></td>
			</tr>
			<tr>
				<td colspan="5">$otherModeDelivery</td>
			</tr>
			<tr>
				<td colspan="5"><b>If you selected "Other" as a type of learning activity in the last row of the table above, please give a detailed explanation in the box below:</b></td>
			</tr>
			<tr>
				<td colspan="5">$otherLearningActivities</td>
			</tr>
	</table>
	<br /><br />
	$Q1_6
	$Q1_7
	$Q1_8
	<b>1.9 If the proposed programme is a professional degree, has approval been applied for from the relevant professional body? (Please upload letter of application or the letter of approval). </b>
	<br />
	$Q1_9
	<br /><br />
	<b>1.10 WORK PLACEMENT FOR EXPERIENTIAL LEARNING: <br />
	Does your programme have work placement / experiential learning?
	</b>
	<br />
	$Q1_10
	<br />
TEXT;

	$innerSQL =<<< SQL
		SELECT * FROM appTable_1_placement_work
		WHERE application_ref = $app_id
SQL;
	$innerRS = mysqli_query($this->getDatabaseConnection(), $innerSQL);

	while ($innerRow = mysqli_fetch_array($innerRS)) {

		$studyYears = HEQCOnline::docgenBlank ($innerRow['year_of_study']);
		$duration = HEQCOnline::docgenBlank ($innerRow['duration_placement']);
		$creditValue = HEQCOnline::docgenBlank ($innerRow['credit_value']);
		$outcomes = HEQCOnline::docgenBlank ($innerRow['learning_outcomes_textFLD']);
		$assessment = HEQCOnline::docgenBlank ($innerRow['ass_methods_textFLD']);
		$monitoring = HEQCOnline::docgenBlank ($innerRow['monitor_procs_placement_textFLD']);
		$placement = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $innerRow['placement_responsible_selectFLD'], "lkp_yn_desc");
		$who = HEQCOnline::docgenBlank ($innerRow['placement_responsible_person']);


   $text .=<<< TEXT
	<table>
		<tr>
			<td>Year(s) of study when experiential learning takes place:</td>
			<td>$studyYears</td>
		</tr>
		<tr>
			<td>Duration of the placement:</td>
			<td>$duration</td>
		</tr>
		<tr>
			<td>Credit Value:</td>
			<td>$creditValue</td>
		</tr>
		<tr>
			<td>Expected learning outcomes:</td>
			<td>$outcomes</td>
		</tr>
		<tr>
			<td>Assessment methods:</td>
		<td>$assessment</td>
		</tr>
		<tr>
			<td>Monitoring procedures:</td>
			<td>$monitoring</td>
		</tr>
		<tr>
			<td>Placement is an institutional responsibility?</td>
			<td>$placement</td>
		</tr>
		<tr>
			<td>Who is responsible? (only if answered no in previous question)</td>
			<td>$who</td>
		</tr>
	</table>
TEXT;

	}


	$text .=<<< TEXT
	<br />
	$prepmaterials_doc
	$budget_doc
	$contract_arragement_doc
	$elective_modules_doc
	$saqa_submission_doc
	$outline_courses_doc
	$additional_doc
TEXT;

	echo $text;
}

function docgenBlank($var_val){

	$val = ($var_val =="") ? "-" : $var_val	;
	return $val;
}

function displayRegistrarDeclarationDocGen($app_id, $critNum, $template) {
	$signed = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, $critNum."_registrarDeclaration_lkp");
	$signedBy = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, $critNum."_registrarDeclaration_userRef");
	$signedTitle = HEQCOnline::getValueFromTable("users", "user_id", $signedBy, "title_ref");
	$signedByStr = HEQCOnline::getValueFromTable("lkp_title", "lkp_title_id", $signedTitle, "lkp_title_desc")." ".HEQCOnline::getValueFromTable("users", "user_id", $signedBy, "name")." ".HEQCOnline::getValueFromTable("users", "user_id", $signedBy, "surname");
	$signedDate = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, $critNum."_registrarDeclaration_date");

	$signedText = ($signed == "2") ? "<br /><br />Above declaration signed by $signedByStr on $signedDate" : $signed;
	$minimumStandards = "<b>Minimum standards</b><br/>".HEQCOnline::getTextContentWithoutProgramming($template, "minimumStandards", "docgen");
//	$declarationText = HEQCOnline::getTextContentWithoutProgramming($template, "publicRegistrarDeclaration", "docgen");

	$displayBlurb =<<< DISPLAY
		<br /><br />
		$minimumStandards
		<br /><br />
//		$declarationText
		$signedText
		<br /><br />
DISPLAY;
	echo $displayBlurb;
}

function displaySectionC($app_id) {
	$intro =<<< TEXT
	<br />
	<b>C. PROGRAMMES OFFERED THROUGH DISTANCE EDUCATION </b>
	<br /><br />
TEXT;
	echo $intro;

	HEQConline::displayTemplateRTFReport($app_id,"accForm30_v2");
}


function displaySectionB($app_id) {
	$prov_type = HEQCOnline::checkAppPrivPubl($app_id);
	$intro =<<< TEXT
	<br />
	<span align="center"><b>B) APPLICATION FORM FOR PROGRAMME ACCREDITATION</b></span>
	<br /><br />
TEXT;
	echo $intro;
	HEQConline::displayCriterion1($app_id);
	echo "<page />";
	echo "<b><u>2. STUDENT RECRUITMENT, ADMISSION AND SELECTION: (Criterion 2)</u></b>";
	HEQConline::displayTemplateRTFReport($app_id,"accForm6_v2");
	echo "<page />";
	echo "<b><u>3. STAFF QUALIFICATIONS: (Criterion 3)</u></b>";

	//if public, display declaration, else display per child
	if ($prov_type == 1 ) {
		HEQConline::displayTemplateRTFReportperChild("accForm8_2_v2","application_ref",$app_id);
	} else {
		HEQCOnline::displayRegistrarDeclarationDocGen($app_id, "3", "accForm8_1_v2");
	}

	echo "<page />";
	echo "<b><u>4. STAFF SIZE AND SENIORITY: (Criterion 4) </u></b>";
	//if public, display declaration, else display per child
	if ($prov_type == 1 ) {
	    HEQConline::displayTemplateRTFReportperChild("accForm8b_2_v2","application_ref",$app_id);
	} else {
	          HEQCOnline::displayRegistrarDeclarationDocGen($app_id, "4", "accForm8b_v2");
	}
	echo "<page />";
	echo "<b><u>5. TEACHING AND LEARNING STRATEGY: (Criterion 5)</u></b>";
	HEQConline::displayTemplateRTFReport($app_id,"accForm9_v2");
	echo "<page />";
	echo "<b><u>6. ASSESSMENT: (Criterion 6)</u></b>";
	HEQConline::displayTemplateRTFReport($app_id,"accForm14_v2");
	echo "<page />";
	echo "<b><u>7. INFRASTRUCTURE AND LIBRARY RESOURCES: (Criterion 7)</u></b>";
	//if public, display declaration, else display per child
	if ($prov_type == 1 ) {
	    HEQConline::displayTemplateRTFReportperChild("accForm15_2_v2","application_ref",$app_id);
	}	else {
	    HEQCOnline::displayRegistrarDeclarationDocGen($app_id, "7", "accForm15_v2");
	}
	echo "<page />";
	echo "<b><u>8. PROGRAMME ADMINISTRATIVE SERVICES: (Criterion 8)</u></b>";
	//if public, display declaration, else display per child
	if ($prov_type == 1 ) {
		
		echo "<br /><hr />".HEQConline::displayTemplateRTFReportperChild("accForm17_2_v2","application_ref",$app_id);
		HEQConline::displayTemplateRTFReport($app_id,"accForm17_v2");
	}	else {
	    HEQCOnline::displayRegistrarDeclarationDocGen($app_id, "8", "accForm17_v2");
	}
	echo "<page />";

	$nqf_level = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");

	//check if Criterion 9 needs to be displayed (only for postgrad programmes)
	$nqf_level = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");
	if ($nqf_level >= 3) {
		echo "<b><u>9. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS: (Criterion 9)</u></b>";
		HEQConline::displayTemplateRTFReportperChild("accForm19_2_v2","application_ref",$app_id);
		echo "<page />";
	}

	//check if Criterion 10 / Section C needs to be displayed (only for distance programmes, except UNISA)
	$mode_delivery = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
	$hei_id = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	if (($mode_delivery == 2 || $mode_delivery == 6) && ($hei_id != 54)) {
		echo "<b><u>C. PROGRAMMES OFFERED THROUGH DISTANCE EDUCATION</u></b>";
			HEQConline::displayTemplateRTFReport($app_id,"accForm30_v2");
			echo "<page />";
	}

}

function displaySectionA($app_id) {
//echo $app_id."******";
	$programmeName = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");
	$modeDelivery = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
	$modeDeliveryOther = ($modeDelivery == 5) ? ": ".DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery_specify_char") : "";
	$modeDeliveryStr = DBConnect::getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $modeDelivery, "lkp_mode_of_delivery_desc");
	$intro =<<< INTRO
		<br /><br />
		<b>A) PROGRAMME INFORMATION: <u>$programmeName</u></b>
		<br />
		<b>Mode of delivery: </b>$modeDeliveryStr <i>$modeDeliveryOther</i>
		<br /><br />
INTRO;
	echo $intro;

	$intro1 =<<<INTRO1
		<table width="100%">
			<tr>
				<td><b>Site</b></td>
				<td><b>Contact name</b></td>
				<td><b>Contact surname</b></td>
				<td><b>Contact email</b></td>
				<td><b>Contact tel. no.</b></td>
				<td><b>Contact fax. no.</b></td>
			</tr>
INTRO1;
	echo $intro1;

	$SQL =<<< sSQL
			SELECT ia_criteria_per_site_id, institutional_profile_sites.*
			FROM ia_criteria_per_site, institutional_profile_sites
			WHERE application_ref = $app_id
			AND institutional_profile_sites_id = institutional_profile_sites_ref
sSQL;

	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if (mysqli_num_rows($rs)) {
		while ($row = mysqli_fetch_array($rs)){
			$site_name = $row["site_name"].", ".$row["location"]." (".$row['establishment'].")";
			$contact_name = $row["contact_name"];
			$contact_surname = $row["contact_surname"];
			$contact_email = $row["contact_email"];
			$contact_nr = $row["contact_nr"];
			$contact_fax_nr = $row["contact_fax_nr"];

			$intro =<<< INTRO
				<tr>
					<td>$site_name </td>
					<td>$contact_name</td>
					<td>$contact_surname</td>
					<td>$contact_email</td>
					<td>$contact_nr</td>
					<td>$contact_fax_nr</td>
				</tr>
INTRO;
		echo $intro;
		}
	}

	echo "</table>";

	HEQConline::displayTemplateRTFReport($app_id,"accForm1_v2");

	echo "<page />";

}



//***Rebecca::2008-02-18 - displays populated application form

function displayPopulatedApplicationFormPerCriteria ($app_id=0, $type) {

	$intro =<<<INTRO
	<br />
	<b>APPLICATION FORM FOR PROGRAMME ACCREDITATION: </b>
	<br /><br />
INTRO;
	echo $intro;
	HEQConline::displaySectionA($app_id);
	HEQConline::displaySectionB($app_id);

	//check if Section C needs to be displayed (only for distance programmes, not Unisa)
	$mode_delivery = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
	$HEI_id = HEQCOnline::getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	if (($mode_delivery == 2 || $mode_delivery == 6) && ($HEI_id != 54)) {
		HEQConline::displaySectionC($app_id);
	}
}

function getTemplateTableAndKey($template){
	$tmpl_array = array();

	$wkfSql =<<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;
	$wkfrs = mysqli_query($this->getDatabaseConnection(), $wkfSql);
	if ($wkfrow = mysqli_fetch_array($wkfrs)){
		$tmpl_array[$template]["dbTableName"] = $wkfrow["template_dbTableName"];
		$tmpl_array[$template]["dbTableKeyField"] = $wkfrow["template_dbTableKeyField"];
	}

	return $tmpl_array;
}

// Robin 2008-02-25
// Some templates may be child templates - thus they will return many records for the parent key.
function displayTemplateRTFReportperChild($template, $parent_field, $parent_val){
	// Get the base table name and key for the template.
	$wkfSql =<<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;

	$wkfrs = mysqli_query($this->getDatabaseConnection(), $wkfSql);
	if ($wkfrow = mysqli_fetch_array($wkfrs)){
		$templateTable = $wkfrow["template_dbTableName"];
		$templateTableKey = $wkfrow["template_dbTableKeyField"];
	}

// Get the IDs for each of the child records and run the template report.
	$childsql =<<<CHILDSQL
		SELECT $templateTableKey
		FROM $templateTable
		WHERE $parent_field = '$parent_val'
CHILDSQL;

	$childrs = mysqli_query($this->getDatabaseConnection(), $childsql);
	while ($childrow = mysqli_fetch_array($childrs)){
		if ($templateTableKey == "ia_criteria_per_site_id") {
			$site_id = HEQCOnline::getValueFromTable("ia_criteria_per_site", "ia_criteria_per_site_id", $childrow["$templateTableKey"], "institutional_profile_sites_ref");
			$siteName = HEQCOnline::getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id, "site_name");
		}
		echo "<br /><br /><br /><b><u>Information for site of delivery: $siteName</u></b>";
		HEQCOnline::displayTemplateRTFReport($childrow["$templateTableKey"],$template, $parent_val);

	}

}

//Rebecca 2008-03-12
//Transforms strings (validations) using $this or HEQCOnline which can be used by docgen
function convertForDocGen($str, $theID) {
	$match = preg_match('/(HEQCOnline::dbTableInfoArray|\$this->dbTableInfoArray).+dbTableCurrentID/', $str);
	if ($match == 1) {
		$str = preg_replace('/(HEQCOnline::dbTableInfoArray|\$this->dbTableInfoArray).+?dbTableCurrentID/', $theID, $str);
		HEQCOnline::convertForDocGen($str, $theID);
	}

	$match = preg_match('/\$currentTableId/', $str);
	if ($match == 1) {
		$str =  preg_replace('/\$currentTableId/', $theID, $str);
		HEQCOnline::convertForDocGen($str, $theID);
	}

	return $str;
}


// Robin 2008-02-22
// Format templates for rtf report by reading information from the template_field table.

function displayTemplateRTFReport($id_val,$template, $currentid=""){
	// Get the base table name and key for the template.
	$wkfSql =<<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;

	$wkfrs = mysqli_query($this->getDatabaseConnection(), $wkfSql);
	if ($wkfrow = mysqli_fetch_array($wkfrs)){
		$templateTable = $wkfrow["template_dbTableName"];
		$templateTableKey = $wkfrow["template_dbTableKeyField"];
	}
	
	// Table and key for the data record is required.
	if (mysqli_num_rows($wkfrs) == 0 || $templateTable == "" || $templateTableKey == "" ){
		return false;
	}


	/*
		Get the database fields for the template.  At the moment the assumption is that all fields for
		a specific template will be displayed in the report. A linking report table could be
		set up to list the report name and fields required in the report (which then links to
		template_field to obtain the relevant information for the fields)
	*/

	$rtfhtml = "";

	$sql =<<<FIELDS
			SELECT * FROM template_field
			WHERE template_name = '$template'
			AND fieldDBconnected = 1
			ORDER BY fieldOrder
FIELDS;

//added 3 infront of _registrarDeclaration
	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	while ($row = mysqli_fetch_array($rs)){
		$fieldName = $row['fieldName'];
		if (strpos($fieldName, '%_registrarDeclaration_%') === false) {
		
			$fieldCondition = $row['fieldValidationCondition'];
			$evalRes = false;

			// Show field only if it meets condition
			if ($fieldCondition != ""){
				if($template == 'accForm19_2_v2'){
					$fieldCondition = HEQConline::convertForDocGen($fieldCondition, $currentid);
				}else{
					$fieldCondition = HEQConline::convertForDocGen($fieldCondition, $id_val);
				}
				$evalStr = "return (($fieldCondition)?(true):(false));";
				$evalRes = eval($evalStr);
			} else {
				$evalRes = true;
			}
			
			if (($evalRes == true) || ($evalRes == 1)) {
				// get field value and format it according to its type.
				$fieldTitle = $row["fieldTitle"];
				$fieldType = $row["fieldType"];
				switch ($fieldType){
					case "TEXTAREA":
						$fieldval = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$val = simple_text2html($fieldval, "docgen");
						$fldhtml = "<br /><br /><b>$fieldTitle</b><br />$val";						
						break;
					case "MULTIPLE":
							$fldhtml = "";
							break;
					case "HIDDEN":
							$fldhtml = "";
							break;
					case "PASSWORD":
							$fldhtml = "";
							break;
					case "FILE" :
							$val = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
							$docName = ($val != 0) ? HEQConline::getValueFromTable("documents", "document_id", $val, "document_name") : "Document not uploaded";
							$fldhtml = "<br/><b>* $fieldTitle </b>$docName";
							break;
					case "CHECKBOX":
						$fieldval = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$val = DBConnect::getValueFromTable($row["fieldSelectTable"], $row["fieldSelectID"], $fieldval, $row["fieldSelectName"]);
						$fldhtml = "<br /><br /><b>$fieldTitle</b>&nbsp;$val";
						break;
					case "RADIO":
						$fieldval = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$val = DBConnect::getValueFromTable($row["fieldSelectTable"], $row["fieldSelectID"], $fieldval, $row["fieldSelectName"]);
						$fldhtml = "<br /><br /><b>$fieldTitle</b>&nbsp;$val";
						break;
					case "RADIO:VERTICAL":
						$fieldval = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$val = DBConnect::getValueFromTable($row["fieldSelectTable"], $row["fieldSelectID"], $fieldval, $row["fieldSelectName"]);
						$fldhtml = "<br /><br /><b>$fieldTitle</b>&nbsp;$val";
						break;
					case "SELECT":
						$fieldval = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$val = DBConnect::getValueFromTable($row["fieldSelectTable"], $row["fieldSelectID"], $fieldval, $row["fieldSelectName"]);
						$fldhtml = "<br /><br /><b>$fieldTitle</b>&nbsp;$val";
						break;
					default:
						$val = DBConnect::getValueFromTable($templateTable, $templateTableKey, $id_val, $row["fieldName"]);
						$fldhtml = "<br /><br /><b>$fieldTitle</b>&nbsp;$val";
						break;
				} //end case
				$rtfhtml .=  $fldhtml;
			} else {
				$rtfhtml .= "";
			} //end if-else
		} // end if
	} // end while
	echo $rtfhtml;


}

function getSitesOfDeliveryList($app_id) {
	$sites_SQL = "SELECT * FROM lkp_sites, Institutions_application WHERE application_ref = application_id AND application_id=".$app_id;
	$sites_rs = mysqli_query($this->getDatabaseConnection(), $sites_SQL);
	$delimiter = '';

	while($sites_row = mysqli_fetch_array($sites_rs))
	{
		echo $delimiter;
		echo dbConnect::getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $sites_row["sites_ref"], "site_name");
		$delimiter = ", ";
	}
}

	function getProgrammeDetailsPerInstitution($p_RS, $format) {
		if (mysqli_num_rows($p_RS) > 0) {
		switch ($format) {
			case "html" : 	echo "<table border='0' cellpadding='2' cellspacing='2' width='100%' align='center'>";
							echo "<tr class='onblue'><td colspan='7' align='right'>Total accredited applications: ".mysqli_num_rows($p_RS)."</td></tr>";
						break;
			case "docgen" : 	echo "<table border='t,b,r,l' width='140%'>";
							echo "<tr><td colspan='7' align='right'>Total accredited applications: ".mysqli_num_rows($p_RS)."</td></tr>";
						break;
		}//end switch

		while ($p_row = mysqli_fetch_array($p_RS)) {

		$prog_name = $p_row["program_name"]."<br />- ".$p_row["CHE_reference_code"];
		$prog_designation = $p_row["designation"];
		$prog_mode = dbConnect::getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $p_row["mode_delivery"], "lkp_mode_of_delivery_desc");
		$prog_duration = $p_row["expected_min_duration"];
		$prog_nqf = dbConnect::getValueFromTable("NQF_level", "NQF_id", $p_row["NQF_ref"], "NQF_level");
		$prog_credits = $p_row["num_credits"];
		$prog_cesm = dbConnect::getValueFromTable("SpecialisationCESM_code1", "CESM_code1", $p_row["CESM_code1"], "Description");
		$prog_status = dbConnect::getValueFromTable("lkp_desicion", "lkp_id", $p_row["AC_desision"], "lkp_title");
		$prog_date_ac = $p_row["AC_Meeting_date"];
		//$prog_conditions = simple_text2html($p_row["AC_conditions"], $format);
		$prog_conditions = $p_row["AC_conditions"];

		switch ($format) {
			case "html" : 	$programme_details_html =<<<TXT
				<tr>
				<td class='onblueb'>Programme name</td>
				<td class='onblueb'>Designation</td>
				<td class='onblueb' width='10%'>Mode</td>
				<td class='onblueb'>Duration</td>
				<td class='onblueb'>NQF level</td>
				<td class='onblueb'>No. of credits</td>
				<td class='onblueb'>CESM category</td>
				</tr>

				<tr class='onblue'>
				<td rowspan='4' valign='top'>$prog_name</td>
				<td>$prog_designation</td>
				<td>$prog_mode</td>
				<td>$prog_duration</td>
				<td>$prog_nqf</td>
				<td>$prog_credits</td>
				<td>$prog_cesm</td>
				</tr>
TXT;
				echo $programme_details_html;
				echo "<tr class='onblue'><td valign='top' class='onblueb'>Sites of delivery</td><td colspan='5' valign='top'>";
				HEQConline::getSitesOfDeliveryList($p_row["application_id"]);
				echo "</td></tr>";

				$programme_outcomes_html =<<<TXT
					<tr>
					<td class='onblueb'>Status</td>
					<td class='onblueb'>Date</td>
					<td colspan='4' class='onblueb'>Conditions/comments</td>
					</tr>

					<tr class='onblue'>
					<td valign='top'>$prog_status</td>
					<td valign='top'>$prog_date_ac</td>
					<td colspan='4'>$prog_conditions</td>
					</tr>

				<tr><td colspan='7' height='1px' color='#000000'></td></tr>
TXT;
				echo $programme_outcomes_html;
				break;

			case "docgen" :	$programme_details_html =<<<TXT
					<tr>
						<td bgcolor='5'><b>Programme name</b></td>
						<td bgcolor='5'><b>Designation</b></td>
						<td bgcolor='5' width='10%'><b>Mode</b></td>
						<td bgcolor='5'><b>Duration</b></td>
						<td bgcolor='5'><b>NQF level</b></td>
						<td bgcolor='5'><b>No. of credits</b></td>
						<td bgcolor='5'><b>CESM category</b></td>
					</tr>

					<tr>
						<td rowspan='4' valign='top'>$prog_name</td>
						<td>$prog_designation</td>
						<td>$prog_mode</td>
						<td>$prog_duration</td>
						<td>$prog_nqf</td>
						<td>$prog_credits</td>
						<td>$prog_cesm</td>
					</tr>
TXT;

				echo $programme_details_html;
				echo "<tr><td valign='top'><b>Sites of delivery</b></td><td colspan='5'>";
				HEQConline::getSitesOfDeliveryList($p_row["application_id"]);
				echo "</td></tr>";
				$programme_outcomes_html =<<<TXT
					<tr>
						<td><b>Status</b></td>
						<td><b>Date</b></td>
						<td colspan='4'><b>Conditions/comments</b></td>
					</tr>

					<tr>
						<td valign='top'>$prog_status</td>
						<td valign='top'>$prog_date_ac</td>
						<td colspan='4'>$prog_conditions</td>
					</tr>

TXT;
				echo $programme_outcomes_html;
				break;

			}//end switch
		}//end while
	echo "</table>";
	}//end if
	else {
	 echo " <i>This institution has no accredited applications.</i><br />";
	}
}

	function getFullContactName($institution_ref) {
		$contact_title = dbConnect::getValueFromTable("lkp_title", "lkp_title_id", dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_title_ref"), "lkp_title_desc");
		$contact_name = dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_name")." ".dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_surname");
		$contact_full_name = $contact_title." ".$contact_name;
		return $contact_full_name;
	}

	function getSitesOfDeliveryPerInstitution($s_RS, $format) {
		if (mysqli_num_rows($s_RS) > 0) {
			switch ($format) {
				case "html" 	:	echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
									echo "<tr class='onblue'><td colspan='4' align='right'>Total sites of delivery: ".mysqli_num_rows($s_RS)."</td></tr>";
								break;
				case "docgen"	:	echo "<table border='t,b,l,r' width='140%'>";
									echo "<tr><td colspan='4' align='right'>Total sites of delivery: ".mysqli_num_rows($s_RS)."</td></tr>";
								break;
			}//end switch

				while ($s_row = mysqli_fetch_array($s_RS))
				{
					$add_sites_name = $s_row["site_name"];
					$add_sites_contact = HEQConline::getFullContactName($s_row["institution_ref"]);
					$add_sites_tel = $s_row["contact_nr"];
					$add_sites_email = $s_row["contact_email"];
					$add_sites_phys = simple_text2html($s_row["address"], $format);
					$add_sites_post = simple_text2html($s_row["postal_address"], $format);
					switch ($format)
					{
						case "html" 	:
							$additional_sites_html =<<<TXT
								<tr class='onblue'>
								<td valign='top' width='20%' class='onblueb'>Site name</td>
								<td class='onblueb' width='10%'>Contact</td>
								<td class='onblueb'>Contact no.</td>
								<td class='onblueb'>Email</td>
								</tr>

								<tr class='onblue'>
								<td rowspan='3' valign='top'>$add_sites_name</td>
								<td>$add_sites_contact</td>
								<td>$add_sites_tel</td>
								<td>$add_sites_email</td>
								</tr>

								<tr class='onblue'><td valign='top' class='onblueb'>Physical address:</td><td colspan='2'>$add_sites_phys</td></tr>
								<tr class='onblue'><td valign='top' class='onblueb'>Postal address:</td><td colspan='2'>$add_sites_post</td></tr>
TXT;
						echo $additional_sites_html;
						break;
					case "docgen" 	:
						$additional_sites_html =<<<TXT
								<tr>
									<td bgcolor="5"><b>Site name</b></td>
									<td bgcolor="5"><b>Contact</b></td>
									<td bgcolor="5"><b>Contact no.</b></td>
									<td bgcolor="5"><b>Email</b></td>
								</tr>

								<tr>
									<td rowspan='3'>$add_sites_name</td>
									<td>$add_sites_contact</td>
									<td>$add_sites_tel</td>
									<td>$add_sites_email</td>
								</tr>

								<tr><td bgcolor="5"><b>Physical address:</b></td><td colspan='2'>$add_sites_phys</td></tr>
								<tr><td bgcolor="5"><b>Postal address:</b></td><td colspan='2'>$add_sites_post</td></tr>
TXT;
						echo $additional_sites_html;
						break;
					}//end switch
				}//end while
			echo "</table>";
		}//end if
		else {
		 echo "<i>No sites exist for this institution</i>";
		}//end else
	}


	function getSitesOfDeliveryPerApplication($app_id,$type="int") {
		$SQL =<<<SQL
			SELECT ia_criteria_per_site_id, institutional_profile_sites.*
			FROM ia_criteria_per_site, institutional_profile_sites
			WHERE application_ref = $app_id
			AND institutional_profile_sites_id = institutional_profile_sites_ref
SQL;

                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		
		$s_RS = mysqli_query($conn, $SQL);
		$additional_sites_html = "";
		$count = 0;

		if (mysqli_num_rows($s_RS) > 0) {
			$additional_sites_html = ($type == 'ext') ? "" : "<ol>";
			while ($s_row = mysqli_fetch_array($s_RS))
			{
				$count++;
				$name = $s_row["site_name"];
				$location = $s_row["location"];
				
				switch ($type){
				case 'ext':
					$additional_sites_html .= ($count > 1) ? "<br /><br />" : "";
					$additional_sites_html .= "$name<br />";
					if ($s_row["address"] > ''){
						$phys_address = trim(simple_text2html($s_row["address"], "docgen"));
						$additional_sites_html .= $phys_address;
						//$additional_sites_html .= $phys_address . "<br />";
					} else {
						$additional_sites_html .= $location;
						//$additional_sites_html .= $location . "<br />";
					}
					break;
				default:
					$additional_sites_html .= "<li>$name - $location </li>";
					break;
				}
			}
			$additional_sites_html .= ($type == 'ext') ? "" : "</ol>";
			return $additional_sites_html;
		}
		else {
		 return "<i>No sites have been selected for this application</i>";
		}
	}

	function getInstitutionDetails($row, $format) {
		$HEI_name = $row["HEI_name"];
		$HEI_type = dbConnect::getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", dbConnect::getValueFromTable("HEInstitution", "HEI_id", $row["HEI_id"], "priv_publ"), "lnk_priv_publ_desc");
		$HEI_mode = dbConnect::getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", dbConnect::getValueFromTable("institutional_profile", "institution_ref", $row["HEI_id"], "mode_delivery"), "lkp_mode_of_delivery_desc");
		$HEI_main_site_name = dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "site_name");
		$HEI_main_site_contact = HEQConline::getFullContactName($row["HEI_id"]."AND main_site=1");
		$HEI_main_site_tel = dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_nr");
		$HEI_main_site_fax = dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_fax_nr");
		$HEI_main_site_email = dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_email");
		$HEI_main_site_phys = simple_text2html(dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "address"));
		$HEI_main_site_post = simple_text2html(dbConnect::getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "postal_address"), $format);

		switch ($format) {
			case "html" : $inst_details_html =<<<TXT
						<table border='0' cellpadding='2' cellspacing='2' width='50%'>

							<tr class='onblue'><td width='20%' valign='top' class='onblueb'>Institution name</td><td width='30%'>$HEI_name</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Type</td><td>$HEI_type</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Mode</td><td>$HEI_mode</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Main site name</td><td>$HEI_main_site_name</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Contact name</td><td>$HEI_main_site_contact</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Telephone number</td><td>$HEI_main_site_tel</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb' valign='top'>Fax no.</td><td>$HEI_main_site_fax</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Email</td><td>$HEI_main_site_email</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Physical address</td><td>$HEI_main_site_phys</td></tr>
							<tr class='onblue'><td valign='top' class='onblueb'>Postal address</td><td>$HEI_main_site_post</td></tr>

						</table>
TXT;
				break;
			case "docgen" :  $inst_details_html =<<<TXT
							<table border='0'>
								<tr><td width='20%'><b>Institution name:</b></td><td>$HEI_name</td></tr>
								<tr><td><b>Type:</b></td><td>$HEI_type</td></tr>
								<tr><td><b>Mode:</b></td><td>$HEI_mode</td></tr>
								<tr><td><b>Main site name:</b></td><td>$HEI_main_site_name</td></tr>
								<tr><td><b>Contact name:</b></td><td>$HEI_main_site_contact</td></tr>
								<tr><td><b>Telephone number:</b></td><td>$HEI_main_site_tel</td></tr>
								<tr><td><b>Fax no.:</b></td><td>$HEI_main_site_fax</td></tr>
								<tr><td><b>Email:</b></td><td>$HEI_main_site_email</td></tr>
								<tr><td><b>Physical address:</b></td><td>$HEI_main_site_phys</td></tr>
								<tr><td><b>Postal address:</b></td><td>$HEI_main_site_post</td></tr>
							</table>
TXT;
				break;
		}

		return $inst_details_html;

	}

	function displayReportCoverPage($title) {
		$date = date("j F Y");
		$displayReportCoverPage =<<< TXT
			<table border="l,r" width="100%">

			<tr><td>
			<img src="docgen/images/header.png" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />
			</td></tr>

			<tr>
			<td>
			<br /><br /><br />
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

			<p align="center"/><font size="24" color="#000000" align="center">HEQC-online Accreditation System</font>
			<p align="center"/><br /><font size="26" color="#50719c" align="center"><b>$title</b></font>
			<p align="center"/><br /><br /><font size="16" color="#000000" align="center"><i>Generated on $date</i></font>
			<br /><br />
			<p align="center"/><br /><br /><font size="20" color="#000000" align="center">Council on Higher Education</font>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

			</td>
			</tr>

			<tr>
			<td valign="bottom"><img src="docgen/images/footer.png" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>
			</tr>
			</table>
TXT;
		echo $displayReportCoverPage;
	}

	function displayGeneralPageSetup($title, $layout="") {
		//echo ($layout == "landscape") ? "<section landscape='yes'/>\n" : "";
		$displayGeneralPageSetup =<<<TXT
			<header><b>HEQC-online Accreditation System - $title</b></header>
			<footer><table border="0" width="100%"><tr><td align="left">
			<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.png" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />
			</td></tr></table></footer>
TXT;
		echo $displayGeneralPageSetup;
	}

//***end html/docgen functions

function listEvaluatorReports($app_id) {
	$evalReportsArray = array();
	$eSQL = "SELECT * FROM evalReport ";
	$eSQL .= "WHERE application_ref=".$app_id;
	$eRS = mysqli_query($this->getDatabaseConnection(), $eSQL);

	while ($eRow = mysqli_fetch_array($eRS)) {
		$evalReports = new octoDoc($eRow['evalReport_doc']);
		$evalReportsLink = (($eRow['evalReport_doc'] != "") && ($eRow['evalReport_doc'] != 0)) ? "-  <a href='".$evalReports->url()."' target='_blank'>".$evalReports->getFilename()."</a><br>" : "";
		array_push($evalReportsArray, $evalReportsLink);

		$finalEvalDoc = new octoDoc($eRow['application_sum_doc']);
	}
	return $evalReportsArray;
}

function getFinalReport_id($app_id) {
	$SQL = "SELECT * FROM evalReport WHERE application_ref=".$app_id." AND do_summary=2";
	$RS  = mysqli_query($this->getDatabaseConnection(), $SQL);
	if ($row = mysqli_fetch_array($RS)) {
		return $row["application_sum_doc"];
	}
	else
		return 0;
}

function displayApplicationRequests($app_id){
	//displays drafts if within workflow; shows sent requests only if viewing from application status report
	$where = ($this->view == 1) ? " AND request_status=2 " : "";

	$sql =<<<sqlrept
		SELECT *
		FROM appTable_requests
		WHERE application_ref = $app_id
		$where
		ORDER BY appTable_requests_id
sqlrept;
	$rs = mysqli_query($this->getDatabaseConnection(), $sql);

	if (mysqli_num_rows($rs) > 0){
		while ($row = mysqli_fetch_array($rs)){
			$tableHead =<<< tabTitle
					<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td class='oncolourb'>Edit</td>
						<td class='oncolourb'>Date<br>requested</td>
						<td class='oncolourb'>To</td>
						<td class='oncolourb'>From</td>
						<td class='oncolourb'>Status</td>
						<td class='oncolourb'>Response Date</td>
						<td class='oncolourb'>Response documents</td>
					</tr>
tabTitle;
			$to = $this->getValueFromTable("users","user_id",$row["user_to_ref"],"name")." ".$this->getValueFromTable("users","user_id",$row["user_to_ref"],"surname")."<br>".$this->getValueFromTable("users","user_id",$row["user_to_ref"],"email");
			$from = $this->getValueFromTable("users","user_id",$row["user_from_ref"],"name")." ".$this->getValueFromTable("users","user_id",$row["user_from_ref"],"surname");
			$req_id = $row["appTable_requests_id"];
			$req_status = $this->getValueFromTable("lkp_request_status", "lkp_request_status_id", $row["request_status"], "lkp_request_status_desc");
			$edit = ($row["request_status"] == 1) ? '<a href="javascript:setRequest('.$req_id.');"><img src="images/ico_change.gif" border="no" alt="Edit"></a>' : "&nbsp;";

			$doc_link = "&nbsp;";
			$eDoc = new octoDoc($row['response_doc']);
			if ($eDoc->isDoc()) {
				$doc_link = '<a href="'.$eDoc->url().'" target="_blank">'.$eDoc->getFilename().'</a>';
			}

			$tableData =<<< DATA
				<tr class='onblue'>
					<td>$edit</td>
					<td>$row[request_date]</td>
					<td>$to</td>
					<td>$from</td>
					<td>$req_status</td>
					<td>$row[response_date]</td>
					<td>$doc_link</td>
				</tr>
				<tr class='onblue'>
					<td>&nbsp;</td>
					<td class='oncolourb'>Request</td>
					<td colspan="5">$row[request_text]</td>
				</tr>
				<tr class='onblue'>
					<td>&nbsp;</td>
					<td class='oncolourb'>Response</td>
					<td colspan="5">$row[response_text]</td>
				</tr>
				<tr><td colspan="7"><hr></td></tr>
DATA;
			$entireTable =<<< OUTPUT
				$tableHead
					$tableData
				</table>
OUTPUT;
			echo $entireTable;
		}
	} else {
		$entireTable =<<< OUTPUT
			<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td>No previous requests for information.</td>
				</tr>
			</table>
OUTPUT;
		echo $entireTable;
	}
}

function getInstitutionAdministrator($app_id=0, $inst="", $reacc_app_id=0){

	// NOTE: Getting institution from an accreditation application
	if ($app_id > 0){
		$inst = $this->getValueFromTable("Institutions_application","application_id",$app_id,"institution_id");
	}

	// NOTE: Getting institution from a RE_ACCREDITATION application
	if ($reacc_app_id > 0){
		$inst = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_app_id,"institution_ref");
	}
	
	$sql =<<<adminSQL
		SELECT user_id
		FROM users, sec_UserGroups
		WHERE user_id = sec_user_ref
		AND active = 1
		AND sec_group_ref = 4
		AND institution_ref = $inst
adminSQL;

//echo "<br><br><b>Administrator:</b>".$sql."<br><br>";
	$rs = mysqli_query($this->getDatabaseConnection(), $sql) or die($sql . ": " . mysqli_error());
	$n = mysqli_num_rows($rs);
	if ($n == 0){
		$adm_arr = array(0,"No active user is assigned as Institutional Administrator for this institution.");
	}
	if ($n == 1){
		$row = mysqli_fetch_array($rs);
		$adm_arr = array($row["user_id"],"Institutional Administrator");
	}
	if ($n > 1){
		$adm_arr = array(0,"More than one Institutional Administrator exists. Please notify HEQC-Online Support to attend to this immediately.  There should only be one Institutional Administrator.");
	}

	return $adm_arr;
}

//2007-01-02: Rebecca - performs the required action on a gridShowRowByRow
function getCMD_action($cmd) {
		switch ($cmd[0]) {
			case "new":
				if (isset($cmd[6]) && isset($cmd[7])){
					$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5], $cmd[6], $cmd[7]);
				} elseif (isset($cmd[4]) && isset($cmd[5])){
					$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5]);
				} else {
					$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				}
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
}

//2007-01-03: Rebecca - returns a list of the restrictions placed on an AC member
function getRestrictionsList($ac_mem_id) {
	$SQL = "SELECT * FROM lkp_AC_member_restrictions WHERE AC_member_id = ".$ac_mem_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if (mysqli_num_rows($rs) > 0){
		$restriction_ids = array();
		while ($row = mysqli_fetch_array($rs)){
			$restriction_name = DBConnect::getValueFromTable("HEInstitution", "HEI_id", $row['restricted_field_id'], "HEI_name");
			array_push($restriction_ids, $restriction_name);
		}
		return $restriction_ids;
	}
	else return array("NONE");
}

function getRestrictionsIDs($ac_mem_id=0) {
	$SQL  = "SELECT * FROM lkp_AC_member_restrictions WHERE ";
	$SQL .= ($ac_mem_id != 0) ? "AC_member_id = ".$ac_mem_id : "1";

	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if (mysqli_num_rows($rs) > 0){
		$restriction_ids = array();
		while ($row = mysqli_fetch_array($rs)){
			//$restriction_name = DBConnect::getValueFromTable("HEInstitution", "HEI_id", $row['restricted_field_id'], "HEI_name");
			//array_push($restriction_ids, $restriction_name);
			array_push($restriction_ids, $row['restricted_field_id']);
		}
		return $restriction_ids;
	}
	else return array("0");
}

//2007-01-04: Rebecca - returns whether an application comes from a private HEI (1) or public HEI (2)
function checkAppPrivPubl($app_id) {
	$hei_id = HEQConline::getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	$priv_publ = HEQConline::getValueFromTable("HEInstitution", "HEI_id", $hei_id, "priv_publ");
	return $priv_publ;
}

// 2008-02-14 Robin - Certain criteria are captured per site when capturing an application.
// This function creates a table of sites (with a link to edit) for a particular application and a specific criteria.

function buildSiteCriteriaEditforApplication($app_id,$criteria){
	$data = "No sites were found for this application. Please go and select the sites where this programme will be offered before continuing.";

	$sql =<<<getSites
		SELECT ia_criteria_per_site_id, institutional_profile_sites.*
		FROM ia_criteria_per_site, institutional_profile_sites
		WHERE application_ref = $app_id
		AND institutional_profile_sites_id = institutional_profile_sites_ref
getSites;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	if ($rs && mysqli_num_rows($rs) > 0){
			$data =<<<hhead
				<tr>
					<td colspan="7">
						<span class="visi">Please ensure that each question has been completed <u>per site</u> before submitting to the CHE.</span>
					</td>
				</tr>
				<tr>
					<td><b>Edit</b></td>
					<td><b>Site</b></td>
				</tr>
hhead;
		while ($row = mysqli_fetch_array($rs)){
			$site_id = $row["ia_criteria_per_site_id"];

			$jscript = $this->scriptGetForm("ia_criteria_per_site", $site_id, "_labelEditCriteria".$criteria."PerSite");
			$imgPath = $this->relativePath."images";
			$data .=<<<hrow
				<tr>
					<td width="4%">
						<a href='$jscript'>
						<img src="$imgPath/ico_change.gif" border=0>
						</a>
					</td>
					<td>$row[site_name] - $row[location]</td>
				</tr>
hrow;
		}
	}

	$html =<<<sites
		<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
		$data
		</table>
sites;

	return $html;
}

//Rebecca: 22-02-2008. Builds the declaration the registrar of public HEIs has to "sign" for each criterion
//
function buildRegistrarDeclarationForCriterion($appID, $crit_number) {

	// Things we need to know
	//$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $appID, "user_ref");
	$user_arr = $this->getInstitutionAdministrator($appID);
	if ($user_arr[0]==0){
		echo "Processing has been halted for the following reason: <br><br>";
		echo $user_arr[1];
		die();
	}
	$AdminRef = $user_arr[0];
	$fieldDeclarationUserRef = $crit_number."_registrarDeclaration_userRef";
	$signedUserRef = $this->getValueFromTable("Institutions_application", "application_id", $appID, $fieldDeclarationUserRef);
	if ($signedUserRef == 0) { 	$signedUserRef = $AdminRef; }
	$this->formFields[$fieldDeclarationUserRef]->fieldValue = $signedUserRef;
	$this->showField($fieldDeclarationUserRef);

	$nameOfAdministrator = $this->getValueFromTable("lkp_title", "lkp_title_id", $this->getValueFromTable("users", "user_id", $signedUserRef, "title_ref"), "lkp_title_desc")." ".$this->getValueFromTable("users", "user_id", $signedUserRef, "name")." ".$this->getValueFromTable("users", "user_id", $signedUserRef, "surname");

	$fieldDeclarationSigned = $crit_number."_registrarDeclaration_signed";
	$fieldDeclarationDate = $crit_number."_registrarDeclaration_date";
	$declarationDate = $this->getValueFromTable("Institutions_application", "application_id", $appID, $fieldDeclarationDate);
	$checkboxFieldName = $crit_number."_registrarDeclaration_lkp";

	// Things we need to check

	if ((isset($_POST['declaration_cancel']) && ($_POST['declaration_cancel']=='OK')) || $declarationDate < '1900-01-01' || $declarationDate == '1000-01-01') {
		$this->setValueInTable ("Institutions_application", "application_id", $appID, $fieldDeclarationSigned, 'No');
		$this->setValueInTable ("Institutions_application", "application_id", $appID, $fieldDeclarationDate, '1000-01-01');
		$this->setValueInTable ("Institutions_application", "application_id", $appID, $checkboxFieldName, '1');
		$this->formFields[$fieldDeclarationDate]->fieldValue = $this->getValueFromTable("Institutions_application", "application_id", $appID, $fieldDeclarationDate);
		$this->formFields[$checkboxFieldName]->fieldValue = $this->getValueFromTable("Institutions_application", "application_id", $appID, $checkboxFieldName);
	}
	$signAttempt = (isset($_POST['declaration_OK']) && $_POST['declaration_OK']=='OK')?(true):(false);

	// Now we change check if signed
	$declarationSigned = $this->getValueFromTable("Institutions_application", "application_id", $appID, $fieldDeclarationSigned);

	$displayIntro_yn =<<< DISPLAY
			<tr>
				<td colspan="2">
				As the HEQC-online institutional administrator for your institution, you are required to fill in the following declaration.
				Please select the option indicating whether or not your institution complies with the minimum standards, and enter your HEQC-online password as verification of your identity.
				<span class="visi">Note that it is not possible to submit this application if the declaration has not been 'signed'.</span>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td width="50%" align="right" valign="top">
					I have evaluated and verified that the university in general and the unit responsible for the programme
					have met the minimum standards of Criterion $crit_number.
				</td>
				<td valign="top">
DISPLAY;

	$declarationForSubmission = $this->getTextContent("done_v2", "publicRegistrarDeclaration");
$declarationSubmission =<<< DISPLAY
		<tr>
			<td colspan="2">
				$declarationForSubmission
			</td>
		</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
		<tr>
			<td width="50%" align="right" valign="top">
				I declare that I have evaluated the proposed programme submission and have verified that the minimum standards regarding those criteria from which we have been exempted, have been met by the unit applying for programme accreditation.
			</td>
			<td valign="top">
DISPLAY;

	$declarationText = ($crit_number == "final") ? $declarationSubmission : $displayIntro_yn;


	$displayUserSelect =<<< DISPLAY
				</td>
			</tr>
			<tr>
				<td align="right" valign="top">HEQC-online institutional administrator: </td>
				<td valign="top">
DISPLAY;

	$displayDateSigned =<<< DISPLAY
				</td>
			</tr>
			<tr>
				<td align="right"> Date signed:</td>
				<td>
DISPLAY;

	$displayPasswordField =<<< DISPLAY
				</td>
			</tr>
			<tr>
				<td align="right"> Please enter your HEQC-online password for verification:</td>
				<td>
DISPLAY;

	$submitButtonScript = ($this->view != 1) ? "javascript:if(checkFrm(document.all.defaultFrm)){document.all.declaration_OK.value='OK';moveto('stay');}" : "javascript:alert('You are currently in the report view.');";
	$disabled = ($this->view == 1)?("disabled"):("");

	$displaySubmitButton =<<< DISPLAY
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<input type="hidden" name="declaration_OK" value="go">
				<input $disabled type="button" value="Submit Declaration" onClick="$submitButtonScript"><br>
				</td>
			</tr>
DISPLAY;

	$cancelButtonScript = ($this->view != 1) ? "javascript:document.all.declaration_cancel.value='OK';moveto('stay');" : "javascript:alert('You are currently in the report view.');";
	$disabled = ($this->view == 1)?("disabled"):("");
$formIsSigned =<<<DISPLAY
	<tr>
		<td colspan="2"><span class="visi">This declaration was signed by $nameOfAdministrator on $declarationDate.</span><br>To revoke this declaration, press the "Cancel Declaration" button. Please note that you will not be able to submit an application if the HEQC-online administrator has not signed all declarations.</td>
	</tr>
	<tr>
	<td colspan="2" align="right"><input name="declaration_cancel" type="hidden" value="go"><input $disabled type="button" value="Cancel Declaration" onClick="$cancelButtonScript"></td>
	</tr>
DISPLAY;

$signedError =<<<DISPLAY
	<tr><td colspan="2"><hr></td></tr>
	<tr>
		<td colspan="2" align="center"><span class="visi">Incorrect password or invalid date.<br>Please re-enter your HEQC-online system password, and make sure you have chosen a valid date.</span></td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
DISPLAY;

	echo '<hr><table border="0" cellpadding="2" cellspacing="2" class="onblue" width="95%" align="left">';
	if ($declarationSigned == 'Yes') {
		echo $formIsSigned;
	} else {

		echo $declarationText;
		$this->showField($checkboxFieldName);

		echo $displayPasswordField;
		$this->showField($fieldDeclarationSigned);

		echo $displayUserSelect;
		echo $nameOfAdministrator;

		echo $displayDateSigned;
		$this->showField($fieldDeclarationDate);

		echo $displaySubmitButton;
		if ($signAttempt) {
			echo $signedError;
		}
	}
	echo "</table>";
}


// Robin 18/02/2008. Determine whether any sites have been added for an application.
// Note: Selection of sites is a two stage process in two tables: lkp_sites for initial selection
//								ia_criteria_per_site for confirmed selection.
// Once selection is confirmed the user may not reselect sites. Hence this function to check if confirmed sites have been added.
function getNoOfSitesForApplication($app_id){
	$NoOfSites = 0;

	$sql =<<<siteSQL
	SELECT count(*) as NoOfSites
	FROM ia_criteria_per_site
	WHERE application_ref = $app_id
siteSQL;
//echo $sql;
	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	if ($rs){
		$row = mysqli_fetch_array($rs);
		$NoOfSites = $row["NoOfSites"];
	}

	return $NoOfSites;
}

function safeJS ($fld) {
	return (str_replace("$", "%24", $fld));
}

//displays a div if a field in Institutions_application table meets a specified value
function displayifConditionMetInstitutions_applications($app_id, $field_name, $meetsValue) {
	if (DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, $field_name) == $meetsValue)
	{ return "block"; }
	else
	{ return "none"; }
}

// displays a div for reaccreditation if a field in Institutions_application_reaccreditation meets a specified value.
function div_reacc($reacc_id, $field_name, $meetsValue) {
	if (DBConnect::getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, $field_name) == $meetsValue)
	{ return "block"; }
	else
	{ return "none"; }
}

//displays various buttons depending on conditions -> commented below
function displayRelevantButtons($app_id, $currentUserID) {
	// Do not display Question 9 if not postgrad programme
	$NQF_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");
	if ($NQF_id <= 2) {
		$this->formActions["movetoFrom9"]->actionMayShow = false;
	}

	// Do not display Question 10 if not distance OR Unisa
	$mode_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
	$hei_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	if (!($mode_id == 2 || $mode_id == 6) || ($hei_id == 54)) {
		$this->formActions["movetoFrom10"]->actionMayShow = false;
	}

	// Do not display Send Application To Institutional Administrator if the user is Administrator
	if ($this->checkIfAdmin($app_id, $currentUserID)) {
		$this->formActions["changeAdmin"]->actionMayShow = false;
	}

	// Display relevant offline application form
	$prov_type = $this->checkAppPrivPubl($app_id);
	switch ($prov_type) {
		case 1 :	$this->formActions["download_public"]->actionMayShow = false;
					break;
		case 2 : 	$this->formActions["download_private"]->actionMayShow = false;
					break;
	}
}

//checks if current user is administrator for their institution
function checkIfAdmin($app_id, $user_id) {
	// 2010-02-08 Robin: Replace with current administrator and not administrator who started the application (user_ref on application)
	//$admin_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref");
	$adm = $this->getInstitutionAdministrator($app_id);
	$admin_id = $adm[0];
	if ($user_id == $admin_id) {
		return true;
	}
	else {return false;}
}

// Robin 2/03/2008 - return an array with Site name for each ia_criteria_id to be used in validation report.
function getArraySiteNamesforApp($app_id){
	$site_name = false;
	$sql =<<<SSQL
		SELECT ia_criteria_per_site_id, institutional_profile_sites_ref, site_name
		FROM ia_criteria_per_site, institutional_profile_sites
		WHERE institutional_profile_sites_ref = institutional_profile_sites_id
		AND application_ref = $app_id;
SSQL;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	while ($row = mysqli_fetch_array($rs)){
		$site_name[$row["ia_criteria_per_site_id"]]= "<i>Site: ". $row["site_name"]."</i>";
	}

	return $site_name;
}

	function setData(){
	}

	function scriptGetForm ($table, $id, $moveto, $dataval="" ) {
					global $heqcEncrypt;
					$setdata = "";

					$script = 'javascript:alert("You are currently in the report view.");';
					
					if ($this->view != 1) {
						$chRec = $heqcEncrypt->encrypt("$table|$id");
						if ($dataval > ""){
							$setdata = "setData(\"$dataval\");";
						}
						$script = "javascript:".$setdata."getForm(\"$chRec\", \"$moveto\");";
					}

					return ($script);
	}

// Display application report according to its version. A new format was implemented on 1st March 2008.
function displayApplicationFormOverview($app_id, $settings) {

	$app_version = $this->getValueFromTable("Institutions_application","application_id",$app_id,"app_version");
	$prov_type = $this->checkAppPrivPubl($app_id);
	$nqf = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");
	$mode = $this->getValueFromTable("Institutions_application", "application_id", $app_id , "mode_delivery");
	$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id , "institution_id");
	$child = array();

	switch ($app_version){
	case 1:
		//	Rebecca 3/5/2007
		//	Shows the old programme structure table in case users have entered information into it (so that they may still
		//	use it to create the new, uploaded document).
		//	If there is nothing in the table, we do not show the table.

		$SQL = "SELECT * FROM `appTable_1_prog_structure` WHERE `application_ref`=".$app_id;
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$prog_structure_populated = "false";

		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array($rs)) {
				if (($row["course_name"] == "") && ($row["core"] == "") &&
						($row["fundamental"] == "") && ($row["fund_credits"] == "") && ($row["core_credits"] == ""))
				{
					//do nothing
				}
				else
				{
					$prog_structure_populated = "true";
				}
			}
		}

		if ($prog_structure_populated == "true")
		{
			$forms = array ("accForm1", "accForm1b", "accForm3-1", "accForm3-1-prog_struct",
			 "accForm6", "accForm8", "accForm8b", "accForm9", "accForm14", "accForm15", "accForm17", "accForm19");
//				  "evalSelectRequests"
		}
		else
		{
			$forms = array ("accForm1", "accForm1b", "accForm3-1", "accForm6", "accForm8",
			 "accForm8b", "accForm9", "accForm14", "accForm15", "accForm17", "accForm19");
//				  "evalSelectRequests"
		}

			break;
	default: // case 2, 3 and 4
		//Adding downloadable RTF to Application Status Report
		// Robin 6-6-2008: Commented out until can debug why errors.  Errors occur when td cells have nothing in them.  
		//$doc = new octoDocGen ("populatedAppForm", "app_id=".$app_id);
		//$path = ($this->template == "welcome") ? "../" : "";
		//$doc->url ("Download report as document", $path);

		// Only 1 for changes for version 3
		$accForm1 = "accForm1_v2";
		if ($app_version == 3 || $app_version == 4) $accForm1 = "accForm1_v3";

		$forms = array ("accForm1b_v2", "accForm1c_v2", $accForm1, "accForm3-1_v2", "accForm6_v2", "accForm8_1_v2",
		 "accForm8b_v2", "accForm9_v2", "accForm14_v2", "accForm15_v2", "accForm17_v2");

		// Report displays differently for public and private institutions. Per site data capture was introduced
		// in the new application format causing multiple data rows per site for one application. Thus child
		// processing needed to be added.
		switch ($prov_type){
		case 1:
			$child["accForm8_1_v2"] = "accForm8_2_v2";
			$child["accForm8b_v2"] = "accForm8b_2_v2";
			$child["accForm15_v2"] = "accForm15_2_v2";
			$child["accForm17_v2"] = "accForm17_2_v2";
			break;
		}

		if ($nqf >= 3) {
			array_push($forms, "accForm19_v2");
			$child["accForm19_v2"] = "accForm19_2_v2";
		}

		// Exclude Unisa as well
		if (($mode == 2 || $mode == 6) && $inst_id != 54) {
			array_push($forms, "accForm30_v2");
		}

		// We want to include the final declaration (submission page)
		if ($prov_type == 2) {
			array_push($forms, "done_v2");
		}

		break;
	}

	foreach ($forms as $form) {
		$this->displayForm($app_id,$settings,$form,$child);
	}
}

function displayReaccApplicFormOverview ($app_id, $settings){
	$version = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id",$app_id, "reaccreditationVersion");
	if($version ==1)
	{
			//$version =$this->getValueFromTable("users", "user_id",$user_arr[0], "email");
		$forms = array ("reAccForm2", "reAccForm3", "reAccForm5", "reAccForm6", "reAccForm7",
			 "reAccForm8", "reAccForm9", "reAccForm10", "reAccForm11", "reAccForm12", "reAccForm13",
			 "reAccForm14", "reAccForm15", "reAccForm16", "reAccForm17", "reAccForm18","documentBasket");
		$child = array();
	}if($version ==2) 
	{
		
		$forms = array ("reAccForm2_v2","reAccForm6_v3","documentBasket_v2");
		
		$child = array();
	}
	if($version ==3){
		
		$forms = array ("reAccForm2_v2",  "reAccForm6_v2","documentBasket_v2");
		
		$child = array();
	}
	foreach ($forms as $form) {
		$this->displayForm($app_id,$settings,$form,$child);
	}
}

function displayReaccApplicProcessInfo ($app_id, $settings){
	$forms = array ("reAccaddACOutcomes2", "reAccProcessApplic1");
	$child = array();

	foreach ($forms as $form) {
		$this->displayForm($app_id,$settings,$form,$child);
	}
}

function displayProgDataForSite($app_id, $settings){
	$forms = array("accForm8_2_v2",
			"accForm8b_2_v2",
			"accForm15_2_v2",
			"accForm17_2_v2",
			"accForm19_2_v2");
	$child = array();
	foreach ($forms as $form) {
		$this->displayForm($app_id,$settings,$form,$child);
	}
}

function displayForm($app_id,$settings,$form,$child){
		$app = new HEQConline (1);
		$app->parseWorkFlowString($settings);
		$app->template = $form;
		$app->view = 1;
		$app->formStatus = FLD_STATUS_TEXT;
		$app->readTemplate();
		$app->createHTML($app->body);

		// process child data if a child exists.
		if (isset($child[$form]) && $child[$form] > ""){
			$child_id_array = array();
			$tbl_arr = $app->getTemplateTableAndKey($child[$form]);
			if (isset($tbl_arr) && $tbl_arr[$child[$form]]["dbTableName"] > ""){
				$table = $tbl_arr["$child[$form]"]["dbTableName"];
				$key = $tbl_arr["$child[$form]"]["dbTableKeyField"];

				$cSql = "SELECT $key FROM $table WHERE application_ref = $app_id";
				$cRs = mysqli_query($this->getDatabaseConnection(), $cSql);
				while ($cRow = mysqli_fetch_array($cRs)){
					array_push($child_id_array,$cRow["$key"]);
				}

				foreach($child_id_array as $c){
					$app->dbTableInfoArray["$table"]->dbTableName = $table;
					$app->dbTableInfoArray["$table"]->dbTableKeyField = $key;
					$app->dbTableInfoArray["$table"]->dbTableCurrentID = $c;

					$app->template = $child[$form];
					$app->readTemplate();
					$app->createHTML($app->body);
				}
			}
		}

		unset ($app);

}

function displayChildForm($child_id,$child_form,$settings){
		$app = new HEQConline (1);
		$app->parseWorkFlowString($settings);
		$app->view = 1;
		$app->formStatus = FLD_STATUS_TEXT;

		$tbl_arr = $app->getTemplateTableAndKey($child_form);
		if (isset($tbl_arr) && $tbl_arr[$child_form]["dbTableName"] > ""){
			$table = $tbl_arr["$child_form"]["dbTableName"];
			$key = $tbl_arr["$child_form"]["dbTableKeyField"];

			$app->dbTableInfoArray["$table"]->dbTableName = $table;
			$app->dbTableInfoArray["$table"]->dbTableKeyField = $key;
			$app->dbTableInfoArray["$table"]->dbTableCurrentID = $child_id;
			$app->template = $child_form;
			$app->readTemplate();
			$app->createHTML($app->body);
		}
		unset ($app);
}

function buildContactsGrid($parent_id, $contact_type_ref){

	$headArr = array();
	array_push($headArr, "Contact type");
	array_push($headArr, "Surname");
	array_push($headArr, "Name");
	array_push($headArr, "Title");
	array_push($headArr, "Designation");
	array_push($headArr, "Postal Address");
	array_push($headArr, "Physical Address:");
	array_push($headArr, "Fax number");
	array_push($headArr, "Telephone Number:");
	array_push($headArr, "Email:");

	$fieldArr = array();
	array_push($fieldArr, "type__select|name__contact_type_ref|value__".$contact_type_ref."|status__2|description_fld__lkp_contact_desc|fld_key__lkp_contact_id|lkp_table__lkp_contact_type|lkp_condition__1|order_by__lkp_contact_desc");
	array_push($fieldArr, "type__text|name__contact_surname");
	array_push($fieldArr, "type__text|name__contact_name");
	array_push($fieldArr, "type__select|name__contact_title_ref|description_fld__lkp_title_desc|fld_key__lkp_title_id|lkp_table__lkp_title|lkp_condition__1|order_by__lkp_title_desc");
	array_push($fieldArr, "type__text|name__contact_designation");
	array_push($fieldArr, "type__textarea|name__contact_postal_address");
	array_push($fieldArr, "type__textarea|name__contact_physical_address");
	array_push($fieldArr, "type__text|name__contact_fax_nr");
	array_push($fieldArr, "type__text|name__contact_nr");
	array_push($fieldArr, "type__text|name__contact_email");

	$uniqueFlds = "institution_ref__".$parent_id."|contact_type_ref__".$contact_type_ref;

//	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_management_info_system", "institutional_profile_management_info_system_id", "institution_ref", $fieldsArr, 5, "", "", "", $select_arr, "", "", 1, false);
	$this->gridShowTableByRow("institutional_profile_contacts", "institutional_profile_contacts_id", $uniqueFlds, $fieldArr, $headArr, 70, 10);

}

	function displayReaccredHeader($reaccred_id){

		$head = "";
		
		// We do not want the header to display for every report page.  We want it to display on data capture pages.
		if ($this->view != 1){
			$head = "--No reaccreditation application found--";
	
			$sql =<<<HEADER
				SELECT HEI_id, HEI_name, Institutions_application_reaccreditation_id, referenceNumber, programme_name, n.NQF_level
				FROM (Institutions_application_reaccreditation a, HEInstitution i)
				LEFT JOIN NQF_level n ON a.NQF_level = n.NQF_id
				WHERE i.HEI_id = a.institution_ref
				AND Institutions_application_reaccreditation_id = $reaccred_id
HEADER;
	
			$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	
			if ($rs){
				$row = mysqli_fetch_array($rs);
	
				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_institutional_profile___institution_ref=".$row["HEI_id"]."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$row["Institutions_application_reaccreditation_id"];
				$linki = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["HEI_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["HEI_name"]."</a>";
				$linka = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>';
				$head =<<<HEAD
					<table width="85%" border=0  cellpadding="2" cellspacing="2">
					<tr>
						<td width="25%"><b>Institution name</b></td>
						<td class="oncolour">
							$linki
						</td>
					</tr>
					<tr>
						<td><b>HEQC Reference No.</b></td>
						<td class="oncolour">
							$linka
						</td>
					</tr>
					<tr>
						<td><b>Programme name</b></td><td class="oncolour">$row[programme_name]</td>
					</tr>
					<tr>
						<td><b>NQF Level</b></td><td class="oncolour">$row[NQF_level]</td>
					</tr>
					</table>
HEAD;
			}
		}
		return $head;
	}

	function getUserName($user_id,$style=1){
		$name = "Sir/Madame";

		$sql =<<<USERNAME
			SELECT t.lkp_title_desc, u.name, u.surname
			FROM users u
			LEFT JOIN lkp_title t ON t.lkp_title_id = u.title_ref
			WHERE user_id = $user_id
USERNAME;

		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs && mysqli_num_rows($rs) == 1){
			$row = mysqli_fetch_array($rs);
			$title = $row["lkp_title_desc"];
			$name = $row["name"];
			$surname = $row["surname"];
			switch ($style){
				case 2:
					$name = $name . " " . $surname;
					break;
				case 3:
					$name = $title . " " . $surname;
					break;
				case 4:
					$name = $name;
					break;
				default:
					$name = $title . " " . $name . " " . $surname;
			}

		}

		return $name;
	}

	// 2009-06-12 Robin: function to retrieve the payment record for accreditation or re-accreditation.
	function getPaymentInfo($fld, $id){
		$aPayment = array();
		
		$whr = ($fld == "application_ref") ? " AND ia_proceedings_ref = 0 " :  "";
		
		$psql =<<<PAYMENT
			SELECT * FROM payment
			WHERE $fld = $id
			$whr
PAYMENT;
		
		$prs = mysqli_query($this->getDatabaseConnection(), $psql);
		$pnum = mysqli_num_rows($prs);
		if ($pnum == 1){ // there should only be one payment record per application.
			$aPayment = mysqli_fetch_array($prs);
		}
		return $aPayment;
	}

	function getRegistryProcessInfo($type){
		$reg = array();

		switch ($type){
		case "Institutions_application":
			$reg['heqc_ref_field'] = "CHE_reference_code";
			$reg['process'] = "11";
			$reg['flow'] = "154";
			break;
		case "Institutions_application_reaccreditation":
			$reg['heqc_ref_field'] = "referenceNumber";
			$reg['process'] = "151";
			$reg['flow'] = "1665";
			break;
		}
		
		return $reg;
	}

	
	/* 
	2009-08-08 Robin
	Function to setup common search criteria used on several windows.  Only add criteria.  Do not remove criteria.
	*/
	function build_reacc_search_criteria(){
		$fc_arr = array();

		$s_startdate = readPost('subm_start_date');
		$s_enddate = readPost('subm_end_date');
		$ac_startdate = readPost('acmeeting_start_date');
		$ac_enddate = readPost('acmeeting_end_date');
		$s_HEQCref = readPost('search_HEQCref');
		$s_progname = readPost('search_progname');
		$s_inst = readPost('search_institution');
		$s_outcome = readPost('search_outcome');
		
		if ($s_startdate > ''){
			array_push($fc_arr,"reacc_submission_date >= '".$s_startdate."'");
			$this->formFields["subm_start_date"]->fieldValue = $s_startdate;
		}
		
		if ($s_enddate > ''){
			array_push($fc_arr,"reacc_submission_date <= '".$s_enddate."'");
			$this->formFields["subm_end_date"]->fieldValue = $s_enddate;
		}
		
		if ($ac_startdate > ''){
			array_push($fc_arr,"reacc_acmeeting_date >= '".$ac_startdate."'");
			$this->formFields["acmeeting_start_date"]->fieldValue = $ac_startdate;
		}
		
		if ($ac_enddate > ''){
			array_push($fc_arr,"reacc_acmeeting_date <= '".$ac_enddate."'");
			$this->formFields["acmeeting_end_date"]->fieldValue = $ac_enddate;
		}
		
		if ($s_HEQCref > ''){
			array_push($fc_arr,"referenceNumber like '%".$s_HEQCref."%'");
			$this->formFields["search_HEQCref"]->fieldValue = $s_HEQCref;
		}
			
		if ($s_progname > ''){
			array_push($fc_arr,"upper(programme_name) like '%".strtoupper($s_progname)."%'");
			$this->formFields["search_progname"]->fieldValue = $s_progname;
		}
	
		if ($s_inst > 0){
			array_push($fc_arr,"institution_ref = ".$s_inst);
			$this->formFields["search_institution"]->fieldValue = $s_inst;
		}
		
		if ($s_outcome > 0){
			array_push($fc_arr,"reacc_decision_ref = ".$s_outcome);
			$this->formFields["search_outcome"]->fieldValue = $s_outcome;
		}
		
		return $fc_arr;
	}

	/* 
		2010-05-12 Robin
		Function to setup common search criteria for accreditation applications used on several windows.  
		Only add criteria.  Do not remove criteria.
	*/
	function build_candidacy_search_criteria(){
		$fc_arr = array();

		$s_HEQCref = readPost('search_HEQCref');
		$s_progname = readPost('search_progname');
		$s_inst = readPost('search_institution');
		$s_startdate = readPost('subm_start_date');
		$s_enddate = readPost('subm_end_date');
		$s_procstartdate = readPost('proc_subm_start_date');
		$s_procenddate = readPost('proc_subm_end_date');
		$inv_startdate = readPost('invoice_start_date');
		$inv_enddate = readPost('invoice_end_date');
		$eapp_startdate = readPost('evalappoint_start_date');
		$eapp_enddate = readPost('evalappoint_end_date');
		$rec_due_startdate = readPost('recomm_due_start_date');
		$rec_due_enddate = readPost('recomm_due_end_date');
		$ac_startdate = readPost('acmeeting_start_date');
		$ac_enddate = readPost('acmeeting_end_date');
		$heqc_startdate = readPost('heqcmeeting_start_date');
		$heqc_enddate = readPost('heqcmeeting_end_date');
		$outcome_due_startdate = readPost('outcome_due_start_date');
		$outcome_due_enddate = readPost('outcome_due_end_date');
		$s_heqcdecision = readPost('search_heqc_decision');
		$s_outcome = readPost('search_outcome');
		$s_nooutcome = readPost('no_outcome');
		$s_status = readPost('search_status');
		$mode_delivery = readPost('mode_delivery');
		
		if ($s_HEQCref > ''){
			array_push($fc_arr,"Institutions_application.CHE_reference_code like '%".$s_HEQCref."%'");
			$this->formFields["search_HEQCref"]->fieldValue = $s_HEQCref;
		}
		
		if ($s_progname > ''){
			array_push($fc_arr,"upper(Institutions_application.program_name) like '%".strtoupper($s_progname)."%'");
			$this->formFields["search_progname"]->fieldValue = $s_progname;
		}
		
		if ($s_inst > 0){
			array_push($fc_arr,"Institutions_application.institution_id = ".$s_inst);
			$this->formFields["search_institution"]->fieldValue = $s_inst;
		}
		
		if ($s_startdate > ''){
			array_push($fc_arr,"Institutions_application.submission_date >= '".$s_startdate."'");
			$this->formFields["subm_start_date"]->fieldValue = $s_startdate;
		}
		
		if ($s_enddate > ''){
			array_push($fc_arr,"(Institutions_application.submission_date != '1000-01-01' AND Institutions_application.submission_date <= '".$s_enddate."')");
			$this->formFields["subm_end_date"]->fieldValue = $s_enddate;
		}

		if ($s_procstartdate > ''){
			array_push($fc_arr,"ia_proceedings.submission_date >= '".$s_procstartdate."'");
			$this->formFields["proc_subm_start_date"]->fieldValue = $s_procstartdate;
		}
		
		if ($s_procenddate > ''){
			array_push($fc_arr,"(ia_proceedings.submission_date != '1000-01-01' AND ia_proceedings.submission_date <= '".$s_procenddate."')");
			$this->formFields["proc_subm_end_date"]->fieldValue = $s_procenddate;
		}
		
		if ($inv_startdate > ''){
			array_push($fc_arr,"payment.date_invoice >= '".$inv_startdate."'");
			$this->formFields["invoice_start_date"]->fieldValue = $inv_startdate;
		}
		
		if ($inv_enddate > ''){
			array_push($fc_arr,"(payment.date_invoice != '1000-01-01' AND payment.date_invoice <= '".$inv_enddate."')");
			$this->formFields["invoice_end_date"]->fieldValue = $inv_enddate;
		}
		
		if ($eapp_startdate > ''){
			array_push($fc_arr,"evalReport.evalReport_date_sent >= '".$eapp_startdate."'");
			$this->formFields["evalappoint_start_date"]->fieldValue = $eapp_startdate;
		}
		
		if ($eapp_enddate > ''){
			array_push($fc_arr,"(evalReport.evalReport_date_sent != '1000-01-01' AND evalReport.evalReport_date_sent <= '".$eapp_enddate."')");
			$this->formFields["evalappoint_end_date"]->fieldValue = $eapp_enddate;
		}
		
		if ($rec_due_startdate > ''){
			array_push($fc_arr,"ia_proceedings.recomm_access_end_date >= '".$rec_due_startdate."'");
			$this->formFields["recomm_due_start_date"]->fieldValue = $rec_due_startdate;
		}
		
		if ($rec_due_enddate > ''){
			array_push($fc_arr,"(ia_proceedings.recomm_access_end_date != '1000-01-01' AND ia_proceedings.recomm_access_end_date <= '".$rec_due_enddate."')");
			$this->formFields["recomm_due_end_date"]->fieldValue = $rec_due_enddate;
		}
		
		if ($ac_startdate > ''){
			array_push($fc_arr,"(Institutions_application.AC_Meeting_date >= '".$ac_startdate."' OR app.ac_start_date >= '".$ac_startdate."' OR iap.ac_start_date >= '".$ac_startdate."')");
			$this->formFields["acmeeting_start_date"]->fieldValue = $ac_startdate;
		}
		
		if ($ac_enddate > ''){
			array_push($fc_arr,"((Institutions_application.AC_Meeting_date != '1000-01-01' AND Institutions_application.AC_Meeting_date <= '".$ac_enddate."') OR (app.ac_start_date != '1000-01-01' AND app.ac_start_date <= '".$ac_enddate."') OR (iap.ac_start_date != '1000-01-01' AND iap.ac_start_date <= '".$ac_enddate."'))");
			$this->formFields["acmeeting_end_date"]->fieldValue = $ac_enddate;
		}
		
		if ($heqc_startdate > ''){
			array_push($fc_arr,"HEQC_Meeting.heqc_start_date >= '".$heqc_startdate."'");
			$this->formFields["heqcmeeting_start_date"]->fieldValue = $heqc_startdate;
		}
		
		if ($heqc_enddate > ''){
			array_push($fc_arr,"(HEQC_Meeting.heqc_start_date != '1000-01-01' AND HEQC_Meeting.heqc_start_date <= '".$heqc_enddate."')");
			$this->formFields["heqcmeeting_end_date"]->fieldValue = $heqc_enddate;
		}
		
		if ($s_heqcdecision > 0){
			array_push($fc_arr,"(ia_proceedings.heqc_board_decision_ref = '".$s_heqcdecision."')");
			$this->formFields["search_heqc_decision"]->fieldValue = $s_heqcdecision;
		}

		if ($mode_delivery > 0){
			array_push($fc_arr,"(Institutions_application.mode_delivery = '".$mode_delivery."')");
			$this->formFields["mode_delivery"]->fieldValue = $mode_delivery;
		}
		
		if ($outcome_due_startdate > ''){
			$due_date =<<<DUE
				(ia_proceedings.heqc_decision_due_date >= '{$outcome_due_startdate}' OR
				ia_proceedings.condition_short_due_date >= '{$outcome_due_startdate}' OR
				ia_proceedings.condition_prior_due_date >= '{$outcome_due_startdate}' OR
				ia_proceedings.condition_long_due_date >= '{$outcome_due_startdate}')
DUE;
			array_push($fc_arr,$due_date);
			$this->formFields["outcome_due_start_date"]->fieldValue = $outcome_due_startdate;
		}
		
		if ($outcome_due_enddate > ''){
			$due_date_end =<<<DUE
				(ia_proceedings.heqc_decision_due_date <= '{$outcome_due_enddate}' OR
				ia_proceedings.condition_short_due_date > '1000-01-01' AND ia_proceedings.condition_short_due_date <= '{$outcome_due_enddate}' OR
				ia_proceedings.condition_prior_due_date > '1000-01-01' AND ia_proceedings.condition_prior_due_date <= '{$outcome_due_enddate}' OR
				ia_proceedings.condition_long_due_date > '1000-01-01' AND ia_proceedings.condition_long_due_date <= '{$outcome_due_enddate}')
DUE;
			array_push($fc_arr,$due_date_end);
			$this->formFields["outcome_due_end_date"]->fieldValue = $outcome_due_enddate;
		}
		
		if ($s_outcome > 0){
			array_push($fc_arr,"Institutions_application.AC_desision = ".$s_outcome);
			$this->formFields["search_outcome"]->fieldValue = $s_outcome;
		}
		
		if ($s_nooutcome > 0){
			array_push($fc_arr,"(Institutions_application.application_status > -1 AND Institutions_application.AC_desision = '' OR Institutions_application.AC_desision = NULL)");
			$this->formFields["no_outcome"]->fieldValue = $s_nooutcome;
		}
		
		if ($s_status != 0){
			array_push($fc_arr,"Institutions_application.application_status = ".$s_status);
			$this->formFields["search_status"]->fieldValue = $s_status;
		}
		
		return $fc_arr;
	}
	
	function validate_GRID_($tbl_id){
		$valid = "true";
				$lnk1 = "";
				$lnk2 = "";
				$showTitle = "names of the modules/courses";
				$image = $this->imageOK;
				
				$sql =<<<SQL
					SELECT nqf_level, sum(core_credits) as tot_core_credits
					FROM appTable_1_prog_structure
					WHERE application_ref = $grid_id
SQL;
				$rs = mysqli_query($this->getDatabaseConnection(), $sql);
				$row_num = mysqli_num_rows($rs);
				if ($row_num == 0) {
					$image = $this->imageWrong;
					$message = "Please enter the modules.";
				}
				
				while ($row = mysqli_fetch_array($rs)){				
				}
				
		$htmlRow =<<<htmlRow
		 	<tr>
				<td align="center" class="oncolour">$lnk1<img src="images/$image" border=0>$lnk2</td>
				<td class='oncolour'>$showTitle $showField</td>
				<td class='oncolour'><font color="red">$message</font></td>
			</tr>
htmlRow;

		return $htmlRow;
	}
	
	function returnAppToInstBeforePayment($app_id){
		// Get current active institutional administrator - not user that started the application.
		$user_arr = $this->getInstitutionAdministrator($app_id);
		if ($user_arr[0]==0){
			echo "Processing has been halted for the following reason: <br><br>";
			echo $user_arr[1];
			die();
		}
		$app_user_email = $this->getValueFromTable("users", "user_id",$user_arr[0], "email");
		
		$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

		$this->setValueInTable("Institutions_application", "application_id", $app_id, "submission_date", "1000-01-01");
		$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_printed", "0");

		$to = $app_user_email;
		$message = $this->getTextContent ("gatekeeper_comment_app", "cancelSubmission");
		$this->misMailByName($to, "Returning application", $message);
		
		$applicationProcess = ($app_version == 1) ? "5" : "113";
		$id = $this->addActiveProcesses ($applicationProcess, $user_arr[0]);
		$this->completeActiveProcesses ();
	}
	
	function returnReaccAppToInstBeforePayment($reacc_app_id){
		// Get current active institutional administrator - not user that started the application.
		$user_arr = $this->getInstitutionAdministrator(0,"",$reacc_app_id);
		if ($user_arr[0]==0){
			echo "Processing has been halted for the following reason: <br><br>";
			echo $user_arr[1];
			die();
		}
		$app_user_email = $this->getValueFromTable("users", "user_id",$user_arr[0], "email");
		
		$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_app_id, "reacc_submission_date", "1000-01-01");
		$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_app_id, "reacc_applic_printed", "0");

		$to = $app_user_email;
		$message = $this->getTextContent ("reAccgatekeeper_comment_app", "cancelSubmission");
		$this->misMailByName($to, "Returning application", $message);
		
		$version = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id",$reacc_app_id, "reaccreditationVersion");
	print_r($version);
	
	
	if($version == 1){
		$applicationProcess = "130";
		$flow = "1306";	
	}
	else{	
	  $applicationProcess = "210";
	  $flow = "11609";
		
	}
		$id = $this->addActiveProcesses ($applicationProcess, $user_arr[0], $flow);
		$this->completeActiveProcesses ();
	}
	
	function get_outcome_history($app_id){
		$total_meetings = 0;
		$data = "";

		$SQL =<<< sql
			SELECT * FROM ia_AC_outcomes
			WHERE application_ref=$app_id
			ORDER BY AC_date_updated
sql;

		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

		if (mysqli_num_rows($rs) > 0) {
			$total_meetings = mysqli_num_rows($rs);
			while ($row = mysqli_fetch_array($rs)) {
				//$comment_excerpt = ($row['AC_conditions']) ? substr($row['AC_conditions'], 0, 75)."..." : "";
				$ia_id = $row["ia_AC_outcomes_id"];
				//$AC_comments 	 = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$ia_id.'&table=ia_AC_outcomes&return_field=AC_conditions&id_name=ia_AC_outcomes_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a>';
				$AC_comments = simple_text2html($row['AC_conditions']);
				$AC_cond_octoDoc = new octoDoc($row['AC_conditions_doc']);
				$AC_cond_doc	 = "<a href='".$AC_cond_octoDoc->url()."' target='_blank'>".$AC_cond_octoDoc->getFilename()."</a>";
				$AC_decision	 = $this->getValueFromTable("lkp_desicion", "lkp_id", $row['AC_decision'],"lkp_title");
				$meetingDate = $row['AC_meeting_date'];
				$last_updated	 = $row['AC_date_updated'];

				$data .=<<<DATA
					<tr class='onblue'>
					<td valign='top' width="10%">$meetingDate</td>
					<td valign='top'>$AC_decision</td>
					<td valign='top'>$AC_cond_doc</td>
					<td valign='top'><span class="specials">$AC_comments</span></td>
					<td valign='top'>$last_updated</td>
					</tr>
DATA;
			}
			} else {
				//if nothing exists in ia_AC_outcomes, let's check if there is anything in the Institutions_application
				//(AC history may not have been changed yet)
				$SQL2 =<<<sql
					SELECT * FROM Institutions_application
					WHERE application_id=$app_id
sql;
				$rs2 = mysqli_query($this->getDatabaseConnection(), $SQL2);
				if (mysqli_num_rows($rs2) > 0) {
					$total_meetings = mysqli_num_rows($rs2);
					while ($row2 = mysqli_fetch_array($rs2)) {
						//$comment_excerpt = ($row2['AC_conditions']) ? substr($row2['AC_conditions'], 0, 75)."..." : "";
						$ia_id = "";
						//$AC_comments 	 = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$ia_id.'&table=ia_AC_outcomes&return_field=AC_conditions&id_name=ia_AC_outcomes_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a>';
						$AC_comments = simple_text2html($row2['AC_conditions']);
						$AC_cond_octoDoc = new octoDoc($row2['AC_conditions_doc']);
						$AC_cond_doc	 = "<a href='".$AC_cond_octoDoc->url()."' target='_blank'>".$AC_cond_octoDoc->getFilename()."</a>";
						$AC_decision	 = $this->getValueFromTable("lkp_desicion", "lkp_id", $row2['AC_desision'],"lkp_title");
						$meetingDate	 = $row2['AC_Meeting_date'];
			
						$data .=<<< DATA
							<tr class='onblue'>
							<td valign='top'>$meetingDate</td>
							<td valign='top'>$AC_decision</td>
							<td valign='top'>$AC_cond_doc</td>
							<td valign='top'><span class="specials">$AC_comments</span></td>
							<td valign='top'>$meetingDate</td>
							</tr>
DATA;
					}
				} else {
					$data = '<tr class="onblue"><td colspan="5" align="center">- No AC meeting history exists for this application -</td></tr>';
				}
	}

	$html =<<<html_text
		<table>
		<tr align="right">
		<td colspan="5">The outcome has been updated $total_meetings time(s)</td>
		</tr>
		<tr class='oncolourb' valign='top'>
		<td width='8%'>AC meeting date</td>
		<td width='15%'>AC decision</td>
		<td width='15%'>AC document</td>
		<td width='50%'>AC conditions/comments</td>
		<td width='12%'>Information last updated:</td>
		</tr>
		$data
		</table>
html_text;

		return $html;
	}
	

	function download($f, $icon="", $text, $class="", $path="") {
	
		$fs = 0;
		$imgPath = $path."images/";
		if (substr($icon,0,1) == ".") $imgPath = "";
		$img = ($icon > "") ? '<img src="'.$imgPath.$icon.'">' : "";
		$h = '('.$text.' '.$img.' - document currently unavailable) ';
		
		if (file_exists($f)){
			$met = "Kb";
			$fs = filesize($f);
			if ($fs > 0) {
				$s = round(($fs/1024),0);
				if ($s >= 1000) {
					$s = round(($s/1024),1);
					$met = "Mb";
				}
				if ($class > "") $class = 'class="'.$class.'" ';
				$alt = "";
				$h = '<a title="'.$alt.'" '.$class.'href="'.$f.'" target="_blank">'.$img.' '.$text.' ('.$s.$met.')</a>';
			}
		}
		return $h;
	}

	// 2010-10-27 Robin - new function called from checklisting, screening and evaluation in order to return an application to an institution.
	function returnAppToInstWithPayment($app_id,$proc=""){
		// 2010/10/27 Robin: Return the application to the institutional administrator instead of the user who started the application.
		// $to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id , "user_ref"), "email");
		// $new_user = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref");
		
		// In order to prevent a refresh from re-inserting the active process - only do the processing if the current active process status is 0 (active).
		// If status is '1' then it has already been closed and the processing has been doen.

		$current_process_status = -1;  // initialise
		$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
		if ($current_process_status == 0):
			$ins_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
			$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
			if ($user_arr[0]==0):
				echo $user_arr[1];
				die();
			endif;
			$new_user = $user_arr[0];
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			$message = $this->getTextContent ("checkForm1c", "applicationToInstitution");
			$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_printed", "0");
			if ($proc == "screening"){
				$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
			}
			$cc = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
			$this->misMailByName($to, "Status of application for registration", $message, $cc);
			//if is 1, old app, send to 5, if 2 or 3 is new app, send to 113
			$applicationProcess = ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version") == 1) ? "5" : "113";

			// 2010-10-27 Robin: Replace with an insert of an active process instead of changing the user on the current process because you lose the history.
			// This also brough the problem on additional inserts on refresh - but checking status resolves it.
			// $this->changeActiveProcesses ($applicationProcess, $new_user);
			// $this->clearWorkflowSettings ();
			// $this->startFlow (__HOMEPAGE);
			$id = $this->addActiveProcesses ($applicationProcess, $new_user);
			$this->completeActiveProcesses();
		endif; 
	}

	// 2010-10-27 Robin - function to pass processes back and forwards if given the new user and process and last workflow.
	function changeProcessAndUser($new_proc, $new_user, $mail_subject="",$mail_content="", $flow=0,$newWorkFlow=""){
		// In order to prevent a refresh from re-inserting the active process - only do the processing if the current active process status is 0 (active).
		// If status is '1' then it has already been closed and the processing has been done.
//echo "************* New process: " . $new_proc . "**************<br><br>";
//echo "************* New user: " . $new_user . "**************<br><br>";
		$current_process_status = -1;  // initialise
		$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
//echo "************* Current process id: " . $this->active_processes_id . "**************<br><br>";
//echo "************* Current process status: " . $current_process_status . "**************<br><br>";
		if ($current_process_status == 0):
		
			// If an email text is provided then email the user
			if ($mail_subject > "" || $mail_content > ""):
				$cc = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
				$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
//echo "************* cc: " . $cc . "**************<br><br>";
//echo "************* to: " . $to . "**************<br><br>";
				$this->misMailByName($to, $mail_subject, $mail_content, $cc);
			endif;
		
			$id = $this->addActiveProcesses ($new_proc, $new_user, $flow);

			$this->completeActiveProcesses();
//echo "************* id: " . $id . "**************<br><br>";

		endif; 
	}
	
	function notify_finance_percent_complete($mail_subject="",$mail_content="",$finind){
		// In order to prevent a refresh from re-inserting the active process 
		//    - only do the processing if the current active process status is 0 (active).
		//    - If status is '1' then it has already been closed and the processing has been done.
		$current_process_status = -1;
		$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
		$app_id = $this->dbTableInfoArray['Institutions_application']->dbTableCurrentID;
		//$proc_type_id = 0; // No proceeding
		$proc_id = 0; // No proceeding
		if (isset($this->dbTableInfoArray['ia_proceedings'])){
			$proc_id = $this->dbTableInfoArray['ia_proceedings']->dbTableCurrentID;
			//$proc_type_id = $this->getValueFromTable('ia_proceedings','ia_proceedings_id',$proc_id,'lkp_proceedings_ref');
		}

		$heqc_ref = $this->getValueFromTable("Institutions_application","application_id",$app_id,"CHE_reference_code");
		//$subm_date = $this->getValueFromTable("Institutions_application","application_id",$app_id,"submission_date");
		$inst_id = $this->getValueFromTable("Institutions_application","application_id",$app_id,"institution_id");
		$inst_type = $this->getValueFromTable("HEInstitution","HEI_id",$inst_id,"priv_publ");

		// 2014-07-17: Robin - Re-instate financial indicators - commented out line below
		//	if ($subm_date < '2013-04-01'):
		// Private institutions only - excluding Agricultural
		// 2015-04-06: Moving the Private condition to emails only so we can record dates
		// for public as well.  could be used in reporting.
		//if ( $inst_type == 1 && strpos($heqc_ref,'PR') > 0):  
			// 2014-07-17: Robin - Katongo requested indicators for deferrals and representations
			//    - so comented out the exclusion
			//if ($proc_type_id == 0 || $proc_type_id ==1):  
			// Proceeding record must exist.  All applications should have proceedings by this stage.
			$finind_date = '1000-01-01';
			if ($proc_id > 0 ):
				switch ($finind){
				case "1":
					$finind_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"finind_complete_25");
					break;
				case "2":
					$finind_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"finind_complete_50");
					break;
				case "3":
					$finind_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"finind_complete_75");
					break;
				case "4":
					$finind_date = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$proc_id,"finind_complete_100");
					break;
				}

				if ($finind_date == '1000-01-01' && $current_process_status == 0):
					// If an email text is provided then email the user
					if ($mail_subject > "" || $mail_content > ""):
						//$cc = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
						//$finance_user1 = $this->getDBsettingsValue("usr_registry_payment");
						//$finance_user2 = $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_payment_query"), "email");
						$finance_users = $this->getDBsettingsValue("usr_finance_indicator_emails");
						$finance_user_arr = explode(',',$finance_users);
						$proj_adm_fld = ($inst_type == 2) ? ("usr_project_admin_pub") : ("usr_project_admin_priv");
						$proj_adm = $this->getDBsettingsValue($proj_adm_fld);
						
						$today = date("Y-m-d");
						if ($finind == 1) {
							$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "finind_complete_25", $today);
						}
						if ($finind == 2) {
							$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "finind_complete_50", $today);
						}
						if ($finind == 3) {
							$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "finind_complete_75", $today);
						}
						if ($finind == 4) {
							$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "finind_complete_100", $today);
						}
						
						//$cc = $this->getValueFromTable("users", "user_id", $proj_adm, "email") . "," . $finance_user2;
						//$cc = $this->getValueFromTable("users", "user_id", $proj_adm, "email") . "," . $finance_user2;
						//$this->misMail($finance_user1, $mail_subject, $mail_content, $cc);
						//$this->misMail($finance_user2, $mail_subject, $mail_content, $cc);
						//$this->misMail($finance_user1, $mail_subject, $mail_content);
						//$this->misMail($finance_user2, $mail_subject, $mail_content);
						if ( $inst_type == 1 && strpos($heqc_ref,'PR') > 0){
							foreach($finance_user_arr as $u){
								$this->misMail($u, $mail_subject, $mail_content);
							}
						}
					endif;
				endif;
			endif;
		//endif;
		//endif;
	}
	
	function getSelectedRecommUserForAppProceeding ($app_proc_id, $where="") {
	
	$conn= $this->getDatabaseConnection();
		$recomm_arr = array();
		$where_app = "";
		
		if ($where > ""){
			$where_app = " AND " . implode(" AND ", $where);
		}
		
		$SQL =<<<recommSQL
			SELECT users.user_id, CONCAT( users.name, ' ', users.surname ) AS user_name, 
				users.email, users.contact_nr, users.contact_cell_nr, 
				ia_proceedings.ia_proceedings_id,
				ia_proceedings.recomm_user_ref,
				ia_proceedings.application_ref,
				ia_proceedings.lop_isSent_date,
				ia_proceedings.lop_isSent,
				ia_proceedings.portal_sent_date,
				ia_proceedings.recomm_access_end_date,
				ia_proceedings.recomm_complete_ind,
				lkp_proceedings.lkp_proceedings_desc,
				lkp_desicion.lkp_title as recomm_decision
			FROM (ia_proceedings, users)
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
			LEFT JOIN lkp_desicion ON lkp_desicion.lkp_id = ia_proceedings.recomm_decision_ref
			WHERE users.user_id = ia_proceedings.recomm_user_ref
			AND ia_proceedings.ia_proceedings_id=$app_proc_id
			$where_app
			ORDER BY users.surname, users.name
recommSQL;

//echo $SQL;

		$rs = mysqli_query($conn,$SQL);
		if (mysqli_num_rows($rs) > 0):
			$recomm_arr = mysqli_fetch_array($rs);
		endif;
		
		
		return $recomm_arr;
	}

	// 2012-05-08 Robin: This function is used to display the recommendation for approval for application and site recommendations.
	function displayRecommUsers($recomm,$label, $recomm_type="application"){
		
		$cross = '<img src="images/dash_mark.gif">';
		$check = '<img src="images/check_mark.gif">';

		if ($recomm_type == 'application'){
			$link1 = $this->scriptGetForm ('ia_proceedings', $recomm["ia_proceedings_id"], $label);
			$recomm_decision = $recomm['recomm_decision'];
			$proceeding = $recomm['lkp_proceedings_desc'];
		}
		if ($recomm_type == 'site'){
			$link1 = $this->scriptGetForm ('inst_site_app_proceedings', $recomm["inst_site_app_proc_id"], $label);
			$recomm_decision = '&nbsp;';
			$proceeding = $recomm['lkp_site_proceedings_desc'];
		}

		$recomm_complete = ($recomm["recomm_complete_ind"] == 1) ? $check : $cross;


		$html = '<table width="95%" align="center">';
		
		$html .=<<<html
			<tr class='oncolourb'>
				<td><b>Edit<br />recomm.</b></td>
				<td><b>User name</b></td>
				<td><b>Email address</b></td>
				<td><b>Telephone</b></td>
				<td><b>Proceedings</b></td>
				<td><b>Portal access<br>start date</b></td>
				<td><b>Portal access<br>end date</b></td>
				<td><b>Directorate<br>recommendation</b></td>
				<td>Complete<br>indicator</td>
			</tr>
html;
			$html .=<<<html
			<tr>
				<td><a href='$link1'><img src="images/ico_change.gif"></a></td>
				<td>$recomm[user_name]</td>
				<td>$recomm[email]</td>
				<td>$recomm[contact_nr] $recomm[contact_cell_nr]</td>
				<td>$proceeding</td>
				<td>$recomm[portal_sent_date]</td>
				<td>$recomm[recomm_access_end_date]</td>
				<td>$recomm_decision</td>
				<td>$recomm_complete</td>
			</tr>
html;
		$html .= "</table>";
		
		return $html;
	}

	function returnAppToProcess($process_id, $user_setting, $email_text="returnApplication"){
		$current_process_status = -1;  // initialise
		$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
		if ($current_process_status == 0):
			$new_user = $this->getValueFromTable("settings", "s_key", $user_setting, "s_value");
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			$message = $this->getTextContent ("generic", $email_text);
			$this->misMailByName($to, "Application returned", $message);
			$id = $this->addActiveProcesses ($process_id, $new_user);
			$this->completeActiveProcesses();
		endif;
	}

	function getApplicationBackground($app_id){
		return "Application background";
	}

	function generateACMeetingDocument ($meet_id=0, $type, $agenda_type) {
		//2017-09-13: Richard - Added AC agenda type
		switch ($agenda_type){
		case 'consent':
			$agenda_heading = "CONSENT AGENDA";
			$agenda_id = 1;
			break;
		case 'discuss':
			$agenda_heading = "DISCUSSION AGENDA";
			$agenda_id = 2;
			break;
		default:
			$agenda_heading = "DISCUSSION AGENDA";
			$agenda_id = 2;
		}
/*		$report =<<<INTRO
		<br />
		<b>ACCREDITATION COMMITTEE MEETING DOCUMENTATION</b>
		<br /><br />
		<page />
INTRO;*/
		$report =<<<INTRO
		<br />
		<b>ACCREDITATION COMMITTEE MEETING DOCUMENTATION</b>
		<br /><br />
		<b>$agenda_heading</b>
		<page />
INTRO;
/*		$SQL =<<<REPORT
			SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
			AND ia_proceedings.lkp_proceedings_ref <> 4 
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, Institutions_application.program_name
REPORT;*/
		//2017-09-13: Richard - Added AC agenda type
		$SQL =<<<REPORT
			SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
			AND ia_proceedings.lkp_proceedings_ref <> 4 
			AND ia_proceedings.lkp_AC_agenda_type_ref = $agenda_id
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, Institutions_application.program_name
REPORT;
// 20140106 Robin: Conditional proceedings must display in AC Agenda.
//			AND ia_proceedings.lkp_proceedings_ref <> 4 
// 20150629 Robin: Exclude conditional proceedings from AC Agenda

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

} 
$conn->set_charset("utf8");

		$rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No deferrals, representations or accreditation applications have been assigned to this meeting.</p><page />";
		}
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatRecomm($row);
			
			$report .=<<<REPORT
				<page />
REPORT;
		endwhile;

		$report .= HEQConline::formatConditionsSummaryForMeeting($meet_id, $agenda_id);

		return $report;
	}

	function generateRecomm ($proc_id) {

		if ($proc_id == '') return false;

		$report = "";
		
		$SQL =<<<REPORT
			SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.ia_proceedings_id = $proc_id
REPORT;
//echo $SQL;
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
            //    $sm = $conn->prepare($SQL);
             //   $sm->bind_param("s", $proc_id);
             //   $sm->execute();
             //$rs = $sm->get_result();
            $conn->set_charset("utf8");
    
		$rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No application proceedings for specified proceeding.</p>";
		}
		// Only 1 row should be returned. USing while anyway.
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatRecomm($row);
		endwhile;

		return $report;
	}
	
	function formatRecomm($row){
			$app_id = $row["application_id"];
			$proc_id = $row["ia_proceedings_id"];
			$proc_type = $row["lkp_proceedings_ref"];
			$proceeding_desc = DBConnect::getValueFromTable("lkp_proceedings", "lkp_proceedings_id", $proc_type, "lkp_proceedings_desc");
			$meet_id = $row["ac_meeting_ref"];
			$ac_start_date = DBConnect::getValueFromTable("AC_Meeting", "ac_id", $meet_id, "ac_start_date");
			$ac_meeting_start = date("jS F Y",strtotime($ac_start_date));
			$rec_outcome = $row["rec_outcome"] ? $row["rec_outcome"] : "&nbsp;";
			$rec_heading = $row["rec_heading"] ? $row["rec_heading"] : "&nbsp;";
			$backg = $row["applic_background"] ? simple_text2html($row["applic_background"], "docgen") : "&nbsp;";

			$eval_summ = $row["eval_report_summary"] ? simple_text2html($row["eval_report_summary"], "docgen") : "&nbsp;";
			$app_header = HEQConline::getHEQCApplicationTableTop($app_id,'ext');
			$report = "";

			// Note:  Formatting below is specific: <br />$backg on one line otherwise a space is added before the $backg text.
			$report .=<<<REPORT
			<table width="170" border="0" align="center">
			<tr>
				<td align="center">
					<b>HIGHER EDUCATION QUALITY COMMITTEE<br />
					ACCREDITATION COMMITTEE<br />
					<u>MEETING HELD ON <i>$ac_meeting_start</i></u></b>
					<br />
				</td>
			</tr>
			<tr>
				<td>
					<b>Record of proceedings relating to:</b>
				</td>
			</tr>
			</table>
			
			<table width="170" border="1" align="center">
			<tr>
				<td colspan="2">$proceeding_desc</td>
			</tr>
			</table>
			$app_header
			<br /><table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Background</b>
					<br />$backg
				</td>
			</tr>
			</table>
			<br /><table width="170" border="1" align="center">
			<tr>
				<td align="left">
					<b>Summary of Evaluator Report</b>
					<br />$eval_summ
				</td>
			</tr>
			</table>
			<br /><table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Directorate Recommendation</b>
					<br />The Accreditation Directorate recommends that the <i>{$row["program_name"]} ({$row["num_credits"]} credits, {$row["lkp_mode_of_delivery_desc"]} mode)</i> be 
					<br />
					&nbsp;&nbsp;&nbsp; <b>- $rec_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			<br />
REPORT;
			$decision = $row["recomm_decision_ref"];
			$rpt_reason = HEQConline::display_outcome_reason('ia_proceedings_recomm_decision',$proc_id,$rec_heading,$decision);

			$report .= $rpt_reason;
			
			if ($proc_type == 4){
				$app_conditions = HEQConline::displayConditions($app_id, $for="application");
				$report .= "<br /><b>State of all conditions for this application</b><br />" . $app_conditions;
			}
			
		return $report;
	}

	// This function is used for reasons for application recomendation and site recomendations 
	// became they are exactly the same format and values
	/*function display_outcome_reason($table, $ref_id,$heading="",$decision, $type="ext"){
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$reason_width = ($type == 'ext') ? '30' : '30%';  
		$proc_type = -1;
		switch ($table){
		case 'ia_proceedings_recomm_decision':
		case 'ia_proceedings_ac_decision':
		case 'ia_proceedings_heqc_decision':
			$table_width = ($type == 'ext') ? '170' : '90%';  // formatted for rtf docs and html
			$ref_clause = ' ia_proceedings_ref = ' . $ref_id;
			$proc_type = DBConnect::getValueFromTable("ia_proceedings","ia_proceedings_id",$ref_id,"lkp_proceedings_ref");
			if ($heading == ""){
				$heading = DBConnect::getValueFromTable("lkp_desicion", "lkp_id", $decision, "outcome_reason_heading");
			}
			break;
		case 'inst_site_visit_recomm_decision':
		case 'inst_site_visit_ac_decision':
		case 'inst_site_visit_heqc_decision':
			$table_width = ($type == 'ext') ? '165' : '90%';  // formatted for rtf docs and html
			$ref_clause = ' inst_site_visit_ref = ' . $ref_id;
			if ($heading == ""){
				$heading = DBConnect::getValueFromTable("lkp_decision_site", "lkp_decision_site_id", $decision, "outcome_reason_heading");
			}
			break;
		}
		// Get the reasons or conditions for the outcome.
		$rpt_reason = "";
	
		if ($ref_clause > ''){
			if ($decision <> 1){
			// There are NO reasons for provisionally accredited outcome
				$o_sql =<<<OSQL
					SELECT * 
					FROM $table
					LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
					WHERE $ref_clause
					ORDER BY decision_reason_condition
OSQL;
				$o_rs = mysqli_query($conn, $o_sql);
				$num_reason = mysqli_num_rows($o_rs);
				
				if ($num_reason > 0):
					$conditions_eval_heading = "";
					$conditions_met_heading = "";

					if ($proc_type == 4){
						if ($table == 'ia_proceedings_recomm_decision'){
							$conditions_eval_heading =<<<COND
								<td>Evaluator comments</td>
COND;
						}
						$conditions_met_heading =<<<COND
							<td width="10%">Met?</td>
COND;
					}
					$rpt_reason =<<<REPORT
						<table width="$table_width" border="1" align="center">
						<tr>
							<td><b>$heading</b></td>
							<td width="$reason_width"><b>Criterion</b></td>
							$conditions_eval_heading
							$conditions_met_heading
						</tr>
REPORT;
					while ($o_row = mysqli_fetch_array($o_rs)){
						$reas_cond = "";
						// Conditions have a heading
						if ($o_row["lkp_condition_term_id"] <> "a" && $o_row["lkp_condition_term_desc"] > ""){
							$reas_cond = "<b>" . $o_row["lkp_condition_term_desc"] . "</b>";
							$reas_cond .= $o_row["decision_reason_condition"] ? "<br />".$o_row["decision_reason_condition"] : "&nbsp;";
						} else {  // deferred, not accredited
							$reas_cond .= $o_row["decision_reason_condition"] ? $o_row["decision_reason_condition"] : "&nbsp;";
						}
						$reas_cond_fmt = ($reas_cond) ? simple_text2html($reas_cond, "docgen") : "&nbsp;";
						$crit = $o_row["criterion_min_standard"] ? $o_row["criterion_min_standard"] : "&nbsp;";

					$conditions_eval = "";
					$conditions_met = "";

					if ($proc_type == 4){
						if ($table == 'ia_proceedings_recomm_decision'){
							$eval_comment = DBConnect::getValueFromTable("ia_conditions_proceedings","ia_conditions_proceedings_id",$o_row["ia_conditions_proceedings_ref"],"eval_comment");
							$eval_comment = ($eval_comment > "" ? $eval_comment : "&nbsp;");
							$conditions_eval =<<<COND
								<td>$eval_comment</td>
COND;
						}
						$met_yn = DBConnect::getValueFromTable("lkp_yes_no","lkp_yn_id",$o_row["condition_met_yn_ref"],"lkp_yn_desc");
						$met_yn = ($met_yn > "" ? $met_yn : "&nbsp;");
						$conditions_met =<<<COND
							<td>$met_yn</td>
COND;
					}

						$rpt_reason .=<<<REPORT
							<tr>
								<td>$reas_cond_fmt</td>
								<td>$crit</td>
								$conditions_eval
								$conditions_met
							</tr>
REPORT;
				}
	
				$rpt_reason .=<<<REPORT
						</table>
REPORT;
				endif;

				} // end decision <> 1
		}
		return $rpt_reason;
	}
	*/
	function display_outcome_reason($table, $ref_id,$heading="",$decision, $type="ext"){
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$reason_width = ($type == 'ext') ? '30' : '30%';  
		$proc_type = -1;
		switch ($table){
		case 'ia_proceedings_recomm_decision':
		case 'ia_proceedings_ac_decision':
		case 'ia_proceedings_heqc_decision':
			$table_width = ($type == 'ext') ? '170' : '90%';  // formatted for rtf docs and html
			$ref_clause = ' ia_proceedings_ref = ' . $ref_id;
			$proc_type = DBConnect::getValueFromTable("ia_proceedings","ia_proceedings_id",$ref_id,"lkp_proceedings_ref");
			if ($heading == ""){
				$heading = DBConnect::getValueFromTable("lkp_desicion", "lkp_id", $decision, "outcome_reason_heading");
			}
			break;
		case 'inst_site_visit_recomm_decision':
		case 'inst_site_visit_ac_decision':
		case 'inst_site_visit_heqc_decision':
			$table_width = ($type == 'ext') ? '165' : '90%';  // formatted for rtf docs and html
			$ref_clause = ' inst_site_visit_ref = ' . $ref_id;
			if ($heading == ""){
				$heading = DBConnect::getValueFromTable("lkp_decision_site", "lkp_decision_site_id", $decision, "outcome_reason_heading");
			}
			break;
		}
		// Get the reasons or conditions for the outcome.
		$rpt_reason = "";
	
		if ($ref_clause > ''){
			//if ($decision <> 1){
			// There are NO reasons for provisionally accredited outcome
				$o_sql =<<<OSQL
					SELECT * 
					FROM $table
					LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
					WHERE $ref_clause
					ORDER BY decision_reason_condition
OSQL;

				//Determine if there is a reason with content 
				$d_rs = mysqli_query($conn, $o_sql);
				
				while ($d_row = mysqli_fetch_array($d_rs)){
					if ($d_row["decision_reason_condition"] > "" or $d_row["decision_reason_condition"] <> null) {
						$valid_reason_ind = 1;
						//return;
					}
				}


				// Display reasons in table if any reason has a value
				$o_rs = mysqli_query($conn, $o_sql);
				$num_reason = mysqli_num_rows($o_rs);
				
				if ($num_reason > 0 && $valid_reason_ind == 1):
				//if ($num_reason > 0 ):
					$conditions_eval_heading = "";
					$conditions_met_heading = "";

					if ($proc_type == 4){
						if ($table == 'ia_proceedings_recomm_decision'){
							$conditions_eval_heading =<<<COND
								<td>Evaluator comments</td>
COND;
						}
						$conditions_met_heading =<<<COND
							<td width="10%">Met?</td>
COND;
					}
					
					$rpt_reason =<<<REPORT
						<table width="$table_width" border="1" align="center">
						<tr>
							<td><b>$heading</b></td>
							<td width="$reason_width"><b>Criterion</b></td>
							$conditions_eval_heading
							$conditions_met_heading
						</tr>
REPORT;
					while ($o_row = mysqli_fetch_array($o_rs)){
						$reas_cond = "";
						// Conditions have a heading
						if ($o_row["lkp_condition_term_id"] <> "a" && $o_row["lkp_condition_term_desc"] > ""){
							$reas_cond = "<b>" . $o_row["lkp_condition_term_desc"] . "</b>";
							$reas_cond .= $o_row["decision_reason_condition"] ? "<br />".$o_row["decision_reason_condition"] : "&nbsp;";
						} else {  // deferred, not accredited
							$reas_cond .= $o_row["decision_reason_condition"] ? $o_row["decision_reason_condition"] : "&nbsp;";
						}
						$reas_cond_fmt = ($reas_cond) ? simple_text2html($reas_cond, "docgen") : "&nbsp;";
						$crit = $o_row["criterion_min_standard"] ? $o_row["criterion_min_standard"] : "&nbsp;";

						$conditions_eval = "";
						$conditions_met = "";

						if ($proc_type == 4){
							if ($table == 'ia_proceedings_recomm_decision'){
								$eval_comment = DBConnect::getValueFromTable("ia_conditions_proceedings","ia_conditions_proceedings_id",$o_row["ia_conditions_proceedings_ref"],"eval_comment");
								$eval_comment = ($eval_comment > "" ? $eval_comment : "&nbsp;");
								$conditions_eval =<<<COND
									<td>$eval_comment</td>
COND;
							}
							$met_yn = DBConnect::getValueFromTable("lkp_yes_no","lkp_yn_id",$o_row["condition_met_yn_ref"],"lkp_yn_desc");
							$met_yn = ($met_yn > "" ? $met_yn : "&nbsp;");
							$conditions_met =<<<COND
								<td>$met_yn</td>
COND;
						}

						$rpt_reason .=<<<REPORT
							<tr>
								<td>$reas_cond_fmt</td>
								<td>$crit</td>
								$conditions_eval
								$conditions_met
							</tr>
REPORT;
					}
	
				$rpt_reason .=<<<REPORT
						</table>
REPORT;
				endif;
				//}

				//} // end decision <> 1
		}
		return $rpt_reason;
	}

	function generateDocument($proc_id,$xml_doc,$fileName,$docTable,$docTableKey,$docField){
		$doc = new octoDocGen ($xml_doc, "proc_id=".$proc_id);
		$filepath = OCTODOC_DIR.$fileName;

		$rc = $doc->saveDoc($filepath);
		if ($rc) {
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
            if ($conn->connect_errno) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }
			$docID = $this->getValueFromTable("documents","document_url",$fileName,"document_id");
			if ($docID == ""){
				//new document
				$dSQL = "INSERT INTO documents (creation_date,last_update_date,document_name,document_url) values (now(),now(),\"".$fileName."\",\"".$fileName."\")";
				$errorMail = false;
				mysqli_query($conn, $dSQL) or $errorMail = true;
				// location is critical.  Wrong docId will be inserted if this does not occur directly after the statement it refers to.
				$docID = mysqli_insert_id($conn);
				$this->writeLogInfo(10, "SQL-INSREC", "Creation of directorate recommendation:"  . $dSQL."  --> ".mysqli_error($conn), $errorMail);
				
				$iSQL = "UPDATE $docTable set $docField=".$docID." WHERE $docTableKey=".$proc_id;
				$errorMail = false;
				mysqli_query($conn, $iSQL) or $errorMail = true;
				$this->writeLogInfo(10, "SQL-UPDREC", "Creation of directorate recommendation:"  . $iSQL."  --> ".mysqli_error($conn), $errorMail);
			}else{
				//update Document
				$uSQL = "UPDATE documents set last_update_date = now() WHERE document_id=".$docID;
				$errorMail = false;
				mysqli_query($conn, $uSQL) or $errorMail = true;
				$this->writeLogInfo(10, "SQL-UPDREC", "Creation of directorate recommendation:"  . $uSQL."  --> ".mysqli_error($conn), $errorMail);
			}
		}
	}

	function generateACMeetingMinutes ($meet_id=0) {
		$report =<<<INTRO
		<br />
		<b>ACCREDITATION COMMITTEE MEETING MINUTES</b>
		<br /><br />
		<page />
INTRO;

		$SQL =<<<REPORT
			SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				ac.lkp_title AS ac_outcome,
				ac.outcome_reason_heading AS ac_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_desicion AS ac ON ac.lkp_id = ia_proceedings.ac_decision_ref 
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
            AND ia_proceedings.lkp_proceedings_ref NOT IN (4, 6)
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, Institutions_application.program_name
REPORT;



$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
$conn->set_charset("utf8");

		$rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No applications have been assigned to this meeting.</p>";
		}
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatACminutes($row);
			
			$report .=<<<REPORT
				<page />
REPORT;
		endwhile;

		return $report;
	}
	

	function formatACminutes($row){
			$app_id = $row["application_id"];
			$proc_id = $row["ia_proceedings_id"];
			$proceeding_desc = DBConnect::getValueFromTable("lkp_proceedings", "lkp_proceedings_id", $row["lkp_proceedings_ref"], "lkp_proceedings_desc");
			$meet_id = $row["ac_meeting_ref"];
			$ac_start_date = DBConnect::getValueFromTable("AC_Meeting", "ac_id", $meet_id, "ac_start_date");
			$ac_meeting_start = date("jS F Y",strtotime($ac_start_date));
			$rec_outcome = $row["rec_outcome"] ? $row["rec_outcome"] : "";
			$rec_heading = $row["rec_heading"] ? $row["rec_heading"] : "";
			$ac_outcome = $row["ac_outcome"] ? $row["ac_outcome"] : "";
			$ac_heading = $row["ac_heading"] ? $row["ac_heading"] : "";
			$backg = $row["applic_background_ac"] ? simple_text2html($row["applic_background_ac"], "docgen") : "";
			$eval_summ = $row["eval_report_summary_ac"] ? simple_text2html($row["eval_report_summary_ac"], "docgen") : "";
			$ac_discuss = $row["minutes_discussion"] ? simple_text2html($row["minutes_discussion"], "docgen") : "";
			$app_header = HEQConline::getHEQCApplicationTableTop($app_id,'ext');
			$report = "";

			$report .=<<<REPORT
			<table width="170" border="0" align="center">
			<tr>
				<td align="center">
					<b>HIGHER EDUCATION QUALITY COMMITTEE<br />
					ACCREDITATION COMMITTEE<br />
					<u>MEETING HELD ON <i>$ac_meeting_start</i></u></b>
					<br />
				</td>
			</tr>
			<tr>
				<td>
					<b>Record of proceedings relating to:</b>
				</td>
			</tr>
			</table>
			
			<table width="170" border="1" align="center">
			<tr>
				<td colspan="2">$proceeding_desc</td>
			</tr>
			</table>
			$app_header

			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Background</b>
					<br />$backg
				</td>
			</tr>
			</table>
			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Summary of Evaluator Report</b>
					<br />$eval_summ
				</td>
			</tr>
			</table>

			<br />

			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Directorate Recommendation</b>
					<br />The Accreditation Directorate recommends that the <i>{$row["program_name"]} ({$row["num_credits"]} credits, {$row["lkp_mode_of_delivery_desc"]} mode)</i> be 
					<br />
					 <b> $rec_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			<br />
REPORT;
			$rec_decision = $row["recomm_decision_ref"];
			$rec_reason = HEQConline::display_outcome_reason('ia_proceedings_recomm_decision',$proc_id,$rec_heading,$rec_decision);

			$report .= $rec_reason;

			$report .=<<<REPORT
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Accreditation Committee Discussion</b>
					<br />$ac_discuss
				</td>
			</tr>
			</table>

			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Accreditation Committee Recommendation</b>
					<br />The Accreditation Committee recommends that the <i>{$row["program_name"]} ({$row["num_credits"]} credits, {$row["lkp_mode_of_delivery_desc"]} mode)</i> be 
					<br />
					 <b> $ac_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			
			<br />
REPORT;
			$ac_decision = $row["ac_decision_ref"];
			$ac_reason = HEQConline::display_outcome_reason('ia_proceedings_ac_decision',$proc_id,$ac_heading, $ac_decision);

			$report .= $ac_reason;
			
			return $report;
	}
	
	function edit_outcomes($dec_field, $app_proc_id){
		$proc_type = $this->getValueFromTable('ia_proceedings','ia_proceedings_id', $app_proc_id, "lkp_proceedings_ref");
		
		$dec = $this->formFields[$dec_field]->fieldValue;
		// 2011-03-01 Robin
		// I tried to setup two divs: one for conditional and one for the rest but because they both use the same grid and key to same table it creates confusion.
		//if ($dec == 2): // conditional accreditation
			//$dFields = array();
			//array_push($dFields, "type__textarea|cols__80|rows__7|name__decision_reason_condition");
			//array_push($dFields, "type__text|size__25|name__criterion_min_standard");
			//array_push($dFields, "type__select|name__condition_term_ref|description_fld__lkp_condition_term_desc|fld_key__lkp_condition_term_id|lkp_table__lkp_condition_term|lkp_condition__1|order_by__lkp_condition_term_id");

			//$hFields = array("Reasons for deferral or non-accreditation or conditions for conditional accreditation", "Criterion and <br>Minimum Standards","Condition term<br> (if applicable)");
		//else: 
			//$dFields = array();
			//array_push($dFields, "type__textarea|size__255|name__decision_reason_condition");
			//array_push($dFields, "type__textarea|size__255|name__criterion_min_standard");
			//$hFields = array("Reason", "Criterion and Minimum Standards");
		//endif;
		
		// 2013-12-20: Conditions must go through the same processes as other proceedings.  Conditions must not be editable.  User must indicate if met or not.
		$add = "true";
		$del = "true";
		if ($proc_type == 4){ // Conditional proceedings - user may only indicate whether condition has been met or not and may not change condition.
			$dFields = array();
			array_push($dFields, "type__textarea|status__3|cols__40|rows__7|name__decision_reason_condition");
			array_push($dFields, "type__text|status__3|name__criterion_min_standard");
			array_push($dFields, "type__select|status__3|name__condition_term_ref|description_fld__lkp_condition_term_desc|fld_key__lkp_condition_term_id|lkp_table__lkp_condition_term|lkp_condition__1|order_by__lkp_condition_term_id");
			// Only display evaluation if it is the recommendation or AC meeting
			if ($dec_field == 'recomm_decision_ref' || $dec_field == 'ac_decision_ref'){
				array_push($dFields, "type__select|status__3|name__ia_conditions_proceedings_ref|description_fld__eval_comment|fld_key__ia_conditions_proceedings_id|lkp_table__ia_conditions_proceedings|lkp_condition__1|order_by__ia_conditions_proceedings_id");
				$hFields = array("Condition", "Criterion and <br>Minimum<br>Standards","Condition term<br> (if applicable)","Evaluator comment","Recomm<br>Met?");
			} else {
				$hFields = array("Condition", "Criterion and <br>Minimum<br>Standards","Condition term<br> (if applicable)","Recomm<br>Met?");
			}
			array_push($dFields, "type__select|name__condition_met_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_id");

			$add = "";
			$del = "";
		} else {
			$dFields = array();
			array_push($dFields, "type__textarea|cols__80|rows__7|name__decision_reason_condition");
			array_push($dFields, "type__text|size__25|name__criterion_min_standard");
			array_push($dFields, "type__select|name__condition_term_ref|description_fld__lkp_condition_term_desc|fld_key__lkp_condition_term_id|lkp_table__lkp_condition_term|lkp_condition__1|order_by__lkp_condition_term_id");

			$hFields = array("Reasons for deferral or non-accreditation or conditions for conditional accreditation", "Criterion and <br>Minimum Standards","Condition term<br> (if applicable)");
		}
		
		switch ($dec_field){
		case "recomm_decision_ref":
		
			$html1 =<<<HTML1
			<table><tr><td>
				<b>Directorate Recommendation:</b>
				<br>
				<div style="border:2px solid #CCCCCC; padding:5px 2px">
				The Accreditation Directorate recommends that this programme be:
HTML1;

			$html2 =<<<HTML2
				<p>
				</p>
				<table>
				<tr>
					<td colspan="3"></td>
					<b>Please enter the reason if the recommendation is deferred or not accredited or enter the conditions if accreditation is conditional.</b>
					<br>
					<b>Please indicate whether the condition has been met or not if this is a conditional proceedings.</b>.

				</tr>
HTML2;

			$html3 =<<<HTML3
				</table>
				</div>
			</td></tr></table>
HTML3;

			echo $html1;
			if ($proc_type == 4){
				$this->formFields["recomm_decision_ref"]->fieldStatus = 2;
			}
			$this->restrictOutcomeValuesperProceeding($proc_type,"recomm_decision_ref");
			$this->showField('recomm_decision_ref');
			echo $html2;
			echo '<tr><td>';
			$this->gridShowRowByRow("ia_proceedings_recomm_decision", "ia_proceedings_recomm_decision_id", "ia_proceedings_ref__".$app_proc_id, $dFields,$hFields, 10, 5, $add, $del, 1, "decision_reason_condition");
			echo "</td></tr>";
			echo $html3;
			
			break;
		case "ac_decision_ref":

                        //$htmlOne =<<<HTML
                        echo "<tr>";
			echo "	<td>";
			echo "		Background:<br>";
                                            $this->showField('applic_background_ac');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		Summary of Evaluator's Report:<br>";
					$this->showField('eval_report_summary_ac');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		<table width='95%'>";
			echo "		<tr>";
			echo "			<td>The Accreditation Committee recommends:</td>";
			echo "			<td> ";
                                                        if ($proc_type == 4){
                                                                $this->formFields["ac_decision_ref"]->fieldStatus = 2;
                                                        }
                                                        $this->restrictOutcomeValuesperProceeding($proc_type,"ac_decision_ref");
                                                        $this->showField('ac_decision_ref');
			echo "			</td>";
			echo "		</tr>";
			echo "		<tr>";
			echo "			<td colspan='2'><hr></td>";
			echo "		</tr>";
			echo "		<tr>";
			echo "			<td colspan='2'>";
			echo "				Please indicate the <u>reason</u> for the outcome if the outcome is <b><i>not accredited</i></b> or <b><i>deferred</i></b>.";
			echo "				<br>";
			echo "				Please indicate the <u>condition timeframe</u> and the <u>condition</u> if the outcome is <b><i>provisionally accredited (with conditions)</i></b>.";
			echo "				<br>";
			echo "				Please indicate whether the condition has been met or not if this is a conditional proceedings.</b>.";
			echo "			</td>";
			echo "		</tr>";
			echo "		<tr>";
			echo "			<td colspan='2'>";
						$this->gridShowRowByRow("ia_proceedings_ac_decision", "ia_proceedings_ac_decision_id", "ia_proceedings_ref__".$app_proc_id, $dFields,$hFields, 10, 5, $add, $del, 1,"decision_reason_condition");
			echo "			</td>";
			echo "		</tr>";
			echo "		</table>";
			echo "	</td>";
			echo "</tr>";
//HTML;
                        //echo $htmlOne;
			break;
		case "heqc_board_decision_ref":
                        
                        //$htmlTwo =<<<HTML
			echo "<tr>";
			echo "	<td>";
			echo "		Background:<br>";
					$this->showField('applic_background_heqc');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		Summary of Evaluator's Report:<br>";
					$this->showField('eval_report_summary_heqc');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		<table>";
			echo "		<tr>";
			echo "			<td>HEQC Decision</td>";
			echo "			<td>";						
						if ($proc_type == 4){
                                                        $this->formFields["heqc_board_decision_ref"]->fieldStatus = 2;
                                                }
                                                $this->restrictOutcomeValuesperProceeding($proc_type,"heqc_board_decision_ref");
                                                $this->showField('heqc_board_decision_ref');
			echo "			</td>";
			echo "		</tr>";
			echo "		<tr>";
			echo "			<td colspan='2'>";
						$this->gridShowRowByRow("ia_proceedings_heqc_decision", "ia_proceedings_heqc_decision_id", "ia_proceedings_ref__".$app_proc_id, $dFields,$hFields, 10, 5, $add, $del, 1,"decision_reason_condition");
			echo "			</td>";
			echo "		</tr>";
			echo "		</table>";
			echo "	</td>";
			echo "</tr>";
//HTML;
                        //echo $htmlTwo;			
			break;		
		default:
			echo "The outcome that needs to be displayed is unknown.  Please contact HEQC-Online support for assistance";
		}

	}

	function generateHEQCMeetingMinutes ($meet_id=0) {
		$report =<<<INTRO
		<br />
		<b>HEQC MEETING MINUTES</b>
		<br /><br />
		<page />
INTRO;

		$SQL =<<<REPORT
		
			SELECT ia_proceedings.*,
				rec.lkp_title AS rec_outcome,
				rec.outcome_reason_heading AS rec_heading,
				ac.lkp_title AS ac_outcome,
				ac.outcome_reason_heading AS ac_heading,
				heqc.lkp_title AS ac_outcome,
				heqc.lkp_title AS heqc_outcome,
				heqc.outcome_reason_heading AS heqc_heading,
				Institutions_application.application_id,
				Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.mode_delivery,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				Institutions_application.expected_min_duration
			FROM ia_proceedings
				INNER JOIN Institutions_application ON Institutions_application.application_id = ia_proceedings.application_ref
				INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
				LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
				LEFT JOIN lkp_desicion AS rec ON rec.lkp_id = ia_proceedings.recomm_decision_ref 
				LEFT JOIN lkp_desicion AS ac ON ac.lkp_id = ia_proceedings.ac_decision_ref 
				LEFT JOIN lkp_desicion AS heqc ON heqc.lkp_id = ia_proceedings.heqc_board_decision_ref 
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			WHERE ia_proceedings.heqc_meeting_ref = $meet_id
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, Institutions_application.program_name
REPORT;
				
//echo $SQL;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
$conn->set_charset("utf8");

		$rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No applications have been assigned to this meeting.</p>";
		}
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatHEQCminutes($row);
			
			$report .=<<<REPORT
				<page />
REPORT;
		endwhile;

		return $report;
	}

	function formatHEQCminutes($row){
			$app_id = $row["application_id"];
			$proc_id = $row["ia_proceedings_id"];
			$proceeding_desc = DBConnect::getValueFromTable("lkp_proceedings", "lkp_proceedings_id", $row["lkp_proceedings_ref"], "lkp_proceedings_desc");
			$ac_meet_id = $row["ac_meeting_ref"];
			$ac_start_date = DBConnect::getValueFromTable("AC_Meeting", "ac_id", $ac_meet_id, "ac_start_date");
			$ac_meeting_start = date("jS F Y",strtotime($ac_start_date));
			$ac_outcome = $row["ac_outcome"] ? $row["ac_outcome"] : "&nbsp;";
			$heqc_meet_id = $row["heqc_meeting_ref"];
			$heqc_start_date = DBConnect::getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meet_id, "heqc_start_date");
			$heqc_meeting_start = date("jS F Y",strtotime($heqc_start_date));
			$rec_outcome = $row["rec_outcome"] ? $row["rec_outcome"] : "";
			$rec_heading = $row["rec_heading"] ? $row["rec_heading"] : "";
			$ac_outcome = $row["ac_outcome"] ? $row["ac_outcome"] : "";
			$ac_heading = $row["ac_heading"] ? $row["ac_heading"] : "";
			$heqc_outcome = $row["heqc_outcome"] ? $row["heqc_outcome"] : "";
			$heqc_heading = $row["heqc_heading"] ? $row["heqc_heading"] : "";
			$backg = $row["applic_background_heqc"] ? simple_text2html($row["applic_background_heqc"], "docgen") : "&nbsp;";
			$eval_summ = $row["eval_report_summary_heqc"] ? simple_text2html($row["eval_report_summary_heqc"], "docgen") : "&nbsp;";
			$ac_discuss = $row["minutes_discussion"] ? simple_text2html($row["minutes_discussion"], "docgen") : "&nbsp;";
			$heqc_discuss = $row["heqc_minutes_discussion"] ? simple_text2html($row["heqc_minutes_discussion"], "docgen") : "&nbsp;";
			$app_header = HEQConline::getHEQCApplicationTableTop($app_id,'ext');
			$report = "";
			
			$report .=<<<REPORT
			<table width="170" border="0" align="center">
			<tr>
				<td align="center">
					<b>HIGHER EDUCATION QUALITY COMMITTEE<br />
							$heqc_meeting_start<br />
					ACCREDITATION COMMITTEE<br />
					<u>MEETING HELD ON <i>$ac_meeting_start</i></u></b>
					<br />
				</td>
			</tr>
			<tr>
				<td>
					<b>Record of proceedings relating to:</b>
				</td>
			</tr>
			</table>
			
			<table width="170" border="1" align="center">
			<tr>
				<td colspan="2">$proceeding_desc</td>
			</tr>
			</table>
			$app_header
			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Background</b>
					<br />$backg
				</td>
			</tr>
			</table>
			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Summary of Evaluator Report</b>
					<br />$eval_summ
				</td>
			</tr>
			</table>

			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Directorate Recommendation</b>
					<br />The Accreditation Directorate recommends that the <i>{$row["program_name"]} ({$row["num_credits"]} credits, {$row["lkp_mode_of_delivery_desc"]} mode)</i> be 
					<br />
			 <b>- $rec_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			<br />
REPORT;
			$rec_decision = $row["recomm_decision_ref"];
			$rec_reason = HEQConline::display_outcome_reason('ia_proceedings_recomm_decision',$proc_id,$rec_heading,$rec_decision);

			$report .= $rec_reason;

			$report .=<<<REPORT
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Accreditation Committee Discussion</b>
					<br />$ac_discuss
				</td>
			</tr>
			</table>
			
			<br />
REPORT;
			$ac_decision = $row["ac_decision_ref"];
			$ac_reason = HEQConline::display_outcome_reason('ia_proceedings_ac_decision',$proc_id,$ac_heading, $ac_decision);

			$report .= $ac_reason;

			$report .=<<<REPORT
			<br />

			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Accreditation Committee Recommendation</b>
					<br />The Accreditation Committee recommends that the <i>{$row["program_name"]} ({$row["num_credits"]} credits, {$row["lkp_mode_of_delivery_desc"]} mode)</i> be 
					<br />
					 <b>- $ac_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			
			<br />

			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>HEQC Discussion</b>
					<br />$heqc_discuss
				</td>
			</tr>
			</table>

			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>HEQC Decision</b>
					<br />The HEQC approved the recommendation of the Accreditation Committee that the <i>$row[program_name]</i> be <br />
					 <b>$heqc_outcome</b>
					<br />
					<br />
				</td>
			</tr>
			</table>
			
			<br />
REPORT;

			$decision = $row["heqc_board_decision_ref"];
			$rpt_reason = HEQConline::display_outcome_reason('ia_proceedings_heqc_decision',$proc_id,$heqc_heading, $decision);

			$report .= $rpt_reason;

			return $report;
	}
	
	// Displays the application header, background, evaluator summary and ac minutes for an application: $meet may be AC or HEQC
	function formatOutcomeHeader($proc_id, $meet="AC"){
		$sql =<<<SQL
			SELECT *
			FROM ia_proceedings
			LEFT JOIN lkp_proceedings ON ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
			LEFT JOIN AC_Meeting ON ia_proceedings.ac_meeting_ref = AC_Meeting.ac_id
			LEFT JOIN HEQC_Meeting ON ia_proceedings.heqc_meeting_ref = HEQC_Meeting.heqc_id
			WHERE ia_proceedings_id = $proc_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$row = mysqli_fetch_array($rs);
	
		$app_id = $row["application_ref"];
		$proceeding_desc = $row["lkp_proceedings_desc"];
		$ac_meeting_start = $row["ac_start_date"];
		$heqc_start_date = $row["heqc_start_date"];
		$heqc_meeting_start = date("jS F Y",strtotime($heqc_start_date));

		// 2012-06-15 Robin: Background and eval summary is editable per directrate, ac and heqc in edit_outcomes
		//$backg = $row["applic_background"] ? simple_text2html($row["applic_background"]) : "&nbsp;";
		//$eval_summ = $row["eval_report_summary"] ? simple_text2html($row["eval_report_summary"]) : "&nbsp;";
		$ac_minutes = $row["minutes_discussion"] ? simple_text2html($row["minutes_discussion"]) : "&nbsp;";
		$app_header = $this->getHEQCApplicationTableTop($app_id,'int');
		$report = "";

		$heqc1 = "";
		if ($meet == "HEQC"){
			$heqc1 =<<<REPORT
			<tr>
				<td width="30%"><b>HEQC Meeting Date</b></td><td>$heqc_meeting_start</td>
			</tr>				
REPORT;
		}
		
		$heqc2 = "";
		if ($meet == "HEQC"){
			$heqc2 =<<<REPORT
			<tr>
				<td>
					<b>AC Meeting discussion</b><br>
					$ac_minutes
				</td>
			</tr>
REPORT;
		}
		
		$report .=<<<REPORT
			<table border="0" width="80%" cellpadding="2" cellspacing="2">
			$heqc1
			<tr>
				<td width="30%"><b>AC Meeting Date</b></td><td>$ac_meeting_start</td>
			</tr>
			<tr>
				<td><b>Record of proceedings relating to:</b></td><td>$proceeding_desc</td>
			</tr>
			</table>
			
			$app_header
			
			<table border="ridge" width="80%" cellpadding="2" cellspacing="2">
			$heqc2
			</table>
REPORT;
/*
			<tr>
				<td>
					<b>Background</b><br>
					$backg
				</td>
			</tr>
			<tr>
				<td>
					<b>Summary of Evaluator Report</b><br>
					$eval_summ
				</td>
			</tr>
*/
		return $report;
	}
	
	function getHEQCMeetingTableTop($heqc_meeting_id) {
	$SQL = "SELECT * FROM HEQC_Meeting WHERE heqc_id = ".$heqc_meeting_id;
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		echo "<tr class='oncolourb'><td colspan='2' align='center'>HEQC Meeting:</td></tr>";
		while ($row = mysqli_fetch_array($rs)) {
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='20%'>Meeting date:</td>";
			echo "<td>".$row["heqc_start_date"]."</td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb'>Meeting venue:</td>";
			echo "<td>".$row["heqc_meeting_venue"]."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '<br>';
	}
}

	function displayHEQCMeetings($title, $SQL, $moveto="") {
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

		echo $title;
		echo '<table width="95%" border=0 align="left" cellpadding="2" cellspacing="2" align="center">';

		if (mysqli_num_rows($rs)) {
			echo "<tr class='oncolourb' valign='top'>";
			echo "<td width='20%'>Date HEQC Meeting held</td>";
			echo "<td>HEQC Meeting venue</td>";
			echo "</tr>";
			while ($row = mysqli_fetch_array($rs)) {
			 $link = $this->scriptGetForm("HEQC_Meeting",$row["heqc_id"],$moveto);
			 echo "<tr class='onblue'>";
			 echo "<td width='15%' align='center'>";
			 echo "<a href='". $link ."'>";
			 echo $row['heqc_start_date'];
			 echo "</a></td>";
			 echo "<td>".$row['heqc_meeting_venue'];
			 echo "</td></tr>";
			}
		}
		else {
			echo "<tr class='onblue'><td align='center'> - No HEQC Meetings match this criteria - </td></tr>";
		}
		echo "</table>";
	}

	function displayColleaguesInGroup($group_id){
		$group_name = $this->getValueFromTable("sec_Groups", "sec_group_id", $group_id, "sec_group_desc");
		
		$html = "There are no users assigned to this group: " . $group_name . ".  Please request the user administrator of HEQC-Online to add the required users to this group.";
		
		$SQL =<<<GETUSERS
			SELECT email, user_id 
			FROM users, sec_UserGroups
			WHERE sec_group_ref = $group_id 
			AND sec_user_ref=user_id 
			AND active = 1 
GETUSERS;
		
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (!$RS){
			$this->writeLogInfo(10, "SQL-GETVAL", "Function: displayColleaguesInGroup:<br><br>" . $SQL."  --> ".mysqli_error(), true);
		}
		
		if ($RS){
			$html =<<<HTML
				<select name="user_ref">
HTML;
			while ($row=mysqli_fetch_array($RS)) {
				$sel = "";
				if ($this->currentUserID == $row["user_id"]) $sel = "SELECTED";
				$html .= '<option value="'.$row["user_id"].'" '.$sel.'>'.$row["email"].'</option>';
			}
			$html .=<<<HTML
				 </select>
HTML;
		}
		echo $html;
	}

	function create_proceedings($app_id, $proc_type, $app_proc_id,$reacc_id){
	$conn = $this->getDatabaseConnection();
		$new_proc_id = 0;
		$dt = date("Y-m-d");
		
		$sql =<<<INSERT
			INSERT INTO ia_proceedings (
				application_ref, lkp_proceedings_ref, prev_ia_proceedings_ref, submission_date,
				reaccreditation_application_ref
			)
			VALUES ($app_id, $proc_type, $app_proc_id, '{$dt}',$reacc_id)
INSERT;

		$rs = mysqli_query($conn, $sql);
		if (!$rs){
			$this->writeLogInfo(10, "SQL", "Function: create_proceedings:<br><br>" . $SQL."  --> ".mysqli_error(), true);
		} else {
			$new_proc_id = mysqli_insert_id($conn); 
		}
		return $new_proc_id;
	}
	
	function displayOutcome($app_proc_id){

		$psql =<<<SQL
			SELECT * 
			FROM ia_proceedings
			LEFT JOIN lkp_proceedings ON lkp_proceedings_ref = lkp_proceedings_id
			WHERE ia_proceedings_id = $app_proc_id
SQL;

		$prs = mysqli_query($this->getDatabaseConnection(), $psql);
		if (!$prs){
			echo "The proceedings was not found. Please try again.";
			return;
		}
		$prow = mysqli_fetch_array($prs);
		$app_id = $prow["application_ref"];
		$dec = $prow["heqc_board_decision_ref"];
		$proceeding = $prow["lkp_proceedings_desc"];

		$due_date = $prow["heqc_decision_due_date"];
		$display_due_date = "";
		if ($due_date > '1000-01-01'){
			$display_due_date = "<tr><td>Due date: <b>$due_date</b></td></tr>";
		}
		
		$letter_link = 'has not been uploaded';
		if ($prow['decision_doc'] > 0){
			$letter = new octoDoc($prow['decision_doc']);
			$letter_link = "<a href='".$letter->url()."' target='_blank'>".$letter->getFilename()."</a>";
		}
		
		$outcome = $this->getValueFromTable("lkp_desicion", "lkp_id", $dec, "lkp_title");

		/* Proceedings: Candidacy application, deferral, representation all directorate recommendations, ac meetings and heqc meetings 
		in the same format
		Proceedings: Conditional - Only has a list of conditions that goes to the AC meeting - thus it needs a different format
		*/
		//if ($prow["lkp_proceedings_ref"] == 4){
		//2017-10-20 Richard: Include conditional re-accred
		if (($prow["lkp_proceedings_ref"] == 4) || ($prow["lkp_proceedings_ref"] == 6)){
			$reasons_conditions = $this->displayConditions($app_id, $for="application");
		} else {
			$heading = '<tr class="oncolourb"><td>Reason</td><td width="15%">Criterion<br />Minimum Standard</td></tr>';
			//if ($dec == 2){ 
			//2017-10-20 Richard: Include conditional re-accred
			if (($dec == 2) || ($dec == 6)){
				$heading = '<tr class="oncolourb"><td width="15%">Term</td><td>Condition</td><td width="15%">Criterion<br />Minimum Standard</td></tr>'; 
			}
			$table_body = "";
			$sql =<<<REASONS
				SELECT *
				FROM ia_proceedings_heqc_decision
				LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
				WHERE ia_proceedings_ref = $app_proc_id
				ORDER BY decision_reason_condition
REASONS;
			$rs = mysqli_query($this->getDatabaseConnection(), $sql);
			while ($row = mysqli_fetch_array($rs)){
				$term = "";
				//if ($dec == 2){
				//2017-10-20 Richard: Include conditional re-accred
				if (($dec == 2) || ($dec == 6)){ 
					$term = "<td>". $row["lkp_condition_term_desc"] ."</td>";
				}
				$table_body .=<<<BODY
					<tr class="onblue">$term<td>$row[decision_reason_condition]</td><td>$row[criterion_min_standard]</td></tr>
BODY;
			}
			$reasons_conditions =<<<HTML
					<table width="100%" cellpadding="2" cellspacing="2">
						$heading
						$table_body
					</table>
HTML;
		}

		
		$html =<<<HTML
			<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td>Proceeding: <b>$proceeding</b></td>
			</tr>
			<tr>
				<td>Outcome: <b>$outcome</b></td>
			</tr>
			<tr>
				<td>
					$reasons_conditions
				</td>
			</tr>
			<tr>
				<td>Letter of recommendation: <b>$letter_link</b></td>
			</tr>
			$display_due_date
			</table>
HTML;
		echo $html;
	}

	/* Display conditions for the application or the proceeding */
	function displayConditions($id, $for="application",$type="recomm"){

		$heading = '<tr class="oncolourb"><td width="15%">Term</td><td>Condition</td><td width="15%">Criterion<br />Minimum Standard</td><td width="10%">Condition met</td></tr>'; 
		$table_body = "";
		if ($for == "application"){

			$sql =<<<REASONS
			SELECT *
			FROM ia_conditions
			LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions.condition_term_ref
			LEFT JOIN lkp_yes_no ON lkp_yes_no.lkp_yn_id = ia_conditions.condition_met_yn_ref
			WHERE  application_ref = $id
			ORDER BY decision_reason_condition
REASONS;
		}

		if ($for == "proceeding"){
			$yn_fld = "recomm_condition_met_yn_ref";
			if ($type == "eval"){
				$yn_fld = "eval_condition_met_yn_ref";
			}

			$sql =<<<REASONS
			SELECT *
			FROM ia_conditions_proceedings
			LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions_proceedings.condition_term_ref
			LEFT JOIN lkp_yes_no ON lkp_yes_no.lkp_yn_id = ia_conditions_proceedings.{$yn_fld}
			WHERE  ia_proceedings_ref = $id
			ORDER BY decision_reason_condition
REASONS;
		}
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
		$rs = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_array($rs)){
			$term = '<td width="15%">'. $row["lkp_condition_term_desc"] ."</td>";
			$condition_met = '<td width="10%">'. (($row["lkp_yn_desc"] > "") ? $row["lkp_yn_desc"] : "&nbsp;") ."</td>";
			$crit_min_std = ($row["criterion_min_standard"] > "") ? $row["criterion_min_standard"] : "&nbsp;";
			$table_body .=<<<BODY
				<tr class="onblue">$term<td>$row[decision_reason_condition]</td><td width="15%">{$crit_min_std}</td>{$condition_met}</tr>
				
BODY;
		}
			
		$html =<<<HTML
					<table width="100%" cellpadding="2" cellspacing="2">
					$heading
					$table_body
					</table>
HTML;
		return $html;
	}
	
	// ent can be "reacc" or "app"
	function getHEQCApplicationTableTop($id="",$rpt="int",$ent="app"){
		if ($id == ""){
			return "Missing ids when building application header. Please contact support.";
		}
		if ($ent == "reacc"){
			$sql =<<<REACC
			SELECT Institutions_application.CHE_reference_code,
				HEInstitution.HEI_name,
				Institutions_application_reaccreditation.programme_name AS program_name,
				NQF_level.NQF_level,
				Institutions_application_reaccreditation.saqa_credits AS num_credits,
				Institutions_application_reaccreditation.full_time_duration AS full_time,
				Institutions_application_reaccreditation.part_time_duration AS part_time,
				lnk_priv_publ.lnk_priv_publ_desc,
				Institutions_application.mode_delivery,
				Institutions_application.qualification_type_ref,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				lkp_qualification_type.min_credit_range
			FROM (Institutions_application_reaccreditation,
				Institutions_application,
				HEInstitution)
			LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application_reaccreditation.NQF_level
			LEFT JOIN lnk_priv_publ ON lnk_priv_publ.lnk_priv_publ_id = HEInstitution.priv_publ
			LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery.lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			LEFT JOIN lkp_qualification_type ON lkp_qualification_type_id = Institutions_application.qualification_type_ref
			WHERE Institutions_application_reaccreditation.referenceNumber = Institutions_application.CHE_reference_code 
			AND HEInstitution.HEI_id = Institutions_application_reaccreditation.institution_ref
			AND Institutions_application_reaccreditation.Institutions_application_reaccreditation_id = {$id}
REACC;
		} else {
			// Get Application information
			$sql =<<<APPSQL
			SELECT Institutions_application.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name,
				Institutions_application.program_name,
				NQF_level.NQF_level,
				Institutions_application.num_credits,
				Institutions_application.qualification_type_ref,
				Institutions_application.mode_delivery,
				Institutions_application.full_time,
				Institutions_application.part_time,
				lnk_priv_publ.lnk_priv_publ_desc,
				lkp_mode_of_delivery.lkp_mode_of_delivery_desc,
				lkp_qualification_type.min_credit_range
			FROM Institutions_application
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
			LEFT JOIN NQF_level ON NQF_level.NQF_id = Institutions_application.NQF_ref
			LEFT JOIN lnk_priv_publ ON lnk_priv_publ_id = priv_publ
			LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery.lkp_mode_of_delivery_id = Institutions_application.mode_delivery
			LEFT JOIN lkp_qualification_type ON lkp_qualification_type_id = Institutions_application.qualification_type_ref
			WHERE Institutions_application.application_id = {$id}
APPSQL;
		}
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$rs = mysqli_query($conn, $sql);
		$n_rows = mysqli_num_rows($rs);

		if ($n_rows > 0){
			$row = mysqli_fetch_array($rs);

			$priv_publ_desc = ($row["lnk_priv_publ_desc"] > '') ? $row["lnk_priv_publ_desc"] : "";
			$mode_delivery_desc = ($row["lkp_mode_of_delivery_desc"] > '') ? $row["lkp_mode_of_delivery_desc"] : "";
			$min_duration = 'Full-time: ' . $row["full_time"] . " / Part-time: " . $row["part_time"];
			$min_credits = ($row["min_credit_range"] > '') ? $row["min_credit_range"] : "";
			$nqf_level = ($row["NQF_level"] > '') ? $row["NQF_level"] : "";
			$num_credits = ($row["num_credits"] > '') ? $row["num_credits"] : "";
			if ($ent == "reacc"){
				$app_sites = HEQConline::getSitesOfDelivery("reacc",$id,$rpt);
			} else {
				$app_sites = HEQConline::getSitesOfDeliveryPerApplication($id,$rpt);
			}
			switch ($rpt){
				case 'ext':
					$tbl_head = 'width="170" border="1" align="center"';
					break;
				default:
					$tbl_head = 'border="ridge" width="80%" cellpadding="2" cellspacing="2"';
			}
			
			$app_info =<<<APPINFO
				<table $tbl_head>
					<tr><td>CHE Reference number</td><td>$row[CHE_reference_code]</td></tr>
					<tr><td>Public or private institution</td><td>$priv_publ_desc</td></tr>
					<tr><td>Institution name</td><td>$row[HEI_name]</td></tr>
					<tr><td>Title of programme</td><td>$row[program_name]</td></tr>
					<tr><td>NQF level</td><td>$nqf_level</td></tr>
					<tr><td>Minimum number of credits</td><td>$min_credits</td></tr>
					<tr><td>Number of credits for this programme</td><td>$num_credits</td></tr>
					<tr><td>Tuition mode</td><td>$mode_delivery_desc</td></tr>
					<tr><td>Minimum duration of study <i>(full-time / part-time)</i></td><td>$min_duration</td></tr>
					<tr><td>Site(s) of delivery</td><td>$app_sites</td></tr>
				</table>
APPINFO;
		} else {
			$app_info =<<<APPINFO
				<table $tbl_head>
					<tr><td>The application was not found.  Please contact support.</td></tr>
				</table>
APPINFO;
		}
		return $app_info;
	}

	function defaultOutcome($type, $app_proc_id){
		// 1. Check whether an outcome exists for the proceedings
		$sql =<<<OUTCOME
			SELECT lkp_proceedings_ref, recomm_decision_ref, ac_decision_ref, heqc_board_decision_ref, application_ref, heqc_meeting_ref, 
			applic_background, applic_background_ac, eval_report_summary, eval_report_summary_ac,
			representation_ind, representation_doc, reaccreditation_application_ref
			FROM ia_proceedings
			WHERE ia_proceedings_id = $app_proc_id;
OUTCOME;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$rs = mysqli_query($conn, $sql);
		if ($rs){
			$row = mysqli_fetch_array($rs);
			$recomm_dec = $row["recomm_decision_ref"];
			$ac_dec = $row["ac_decision_ref"];
			$heqc_dec = $row["heqc_board_decision_ref"];
			$recomm_backg = $row["applic_background"];
			$ac_backg = $row["applic_background_ac"];
			$recomm_eval_summ = $row["eval_report_summary"];
			$ac_eval_summ = $row["eval_report_summary_ac"];
			$app_id = $row["application_ref"];
			$proc_type = $row["lkp_proceedings_ref"];
			$reacc_id = $row["lkp_proceedings_ref"];

			switch ($type){
			case "RECOMM":
				if (!($recomm_dec > 0)){

					// Set conditions for the recommendation if this is a conditional proceedings.  Data is captured by user for other proceedings.
					if ($proc_type == 4){
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"recomm_decision_ref","2");

						$ins_sql =<<<INSERT
							INSERT INTO ia_proceedings_recomm_decision (ia_proceedings_recomm_decision_id, ia_proceedings_ref, 
							ia_conditions_proceedings_ref, decision_reason_condition, condition_term_ref, criterion_min_standard,
							condition_met_yn_ref, ia_conditions_ref)
							SELECT NULL, ia_proceedings_ref, 
							ia_conditions_proceedings_id, decision_reason_condition, condition_term_ref, criterion_min_standard,
							eval_condition_met_yn_ref, ia_conditions_ref
							FROM ia_conditions_proceedings
							WHERE ia_proceedings_ref = $app_proc_id
INSERT;
						$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or die(mysqli_error());
					}
					//2017-10-20 Richard: Include conditional re-accred
					if ($proc_type == 6){		
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"recomm_decision_ref","6");

						$ins_sql =<<<INSERT
							INSERT INTO ia_proceedings_recomm_decision (ia_proceedings_recomm_decision_id, ia_proceedings_ref, 
							ia_conditions_proceedings_ref, decision_reason_condition, condition_term_ref, criterion_min_standard,
							condition_met_yn_ref, ia_conditions_ref)
							SELECT NULL, ia_proceedings_ref, 
							ia_conditions_proceedings_id, decision_reason_condition, condition_term_ref, criterion_min_standard,
							eval_condition_met_yn_ref, ia_conditions_ref
							FROM ia_conditions_proceedings
							WHERE ia_proceedings_ref = $app_proc_id
INSERT;
						$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or die(mysqli_error());
					}
				}
				break;
			case "AC":
				// If no outcome then default ac outcome to directorate recommendation outcome.
				if (!($ac_dec > 0)){

					$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"ac_decision_ref",$recomm_dec);

					//2013-12-20 Robin: Conditional proceedings must follow the same process flow as other proceedings
					//2015-06-29 Robin: Reverse out conditional proceedings - keep the table flow
					//if ($proc_type != 4){  // not for conditions proceedings
					//2017-10-20 Richard: Include conditional re-accred
					if (($proc_type != 4) && ($proc_type != 6)){
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"applic_background_ac",$recomm_backg);
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"eval_report_summary_ac",$recomm_eval_summ);
					}

					$ins_sql =<<<INSERT
						INSERT INTO ia_proceedings_ac_decision
						SELECT NULL, ia_proceedings_ref, ia_conditions_proceedings_ref, decision_reason_condition, 
						condition_term_ref, criterion_min_standard,
						condition_met_yn_ref, ia_conditions_ref
						FROM ia_proceedings_recomm_decision
						WHERE ia_proceedings_ref = $app_proc_id
INSERT;
						$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or die(mysqli_error() . " " . $ins_sql);
				}

				break;
			case "HEQC":
				// If no outcome then default heqc outcome to AC outcome.
				if (!($heqc_dec > 0)){
					$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_board_decision_ref",$ac_dec);

					//2013-12-20 Robin: Conditional proceedings must follow the same process flow as other proceedings
					//2015-06-29 Robin: Reverse out conditional proceedings - keep the table flow
					//if ($proc_type != 4){  // not for conditions proceedings
					//2017-10-20 Richard: Include conditional re-accred
					if (($proc_type != 4) && ($proc_type != 6)){
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"applic_background_heqc",$ac_backg);
						$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"eval_report_summary_heqc",$ac_eval_summ);
					}

					$ins_sql =<<<INSERT
						INSERT INTO ia_proceedings_heqc_decision
						SELECT NULL, ia_proceedings_ref, ia_conditions_proceedings_ref, decision_reason_condition, 
						condition_term_ref, criterion_min_standard,
						condition_met_yn_ref, ia_conditions_ref
						FROM ia_proceedings_ac_decision
						WHERE ia_proceedings_ref = $app_proc_id
INSERT;
					$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or die("Default HEQSF outcome:" . mysqli_error() . "<br>" . $ins_sql);
				}
				break;
			case "CONDITIONS":
				//if ($proc_type == 4){
				//2017-10-20 Richard: Include conditional re-accred
				if (($proc_type == 4) || ($proc_type == 6)){
					$sel =<<<SELECT
						SELECT ia_conditions_ref, recomm_condition_met_yn_ref
						FROM  ia_conditions_proceedings
						WHERE ia_proceedings_ref = $app_proc_id
						AND ia_proceedings_ref > 0
SELECT;
					$rs = mysqli_query($this->getDatabaseConnection(), $sel);
					while ($row = mysqli_fetch_array($rs)){
						if ($row["ia_conditions_ref"] > 0){
							$sql =<<<UPDCOND
								UPDATE ia_conditions 
								SET condition_met_yn_ref = {$row["recomm_condition_met_yn_ref"]}
								WHERE ia_conditions_id = {$row["ia_conditions_ref"]}
UPDCOND;
							$errorMail = false;
							mysqli_query($this->getDatabaseConnection(), $sql) or $errorMail = true;
							$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error(), $errorMail);
						}
					}	
					
					// If all conditions have been met then set heqc_dec_ref to provisionally accredited - else with conditions
					$sql =<<<COUNTCOND
						SELECT count(*) AS unmet 
						FROM ia_conditions
						WHERE application_ref = $app_id
						AND condition_term_ref IN ('s','p','l')
						AND condition_met_yn_ref != 2
COUNTCOND;
					$urs = mysqli_query($this->getDatabaseConnection(), $sql);
					$urow = mysqli_fetch_array($urs);

					$unmet = $urow['unmet'];
					//2017-10-20 Richard: Include conditional re-accred
					if ($proc_type == 6){
						$dec_ref = 5; //Provisionally re-accredited
					}else{
						$dec_ref = 1; //Provisionally accredited
					}
					if ($unmet > 0){
						//2017-10-20 Richard: Include conditional re-accred
						if ($proc_type == 6){
							$dec_ref = "6";
						}else{
							$dec_ref = "2";
						}
					}

					$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_board_decision_ref",$dec_ref);
				}
				break;
			// **************************************************************************************************************************
			// Keep Institutions application outcome (overall outcome) up to date for reporting purposes on application outcomes.
			// Set it to final outcome based on HEQC meeting outcome for the current proceedings (if it is the end of the proceedings):
			//		- Provisionally accredited
			//		- Not accredited without a representation
			// Final outcome is not set if the outcome is:
			//		- Deferred (as determined in the AC meeting) then it will go through another proceedings. 
			// 		- Not accredited (but a representation is received) then it will go through another proceedings.
			//		- Provisionally accredited with conditions as it will go through another proceedings.
			// Note: Institutions_application: AC_desision reflects the overall outcome of the application.
			// Note: The HEQC meeting outcome may also be edited from menu option HEQC Meeting: HEQC Meetings: Edit minutes.  However the minutes
			// should always be edited and finalised before the outcome is processed (this process) resulting in the final outcome being
			// ***************************************************************************************************************************
			case "final":   
				$heqc_meeting_ref = $row['heqc_meeting_ref'];
				if ($heqc_dec == 1  || ($heqc_dec == 3 && $row['representation_ind'] == 1 && $row['representation_doc'] == 0)){
					if ($heqc_meeting_ref > 0){
						$decision_date = $this->getValueFromTable("HEQC_Meeting","heqc_id",$heqc_meeting_ref,"heqc_start_date");
					} else {
						$decision_date = date("Y-m-d");
					}
					$this->setValueInTable("Institutions_application","application_id",$app_id,"AC_desision",$heqc_dec);
					$this->setValueInTable("Institutions_application", "application_id", $app_id, "AC_Meeting_date", $decision_date);
				}
				// If a reaccreditation proceedings then update reaccreditation outcome.
				if ($reacc_id > 0 && ($proc_type == 5 || $proc_type == 6 || $proc_type == 7 || $proc_type == 8)){
					$this->setValueInTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_id,"reacc_decision_ref",$heqc_dec);
					$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "reacc_acmeeting_date", $decision_date);
				}
				break;			
			}
		}	
	}
	
	function makeDropdownOfGroupUsers($grp){
		$items = "";
	
		$SQL =<<<GETUSERS
		SELECT email, user_id 
		FROM users, sec_UserGroups
		WHERE sec_group_ref = $grp 
		AND sec_user_ref=user_id 
		AND active = 1 
GETUSERS;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$sel = "";
			if ($this->currentUserID == $row["user_id"]) $sel = "SELECTED";
			$items .= '<option value="'.$row["user_id"].'" '.$sel.'>'.$row["email"].'</option>';
		}
		$dd =<<<DROPDOWN
			<select name="user_ref">
			$items
			</select>
DROPDOWN;
		return $dd;
	}
	function getApplicationDocs($app_id){
		$docs_arr = array();
		$sql =<<<SQL
			SELECT * 
			FROM ia_documents
			WHERE application_ref = $app_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$eDoc = new octoDoc($row['application_doc']);
				if ($eDoc->isDoc()) {
					$title = ($row['document_title'] > '') ? $row['document_title'] : $eDoc->getFilename();
					$doc_link = '<a href="'.$eDoc->url().'" target="_blank">'.$title.'</a>';
					array_push ($docs_arr, $doc_link);
				}
			}
		}
		$sql =<<<SQL
			SELECT secretariat_doc, AC_conditions_doc, representation_doc, condition_doc, deferral_doc 
			FROM Institutions_application
			WHERE application_id = $app_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){

				$sDoc = new octoDoc($row['secretariat_doc']);
				if ($sDoc->isDoc()) {
					$doc_link = '<a href="'.$sDoc->url().'" target="_blank">Directorate recommendation</a>';
					array_push ($docs_arr, $doc_link);
				}
				
				$lDoc = new octoDoc($row['AC_conditions_doc']);
				if ($lDoc->isDoc()) {
					$doc_link = '<a href="'.$lDoc->url().'" target="_blank">'.$lDoc->getFilename().'</a>';
					array_push ($docs_arr, $doc_link);
				}
				
				$rDoc = new octoDoc($row['representation_doc']);
				if ($rDoc->isDoc()) {
					$doc_link = '<a href="'.$rDoc->url().'" target="_blank">Representation</a>';
					array_push ($docs_arr, $doc_link);
				}
				
				$cDoc = new octoDoc($row['condition_doc']);
				if ($cDoc->isDoc()) {
					$doc_link = '<a href="'.$cDoc->url().'" target="_blank">Conditions</a>';
					array_push ($docs_arr, $doc_link);
				}
				
				$dDoc = new octoDoc($row['deferral_doc']);
				if ($dDoc->isDoc()) {
					$doc_link = '<a href="'.$dDoc->url().'" target="_blank">Deferral</a>';
					array_push ($docs_arr, $doc_link);
				}
			}
		}
		
		return $docs_arr;
	}
	
	function getProceedingDocs($proc_id, $func=""){
		$docs_arr = array();

		switch ($func){
		case "evaluator portal"	:
			$heqc_board_decision_ref = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $proc_id, "heqc_board_decision_ref");
			$cols = "representation_doc, condition_doc, deferral_doc";
			if($heqc_board_decision_ref == 4 || $heqc_board_decision_ref == 3){
				$cols .= ", decision_doc";
			}			
			break;
		case "application header"	:
			$cols = "decision_doc, representation_doc, condition_doc, deferral_doc";
			break;
		default: //No docs
			$cols = "ia_proceedings_id";
		}
		
		$sql =<<<SQL
			SELECT $cols 
			FROM ia_proceedings
			WHERE ia_proceedings_id = $proc_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){

				if (isset($row['decision_doc'])){
					$lDoc = new octoDoc($row['decision_doc']);
					if ($lDoc->isDoc()) {
						$doc_link = '<a href="'.$lDoc->url().'" target="_blank">'.$lDoc->getFilename().'</a>';
						array_push ($docs_arr, $doc_link);
					}
				}
				if (isset($row['representation_doc'])){
					$rDoc = new octoDoc($row['representation_doc']);
					if ($rDoc->isDoc()) {
						$doc_link = '<a href="'.$rDoc->url().'" target="_blank">Representation</a>';
						array_push ($docs_arr, $doc_link);
					}
				}

				if (isset($row['condition_doc'])){
					$cDoc = new octoDoc($row['condition_doc']);
					if ($cDoc->isDoc()) {
						$doc_link = '<a href="'.$cDoc->url().'" target="_blank">Conditions</a>';
						array_push ($docs_arr, $doc_link);
					}
				}
				
				if (isset($row['deferral_doc'])){
					$dDoc = new octoDoc($row['deferral_doc']);
					if ($dDoc->isDoc()) {
						$doc_link = '<a href="'.$dDoc->url().'" target="_blank">Deferral</a>';
						array_push ($docs_arr, $doc_link);
					}
				}
			}
		}
		
		return $docs_arr;
	}
	
	// 2012-11-13 Robin: Used to display completed evaluations for an application.  Evaluations are linked to proceedings now.
	function displayListofEvaluations($app_id){

		/*
		$evals = $this->getSelectedEvaluatorsForApplication($id, $crit, $type,"evalReport_id"); 
		if (count($evals) == 0){
			return "<br>No previous evaluations were found.<br>";
		}
		*/
		$che_ref = $this->getValueFromTable("Institutions_application","application_id",$app_id,"CHE_reference_code");
		
		$sql =<<<evalSQL
			SELECT Eval_Auditors.Persnr, CONCAT(Eval_Auditors.Surname,', ',Eval_Auditors.Names,' ') as Name, Eval_Auditors.Surname, Eval_Auditors.Names, Eval_Auditors.E_mail, evalReport.do_summary,
			evalReport.lop_isSent, evalReport.lop_isSent_date, Eval_Auditors.Work_Number, evalReport.evalReport_date_sent, Eval_Auditors.Title_ref, t.lkp_title_desc, evalReport.evalReport_doc,
			evalReport.evalReport_id, evalReport.application_sum_doc, Eval_Auditors.user_ref, evalReport.evalReport_date_completed, evalReport.view_by_other_eval_yn_ref,
			evalReport.application_ref, evalReport.eval_contract_doc, evalReport.ia_proceedings_ref, evalReport.reaccreditation_application_ref
			FROM (Eval_Auditors, evalReport)
			LEFT JOIN lkp_title t ON t.lkp_title_id = Eval_Auditors.Title_ref
			WHERE evalReport.Persnr_ref=Eval_Auditors.Persnr
			AND evalReport_completed = 2
			AND (evalReport.application_ref = {$app_id} 
				OR reaccreditation_application_ref IN (
					SELECT Institutions_application_reaccreditation_id 
					FROM Institutions_application_reaccreditation
					WHERE referenceNumber = '{$che_ref}'
					)
				)
			ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names
evalSQL;



 //file_put_contents('php://stderr', print_r($sql, TRUE));
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$prow = "";
		while ($row = mysqli_fetch_array($rs)) {
			$proc_type = '';
			$proc_desc = "&nbsp;";
			if ($row["reaccreditation_application_ref"] > ''){
				$proc_desc = "Reaccreditation";
			}
			if ($row["ia_proceedings_ref"] > ''){
				$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$row["ia_proceedings_ref"],"lkp_proceedings_ref");
				$proc_desc = $this->getValueFromTable("lkp_proceedings","lkp_proceedings_id",$proc_type,"lkp_proceedings_desc");
			}

			if ($proc_type == 4){
				$reptLink = "<a href='pages/conditionsForProceedings.php?id=".base64_encode($row["ia_proceedings_ref"])."' target='_blank'>View conditions</a>";
			} else {
				$rept = new octoDoc($row["evalReport_doc"]);
				$reptLink = "<a href='".$rept->url()."' target='_blank'>".$rept->getFilename()."</a>";
			}

			$prow .=<<<HTML
				<tr class="oncoloursoft">
					<td>{$row["Name"]}</td>
					<td>{$row["E_mail"]}</td>
					<td>{$row["Work_Number"]}</td>
					<td>{$proc_desc}</td>
					<td>{$row["evalReport_date_completed"]}</td>
					<td>{$reptLink}</td>
				</tr>
HTML;
		}

		$phtml =<<<HTML
			<table border="0" cellpadding="2" cellspacing="2">
			<tr class="oncolourb">
				<td>Name</td>
				<td>Email</td>
				<td>Tel</td>
				<td>Type</td>
				<td>Date completed</td>
				<td>Report</td>
			</tr>
			{$prow}
			</table>
HTML;
		return $phtml;
	}

	function edit_conditions($dec, $app_proc_id){
		
		$dFields = array();
		array_push($dFields, "type__select|status__3|name__condition_term_ref|description_fld__lkp_condition_term_desc|fld_key__lkp_condition_term_id|lkp_table__lkp_condition_term|lkp_condition__1|order_by__lkp_condition_term_id");
		array_push($dFields, "type__textarea|status__3|cols__40|rows__7|name__decision_reason_condition");
		array_push($dFields, "type__text|status__3|size__25|name__criterion_min_standard");

		switch ($dec){
		case "eval":
                    //$htmlFour =<<<HTML
		echo "	<table>";
		echo "	<tr>";
		echo "		<td>";
		echo "			<b>Evaluators Recommendation:</b>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td>"; 
					$hFields = array("Term", "Condition", "Criterion and <br>Minimum Standards","Condition<br>met","Indicate how and whether the institution met the requirements of each condition");
					array_push($dFields, "type__select|name__eval_condition_met_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_id");
					array_push($dFields, "type__textarea|cols__60|rows__7|name__eval_comment");
					$this->gridShowRowByRow("ia_conditions_proceedings", "ia_conditions_proceedings_id", "ia_proceedings_ref__".$app_proc_id, $dFields,$hFields, 10, 5, "", "", 1);
		echo "		</td>";
		echo "	</tr>";
		echo "	</table>";
//HTML;
                        //echo $htmlFour;
			break;
		case "recomm":
                    //$htmlThree =<<<HTML
                echo "<table>";
                echo "<tr>";
		echo "		<td>";
		echo "			<b>Directorate Recommendation:</b>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td>";
                                    // 2015-06-19 Robin: Re-allow recomm for conditions - No longer using processes 159 to 162 
                                    $hFields = array("Term", "Condition", "Criterion and <br>Minimum Standards","Evaluator<br>decision<br>(Condition met)","Evaluator comment","Condition<br>met","Copy and edit the evaluators comment to make a final recommendation for this condition");
                                    //$hFields = array("Term", "Condition", "Criterion and <br>Minimum Standards","Evaluator<br>decision<br>(Condition met)","Evaluator comment");
                                    array_push($dFields, "type__select|status__3|name__eval_condition_met_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_id");
                                    array_push($dFields, "type__textarea|status__3|cols__40|rows__7|name__eval_comment");
                                    // 2015-06-19 Robin: Re-allow recomm for conditions - No longer using processes 159 to 162 
                                    array_push($dFields, "type__select|name__recomm_condition_met_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_id");
                                    array_push($dFields, "type__textarea|cols__60|rows__7|name__recomm_comment");

                                    $this->gridShowRowByRow("ia_conditions_proceedings", "ia_conditions_proceedings_id", "ia_proceedings_ref__".$app_proc_id, $dFields,$hFields, 10, 5, "", "", 1);
		echo "		</td>";
		echo "	</tr>";
                echo "    </table>";
//HTML;
                    //echo $htmlThree;
			break;
		default:
			echo "The conditions that need to be displayed are unknown.  Please contact HEQC-Online support for assistance";
		}
	}
	
	function formatConditionsSummaryForMeeting($meet_id, $agenda_id){
		/* 20150629 New format for conditions table in AC meeting document.  Comment out old format
		$sql =<<<CONDITIONS
			SELECT lnk_priv_publ_desc, HEI_name, CHE_reference_code, program_name, lkp_condition_term_desc, 
				ia_conditions_proceedings.recomm_condition_met_yn_ref, lkp_yn_desc, count(*) as no_conditions
			FROM (Institutions_application, HEInstitution, ia_proceedings, ia_conditions)
			LEFT JOIN ia_conditions_proceedings ON ia_conditions_proceedings.ia_conditions_ref = ia_conditions.ia_conditions_id
			LEFT JOIN lnk_priv_publ ON lnk_priv_publ.lnk_priv_publ_id = HEInstitution.priv_publ
			LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions.condition_term_ref
			LEFT JOIN lkp_yes_no ON lkp_yes_no.lkp_yn_id = ia_conditions_proceedings.recomm_condition_met_yn_ref
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
			AND ia_proceedings.lkp_proceedings_ref = 4
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND Institutions_application.application_id = ia_conditions.application_ref
			GROUP BY lnk_priv_publ_desc, HEI_name, CHE_reference_code, program_name, ia_conditions.condition_term_ref, recomm_condition_met_yn_ref
			ORDER BY lnk_priv_publ_desc, HEI_name, CHE_reference_code, program_name, ia_conditions.condition_term_ref, recomm_condition_met_yn_ref
CONDITIONS;
		*/
/*		$sql =<<<CONDITIONS
			SELECT lnk_priv_publ_desc, HEInstitution.HEI_name, Institutions_application.CHE_reference_code, 
			Institutions_application.program_name, lkp_condition_term.lkp_condition_term_desc, 
			ia_conditions.decision_reason_condition, ia_conditions.condition_met_yn_ref, lkp_yn_desc
			FROM (Institutions_application, HEInstitution, ia_proceedings, ia_conditions)
			LEFT JOIN lnk_priv_publ ON lnk_priv_publ.lnk_priv_publ_id = HEInstitution.priv_publ
			LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions.condition_term_ref
			LEFT JOIN lkp_yes_no ON lkp_yes_no.lkp_yn_id = ia_conditions.condition_met_yn_ref
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
			AND ia_proceedings.lkp_proceedings_ref = 4
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND Institutions_application.application_id = ia_conditions.application_ref
			ORDER BY lnk_priv_publ_desc, HEI_name, CHE_reference_code, program_name, ia_conditions.condition_term_ref, ia_conditions.decision_reason_condition
CONDITIONS;*/
/*		//2017-09-13: Richard - Added AC agenda type
		$sql =<<<CONDITIONS
			SELECT lnk_priv_publ_desc, HEInstitution.HEI_name, Institutions_application.CHE_reference_code, 
			Institutions_application.program_name, lkp_condition_term.lkp_condition_term_desc, 
			ia_conditions.decision_reason_condition, ia_conditions.condition_met_yn_ref, lkp_yn_desc
			FROM (Institutions_application, HEInstitution, ia_proceedings, ia_conditions)
			LEFT JOIN lnk_priv_publ ON lnk_priv_publ.lnk_priv_publ_id = HEInstitution.priv_publ
			LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions.condition_term_ref
			LEFT JOIN lkp_yes_no ON lkp_yes_no.lkp_yn_id = ia_conditions.condition_met_yn_ref
			WHERE ia_proceedings.ac_meeting_ref = $meet_id
			AND ia_proceedings.lkp_proceedings_ref = 4
			AND ia_proceedings.lkp_AC_agenda_type_ref = $agenda_id
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND Institutions_application.application_id = ia_conditions.application_ref
			ORDER BY lnk_priv_publ_desc, HEI_name, CHE_reference_code, program_name, ia_conditions.condition_term_ref, ia_conditions.decision_reason_condition
CONDITIONS;*/ 

                //2018-02-26: Richard - Process conditional Accreditations
                $sql = <<<CONDITIONS
                        SELECT HEInstitution.HEI_name, Institutions_application.CHE_reference_code, 
                        Institutions_application.program_name, lkp_condition_term.lkp_condition_term_desc, 
                        ia_conditions.decision_reason_condition, IF (condition_met_yn_ref = 2, 'Has been met', 
                        IF (@val:=(SELECT recomm_condition_met_yn_ref FROM ia_conditions_proceedings, ia_proceedings WHERE ia_proceedings.ia_proceedings_id = ia_conditions_proceedings.ia_proceedings_ref AND ia_proceedings.application_ref = Institutions_application.application_id AND ia_conditions_proceedings.decision_reason_condition = ia_conditions.decision_reason_condition AND recomm_condition_met_yn_ref = 2) = 2, 'Has been met', 
            'Not yet met')) AS condition_met
                        FROM (Institutions_application, ia_conditions)
                        LEFT JOIN ia_proceedings ON Institutions_application.application_id = ia_proceedings.application_ref
                        LEFT JOIN HEInstitution ON Institutions_application.institution_id = HEInstitution.HEI_id
                        LEFT JOIN lkp_condition_term ON lkp_condition_term.lkp_condition_term_id = ia_conditions.condition_term_ref
                        WHERE ia_proceedings.ac_meeting_ref = $meet_id
                        AND ia_proceedings.lkp_proceedings_ref IN (4)
                        AND ia_proceedings.lkp_AC_agenda_type_ref IN ($agenda_id)
                        AND ia_proceedings.application_ref = Institutions_application.application_id
                        AND Institutions_application.institution_id = HEInstitution.HEI_id
                        AND Institutions_application.application_id = ia_conditions.application_ref
                        ORDER BY HEI_name, program_name, CHE_reference_code, ia_conditions.decision_reason_condition
CONDITIONS;
 
     $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$rs = mysqli_query($conn, $sql);
		if ($rs){
			$html = "List of conditions that were processed<br/><table>";
			while ($row = mysqli_fetch_array($rs)){
				$inst = $row["HEI_name"] ? $row["HEI_name"] : "";
				$ref = $row["CHE_reference_code"] ? $row["CHE_reference_code"] : "";
				$prog = $row["program_name"] ? $row["program_name"] : "";
				$cond = $row["decision_reason_condition"];
				$cond_term = $row["lkp_condition_term_desc"] ? $row["lkp_condition_term_desc"] : "";
				$cond_met = $row["condition_met"];

				/* 2015-06-29 Robin: Display finalised conditions for the application. Final Conditions are updated after evaluator approval.
				$cond_met = "Not processed";
				if ($row["recomm_condition_met_yn_ref"] == 2){
					$cond_met = "Conditions met: " . $row["no_conditions"];
				}
				if ($row["recomm_condition_met_yn_ref"] == 1){
					$cond_met = "Conditions NOT met: " . $row["no_conditions"];
				}
				
				if ($row["condition_met_yn_ref"] == 2){
					$cond_met = "Met";
				}
				if ($row["condition_met_yn_ref"] == 1){
					$cond_met = "NOT met";
				}
				*/
				$html .=<<<ROWS
					<tr>
						<td>$inst</td>
						<td>$ref</td>
						<td>$prog</td>
						<td>$cond_term</td>
						<td>$cond</td>
						<td>$cond_met</td>
					</tr>
ROWS;
			}
			$html .= "</table>";
			$html .= "<page />";
			return $html;
		}
	}
	
	function displayBackgrounds($app_proc_id){
		$html = "";
	
		$sql =<<<BACKG
			SELECT applic_background, applic_background_ac, applic_background_heqc
			FROM ia_proceedings
			WHERE ia_proceedings.ia_proceedings_id = {$app_proc_id}
BACKG;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			$html .= "<table>";
			while ($row = mysqli_fetch_array($rs)){
				$backg = $row["applic_background"] ? simple_text2html($row["applic_background"]) : "&nbsp;";
				$backg_ac = $row["applic_background_ac"] ? simple_text2html($row["applic_background_ac"]) : "&nbsp;";
				$backg_heqc = $row["applic_background_heqc"] ? simple_text2html($row["applic_background_heqc"]) : "&nbsp;";

				$html .=<<<ROWS
					<tr>
						<td>
							<b>Application background from the recommendation</b><br /><br />
							{$backg}
						</td>
					</tr>
					<tr>
						<td>
							<hr>
						</td>
					</tr>
					<tr>
						<td>
							<b>Application background from the Accreditation Committee meeting minutes</b><br /><br />
							{$backg_ac}
						</td>
					</tr>
					<tr>
						<td>
							<hr>
						</td>
					</tr>
					<tr>
						<td>
							<b>Application background from the HEQC meeting minutes</b><br /><br />
							{$backg_heqc}
						</td>
					</tr>
ROWS;
			}
			$html .= "</table>";
		}
		return $html;
	}
	
	// 2011-01-11 Robin - Several site visits may take place as a result of one application. Setup edit for site visits.

function buildSiteVisitListForEdit($site_app_proc_id,$edit_type="default"){
	$data = "";
	$imgPath = "images";
	
	switch ($edit_type){
		case 'sched':
			$edit_img = "ico_change.gif";
			break;
		case 'eval':
			$edit_img = "ico_eval.gif";
			break;
		case 'docs':
			$edit_img = "ico_print.gif";
			break;
		case 'final':
			$edit_img = "ico_print.gif";
			break;
		case 'report':
			$edit_img = "";
			break;
		case 'text':
			$edit_img = "";
			break;
		default;
			$edit_img = "";
	}
	
	$sql =<<<getSites
		SELECT inst_site_visit.inst_site_visit_id, 
			inst_site_visit.final_date_visit,
			inst_site_visit.site_visit_report_doc,			
			institutional_profile_sites.*
		FROM inst_site_visit, institutional_profile_sites
		WHERE inst_site_app_proc_ref = $site_app_proc_id
		AND institutional_profile_sites_id = institutional_profile_sites_ref
getSites;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	if ($rs && mysqli_num_rows($rs) > 0){
		$edit_header = "";
		if ($edit_img > ""){
			$edit_header = "<td><b>Edit</b></td>";
		}

		while ($row = mysqli_fetch_array($rs)){
			$site_visit_id = $row["inst_site_visit_id"];
	
			$apps = "";
			$appstext = "";
			$app_arr = $this->getSelectedApplicationsForSiteVisit($site_visit_id);
			foreach ($app_arr as $a){
				$apps .= $a['program_name'] . "<br />";
				$appstext .= "\t" . $a['program_name'] . "\n";
			}
	
			$evals = "";
			$evalstext = "";
			$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_visit_id, 'visit');
			foreach ($eval_arr as $e){
				$evals .= $e['Name'] . "<br /><i>" . $e['E_mail'] . "</i><br />";
				$evalstext .= "\t" . $e['Name'] . "\n";
			}
			
			// Display the edit link if this is a site data capture request else nothing
			$edit = "";
			if ($edit_img > ""){
				$jscript = $this->scriptGetForm("inst_site_visit", $site_visit_id, "_label_sitevisit_".$edit_type."_per_site");
				$edit =<<<EDIT
					<td width="4%">
						<a href='$jscript'><img src="$imgPath/$edit_img" border="0"></a>
					</td>
EDIT;
			}
			
			$report = "";
			$replace = "";
			$site_doc = new octoDoc($row['site_visit_report_doc']);
			if ($site_doc->isDoc()) {
				$report = '<a href="'.$site_doc->url().'" target="_blank"><img src="'.$imgPath.'/check_mark.gif" border="0">'.$site_doc->getFilename().'</a><br>';
				$replace = " or replace";
			}
			if ($edit_type == 'report'){
				$jscript = $this->scriptGetForm("inst_site_visit", $site_visit_id, "_label_sitevisit_".$edit_type."_per_site");
				$report .=<<<UPLOAD
					<br>
					<a href='$jscript'><img src='$imgPath/ico_print.gif' border="0"></a>&nbsp;Upload$replace
UPLOAD;
			}
			
			switch ($edit_type){
			case 'text':
				$data .= "\nSITE: " . $row['site_name'] . " - " . $row['location'] . 
						"\nDATE: " . $row['final_date_visit'] .
						"\nPROGRAMMES: \n" .
						$appstext .
						"\nPANEL MEMBERS: \n" .
						$evalstext;
				break;
			default:
				$data .=<<<hrow
					<tr>
						$edit
						<td>$row[site_name] - $row[location]</td>
						<td>$row[final_date_visit]</td>
						<td>$apps</td>
						<td>$evals</td>
						<td>$report</td>
					</tr>
hrow;
			}
		}
	} else {
		$data = "No sites were found for this application. Please go and select the sites for which site visits will be scheduled.";
	}

	if ($edit_type == 'text'){
		$html = $data;
	} else {
		$html =<<<sites
			<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
				<tr>
					$edit_header
					<td><b>Site</b></td>
					<td><b>Visit date</b></td>
					<td><b>Programmes</b></td>
					<td><b>Panel members</b></td>
					<td><b>Site visit report</b></td>
				</tr>
				$data
			</table>
sites;
		}

	return $html;
}

// Aim is to provide 3 formats:
// 1. Institution name
// 2. Institution name and Site names and dates
function getSiteApplicationTableTop($site_app_proc_id, $format="default") {

$conn=$this->getDatabaseConnection();
	$html = "";

	$SQLinst =<<<SITEAPPHEAD
		SELECT inst_site_app_proceedings.institution_ref, HEInstitution.HEI_name, HEInstitution.priv_publ
		FROM inst_site_app_proceedings, HEInstitution
		WHERE inst_site_app_proceedings.institution_ref = HEInstitution.HEI_id 
		AND inst_site_app_proc_id = $site_app_proc_id
SITEAPPHEAD;
	
	$SQLsites =<<<SITEAPPHEAD
		SELECT inst_site_visit.institution_ref, HEInstitution.HEI_name, HEInstitution.priv_publ, 
		institutional_profile_sites.site_name, institutional_profile_sites.location, institutional_profile_sites.address
		FROM inst_site_visit, HEInstitution, institutional_profile_sites 
		WHERE inst_site_visit.institution_ref = HEInstitution.HEI_id 
		AND inst_site_visit.institutional_profile_sites_ref = institutional_profile_sites.institutional_profile_sites_id
		AND inst_site_app_proc_ref = $site_app_proc_id
SITEAPPHEAD;

	switch ($format) {
		case 'sites':
			$sites = "";
			$rs = mysqli_query($conn, $SQLsites);
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {
					$priv_publ = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row["priv_publ"], "lnk_priv_publ_desc");
					$inst =  <<<HTML
						<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
						<tr>
							<td valign='top'>Institution</td>
							<td class='oncolourb'>$row[HEI_name] ($priv_publ)</td>
						</tr>
HTML;
					$sites .=<<<HTML
						<tr>
						<td width='30%' valign='top'>Site</td>
						<td class='oncolourb'>$row[site_name], $row[location]: $row[address]</td>
						</tr>
HTML;
				}
				$html = $inst . $sites . '</table>';
			}
			break;
		case 'text':
			$sites = "";
			$rs = mysqli_query($conn, $SQLsites);
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {
				$priv_publ = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row["priv_publ"], "lnk_priv_publ_desc");
				$inst =  "Institution: " . $row['HEI_name'] . " (" .$priv_publ . ")";

				$sites .= "\n\nSite: " . $row['site_name'] . " - " . $row['location'] . "\nAddress: " . $row['address'];
				}
			}
			$html = $inst . $sites;
			break;
		default:
			$rs = mysqli_query($conn, $SQLinst);
			if (mysqli_num_rows($rs) > 0) {
				$row = mysqli_fetch_array($rs);
				$priv_publ = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row["priv_publ"], "lnk_priv_publ_desc");

						$html .=<<<HTML
							<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
								<tr>
								<td valign='top'>Institution</td>
								<td class='oncolourb'>$row[HEI_name] ($priv_publ)</td>
								</tr>
							</table>
HTML;
			}
	}
		
	return $html;
}

function getSiteVisitTableTop($site_visit_id) {
	$SQL =<<<SITEAPPHEAD
		SELECT inst_site_visit.institution_ref, HEInstitution.HEI_name, HEInstitution.priv_publ, 
		 institutional_profile_sites.site_name, institutional_profile_sites.location, institutional_profile_sites.address
		FROM inst_site_visit, HEInstitution, institutional_profile_sites 
		WHERE inst_site_visit.institution_ref = HEInstitution.HEI_id 
		AND inst_site_visit.institutional_profile_sites_ref = institutional_profile_sites.institutional_profile_sites_id
		AND inst_site_visit_id = $site_visit_id
SITEAPPHEAD;

	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

	if (mysqli_num_rows($rs) > 0) {
		$html =  '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			$priv_publ = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row["priv_publ"], "lnk_priv_publ_desc");
			
			$html .=<<<HTML
				<tr>
				<td valign='top'>Institution name:</td>
				<td class='oncolourb'>$row[HEI_name] ($priv_publ)</td>
				</tr>
				<tr>
				<td width='30%' valign='top'>Site name and address:</td>
				<td class='oncolourb'>$row[site_name], $row[location]: $row[address]</td>
				</tr>
HTML;
		}
		$html .= '</table>';
	}
	return $html;
}

	function getSelectedEvaluatorsForSiteVisits ($id, $app_or_visit, $where="") {
		$site_eval_arr = array();

		$where_app = "";
		if ($where > ""){
			$where_app = " AND " . implode(" AND ", $where);
		}
//echo "<br>" . $app_or_visit;		
		switch ($app_or_visit){
		case 'applic':
			$SQL =<<<evalSQL
				SELECT Eval_Auditors.Title_ref, Eval_Auditors.Surname, Eval_Auditors.Names,
					CONCAT(lkp_title.lkp_title_desc, " ", Eval_Auditors.Surname,', ',Eval_Auditors.Names) as Name, 
					Eval_Auditors.Persnr,
					Eval_Auditors.E_mail, Eval_Auditors.Work_Number, Eval_Auditors.Mobile_Number,
					Eval_Auditors.user_ref,
					inst_site_app_proceedings_eval.appoint_email_sent_date,
					inst_site_app_proceedings_eval.inst_site_app_proc_eval_id,
					inst_site_app_proceedings_eval.eval_contract_doc
				FROM (inst_site_app_proceedings_eval, Eval_Auditors)
				LEFT JOIN lkp_title ON lkp_title.lkp_title_id = Eval_Auditors.Title_ref
				WHERE inst_site_app_proceedings_eval.evaluator_persnr = Eval_Auditors.Persnr
				AND inst_site_app_proceedings_eval.inst_site_app_proc_ref = $id 
				$where_app
				ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names
evalSQL;
			break;
		case 'visit': 
			$SQL =<<<evalSQL
				SELECT Eval_Auditors.Title_ref, Eval_Auditors.Surname, Eval_Auditors.Names,
					CONCAT(lkp_title.lkp_title_desc," ", Eval_Auditors.Surname,", ",Eval_Auditors.Names) AS Name, 
					Eval_Auditors.Persnr, 
					Eval_Auditors.E_mail, Eval_Auditors.Work_Number, Eval_Auditors.Mobile_Number,
					Eval_Auditors.user_ref,
					inst_site_visit_eval.inst_site_visit_eval_id
				FROM (inst_site_visit_eval, Eval_Auditors)
				LEFT JOIN lkp_title ON lkp_title.lkp_title_id = Eval_Auditors.Title_ref 
				WHERE inst_site_visit_eval.evaluator_persnr = Eval_Auditors.Persnr
				AND inst_site_visit_eval.inst_site_visit_ref = $id
				$where_app
				ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names
evalSQL;
			break;
		case 'allvisits': 
			$SQL =<<<evalSQL
				SELECT inst_site_visit_eval.inst_site_visit_eval_id,
					inst_site_visit_eval.inst_site_visit_ref,
					Eval_Auditors.Title_ref, Eval_Auditors.Surname, Eval_Auditors.Names,
					CONCAT(lkp_title.lkp_title_desc, " ", Eval_Auditors.Surname,', ',Eval_Auditors.Names) AS Name, 
					Eval_Auditors.Persnr, 
					Eval_Auditors.E_mail, Eval_Auditors.Work_Number, Eval_Auditors.Mobile_Number,
					Eval_Auditors.user_ref,
					institutional_profile_sites.site_name,
					inst_site_visit_eval.panel_letter_sent_date,
					inst_site_app_proceedings_eval.inst_site_app_proc_eval_id,
					inst_site_app_proceedings_eval.eval_contract_doc
				FROM (inst_site_visit, institutional_profile_sites, inst_site_visit_eval, Eval_Auditors, inst_site_app_proceedings_eval)
				LEFT JOIN lkp_title ON lkp_title.lkp_title_id = Eval_Auditors.Title_ref
				WHERE inst_site_visit.inst_site_visit_id = inst_site_visit_eval.inst_site_visit_ref
				AND inst_site_visit_eval.evaluator_persnr = Eval_Auditors.Persnr
				AND inst_site_visit.institutional_profile_sites_ref = institutional_profile_sites.institutional_profile_sites_id
				AND (inst_site_visit_eval.evaluator_persnr = inst_site_app_proceedings_eval.evaluator_persnr AND inst_site_app_proceedings_eval.inst_site_app_proc_ref = $id)
				AND inst_site_visit.inst_site_app_proc_ref = $id
				$where_app
				ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names, inst_site_visit.inst_site_visit_id
evalSQL;
			break;
		}
//echo $SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($row = mysqli_fetch_array($rs)) {
			if ($app_or_visit == 'allvisits'){
				$site_eval_arr[$row["Persnr"]][$row["inst_site_visit_ref"]] = $row;
			} else {
				$site_eval_arr[$row["Persnr"]] = $row;				
			}
		}
		return $site_eval_arr;
	}
	
	function formatEvaluator($persnr, $format="default"){
		$str = "";
		
		$sql =<<<SQL
			SELECT Eval_Auditors.Surname, Eval_Auditors.Names, 
				lkp_title.lkp_title_desc,
				Eval_Auditors.E_mail,
				Eval_Auditors.Work_Number,
				Eval_Auditors.Fax_Number,
				Eval_Auditors.Mobile_Number,
				Eval_Auditors.Job_title,
				Eval_Auditors.Department,
				Eval_Auditors.employer_ref,
				lkp_employer.lkp_employer_name
			FROM Eval_Auditors
			LEFT JOIN lkp_title ON lkp_title.lkp_title_id = Eval_Auditors.Title_ref
			LEFT JOIN lkp_employer ON lkp_employer.lkp_employer_id = employer_ref 
			WHERE Eval_Auditors.Persnr = $persnr
			ORDER BY Eval_Auditors.Surname, Eval_Auditors.Names
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			$row = mysqli_fetch_array($rs);
			switch($format){
			case "titletext":
				$str = $row['lkp_title_desc'] . " " . $row['Names'] . " " . $row['Surname'];
				break;
			case "detailtext":
				$str = "\n" . $row['lkp_title_desc'] . " " . $row['Names'] . " " . $row['Surname'];
				//if ($row['Job_title'] > ''){
				//	$str .= "\n" . $row['Job_title'];
				//}
				if ($row['Department'] > ''){
					$str .= "\n" . $row['Department'];
				}
				if ($row['lkp_employer_name'] > ''){
					$str .= "\n" . $row['lkp_employer_name'];
				}
				if ($row['Work_Number'] > ''){
					$str .= "\nTel:\t" . $row['Work_Number'];
				}
				if ($row['Fax_Number'] > ''){
					$str .= "\nFax:\t" . $row['Fax_Number'];
				}
				if ($row['Mobile_Number'] > ''){
					$str .= "\nMobile:\t" . $row['Mobile_Number'];
				}
				break;
			case 'title':
				$str =<<<STR
					$row[lkp_title_desc] $row[Surname]
STR;
				break;
			default:
				$str =<<<STR
					$row[Surname], $row[Names]
STR;
			}
		}

		return $str;
	}

	function formatSiteDetails($site_id, $format="default"){
		$str = "";
	
		$sql =<<<SQL
			SELECT site_name, location, address, contact_nr, contact_fax_nr
			FROM institutional_profile_sites 
			WHERE institutional_profile_sites_id = $site_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			$row = mysqli_fetch_array($rs);
			switch($format){
			case 'address_tel':
				$addr_arr = explode("\n",$row['address']);
				$address = implode("\n\t", $addr_arr);
				$str = 	$row['site_name'] . "\n" .
						"Address:\t" . 
						$address . "\n" .
						"Tel:\t". $row['contact_nr'] . "\n" .
						"Fax:\t". $row['contact_fax_nr'] . "\n";
				break;
			default:
				$str = $row[site_name] . " " . $row[location];
			}
		}
		return $str;
	}
	
	function formatSiteVisitsForEvaluator($site_app_proc_id, $persnr, $format="default"){
		$html = "";
		
		$sql =<<<SITES
			SELECT final_date_visit, institutional_profile_sites_ref 
			FROM inst_site_visit, inst_site_visit_eval
			WHERE inst_site_visit.inst_site_visit_id = inst_site_visit_eval.inst_site_visit_ref
			AND inst_site_visit.inst_site_app_proc_ref = $site_app_proc_id
			AND inst_site_visit_eval.evaluator_persnr = $persnr
			ORDER BY inst_site_visit.final_date_visit
SITES;

		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		
		if ($rs){

			while ($row = mysqli_fetch_array($rs)){
				switch($format){
				case 'address_tel':
					$site_address = $this->formatSiteDetails($row['institutional_profile_sites_ref'], 'address_tel');
					$html .= 	"\nSite visit date: " . $row['final_date_visit'] . "\n" .
								"Site: " . $site_address . "\n";
					break;
				default:
					$html .= $row['site_name'] . " " . $row['location'];
				}
			}

		}
		return $html;
	}
	
	function formatSiteVisit($site_visit_id, $format='default'){
		$txt = "";
		
		$sql =<<<SITES
		SELECT inst_site_visit_id, final_date_visit, institutional_profile_sites_ref
			FROM inst_site_visit
			WHERE inst_site_visit.inst_site_visit_id = $site_visit_id
SITES;

		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		
		if ($rs){

			while ($row = mysqli_fetch_array($rs)){
				switch($format){
				case 'lettertopanel':
					$site_address = $this->formatSiteDetails($row['institutional_profile_sites_ref'], 'address_tel');
					$txt .= 	"\nDATE: " . $row['final_date_visit'] . "\n" .
								"\nSITE: " . $site_address . "\n";
					$txt .= "\nPANEL MEMBERS/HEQC REPRESENTATIVE";
					$evals = $this->getSelectedEvaluatorsForSiteVisits ($row["inst_site_visit_id"], 'visit');
					foreach ($evals as $e){
						$txt .= "\n\n" . $this->formatEvaluator($e['Persnr'],"detailtext");
					}
					break;
				default:
					$panel_arr = array();
					$evals = $this->getSelectedEvaluatorsForSiteVisits ($row["inst_site_visit_id"], 'visit');
					foreach ($evals as $e){
						array_push($panel_arr, $this->formatEvaluator($e['Persnr'], "titletext"));
					}
					$txt .= implode(' and ', $panel_arr);
				}
			}
		}
		return $txt;
	}
	
	function getSelectedApplicationsForSiteVisit($site_visit_id,$whr=""){
		$app_arr = array();
		
		$sql =<<<SQL
			SELECT inst_site_visit_progs.application_ref, Institutions_application.CHE_reference_code, Institutions_application.program_name,
				inst_site_visit_progs.recomm_offering_ind
			FROM Institutions_application, inst_site_visit_progs 
			WHERE Institutions_application.application_id = inst_site_visit_progs.application_ref
			AND inst_site_visit_progs.site_visit_ref = $site_visit_id
			$whr
			ORDER BY Institutions_application.program_name
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql) or die(mysqli_error());
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$app_arr[$row['application_ref']] = $row;
			}
		}

		return $app_arr;
	}
	
function buildSiteVisitAttachmentForEdit(){
	$data = "No attachments were found.  Click on Add a new attachment in the Actions menu";

	$edit_img = "ico_change.gif";
	
	$sql =<<<ATTACH
		SELECT *
		FROM inst_site_attachment
ATTACH;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	if ($rs && mysqli_num_rows($rs) > 0){
		$data =<<<hhead
				<tr>
					<td><b>Edit</b></td>
					<td><b>Description</b></td>
					<td><b>Attachment</b></td>
				</tr>
hhead;
		while ($row = mysqli_fetch_array($rs)){
			$inst_site_attachment_id = $row["inst_site_attachment_id"];
			
			$jscript = $this->scriptGetForm("inst_site_attachment", $inst_site_attachment_id, "_label_site_attachment");
			$imgPath = "images";

			$attachment = "&nbsp;";
			$att = new octoDoc($row['attachment_doc']);
			if ($att->isDoc()) {
				$attachment = '<a href="'.$att->url().'" target="_blank">'.$att->getFilename().'</a>';
			}
			
			$data .=<<<hrow
				<tr>
					<td width="4%">
						<a href='$jscript'>
						<img src="$imgPath/$edit_img" border=0>
						</a>
					</td>
					<td>$row[attachment_title]</td>
					<td>$attachment</td>
				</tr>
hrow;
		}
	}

	$html =<<<sites
		<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
			$data
		</table>
sites;

	return $html;
}

	function getSiteVisitAttachments($site_visit_id){

		$attach_arr = array();
			
		$sql =<<<SQL
			SELECT institution_notification_doc, schedule_doc, panel_members_letter_doc
			FROM inst_site_visit
			WHERE inst_site_visit_id = $site_visit_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			$row = mysqli_fetch_array($rs);
			if ($row['panel_members_letter_doc'] > 0){
				$attach_arr[$row['panel_members_letter_doc']] = 'Letter to Panel Member';
			}
			if ($row['institution_notification_doc'] > 0){
				$attach_arr[$row['institution_notification_doc']] = 'Notification letter for a site visit to the institution';
			}
			if ($row['schedule_doc'] > 0){
				$attach_arr[$row['schedule_doc']] = 'Site visit schedule';
			}
		}
			
		// Standard attachments
		$sqlg =<<<SQL
			SELECT * 
			FROM inst_site_attachment
SQL;
		$rsg = mysqli_query($this->getDatabaseConnection(), $sqlg);
		if ($rsg){
			while ($rowg = mysqli_fetch_array($rsg)){
				if ($rowg['attachment_doc'] > 0){
					$attach_arr[$rowg['attachment_doc']] = $rowg['attachment_title'];
				}
			}
		}
			
		return $attach_arr;
	}
	
	function getSiteProcAttachments($site_proc_id){

		$attach_arr = array();
			
		$sql =<<<SQL
			SELECT institution_notification_doc, schedule_doc
			FROM inst_site_visit
			WHERE inst_site_app_proc_ref = $site_proc_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			while($row = mysqli_fetch_array($rs)){
				$attach_arr[$row['institution_notification_doc']] = 'Notification document';
				$attach_arr[$row['schedule_doc']] = 'Schedule document';
			}
		}
			
		return $attach_arr;
	}
	
	function getSiteVisitsForApp($site_app_proc_id){
		$sites_arr = array();
		
		$sql =<<<getSites
			SELECT inst_site_visit.*,
				institutional_profile_sites.*
			FROM inst_site_visit, institutional_profile_sites
			WHERE inst_site_app_proc_ref = $site_app_proc_id
			AND institutional_profile_sites_id = institutional_profile_sites_ref
getSites;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$rs = mysqli_query($conn, $sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$sites_arr[$row['inst_site_visit_id']] = $row;
			}
		}

		return $sites_arr;
	}

	function getSiteVisitsForAppAndEval($site_app_proc_id, $eval_persnr){
		$sites_arr = array();
		
		$sql =<<<getSites
			SELECT inst_site_visit.*,
				institutional_profile_sites.*,
				inst_site_visit_eval.*
			FROM inst_site_visit, institutional_profile_sites, inst_site_visit_eval
			WHERE institutional_profile_sites_id = institutional_profile_sites_ref
			AND inst_site_visit.inst_site_visit_id = inst_site_visit_eval.inst_site_visit_ref
			AND inst_site_app_proc_ref = {$site_app_proc_id}
			AND inst_site_visit_eval.evaluator_persnr IN ({$eval_persnr})
getSites;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$sites_arr[$row['inst_site_visit_id']] = $row;
			}
		}

		return $sites_arr;
	}
	
	function getRecommendationUsers($ru_id=""){
		$SQL =<<<recommUsers
			SELECT users.user_id, CONCAT( users.name, ' ', users.surname ) AS user_name, 
				users.email, users.contact_nr, users.contact_cell_nr
			FROM users, sec_UserGroups
			WHERE users.user_id = sec_UserGroups.sec_user_ref
			AND sec_UserGroups.sec_group_ref = 19
			AND users.active = 1
			ORDER BY users.surname, users.name
recommUsers;
		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		$n_rows = mysqli_num_rows($rs);

		if ($n_rows == 0):
			$dir_users =<<<USERS
			<table  class="saphireframe" border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
			<tr class="oncolourb">
				<th align="left">List of available Directorate Recommendation users</th>
			</tr>
			<tr>
				<td><i>No users have been assigned to the Directorate Recommendation security group.</i></td>
			</tr>
			</table>
USERS;
		endif;

		if ($n_rows > 0):
			$dir_users =<<<USERS
						<table border='0' width='95%' align='left' cellpadding='2' cellspacing='2'>
						<tr>
							<td align="left" colspan="5"><span class="visi">Note: You may change the user responsible for the directorate recommendation by clicking on another user and Next.</span></td>
						</tr>
						<tr class="oncolourb">
							<td>Name</td>
							<td>Email address</td>
							<td>Tel number</td>
							<td>Cell number</td>
							<td>
								Assign
							</td>
						</tr>
USERS;
			while ($row = mysqli_fetch_array($rs)) {
				$sel = "";
				$bgcolor = "onblue";
				$user_id = $row["user_id"];
				if ($user_id == $ru_id){
					$bgcolor = "#d6e0eb";
					$sel = "CHECKED";
				}
				$dir_users .=<<<USERS
					<tr class="$bgcolor">
						<td>$row[user_name]</td>
						<td>$row[email]</td>
						<td>$row[contact_nr]</td>
						<td>$row[contact_cell_nr]</td>
						<td><input type="radio" name="recomm_user_id" value="$user_id" $sel /></td>
					</tr>
USERS;
			}
			$dir_users .= '</table>';
		endif;
		
		return $dir_users;
	}
	
	function getSelectedRecommUserForSiteApplication ($site_proc_id, $where="") {
		$recomm_arr = array();
		$where_app = "";
		
		if ($where > ""){
			$where_app = " AND " . implode(" AND ", $where);
		}
		
		$SQL =<<<recommSQL
			SELECT users.user_id, CONCAT( users.name, ' ', users.surname ) AS user_name, 
				users.email, users.contact_nr, users.contact_cell_nr, 
				inst_site_app_proceedings.inst_site_app_proc_id,
				inst_site_app_proceedings.recomm_user_ref,
				inst_site_app_proceedings.lop_isSent_date,
				inst_site_app_proceedings.lop_isSent,
				inst_site_app_proceedings.portal_sent_date,
				inst_site_app_proceedings.recomm_access_end_date,
				inst_site_app_proceedings.recomm_complete_ind,
				lkp_site_proceedings.lkp_site_proceedings_desc
			FROM (inst_site_app_proceedings, users)
			LEFT JOIN lkp_site_proceedings ON lkp_site_proceedings.lkp_site_proceedings_id = inst_site_app_proceedings.lkp_site_proceedings_ref
			WHERE users.user_id = inst_site_app_proceedings.recomm_user_ref
			AND inst_site_app_proceedings.inst_site_app_proc_id = $site_proc_id
			$where_app
			ORDER BY users.surname, users.name
recommSQL;

		$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		if (mysqli_num_rows($rs) > 0):
			$recomm_arr = mysqli_fetch_array($rs);
		endif;
		
		return $recomm_arr;
	}
	
	function getSiteRecommendationTop($site_proc_id, $rpt=""){
		$html = "";
		
		$sql =<<<SITEAPP
			SELECT a.site_application_no,
				a.institution_ref,
				h.HEI_name, 
				l.lnk_priv_publ_desc, 
				lkp_mode_of_delivery_desc,
				lkp_site_proceedings_desc
			FROM (inst_site_application a, 
				inst_site_app_proceedings p,
				HEInstitution h,
				lnk_priv_publ l,
				institutional_profile i,
				lkp_mode_of_delivery m)
			LEFT JOIN lkp_site_proceedings ON p.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			WHERE a.inst_site_app_id = p.inst_site_app_ref 
			AND p.institution_ref = h.HEI_id
			AND h.priv_publ = l.lnk_priv_publ_id
			AND p.institution_ref = i.institution_ref
			AND inst_site_app_proc_id = $site_proc_id
			AND i.mode_delivery = lkp_mode_of_delivery_id
SITEAPP;
		// Get a distinct list of applications for the site application i.e. across site visits.
		// Using group by instead of distinct for performance.
		$sql_apps =<<<SQL
			SELECT inst_site_visit_progs.application_ref, program_name, GROUP_CONCAT(CAST(sites_ref AS CHAR) separator ',') AS sites_offered
			FROM (inst_site_visit, inst_site_visit_progs, Institutions_application)
			LEFT JOIN lkp_sites ON lkp_sites.application_ref = inst_site_visit_progs.application_ref
			WHERE inst_site_visit.inst_site_visit_id = inst_site_visit_progs.site_visit_ref
			AND inst_site_visit_progs.application_ref = Institutions_application.application_id
			AND inst_site_visit.inst_site_app_proc_ref = $site_proc_id
			GROUP BY application_ref
			ORDER BY NULL
SQL;

  $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$site_apps = array();
		$rs_apps = mysqli_query($conn, $sql_apps) or die(mysqli_error());
		if ($rs_apps){
			while ($row_apps = mysqli_fetch_array($rs_apps)){
				array_push($site_apps, $row_apps);
			}
		}

		$rs = mysqli_query($conn, $sql) or die(mysqli_error());
		if ($rs){
			$row = mysqli_fetch_array($rs, MYSQLI_ASSOC);
			switch ($rpt){
			case 'ext':
				$tbl_head = 'width="170" border="1" align="center"';
				break;
			default:
				$tbl_head = 'border="ridge" width="80%" cellpadding="2" cellspacing="2"';
			}
			
			$site_app_no = $row["site_application_no"] ? $row["site_application_no"] : "";
			
			$progs_per_site = "";
			foreach($site_apps AS $a){
				$progs_per_site .= $a['program_name'] . "<br />";
				// Get sites that the programme is offered at
				if ($a['sites_offered'] > ''){
					$progs_per_site .= '<i>Currently offered at:</i><br />';
					$sql_sites =<<<SITES
						SELECT GROUP_CONCAT(CONCAT(location, '-', site_name) SEPARATOR '<br />') AS at_sites
						FROM institutional_profile_sites
						WHERE institutional_profile_sites_id IN ($a[sites_offered])
SITES;
					$rs_sites = mysqli_query($conn, $sql_sites) or die(mysqli_error());
					if ($rs_sites){
						$row_sites = mysqli_fetch_array($rs_sites);
						$progs_per_site .= $row_sites['at_sites'] . "<br /><br />";
					}
				}
			}
			$sites_of_delivery = HEQConline::getSitesOfDelivery('inst',$row["institution_ref"],"",$rpt);
			
			$html =<<<HTML
				<table $tbl_head>
				<tr><td>Record of proceedings relating to:</td><td>$row[lkp_site_proceedings_desc]</td></tr>
				<tr><td><b>Application number</b></td><td>$site_app_no</td></tr>
				<tr><td><b>Public or private institution</b></td><td>$row[lnk_priv_publ_desc]</td></tr>
				<tr><td><b>Name of institution</b></td><td>$row[HEI_name]</td></tr>
				<tr><td><b>Title of programme/s</b></td><td>$progs_per_site</td></tr>
				<tr><td><b>Tuition mode</b></td><td>$row[lkp_mode_of_delivery_desc]</td></tr>
				<tr><td><b>Site(s) of delivery</b></td><td>$sites_of_delivery</td></tr>
				</table>
HTML;
		}
		return $html;
	}
	
	function edit_site_recomm($dec_type, $site_proc_id){
		
		switch ($dec_type){
		case 'recomm':
                        echo $this->getSiteRecommendationTop($site_proc_id);
                        //$htmlFive =<<<HTML
                        echo "<tr>";
			echo "	<td>";
			echo "		Background:<br>";
						if (!($this->formFields["applic_background"]->fieldValue > "")){
							$inst_id = $this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"institution_ref");
							if ($inst_id > 0){
								$default_background = $this->getValueFromTable("HEInstitution","HEI_id",$inst_id,"background");
								$this->formFields["applic_background"]->fieldValue = $default_background;
							}
						}
						$this->showField('applic_background');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		Summary of Evaluator's Report:<br>";
					$this->showField('eval_report_summary');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		<b>Directorate recommendation</b>";
						echo $this->display_site_outcomes("recomm", $site_proc_id, "int"); 
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
					// Display a comment box for the intermediate and final approver.
					if ($this->flowID == 180 || $this->flowID == 181) {
						echo "<span class='specialrb'>Reviewer comments (during intermediate and final approval)</span>";
						$this->showField('recomm_approve_comment');
					}
					
			echo "	</td>";
			echo "</tr>";
//HTML;
                        //echo $htmlFive;
			break;
		case 'ac':
			echo $this->getSiteRecommendationTop($site_proc_id);
                        //$htmlSix =<<<HTML
			echo "<tr>";
			echo "	<td>";
			echo "		Background:<br>";
						$this->showField('applic_background_ac');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		Summary of Evaluator's Report:<br>";
					$this->showField('eval_report_summary_ac');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		<b>Directorate recommendation</b>";
						echo $this->display_site_outcomes("ac", $site_proc_id, "int");
			echo "	</td>";
			echo "</tr>";
//HTML;
                        //echo $htmlSix;			
			break;
		case 'heqc':
			echo $this->getSiteRecommendationTop($site_proc_id);
                        //$htmlSeven =<<<HTML
			echo "<tr>";
			echo "	<td>";
			echo "		Background:<br>";
                                            $this->showField('applic_background_heqc');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		Summary of Evaluator's Report:<br>";
					$this->showField('eval_report_summary_heqc');
			echo "	</td>";
			echo "</tr>";
			echo "<tr>";
			echo "	<td>";
			echo "		<b>Directorate recommendation</b>";
                                           echo $this->display_site_outcomes("heqc", $site_proc_id, "int");
			echo "	</td>";
			echo "</tr>";
//HTML;
                        //echo $htmlSeven;			
			break;
		}
	}
	
	function display_site_outcomes($recomm_type, $site_proc_id, $type="ext"){
		$outer_width = ($type == 'ext') ? '168' : '90%';  // formatted for rtf docs and html
		$inner_width = ($type == 'ext') ? '165' : '90%';  // formatted for rtf docs and html

		$decision_field = "";
		$jscript = "";
		switch($recomm_type){
		case 'recomm':
			$decision_field = "site_recomm_decision_ref";
			$prog_ind_field = "recomm_offering_ind";
			$committee = "Accreditation Directorate";
			$reason_table = 'inst_site_visit_recomm_decision';
			break;
		case 'ac':
			$decision_field = "site_ac_decision_ref";
			$prog_ind_field = "recomm_offering_ind";
			$committee = "Accreditation Committee";
			$reason_table = 'inst_site_visit_ac_decision';
			break;
		case 'heqc':
			$decision_field = "site_heqc_decision_ref";
			$prog_ind_field = "recomm_offering_ind";
			$committee = "HEQC Committee";
			$reason_table = 'inst_site_visit_heqc_decision';
			break;
		}

		$recomm_per_site = "Recommendation type was not valid.  No site recommendation can be requested.";
		if ($decision_field > ""){
			$recomm_per_site = "";
			$sites_arr = HEQConline::getSiteVisitsForApp($site_proc_id);
			foreach($sites_arr as $s){
				$edit = "";
				if ($type == 'int'){
					switch ($this->flowID){
					case '179':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_prelim1");
						break;
					case '180':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_inter1");
						break;
					case '181':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_final1");
						break;
					case '184':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_portal");
						break;
					case '182':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_approve1");
						break;
					case '185':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_approve_outcome");
						break;
					case '187':
						$jscript = $this->scriptGetForm("inst_site_visit", $s['inst_site_visit_id'], "_siteRecommForm_heqc_outcome");
						break;
					}
					if ($jscript > ""){
						$imgPath = $this->relativePath."images";
						$edit =<<<EDIT
							<a href='$jscript'><img src="$imgPath/ico_change.gif" border="0"></a>
EDIT;
					}
				}
				$site = $s['site_name'];
				$address = $s['address'];
				$decision = "No decision made";
				if ($s[$decision_field] > 0){
					$decision = DBConnect::getValueFromTable('lkp_decision_site','lkp_decision_site_id',$s[$decision_field],'decision_site_descr');
				}
				$progs_sql =<<<PROGS
					SELECT GROUP_CONCAT(Institutions_application.program_name separator '<br />') AS progs_offered
					FROM Institutions_application, inst_site_visit_progs
					WHERE Institutions_application.application_id = inst_site_visit_progs.application_ref
					AND inst_site_visit_progs.site_visit_ref = $s[inst_site_visit_id]
					AND inst_site_visit_progs.{$prog_ind_field} = 2
					ORDER BY Institutions_application.program_name
PROGS;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
				$rsp = mysqli_query($conn, $progs_sql);
				$rowp = mysqli_fetch_array($rsp);
				$progs_per_site = "No program offerings permitted";
				if ($rowp["progs_offered"] > ''){
					$progs_per_site = $rowp["progs_offered"];
				}
				$reasons = HEQConline::display_outcome_reason($reason_table,$s['inst_site_visit_id'],"",$s["$decision_field"],$type);
				$recomm_per_site .=<<<PERSITE
					<br /><table width="{$outer_width}" border="0">
					<tr>
						<td>$edit
							<b>Site: $site, $address</b>
							<br /><br />The $committee recommends that the above site be 
							<br /><br />
							 <b>- $decision</b>
							<br />$reasons
							<br />and that the institution be permitted to offer the following programmes to this site of delivery:
							<table width="{$inner_width}" align="center">
								<tr><td>$progs_per_site</td></tr>
							</table>
							<br />
						</td>
					</tr>
					</table>
					<br />
PERSITE;

			}
		}
		return $recomm_per_site;
	}
	
	function getInstitutionContacts($inst_id){
		$sql =<<<INST
			SELECT institutional_profile_contacts_id, contact_designation, lkp_title_desc, contact_name, contact_surname, contact_email, contact_nr
			FROM institutional_profile_contacts
			LEFT JOIN lkp_title ON contact_title_ref = lkp_title_id
			WHERE institution_ref = {$inst_id}
			UNION
			SELECT user_id, 'Institution administrator', lkp_title_desc, name, surname, email, contact_nr
			FROM (users, sec_UserGroups)
			LEFT JOIN lkp_title ON users.title_ref = lkp_title_id
			WHERE users.institution_ref = {$inst_id}
			AND users.user_id = sec_UserGroups.sec_user_ref
			AND sec_UserGroups.sec_group_ref = 4
			ORDER BY contact_designation
INST;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$contacts = array();
		while ($row = mysqli_fetch_array($rs)){
			$contacts[$row['institutional_profile_contacts_id']] = $row;
		}
		return $contacts;
	}

	function generateMeetingDocumentForSites ($meet_id=0, $type, $agenda_type) {
		if ($meet_id == '') return false;

		switch ($type){
		case 'recomm':
			$where = "inst_site_app_proceedings.ac_meeting_ref = $meet_id";
			break;
		default:
			$where = "inst_site_app_proceedings.ac_meeting_ref = -1";
		}
		
		//2017-09-13: Richard - Added AC agenda type
		switch ($agenda_type){
		case 'consent':
			$agenda_id = 1;
			break;
		case 'discuss':
			$agenda_id = 2;
			break;
		default:
			$agenda_id = 2;
		}
		$where .= " AND inst_site_app_proceedings.lkp_AC_agenda_type_ref = $agenda_id";
		
		$report = "";

		$SQL =<<<REPORT
			SELECT inst_site_app_proceedings.*
			FROM inst_site_app_proceedings
			WHERE $where
REPORT;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

		$rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No site applications have been assigned to this meeting.</p><page />";
		}
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatSiteRecomm($row,"recomm");
			
			$report .=<<<REPORT
				<page />
REPORT;
		endwhile;

		return $report;
	}
	
	function generateSiteRecomm ($site_proc_id, $recomm_type) {

		if ($site_proc_id == '') return false;

		$report = "";
		
		$SQL =<<<REPORT
			SELECT inst_site_app_proceedings.*
			FROM inst_site_app_proceedings
			WHERE inst_site_app_proceedings.inst_site_app_proc_id = $site_proc_id
REPORT;

       $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
 $rs = mysqli_query($conn, $SQL);
		$n = mysqli_num_rows($rs);
		if ($n == 0) {
			$report .= "<p>No site proceedings for specified proceeding.</p>";
		}
		// Only 1 row should be returned. USing while anyway.
		while ($row = mysqli_fetch_array($rs)):
			$report .= HEQConline::formatSiteRecomm($row, $recomm_type);
		endwhile;

		return $report;
	}
	
		function formatSiteRecomm($row,$recomm_type){
			$site_proc_id = $row["inst_site_app_proc_id"];

			$meet_id = $row["ac_meeting_ref"];
			$ac_start_date = DBConnect::getValueFromTable("AC_Meeting", "ac_id", $meet_id, "ac_start_date");
			$ac_meeting_start = date("jS F Y",strtotime($ac_start_date));

			$proceeding_desc = DBConnect::getValueFromTable("lkp_site_proceedings", "lkp_site_proceedings_id", $row["lkp_site_proceedings_ref"], "lkp_site_proceedings_desc");
			
			switch($recomm_type){
			case 'recomm':
				$backg = $row["applic_background"] ? simple_text2html($row["applic_background"], "docgen") : "";
				$eval_summ = $row["eval_report_summary"] ? simple_text2html($row["eval_report_summary"], "docgen") : "";
				break;
			case 'ac': 
				$backg = "";
				$eval_summ = "";
				break;
			case 'heqc':
				$backg = "";
				$eval_summ = "";
				break;
			}

			$site_header = HEQConline::getSiteRecommendationTop($site_proc_id,'ext');
			$site_outcomes = HEQConline::display_site_outcomes("recomm", $site_proc_id, "ext");
			
			$report = "";

			// Note:  Formatting below is specific: <br />$backg on one line otherwise a space is added before the $backg text.
			$report .=<<<REPORT
			<table width="170" border="0" align="center">
			<tr>
				<td align="center">
					<b>HIGHER EDUCATION QUALITY COMMITTEE<br />
					ACCREDITATION COMMITTEE<br />
					<u>MEETING HELD ON <i>$ac_meeting_start</i></u></b>
					<br />
				</td>
			</tr>
			<tr>
				<td>
					<b>Record of proceedings relating to:</b>
				</td>
			</tr>
			</table>
			
			<table width="170" border="1" align="center">
			<tr>
				<td colspan="2">$proceeding_desc</td>
			</tr>
			</table>
			$site_header
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Background</b>
					<br />$backg
				</td>
			</tr>
			</table>
			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td align="left">
					<b>Summary of Evaluator Report</b>
					<br />$eval_summ
				</td>
			</tr>
			</table>
			
			<br />
			
			<table width="170" border="1" align="center">
			<tr>
				<td>
					<b>Directorate recommendation</b>
						$site_outcomes
				</td>
			</tr>
			</table>
			
			<br />
REPORT;
			
		return $report;
	}
	
	function getSitesOfDelivery($entity,$id,$format,$type="int") {
	
		switch ($entity){
			case 'inst':
				$sql =<<<SQL
					SELECT *
					FROM institutional_profile_sites
					WHERE institution_ref = $id
SQL;
				break;
			case 'reacc':
				$sql =<<<REACC
					SELECT * 
					FROM institutional_profile_sites, lkp_sites_reaccred
					WHERE lkp_sites_reaccred.sites_ref = institutional_profile_sites.institutional_profile_sites_id
					AND lkp_sites_reaccred.Institutions_application_reaccreditation_ref = {$id}
REACC;
				break;
		}
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$rs = mysqli_query($conn, $sql);
		$additional_sites_html = "";
		$count = 0;

		if (mysqli_num_rows($rs) > 0) {
			$additional_sites_html = ($type == 'ext') ? "" : "<ol>";
			while ($row = mysqli_fetch_array($rs))
			{
				$count++;
				$name = $row["site_name"];
				$location = $row["location"];
				
				switch ($type){
				case 'ext':
					$additional_sites_html .= ($count > 1) ? "<br /><br />" : "";
					$additional_sites_html .= "$name<br />";
					if ($row["address"] > ''){
						$phys_address = trim(simple_text2html($row["address"], "docgen"));
						$additional_sites_html .= $phys_address;
					} else {
						$additional_sites_html .= $location;
					}
					break;
				default:
					$additional_sites_html .= "<li>$name - $location </li>";
					break;
				}
			}
			$additional_sites_html .= ($type == 'ext') ? "" : "</ol>";
			return $additional_sites_html;
		}
		else {
		 return "<i>No sites have been found</i>";
		}
	}
	
	function defaultSiteOutcome($type, $site_proc_id){
	// 1. Check whether an outcome exists for the proceedings
		$sql_proc =<<<PROCEEDING
			SELECT inst_site_app_proc_id, lkp_site_proceedings_ref, 
				applic_background, applic_background_ac, applic_background_heqc,
				eval_report_summary, eval_report_summary_ac, eval_report_summary_heqc
			FROM inst_site_app_proceedings
			WHERE inst_site_app_proc_id = {$site_proc_id}
PROCEEDING;
		$rs_proc = mysqli_query($this->getDatabaseConnection(), $sql_proc);
		if ($rs_proc){
			while ($row_proc = mysqli_fetch_array($rs_proc)){
				$recomm_backg = $row_proc["applic_background"];
				$ac_backg = $row_proc["applic_background_ac"];
				$heqc_backg = $row_proc["applic_background_heqc"];
				$recomm_eval_summ = $row_proc["eval_report_summary"];
				$ac_eval_summ = $row_proc["eval_report_summary_ac"];
				$heqc_eval_summ = $row_proc["eval_report_summary_heqc"];
				$site_proc_id = $row_proc["inst_site_app_proc_id"];

				switch ($type){
				case "AC":

					if (!($ac_backg > "") && !($ac_eval_summ > "")){

						$this->setValueInTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"applic_background_ac",$recomm_backg);
						$this->setValueInTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"eval_report_summary_ac",$recomm_eval_summ);
					}
					break;
				case "HEQC":
					if ($heqc_backg == "" && $heqc_eval_summ == ""){
						$this->setValueInTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"applic_background_heqc",$ac_backg);
						$this->setValueInTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"eval_report_summary_heqc",$ac_eval_summ);
					}
					break;
				}
	
				$sql_visit =<<<SITEVISIT
					SELECT inst_site_visit_id,  site_recomm_decision_ref, 
					site_ac_decision_ref, site_heqc_decision_ref  
					FROM inst_site_visit
					WHERE inst_site_visit.inst_site_app_proc_ref = {$site_proc_id}
SITEVISIT;

				$rs_visit = mysqli_query($this->getDatabaseConnection(), $sql_visit);
				if ($rs_visit){
					while ($row_visit = mysqli_fetch_array($rs_visit)){
						$site_visit_id = $row_visit["inst_site_visit_id"];
						$site_recomm_dec = $row_visit["site_recomm_decision_ref"];
						$site_ac_dec = $row_visit["site_ac_decision_ref"];
						$site_heqc_dec = $row_visit["site_heqc_decision_ref"];

						switch ($type){
						case "AC":
							// If no outcome then default ac outcome to directorate recommendation outcome.
							if (!($site_ac_dec > 0)){

								$this->setValueInTable("inst_site_visit","inst_site_visit_id",$site_visit_id,"site_ac_decision_ref",$site_recomm_dec);

								$ins_sql =<<<INSERT
									INSERT INTO inst_site_visit_ac_decision
									SELECT NULL, inst_site_visit_ref, decision_reason_condition, condition_term_ref, criterion_min_standard
									FROM inst_site_visit_recomm_decision
									WHERE inst_site_visit_ref = $site_visit_id
INSERT;
								$errorMail = false;
								$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or $errorMail = true;
								$this->writeLogInfo(10, "SQL-INSREC", "class.HEQCOnline.php - DefaultSiteOutcome:"  . $ins_sql."  --> ".mysqli_error(), $errorMail);
							}
							break;
						case "HEQC":
							// If no outcome then default heqc outcome to AC outcome.
							if (!($site_heqc_dec > 0)){

								$this->setValueInTable("inst_site_visit","inst_site_visit_id",$site_visit_id,"site_heqc_decision_ref",$site_ac_dec);

								$ins_sql =<<<INSERT
									INSERT INTO inst_site_visit_heqc_decision
									SELECT NULL, inst_site_visit_ref, decision_reason_condition, condition_term_ref, criterion_min_standard
									FROM inst_site_visit_ac_decision
									WHERE inst_site_visit_ref = $site_visit_id
INSERT;
								$errorMail = false;
								$ins_rs = mysqli_query($this->getDatabaseConnection(), $ins_sql) or $error_mail = true;
								$this->writeLogInfo(10, "SQL-INSREC", "class.HEQCOnline.php - DefaultSiteOutcome:"  . $ins_sql."  --> ".mysqli_error(), $errorMail);
							}
							break;
						}
					}	
				}
			}
		}
	}
	
	function getProgrammesForSite($site_id){	
		$progs = array();
		
		$sql =<<<PROGRAMMES
			SELECT application_id,
					program_name,
				   CHE_reference_code,
				   lkp_title,
				lkp_sites.lkp_sites_id
			FROM Institutions_application,
				 lkp_sites, 
				 lkp_desicion
			WHERE lkp_sites.sites_ref = $site_id 
			AND lkp_sites.application_ref = Institutions_application.application_id
			AND lkp_desicion.lkp_id = Institutions_application.AC_desision
			AND Institutions_application.AC_desision IN (1,2,3,4)
			ORDER BY program_name
PROGRAMMES;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql) or die(mysqli_error());
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$progs[$row['application_id']] = $row;
			}
		}
		return $progs; 
	}
	
	function getEvalPersnrForUser($user_id){
	    $eparr["personNumber"] = '';
		$eparr["personEmail"] = '';

		// Get the Persnr of the evaluator using the user_id of the evaluator logged in
		$sql =<<<SQL
			SELECT Eval_Auditors.Persnr, users.email
			FROM users, Eval_Auditors
			WHERE users.user_id = Eval_Auditors.user_ref
			AND Eval_Auditors.user_ref = '{$user_id}'
SQL;

//echo $user_id;
          $conn= $this->getDatabaseConnection();
 
		$rs = mysqli_query($conn, $sql);
		
		// Notify support of multiple evaluator records assigned to one user record.
		$num_eval_records_for_user = mysqli_num_rows($rs);
		if ($num_eval_records_for_user > 1){
			$this->writeLogInfo(10, "SQL-EVAL", "Multiple evaluator records for user:"  . $sql, 'true');
		}
		while ($row = mysqli_fetch_array($rs)){
			$eparr["personNumber"]= $row['Persnr'];
			$eparr["personEmail"][$row['Persnr']] = $row['email'];
		}
		
		return $eparr;
	}
	
	function getUsersInGroup($grp){
		$users = array();
	
		$SQL =<<<GETUSERS
			SELECT user_id, email
			FROM users, sec_UserGroups
			WHERE sec_group_ref = $grp 
			AND sec_user_ref = user_id 
			AND active = 1 
GETUSERS;
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
		while ($RS && ($row = mysqli_fetch_array($RS))) {
			$users[$row["user_id"]] = $row;
		}
		return $users;
	}
	
	function getActiveProcessforApp($id,$ent="applic",$ret="process"){
		$process['name'] = '';
		$active_processes_id = -1;

		switch($ent){
		case "applic":
			$sql =<<<PROCESS
				SELECT processes.processes_desc, users.name, active_processes_id
				FROM active_processes
				LEFT JOIN processes ON active_processes.processes_ref = processes.processes_id
				LEFT JOIN users ON active_processes.user_ref = users.user_id
				WHERE active_processes.status = 0
				AND (active_processes.workflow_settings like '%application_id={$id}&%')
PROCESS;
			break;
		case "reacc":
			$sql =<<<PROCESS
				SELECT processes.processes_desc, users.name, active_processes_id
				FROM active_processes
				LEFT JOIN processes ON active_processes.processes_ref = processes.processes_id
				LEFT JOIN users ON active_processes.user_ref = users.user_id
				WHERE active_processes.status = 0
				AND (active_processes.workflow_settings like '%Institutions_application_reaccreditation_id={$id}&%')
PROCESS;
			break;
		case "proceeding":
			$sql =<<<PROCESS
				SELECT processes.processes_desc, users.name, active_processes_id
				FROM active_processes
				LEFT JOIN processes ON active_processes.processes_ref = processes.processes_id
				LEFT JOIN users ON active_processes.user_ref = users.user_id
				WHERE active_processes.status = 0
				AND (active_processes.workflow_settings like '%ia_proceedings_id={$id}&%')
PROCESS;
			break;
		}

 		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$n = mysqli_num_rows($rs);
		if ($n > 0){
			while ($row = mysqli_fetch_array($rs)){
				$process['name'] .= $row['processes_desc'] . "-" . $row['name'];
				$active_processes_id = $row['active_processes_id'];
			}
		} else {
			$process['name'] = 'closed';
		}
		if ($ret == "process") {
			return $process;
		} else {
			return $active_processes_id;
		}
	}
	
	function sendPaymentReminders (){
		$rem1_days = $this->getDBsettingsValue("reminder1_days_from_invoice");
		$rem2_days = $this->getDBsettingsValue("reminder2_days_from_reminder1");
		$rem3_days = $this->getDBsettingsValue("reminderw_days_from_reminder2");
		$today = date('Y-m-d');	
		$now = date('Y-m-d H:i:s');	
//echo "Today: " . $today . " now: " . $now . "<br>";
		
		// Get all the users that must be emailed a copy of the email: Payment administrator, users in finance emails group, current user and octoplus
		$pay_adm_id = $this->getDBsettingsValue("usr_registry_payment");
		$cc = array();
		$cc[0] = $this->getValueFromTable("users","user_id",$pay_adm_id,"email");
		$cc[1] = $this->getValueFromTable("users","user_id",$this->currentUserID,"email");
		$cc[2] = $this->getDBsettingsValue("support_technical_email");

		$usr_to_copy = $this->getUsersInGroup(34); //usr_finance_emails group
		if (count($usr_to_copy) > 0){
			foreach($usr_to_copy as $u){
				array_push($cc, $u[1]);
			}
		}
		$cc_arr = array_unique($cc);
		$cc_list = implode(",", $cc_arr);
		// Identify all unpaid payment records (Could be a payment for a new application, new re-accreditation application, proceeding (deferral, representation or 
		// condition) or site visit.  Payments cannot be cancelled for proceeding or site visit applications as these cannot be returned to the institution.  
		// For new applications (accreditation and re-accreditation) payments can be cancelled and the application returned to the institution for re-submission.
		$sql =<<<SQL
				SELECT payment_id, application_ref,	ia_proceedings_ref,	reaccreditation_application_ref,
					date_invoice, invoice_total, payment_total, date_first_reminder,
					date_final_reminder, date_cancelled
				FROM payment
				WHERE date_invoice > '1000-01-01'
				AND received_confirmation = 0
				AND date_cancelled = '1000-01-01'
				ORDER BY date_invoice, payment_id
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		$i = 0;
		$n = 0;
		while ($row = mysqli_fetch_array($rs)){
			$n++;
			
			$payment_id = $row["payment_id"];

			$app_id = $row["application_ref"];
			$app_proc_id = $row["ia_proceedings_ref"];
			$reacc_id = $row["reaccreditation_application_ref"];

			$may_withdraw = "no";
			if ($app_proc_id > 0){ // Payment for proceeding and there will always be an app_id as well
				// Can't return proceedings based on no payment - they're too far down the process.
				// if there is a proc_id there is also an app_id.  Adm is determined above.
				$type = "proceeding";
				$instAdm = $this->getInstitutionAdministrator($app_id);
				$inst_id = $this->getValueFromTable("Institutions_application","application_id",$app_id,"institution_id");
				$act_proc_id = $this->getActiveProcessforApp($app_proc_id,"proceeding","id");
				$may_withdraw = "no";
			} elseif ($app_id > 0){  // New accreditation application
				$type = "accred";
				$instAdm = $this->getInstitutionAdministrator($app_id);
				$inst_id = $this->getValueFromTable("Institutions_application","application_id",$app_id,"institution_id");
				$act_proc_id = $this->getActiveProcessforApp($app_id,"applic","id");
				$may_withdraw = "yes";
			} elseif ($reacc_id > 0){ // New re-accreditation application
				$type = "reaccred";
				$instAdm = $this->getInstitutionAdministrator(0,"",$reacc_id);
				$inst_id = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_id,"institution_ref");
				$act_proc_id = $this->getActiveProcessforApp($reacc_id,"reacc","id");
				$may_withdraw = "no";
			} else {
				// NOTE: set this up to email as an issue.  If can't identify type of payment record then just bypass this record and continue with the next record.
				echo "<br>Payment reminders - Payment id: " . $payment_id . " is not defined.  Please report this message to HEQC-online support.";
				continue;
			}

			if ($act_proc_id == -1){
				// NOTE: set this up to email as an issue.  If can't identify type of payment record then just bypass this record and continue with the next record.
				echo "<br>Payment reminders - Payment id: " . $payment_id . " Open active process has not been found.  Please report this message to HEQC-online support.";
				continue;
			}
			
			if ($instAdm[0]==0){
				echo "<br>" . $instAdm[1];
				continue;
			}

//			$this->dbTableInfoArray["HEInstitution"] = new dbTableInfo ("HEInstitution", "HEI_id", $inst_id);
//			$this->dbTableInfoArray["Institutions_application"] = new dbTableInfo ("Institutions_application", "application_id", $app_id);
//			$this->dbTableInfoArray["ia_proceedings"] = new dbTableInfo ("ia_proceedings", "ia_proceedings_id", $app_proc_id);
//			$this->dbTableInfoArray["Institutions_application_reaccreditation"] = new dbTableInfo ("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id);
//			$this->dbTableInfoArray["payment"] = new dbTableInfo ("payment", "payment_id", $payment_id);
			$this->dbTableInfoArray = $this->parseOtherWorkflowProcess($act_proc_id);

			$date_invoice =  $row["date_invoice"];
			$seconds_diff =  strtotime("now") - strtotime($date_invoice);
			$days_outstanding = $seconds_diff/(60 * 60 * 24);
			$send_reminder = "no";
			$subject = "";
			$message = "";
			
			if ($row["date_first_reminder"] == '1000-01-01'){
				if ($days_outstanding > $rem1_days){
					//populate and email reminder 1 to institutional administrator
					$subject = "payment reminder";
					switch ($type){
					case "accred":
					case "proceeding":  // proceeding has app id with its own payment id so the same reminder email can be used.
						$message = $this->getTextContent ("payCheckForm6", "firstPaymentReminder");
						break;
					case "reaccred":
						$message = $this->getTextContent ("reAccpayCheckForm6", "reAccfirstPaymentReminder");
						break;
					}
					$send_reminder = "yes";
					$this->setValueInTable('payment','payment_id',$payment_id,"date_first_reminder",$today);
//echo "First reminder - payment_id: " . $payment_id;
				}
			}

			if ($row["date_final_reminder"] == '1000-01-01'){
				if ($row["date_first_reminder"] > '1000-01-01'){  // first reminder must have been sent
					$rem2_sec =  strtotime("now") - strtotime($row["date_first_reminder"]);  //days since first reminder was sent
					$rem2_outstanding = $rem2_sec/(60 * 60 * 24);
					if ($rem2_outstanding > $rem2_days){
					//populate and email reminder 1 to institutional administrator
						$subject = "final payment reminder";
						switch ($type){
						case "accred":
						case "proceeding":  // proceeding has app id with its own payment id so the same reminder email can be used.
							$message = $this->getTextContent ("payCheckForm8", "finalPaymentReminder");
							break;
						case "reaccred":
							$message = $this->getTextContent ("reAccpayCheckForm8", "reAccfinalPaymentReminder");
							break;
						}
						$send_reminder = "yes";
//echo "<br><br>Final reminder - payment_id: " . $payment_id;
						$this->setValueInTable('payment','payment_id',$payment_id,"date_final_reminder",$today);
					}
				}
			}
				
			if ($may_withdraw == "yes" && $row["date_cancelled"] == '1000-01-01'){
				if ($row["date_final_reminder"] > '1000-01-01'){  // final reminder must have been sent
					$rem3_sec =  strtotime("now") - strtotime($row["date_final_reminder"]);  //days since first reminder was sent
					$rem3_outstanding = $rem3_sec/(60 * 60 * 24);
//echo  "<br>Rem3 - Date final reminder: " . $row["date_final_reminder"];
//echo  "<br>Rem3 - outstanding days: " . $rem3_outstanding;
//echo  "<br>Rem3 - max allowed days: " . $rem3_days;

					if ($rem3_outstanding > $rem3_days){

						$subject = "return notice";

						$wkflow = $this->getValueFromTable("active_processes","active_processes_id",$act_proc_id,"workflow_settings");

						$arr_wkflow1 = array();
						$arr_wkflow = explode("&", $wkflow);
						foreach ($arr_wkflow as $a) {
							if (strpos($a,"LOGIC_SET") === 0){ // Found LOGIC_SET
								$a = "";
							}
							if (strpos($a,"DBINF_payment___payment_id") === 0){ // Found Payment_id
								$a = "";
							}
							if (strpos($a,"ACTPROC") === 0){ // Found ACTPROC
								$a = "ACTPROC=";
							}
							if ($a > ""){
								array_push($arr_wkflow1,$a);
							}
						}
						$newWorkFlow = implode("&",$arr_wkflow1);
	
						$id = $this->addActiveProcesses (113, $instAdm[0], 0, 0, false, $newWorkFlow);
						$this->setValueInTable("active_processes","active_processes_id",$act_proc_id,"status",1);

						$send_reminder = "yes"; // Reminder is sent when process is returned to user in line above.
						$this->setValueInTable('payment','payment_id',$payment_id,"date_cancelled",date('Y-m-d'));
						$this->setValueInTable('payment','payment_id',$payment_id,"reason_cancelled","Returned to administrator on $now due to non-payment");

						switch ($type){
						case "accred":
						case "proceeding":  // proceeding has app id with its own payment id so the same reminder email can be used.
							$message = $this->getTextContent ("paymentReminders", "returnReminder");
							$this->setValueInTable("Institutions_application", "application_id", $app_id, "submission_date", "1000-01-01");
							$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_printed", "0");
							break;
						case "reaccred":
							$message = $this->getTextContent ("reAccpaymentReminders", "reAccReturnReminder");
							$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "submission_date", "1000-01-01");
							$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "application_printed", "0");
							break;
						}
						
//echo "<br><br>Withdrawal - payment_id: " . $payment_id;
//echo "<br><br>Active process: " . $act_proc_id . "<br><br>New active process: " . $id;
//echo "<br>" . $newWorkFlow;
 					}
				}  
			}

			if ($send_reminder == "yes"){
				$i++;
				// Email payment administrator, institutional administrator and current user (if different to institutional administrator)
				// Need to set this in order for the Audit Trail Log to be written for this application
				$this->active_processes_id = $act_proc_id;
				$workflow_settings = $this->getValueFromTable("active_processes","active_processes_id",$act_proc_id,"workflow_settings");
				$this->parseWorkFlowString ($workflow_settings);
				$this->misMail($instAdm[0], $subject, $message, $cc_list); // Institutional administrator and cc finance group, current user, payment administrator and technical support.	

//echo "Total: " . $n . " - reminders: " . $i;			
//if ($i > 0) {die();}
			}
			
		}

	}
	
	function getApplicationTitleHistory($app_id){
		$html = "";
		$sql =<<<SQL
			SELECT ia_title_history_id, old_title, new_title, date_changed, users.name, reason, reason_doc 
			FROM ia_title_history 
			LEFT JOIN users ON ia_title_history.user_ref = users.user_id
			WHERE application_ref = {$app_id} 
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		if (mysqli_num_rows($rs) > 0){
				$html =<<<HTML
					<table>
					<tr class="onblue">
						<td>Date changed</td>
						<td>Old name</td>
						<td>New name</td>
						<td>User</td>
						<td>Reason</td>
						<td>Evidence</td>
					</tr>
HTML;

			while ($row = mysqli_fetch_array($rs)){
				$reason_doc = "&nbsp;";
				if ($row['reason_doc'] > 0){
					$octoDoc = new octoDoc($row['reason_doc']);
					$reason_doc	 = "<a href='".$octoDoc->url()."' target='_blank'>Document</a>";
				}

				$html .=<<<HTML
					<tr>
						<td>{$row["date_changed"]}</td>
						<td>{$row["old_title"]}</td>
						<td>{$row["new_title"]}</td>
						<td>{$row["name"]}</td>
						<td>{$row["reason"]}</td>
						<td>{$reason_doc}</td>
					</tr>
HTML;
			}
			$html .= "</table>";
		}
		return $html;
	}
	
	function displayComments(){
		$html ='';
		$addCommentbtn = "<button id = 'add_app_comment'>Add New comment</button> ";
		
		if(isset($this->workFlow_settings['DBINF_Institutions_application___application_id']) && !empty($this->dbTableInfoArray['Institutions_application']->dbTableKeyField) && ($this->dbTableInfoArray['Institutions_application']->dbTableCurrentID != 'NEW') ){
			$appRef = $this->dbTableInfoArray['Institutions_application']->dbTableCurrentID;
			$sql = "SELECT *
			FROM (SELECT ia_comments_id, application_ref, comment_date ,user_ref, comment FROM ia_comments WHERE application_ref = ". $appRef ." ORDER BY comment_date DESC) t ORDER BY ia_comments_id DESC LIMIT 1";
			$rs = mysqli_query($this->getDatabaseConnection(), $sql);
			$commentsCount = mysqli_num_rows($rs);
			
			$html .= "<table width='100%' border='0' align='center' cellpadding='2' cellspacing='2'>";
			$html .= "<tr>";
			$html .= "<td bgcolor='#CC3300' align='center'>";
			$html .= "<span class='whiteb'>Recent comments on this application</span>";
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</table>";
			$html .= "<table id='comment_table' width='95%' border='0' align='center'>";
			$html .= "<tbody>";
			$html .= "<tr>";
			$html .= "<td>";
			$html .= "<div id ='comment_list'>";
			$btnClass = ( $commentsCount == 0 ) ? "hidden" : "view_app_comment";
			if($commentsCount > 0){				
				while($row = mysqli_fetch_array($rs)){
					$html .= $row['comment'] . "<br><small>". $this->getUserName($row['user_ref'], 2) . ", " .$this->getCommentProcessDetails($this->active_processes_id) . ", " . $row['comment_date']  . "</small>";
				}								
			}
			$html .= "</div>";
			$html .= $addCommentbtn;
			$ViewAllCommentbtn = "<button id = 'view_app_comment' class= '$btnClass' >View all comments </button> ";
			$html .= $ViewAllCommentbtn;
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</tbody>";
			$html .= "</table>";
		}
			
		return $html;

	}
	
	function getCommentProcessDetails($activeProcessId){
		$processes_desc ='';
		$sql= "SELECT * FROM active_processes 
			LEFT JOIN processes ON active_processes.processes_ref = processes.processes_id
			WHERE active_processes_id = ?";
                //file_put_contents('php://stderr', print_r($sql, TRUE));
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

        $sm = $conn->prepare($sql);
		$sm->bind_param("s", $activeProcessId);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		while($row = mysqli_fetch_array($rs, MYSQLI_BOTH)){
                    $processes_desc .= $row['processes_desc'];
		}
		return $processes_desc;
	}

	function getPayData($id,$field){
		$pay_data = array();
		switch ($field){
		case "payment_id":
			$sql =<<<SQL
				SELECT * FROM payment WHERE payment_id = $id
SQL;
			break;
		case "application_ref":
			$sql =<<<SQL
				SELECT * FROM payment WHERE application_ref = $id AND ia_proceedings_ref = 0
SQL;
			break;
		case "ia_proceedings_ref":
			$sql =<<<SQL
				SELECT * FROM payment WHERE ia_proceedings_ref = $id
SQL;
			break;
		case "reaccreditation_application_ref":
			$sql =<<<SQL
				SELECT * FROM payment WHERE reaccreditation_application_ref = $id
SQL;
			break;
		}
		if ($sql > ""){
			$rs = mysqli_query($this->getDatabaseConnection(), $sql);
			while ($row = mysqli_fetch_array($rs,MYSQLI_ASSOC)){
				array_push($pay_data,$row);
			}
		}
		return $pay_data;
	}
	
	function is_condition_met($app_id,$condition_text){
                $conn = $this->getDatabaseConnection();
		$condition_text = mysqli_real_escape_string($conn, $condition_text);
		$sql =<<<SQL
			SELECT IF(condition_met_yn_ref = 1, "Not met", IF(condition_met_yn_ref = 2, "Met","-")) AS condition_state
			FROM ia_conditions
			WHERE ia_conditions.application_ref = {$app_id}
			AND ia_conditions.decision_reason_condition = '{$condition_text}'
SQL;
			// SELECT condition_met_yn_ref
			// FROM ia_conditions
			// WHERE ia_conditions_id IN (SELECT ia_conditions_ref 
										// FROM ia_conditions_proceedings
										// WHERE ia_proceedings_ref = {$app_proc_id}
										// )
			// AND ia_conditions.decision_reason_condition = '{$condition_text}'
//echo $sql . "<br>";
                $met = null;
		$rs = mysqli_query($conn, $sql) or die(mysqli_error());
		$nr = mysqli_num_rows($rs);
		if ($nr == 0){
			$met = "-";
		}
		if ($nr == 1){
			$row = mysqli_fetch_array($rs);
			$met = $row["condition_state"];
		}
		if ($nr > 2){
			$met = "Duplicates";
		}
		return $met;
	}

	function userGroup ($group, $user=0) {
		$ret = false;
		if ($user == 0) {  // if no user is spesified, use the active user.
			$user = $this->currentUserID;
		}

		$SQL = "SELECT * FROM sec_UserGroups, sec_Groups ".
					 "WHERE sec_user_ref = $user ".
						 "AND sec_group_ref = sec_group_id ".
						 "AND sec_group_type = '$group'";
		$rs = mysqli_query ($this->getDatabaseConnection(), $SQL);
		if ( $row = mysqli_fetch_array ($rs) ) {
			$ret = true;
		}
		return ($ret);
	}
	
	function restrictOutcomeValuesperProceeding($proc_type,$fieldName){
		$arr_outcome = array();

		switch ($proc_type){
			case 5:
			case 6:
			case 7:
			case 8:
				$sql =<<<REF
					SELECT lkp_id, lkp_title 
					FROM lkp_desicion 
					WHERE lkp_id IN (5,6,7,8)
REF;
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			default:
				$sql =<<<REF
					SELECT lkp_id, lkp_title 
					FROM lkp_desicion 
					WHERE lkp_id IN (1,2,3,4)
REF;
		}
		$rs = mysqli_query($this->getDatabaseConnection(), $sql);
		while ($row = mysqli_fetch_array($rs)) {
			$arr_outcome[$row["lkp_id"]] = $row["lkp_title"];
		}
		$this->formFields[$fieldName]->fieldValuesArray = $arr_outcome;
		return $arr_outcome;
	}
	// END of Class
	}
?>
