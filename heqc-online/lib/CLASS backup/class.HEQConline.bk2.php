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
		$SQL = "SELECT holiday_date FROM `lkp_public_holidays` WHERE holiday_date >= ?";
		$d = date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $d);
		$sm->execute();
		$RS = $sm->get_result();
		
		//$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
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
					 "WHERE division = ? ".
						 "AND institution = ? ".
						 "AND program = ?";
                
                $sm = $conn->prepare($SQL);
		$sm->bind_param("sss", $division, $institution, $program);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO CHE_referenceNo VALUES"."(NULL, '$division', '$institution', '$program', $newNum, '$progType')";
                
                $sm = $conn->prepare($SQL);
		$sm->bind_param("sssss", $division, $institution, $program, $newNum, $progType);
		$sm->execute();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

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
		$SQL = "SELECT * FROM `last_hei_code` WHERE public_private=?";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $prov);
		$sm->execute();
		$rs = $sm->get_result();
		
		$hei_code = "";
		if ($rs && ($row=mysqli_fetch_array($rs))) {
			$hei_code = $row["public_private"].sprintf("%03u", $row["hei_code_num"]);
		}
		$SQL = "UPDATE `last_hei_code` SET hei_code_num=(hei_code_num+1) WHERE public_private=?";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $prov);
		$sm->execute();
		$rs = $sm->get_result();
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		
		return $hei_code;
	}

	function createInstitution_reference ($org) {
                $conn = $this->getDatabaseConnection();
		$newNum = 1;
		$org_type = $org;
		// first find the last program
		$SQL = "SELECT max(orgNo) FROM Institution_referenceNo ".
					 "WHERE org_type = ? ";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $org);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO Institution_referenceNo VALUES".
					 "(NULL, ?, ?)";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("ss", $org_type, $newNum);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

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
		$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld."=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $keyFldValue);
		$sm->execute();
		$rs = $sm->get_result();
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
		
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
		$SQL = "SELECT * FROM `".$table."` WHERE siteVisit_ref=?";
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $siteVisit_id);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $siteVisit_id);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		$SQL = "SELECT ".implode(", ", array_keys($fieldsArr))."  FROM `".$table."` WHERE siteVisit_ref= ? ORDER BY ?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("ss", $siteVisit_id, $keyFld);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);

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
		$SQL = "SELECT * FROM `".$table."` WHERE siteVisit_ref=?";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $siteVisit_id);
		$sm->execute();
		$rs = $sm->get_result();
				
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		$SQL = "SELECT date_visit1, date_visit2, final_date_visit FROM siteVisit WHERE siteVisit_id=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
		$sm->execute();
		$RS = $sm->get_result();
		
		//$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		$SQL = "SELECT * FROM `".$table."` WHERE ".$tableKeyFld."=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $tableKeyVal);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $app_id);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
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
		$site_arr = array();
		$SQL = "SELECT ".$selectFld." FROM siteVisit WHERE ".$keyFld."=".$keyVal;
		$rs = mysqli_query ($SQL);
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
		$SQL = "SELECT site_delivery FROM siteVisit WHERE application_ref=".$app_id;
		$rs = mysqli_query ($SQL);
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
				$valueRS = mysqli_query($this->getDatabaseConnection(), $SQL);
				if ($valueRS && (mysqli_num_rows($valueRS) > 0) && ($rr = mysqli_fetch_object($valueRS))) {
					$SQL = "UPDATE `siteVisit_report` SET site_ref='".$site_ref."', application_ref='".$app_ref."', commend='".$value."' WHERE siteVisit_report_areas_ref='".$ref[1]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
					$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
					$id = $rr->siteVisit_report_id;
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, commend, siteVisit_report_areas_ref) VALUES ('".$site_ref."', '".$app_ref."', '".$value."', '".$ref[1]."')";
					$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
					$id = mysqli_insert_id();
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
                
		$RS = mysqli_query($this->getDatabaseConnection(), "SELECT * FROM `".$lookup_template_table."`");
		$num_rows = mysqli_num_rows($RS);
		while ($RS && ($template_table_row=mysqli_fetch_array($RS))) {
			$init_RS = mysqli_query($this->getDatabaseConnection(), "INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
			$last_id = mysqli_insert_id();
			foreach ($fieldsArr AS $initK=>$initV) {
				mysqli_query($this->getDatabaseConnection(), "UPDATE `".$childTable."`  SET ".$initK."='".$template_table_row[$initK]."' WHERE ".$childRef."=".$parentTable_id." AND ".$childKeyFld."='".$last_id."'");
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

}