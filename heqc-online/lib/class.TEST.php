<?php

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
	/**
	 * default constructor
	 *
	 * this function calls the {@link workFlow} function.
	 * @author Diederik de Roos
	 * @param integer $flowID
	*/
	function HEQConline ($flowID) {
		$this->readPath ();
		$this->workFlow ($flowID);
		$this->populatePublicHolidays ();
		$this->populatePrivatePublicDocs ();
	}

	function readPath () {
		global $path;

		$this->relativePath = (isset($path))?($path):("");
	}

	function populatePublicHolidays () {
		$this->public_holidays = array();
		$SQL = "SELECT holiday_date FROM `lkp_public_holidays` WHERE holiday_date >= '".date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")))."'";
		$RS = mysqli_query($SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			array_push($this->public_holidays, $row["holiday_date"]);
		}
	}

	function populatePrivatePublicDocs () {
		$this->private_docs = $this->public_docs = "";
		$SQL = "SELECT * FROM `lkp_application_docs`";
		$RS = mysqli_query($SQL);
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
		$newNum = 1;

		// first find the last program
		$SQL = "SELECT max(progNo) FROM CHE_referenceNo ".
					 "WHERE division = '$division' ".
						 "AND institution = '$institution' ".
						 "AND program = '$program'";
		$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO CHE_referenceNo VALUES".
					 "(NULL, '$division', '$institution', '$program', $newNum, '$progType')";
		$rs = mysqli_query($SQL);

		// format the reference number
		$reference = sprintf("%s/%s/%s%03u%s", $division, $institution, $program, $newNum, $progType);

		return ($reference);
	}

	/*
	 * Louwtjie: 2004-08-11
	 * function to return the last hei_code and update the database with the new one.
	*/
	function getLastHEIcode($prov="PR") {
		$SQL = "SELECT * FROM `last_hei_code` WHERE public_private='".$prov."'";
		$rs = mysqli_query($SQL);
		$hei_code = "";
		if ($rs && ($row=mysqli_fetch_array($rs))) {
			$hei_code = $row["public_private"].sprintf("%03u", $row["hei_code_num"]);
		}
		$SQL = "UPDATE `last_hei_code` SET hei_code_num=(hei_code_num+1) WHERE public_private='".$prov."'";
		$rs = mysqli_query($SQL);
		return $hei_code;
	}

	function createInstitution_reference ($org) {
		$newNum = 1;
		$org_type = $org;
		// first find the last program
		$SQL = "SELECT max(orgNo) FROM Institution_referenceNo ".
					 "WHERE org_type = '$org' ";
		$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			$newNum = $row[0] + 1;
		}

		// insert a new program
		$SQL = "INSERT INTO Institution_referenceNo VALUES".
					 "(NULL, '$org_type', '$newNum')";
		$rs = mysqli_query($SQL);

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
		$rs = mysqli_query($SQL);
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
		$rs = mysqli_query($SQL);
	}

	/**
	 * show the transport table for site visit process
	 * @author Louwtjie
	 * 30-03-2004
	 * @return string $content All HTML used to display the transport table
	*/
	function showTransport($siteVisit_id, $table, $keyFld, $fieldsArr, $sizeOfFld=10, $report=0, $tableHeading="", $numEntires=0, $evalsArr){
		$SQL = "SELECT * FROM `".$table."` WHERE siteVisit_ref=".$siteVisit_id;
		$rs = mysqli_query($SQL);
		$numAlready = mysqli_num_rows($rs);
		if ( !($numAlready == $numEntires) ) {
			for ($i=0; $i<$numEntires; $i++) {
				$this->saveProgram("siteVisit_transport", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "application_ref, site_ref", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "application_ref")."','".$this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_ref"));
			}
		}
		$rs = mysqli_query($SQL);
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
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "Persnr_ref") > "") {
					$new = explode("|", $evalsArr[$cc]);
					$value1 = $new[0];
					$value2 = $new[1];
					$content .=  "<td><input size='".$sizeOfFld."' type='HIDDEN' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value1."'>";
					$content .=  $value2."</td>";
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td><input size='".$sizeOfFld."' type='TEXT' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
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
		$rs = mysqli_query($SQL);
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
		$rs = mysqli_query($SQL);

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
		$rs = mysqli_query($SQL);
			$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row for scheduling the site visit. <i>Note that you can have more than one row in which to plan activities of the site visit.</i>";

		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if (($value == '1970-01-01') || ($value == '00:00:00')) $value = '';
				if (stristr($key, "time") > "") {
					$content .=  '<td valign="top"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td valign="top"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "text") > "") {
					$content .=  "<td valign='top'><textarea size='".$sizeOfFld."' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."'>".$value."</textarea></td>";
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td valign='top'><input size='".$sizeOfFld."' type='TEXT' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
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
		$content .= '<tr><td><input type="HIDDEN" name="GRID_save_'.$row[$keyFld].'" value="1"></td></tr>';
		$content .=  "</table>";
		if (!$report) {
			$content .=  "<input type='hidden' name='cmd' value=''>";
			$content .=  "<input type='hidden' name='id' value=''>";
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
		$RS = mysqli_query($SQL);
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
		$rs = mysqli_query($SQL);
		if (!mysqli_num_rows($rs)) {
			$message = "To start filling in the table, click on the 'Insert' link on the right of the table";
		}

		while ($row = mysqli_fetch_assoc($rs)){
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {
				if (stristr($key, "time") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "date") > "") {
					$content .=  '<td><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$keyFld].'$'.$keyFld.'$'.$key.'$'.$table.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else {
					if (in_array($key, $array_keys)) {
						$content .=  "<td><input size='".$sizeOfFld."' type='TEXT' name='GRID_".$row[$keyFld]."$".$keyFld."$".$key."$".$table."' value='".$value."'></td>";
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
	function getSelectedEvaluatorsForApplication ($app_id, $where="") {
		$eval_arr = array();
		$where_app = "";

		if ($where > ""){
			$where_app = " AND " . implode(" AND ", $where);
		}

		$SQL = <<<evalSQL
			SELECT Persnr, CONCAT(Surname,', ',Names,' ') as Name, Surname, Names, E_mail, do_summary,
			lop_isSent, lop_isSent_date, Work_Number, evalReport_date_sent, Title_ref
			FROM Eval_Auditors, evalReport
			WHERE Persnr_ref=Persnr
			AND application_ref=$app_id
			$where_app
			ORDER BY Surname, Names
evalSQL;
		$rs = mysqli_query($SQL);
		while ($row = mysqli_fetch_array($rs)) {
			$eval_arr[$row["Persnr"]] = $row;
		}
		return $eval_arr;
	}


	/*
	2004-04-01
	Louwtjie
	Return a array of the evaluators that worked on a specific application.
	*/
	function getEvaluatorsPerApplication ($app_id) {
		$eval_arr = array();
		$SQL = "SELECT Names, Surname FROM Eval_Auditors, evalReport WHERE do_sitevisit_checkbox=1 AND active=1 AND eval_site_visit_status_confirm=1 AND Persnr_ref=Persnr AND application_ref=".$app_id." ORDER BY Surname, Names";
		$rs = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
		while ($row = mysqli_fetch_object($RS)) {
			$all_docs[$row->lkp_application_docs_fieldName] = $row->lkp_application_docs_desc."|".$row->private_public;
		}

		$rs = mysqli_list_fields($this->DBname, "Institutions_application", $this->conn);
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
								$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
			$RS = mysqli_query($SQL);
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
					$RS = mysqli_query($SQL);
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
				$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
				$valueRS = mysqli_query($SQL);
				if ($valueRS && (mysqli_num_rows($valueRS) > 0) && ($rr = mysqli_fetch_object($valueRS))) {
					$SQL = "UPDATE `siteVisit_report` SET site_ref='".$site_ref."', application_ref='".$app_ref."', commend='".$value."' WHERE siteVisit_report_areas_ref='".$ref[1]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
					$RS = mysqli_query($SQL);
					$id = $rr->siteVisit_report_id;
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, commend, siteVisit_report_areas_ref) VALUES ('".$site_ref."', '".$app_ref."', '".$value."', '".$ref[1]."')";
					$RS = mysqli_query($SQL);
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
				$RSarea = mysqli_query($SQLarea);
				if ($RSarea && (mysqli_num_rows($RSarea) > 0)) {
					$SQL = "UPDATE `siteVisit_report` SET documentation='".$docArea[1]."'  WHERE siteVisit_report_areas_ref='".$docArea[0]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, siteVisit_report_areas_ref, documentation) VALUES ('".$site_ref."', '".$app_ref."', '".$docArea[0]."', '".$docArea[1]."')";
				}
				$RS = mysqli_query($SQL);
			}
		}

		if (count($comments) > 0) {
			foreach ($comments AS $value) {
				$qRef = explode("|", $value[1]);
				$com = explode("|", $value[0]);
				$SQLarea = "SELECT * FROM `siteVisit_report` WHERE siteVisit_report_areas_ref='".$qRef[0]."' AND application_ref='".$app_ref."' AND site_ref='".$site_ref."'";
				$RSarea = mysqli_query($SQLarea);
				if ($RSarea && (mysqli_num_rows($RSarea) > 0)) {
					$SQL = "UPDATE `siteVisit_report` SET comments='".$com[1]."' WHERE application_ref='".$app_ref."' AND site_ref='".$site_ref."' AND siteVisit_report_areas_ref='".$qRef[0]."'";
				}else {
					$SQL = "INSERT INTO `siteVisit_report` (site_ref, application_ref, siteVisit_report_areas_ref, comments) VALUES ('".$site_ref."', '".$app_ref."', '".$qRef[0]."', '".$com[1]."')";
				}
				$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
			$valueRS = mysqli_query($SQL);
		}
		if (($valueRS > "") && (mysqli_num_rows($valueRS) < 14)) {
			$arr = array();
			while ($valueRS && ($tmpRow=mysqli_fetch_array($valueRS))) {
				$arr[$tmpRow["siteVisit_report_areas_ref"]] = $tmpRow["commend"]."|".$tmpRow["documentation"]."|".$tmpRow["comments"];
			}
			$SQL = "DELETE FROM `siteVisit_report` WHERE application_ref='".$application_ref."' AND site_ref='".$site_ref."'";
			$RS = mysqli_query($SQL);
			for ($j=1; $j<14; $j++) {
				$SQL = "INSERT INTO `siteVisit_report` VALUES (NULL, '".$application_ref."', '".$site_ref."', '".$j."', ";
				if (isset($arr[$j]) && ($arr[$j] > "")) {
					$tmp = explode("|", $arr[$j]);
					$SQL .= "'".$tmp[0]."', '".$tmp[1]."', '".$tmp[2]."')";
				}else {
					$SQL .= "'0', '0', '')";
				}
 				$RS = mysqli_query($SQL);
			}
			$SQL = "SELECT * FROM siteVisit_report WHERE application_ref='".$application_ref."' AND site_ref='".$site_ref."' ORDER by siteVisit_report_id";
			$valueRS = mysqli_query($SQL);
		}
		$html = "";
		$SQL = "SELECT * FROM siteVisit_report_headings";
		$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
				$html .= '<td valign="top"><input type="radio" name="commend|'.$row->siteVisit_report_areas_id.'" value="1" '.$dis.' '.$checkCommend1.'></td>';
				$html .= '<td valign="top"><input type="radio" name="commend|'.$row->siteVisit_report_areas_id.'" value="2" '.$dis.' '.$checkCommend2.'></td>';
				$html .= '<td valign="top"><input type="radio" name="commend|'.$row->siteVisit_report_areas_id.'" value="3" '.$dis.' '.$checkCommend3.'></td>';
				$html .= '<td valign="top"><input type="radio" name="commend|'.$row->siteVisit_report_areas_id.'" value="4" '.$dis.' '.$checkCommend4.'></td>';
				$html .= '<td valign="top"><input type="radio" name="documentation|'.$row->siteVisit_report_areas_id.'" value="1" '.$dis.' '.$checkNo.'>No';
				$html .= '&nbsp;<input type="radio" name="documentation|'.$row->siteVisit_report_areas_id.'" value="2" '.$dis.' '.$checkYes.'>Yes</td>';
//the following 5 rows are for the extra comments column that has been replaced by the last comments row.
//				if (($row->main_heading=="INFRASTRUCTURE") && ($count==0)) $html .= '<td valign="top" rowspan="6"><textarea rows="12" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentINFRASTRUCTURE.'</textarea></td>';
//				if (($row->main_heading=="STAFF") && ($count==6)) $html .= '<td valign="top" rowspan="2"><textarea rows="7" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentSTAFF.'</textarea></td>';
//				if (($row->main_heading=="STUDENTS") && ($count==8)) $html .= '<td valign="top" rowspan="3"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentSTUDENTS.'</textarea></td>';
//				if (($row->main_heading=="LEARNING MATERIALS") && ($count==11)) $html .= '<td valign="top" rowspan="1"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentLEARNINGMATERIAL.'</textarea></td>';
//				if (($row->main_heading=="OTHER") && ($count==12)) $html .= '<td valign="top" rowspan="1"><textarea rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentOTHER.'</textarea></td>';
				$html .= '</tr>';
			}

			if ($count == 13) {
				$html .= '<tr><td colspan="6" valign="top" rowspan="1"><textarea style="width:100%" rows="10" name="comments|'.$row->siteVisit_report_areas_id.'" '.$dis.'>'.$commentCOMMENTS.'</textarea></td></tr>';
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
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$applicationID, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$applicationID, "institution_id")."&DBINF_Institutions_application___application_id=".$applicationID;
		echo '<table width="75%" border=0  cellpadding="2" cellspacing="2">';
		echo '<tr>';
		echo '	<td width="40%">&nbsp;</td>';
		echo '	<td>&nbsp;</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>INSTITUTION NAME:</b> </td>';
		echo '	<td valign="top" class="oncolour"><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$this->getValueFromTable("Institutions_application", "application_id",$applicationID, "institution_id").'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id",$applicationID, "institution_id"), "HEI_name").' - (view profile)</a></td>';
//		echo '	<td valign="top" class="oncolour">'.$this->table_field_info($this->active_processes_id, "InstitutionName").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>PROVIDER TYPE:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->table_field_info($this->active_processes_id, "InstitutionType").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>PROGRAMME NAME:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->table_field_info($this->active_processes_id, "ProgrammeName").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>NQF Level:</b></td>';
		echo '	<td valign="top" class="oncolour">'.$this->getValueFromTable("NQF_level", "NQF_id", $this->getValueFromTable("Institutions_application", "application_id", $applicationID,"NQF_ref"), "NQF_level").'</td>';
		echo '</tr><tr>';
		echo '	<td valign="top" width="30%" align="right"><b>HEQC - Reference Number:</b></td>';
		echo '	<td valign="top" class="oncolour"><a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$applicationID.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->table_field_info($this->active_processes_id, "HEQC_ref").' - (view application form)</a></td>';
		echo '</tr></table>';
	}

	/**
	* Louwtjie du Toit
	* Date: 2004-0707
	* Function to display the general program info
	*/
	function showGeneralProgramInfo ($app_id) {
		$SQL = "SELECT * FROM `Institutions_application` WHERE application_id=".$app_id;
		$RS = mysqli_query($SQL);
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
			echo '<td align="right"><b>Credits number:</b> </td>';
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
			$rs2 = mysqli_query($sql2);
			if ($rs2 && ($row2 = mysqli_fetch_array($rs2))) $site = $row2["location"];
			echo '<td class="oncoloursoft">'.$site.'</td>';
			echo '</tr>';
		}
	}

/*
This function makes the summary of expired/due processes
*/
function makeSumProcTable(){
	$SQL2 = "SELECT * FROM active_processes WHERE status=0 AND (due_date <> \"1970-01-01\") AND (due_date < NOW()) AND (expiry_date <> \"1970-01-01\") AND (expiry_date > NOW())";
	$rs2 = mysqli_query($SQL2);
	$SQL3 = "SELECT * FROM active_processes WHERE status=0 AND (expiry_date <> \"1970-01-01\") AND (expiry_date < NOW())";
	$rs3 = mysqli_query($SQL3);
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
	$SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id and user_id = ".$this->currentUserID." AND status = 0 AND active_date <= now() ORDER BY last_updated DESC";
	$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_array ($rs)) {
			$desc = $this->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
			$dueStyle = "";
			if ( ($row["due_date"]!="1970-01-01") && ($row["due_date"]<=date("Y-m-d")) ) {
				$dueStyle = "CLASS=due";
			}
			if ( ($row["expiry_date"]!="1970-01-01") && ($row["expiry_date"]<=date("Y-m-d")) ) {
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

	/**
	 * deletes a row from a grid
	 * @author Louwtjie
	 * 2004-10-27
	 * @param string $table The MySQL table name which holds the program information.
	 * @param string $keyFld The key field of the MySQL table.
	 * @param mixed $keyFldValue The value of the key field.
	*/
	function gridDeleteRow($table, $keyFld, $keyFldValue){
		$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld."=".$keyFldValue;
		$rs = mysqli_query($SQL);
	}

	/*
	Louwtjie
	2004-10-27
	inserts a new program row in a grid
	*/
	function gridInsertRow($table, $keyFld, $keyFldValue, $keyFld2="", $keyFldValue2=0){
		$fld2_key = ($keyFld2>"")?(", ".$keyFld2):("");
		$fld2_value = ($keyFldValue2>0)?(", '".$keyFldValue2."'"):("");

		$SQL ="INSERT INTO `".$table."` (".$keyFld.$fld2_key.")";
		$SQL .= " VALUES ('".$keyFldValue."'".$fld2_value.")";
		$rs = mysqli_query($SQL);
	}

	function doPopulateGridFromTemplateTable ($parentTable, $parentTable_id, $childTable, $childKeyFld, $childRef, $fieldsArr, $lookup_template_table) {
		$RS = mysqli_query("SELECT * FROM `".$lookup_template_table."`");
		$num_rows = mysqli_num_rows($RS);
		while ($RS && ($template_table_row=mysqli_fetch_array($RS))) {
			$init_RS = mysqli_query("INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
			$last_id = mysqli_insert_id();
			foreach ($fieldsArr AS $initK=>$initV) {
				mysqli_query("UPDATE `".$childTable."`  SET ".$initK."='".$template_table_row[$initK]."' WHERE ".$childRef."=".$parentTable_id." AND ".$childKeyFld."='".$last_id."'");
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
	function createHTMLGridSQL ($table, $keyFLD, $unique_flds_array) {

/*
	echo $table."<br><br>";
	echo $keyFLD."<br><br>";
	print_r($unique_flds_array);
*/

		$main_SQL = "SELECT * FROM ".$table;
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				array_push ($andArr, $unique_flds_array[$i][0]."='".$unique_flds_array[$i][1]."'");
			}
			$main_SQL .= " WHERE ".implode (" AND ", $andArr);
		}
		$main_SQL .= " ORDER BY ".$keyFLD;
		$RS = mysqli_query ($main_SQL);
		return $RS;
	}

	/*
	 * Louwtjie: function to create the rows in database before displaying them on screen.
	*/
	function createHTMLGridInsertWithLookup ($table, $lkp_row_table, $lkp_row_id, $lkp_row_ref, $unique_flds_array) {
		$lkp_array = array();
		if ($lkp_row_ref > "") {
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

			$lkp_SQL .= ") WHERE ".$lkp_row_ref." IS NULL";

			$lkp_RS = mysqli_query($lkp_SQL);
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

			mysqli_query($SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL", $SQL."  --> ".mysqli_error(), $errorMail);
			$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
		}
	}

	/*
	 * Louwtjie: function to create the rows in database before displaying them on screen.
	*/
	function createHTMLGridInsertWithoutLookup ($table, $unique_flds_array) {
		$SQL = "INSERT INTO `".$table."` (";
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				array_push ($andArr, $unique_flds_array[$i][0]);
			}
			$SQL .= implode (" , ", $andArr);
		}

		$SQL .= ") VALUES (";
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				array_push ($andArr, "'".$unique_flds_array[$i][1]."'");
			}
			$SQL .= implode (" , ", $andArr);
		}
		$SQL .= ")";

		$errorMail = false;
		mysqli_query($SQL) or $errorMail = true;
		$this->writeLogInfo(10, "SQL", $SQL."  --> ".mysqli_error(), $errorMail);
		$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
	}

	/*
	 * Louwtjie: function to create the fields for the grid functions.
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

		//the following needs to be checked if it is a select or radio type field.

		$fld_lkp_desc = (isset($html_arr["description_fld"]))?($html_arr["description_fld"]):("");
		$fld_lkp_key = (isset($html_arr["fld_key"]))?($html_arr["fld_key"]):("");
		$fld_lkp_table = (isset($html_arr["lkp_table"]))?($html_arr["lkp_table"]):("");
		$fld_lkp_condition = (isset($html_arr["lkp_condition"]))?($html_arr["lkp_condition"]):("");
		$fld_lkp_order_by = (isset($html_arr["order_by"]))?($html_arr["order_by"]):("");

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
		$this->formFields[$fieldName]->fieldCols = $cols;
		$this->formFields[$fieldName]->fieldRows = $rows;

		//we don't have solid rules for checkbox values so if it has a value of 1 make it "checked"
		if (($fieldType == "checkbox") && ($row[$html_arr["name"]] == 1)) {
			$this->formFields[$fieldName]->fieldOptions = "checked";
		}

		//create the select or radio type field's options
		if (($fieldType == "select") || ($fieldType == "radio")) {
			$fld_lkp_SQL = "SELECT ".$fld_lkp_desc.", ".$fld_lkp_key." FROM ".$fld_lkp_table." WHERE ".$fld_lkp_condition." ORDER BY ".$fld_lkp_order_by."";
			$fld_lkp_RS = mysqli_query($fld_lkp_SQL);
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
	function gridShow ($table, $key_fld, $unique_flds, $fields_arr, $html_table_headings_arr, $lkp_row_table="", $lkp_row_id="", $lkp_row_desc="", $lkp_row_ref="", $lkp_row_desc_col=1, $cols=40, $rows=5, $createFileUpload=false, $fileUpload_name="") {

		$unique_flds_array = $this->doubleExplode ($unique_flds);

		//First of all, we need to select the rows out of the database to see whether it exists or not.
		//That's why we need the unique fields to uniquely identify the rows we're working with.
		$RS = $this->createHTMLGridSQL ($table, $lkp_row_ref, $unique_flds_array);

		//actual number of rows we have in DB
		$actual_rows = mysqli_num_rows($RS);

		$lookup_sql = "SELECT ".$lkp_row_id." FROM ".$lkp_row_table;
		$lookup_rows = mysqli_num_rows(mysqli_query($lookup_sql));

		//Now, we check to see if we've got any rows to work with. If there are rows, it means that we've already created the entries
		//in the database and now we need some values for them as well as to print them in our table.

		//first see if we're dealing with a fixed grid else adjust the number_rows accordingly.
		if ( (!($actual_rows > 0)) || ($actual_rows != $lookup_rows) ) {
			$this->createHTMLGridInsertWithLookup ($table, $lkp_row_table, $lkp_row_id, $lkp_row_ref, $unique_flds_array);
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
						$lkp_rs = mysqli_query($lkp_SQL);
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
	function gridShowRowByRow ($table, $keyFLD, $unique_flds, $fieldArr, $headingArr, $cols=40, $rows=5, $add="", $del="",$dfltRow=1) {
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
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);
		while ($RS && ($row=mysqli_fetch_array($RS, MYSQL_ASSOC))) {
			$rowID = $row[$keyFLD];
			echo "<tr>\n";
			for ($i=0; $i < count($fieldArr); $i++) {
				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $fieldArr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);

				echo "<td valign='top'>\n";
				//print the field after all properties have been set.
				$this->showField($fieldName);

				//if this is the last field, print the hidden save field aswell
				if ($i == (count($fieldArr)-1)) {
					echo "<input type='HIDDEN'  name='GRID_save_".$table."_".$rowID."' value='1'>\n";
				}

				echo "</td>\n";
				//temporary solution for checkboxes to become unchecked.
				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->setValueInTable ($table, $key_fld, $row[$keyFLD], $html_arr["name"], 0);
				}
			}

			// display link to delete a record if requested
			if ($del==true){
				echo "<td>\n";
				echo "<a href='javascript:changeCMD(\"del|".$table."|".$keyFLD."|".$row[$keyFLD]."\");moveto(\"stay\")'>Del</a>";
				echo "</td>\n";
			}
			echo "</tr>\n";

		}

		// display link to add a new record if requested
		if ($add==true){
			$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row in which to supply the relevant information. <i>Note that you can add multiple rows</i>.";
			echo "<tr>";
			echo "<td valign='top' colspan='".(count($headingArr))."'>".$message."</td><td align='left'><a href='javascript:changeCMD(\"new|".$table."|".$unique_flds_array[0][0]."|".$unique_flds_array[0][1]."\");moveto(\"stay\")'>Add</a></td>";
			echo "</tr>";
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
		while ($RS && ($row=mysqli_fetch_array($RS, MYSQL_ASSOC))) {
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

				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $value, 70, 10, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);
				$this->showField ($fieldName);

				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->setValueInTable ($table, $key_fld, $row[$keyFLD], $html_arr["name"], 0);
				}

				echo "			</td>\n";
				echo "		</tr>\n";
				$count++;
			}
			echo '		<tr><td colspan=2><input type="HIDDEN" name="GRID_save_'.$row[$keyFLD].'" value="1"></td></tr>';
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
			$rows_rs = mysqli_num_rows(mysqli_query("SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id));
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
		$rs = mysqli_query($SQL);
		$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row in which to supply the relevant information. <i>Note that you can add multiple rows</i>.";
		$field_size = $sizeOfFld;
		while ($row = mysqli_fetch_assoc($rs)) {
			$content .=  "<tr>";
			foreach ($row AS $key=>$value) {

				$name_of_field = 'GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable;
				$sizeOfFld = $field_size;

				if (($value == '1970-01-01') || ($value == '00:00:00')) $value = '';
				if (stristr($key, "timeFLD") > "") {
					$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
					$content .=  '<td valign="top" align="right"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$value.'"><a href="javascript:showTime(\'GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\', \''.$value.'\');">';
					if (!$report) $content .=  '<img src="images/icon_time.gif" border=0></a></td>';
				}else if (stristr($key, "dateFLD") > "") {
					$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
					$content .=  '<td valign="top" align="right"><input readonly size="'.$sizeOfFld.'" type="TEXT" name="GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'" value="'.$value.'"><a href="javascript:show_calendar(\'defaultFrm.GRID_'.$row[$childKeyFld].'$'.$childKeyFld.'$'.$key.'$'.$childTable.'\');">';
					if (!$report) $content .=  '<img src="images/icon_calendar.gif" border=0></a></td>';
				}else if (stristr($key, "textFLD") > "") {
					$content .=  "<td valign='top' align='right'><textarea size='".$sizeOfFld."' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'>".$value."</textarea></td>";
				}else if (stristr($key, "selectFLD") > "") {
					$content .=  "<td valign='top' align='right'>";
					$content .=  "<select  name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'>";
					$content .=  "<option value='0'>- Select -</option>";
					$select_SQL = "SELECT ".$select_array[$key]["description_fld"].", ".$select_array[$key]["fld_key"]." FROM ".$select_array[$key]["lkp_table"]." WHERE ".$select_array[$key]["lkp_condition"]." ORDER BY ".$select_array[$key]["order_by"]."";
					$select_RS = mysqli_query($select_SQL);
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
					$select_RS = mysqli_query($select_SQL);
					while ($select_RS && ($select_row=mysqli_fetch_array($select_RS))) {
						$selected = "";
						if ($value == $select_row[$select_array[$key]["fld_key"]]) {
							$selected = " checked";
						}
						$content .=  "<input type='radio' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$select_row[$select_array[$key]["fld_key"]]."' ".$selected.">";
						$content .=  $select_row[$select_array[$key]["description_fld"]]."&nbsp;";
					}
					$content .=  "</td>";
				}else if (stristr($key, "checkboxFLD") > "") {
					$content .=  "<td valign=top>";
					$content .=  "<input type='checkbox' value='1' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."'";
					if ($row[$key] == 1) $content .=  "checked";
					$content .=  ">";
					$SQL_del = "UPDATE ".$childTable." SET ".$key."=0 WHERE ".$childKeyFld."=".$row[$childKeyFld];
					$RS = mysqli_query($SQL_del);
				}else {
					if (in_array($key, $array_keys)) {
						$sizeOfFld = (is_array($fieldsArr[$key]))?($fieldsArr[$key][1]):($sizeOfFld);
						$content .=  "<td valign='top' align='right'><input size='".$sizeOfFld."' type='TEXT' name='GRID_".$row[$childKeyFld]."$".$childKeyFld."$".$key."$".$childTable."' value='".$value."'></td>";
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
		if (($add_initial_DB_row) && !(mysqli_num_rows(mysqli_query("SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id)))) {
			$init_RS = mysqli_query("INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
		}

		$SQL = "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id." ORDER BY ".$childKeyFld;

		$rs = mysqli_query($SQL);
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
				if (($value == '1970-01-01') || ($value == '00:00:00')) $value = '';
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
						$select_RS = mysqli_query($select_SQL);
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
						$select_RS = mysqli_query($select_SQL);
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
						$RS = mysqli_query($SQL_del);
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

		$RS = mysqli_query ($main_SQL);

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
				$rs = mysqli_query($SQL);
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
		$RS = mysqli_query($main_SQL);
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
		$RS = mysqli_query($SQL);
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
		$SQL = "SELECT site_visit, application_ref, final_date_visit FROM `siteVisit` WHERE final_date_visit > '1970-01-01' AND institution_ref=".$this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id")." AND site_ref=".$site_ref;
		$RS = mysqli_query($SQL);
		if (mysqli_num_rows($RS) > 0) {
			echo '<table border="1" width="50%"><tr><td class="oncolour"><b>PROGRAMME</b></td><td class="oncolour"><b>DATE</b></td></tr>';
			while ($row = mysqli_fetch_object($RS)) {
				if (($row->site_visit == 'Yes') && ($row->final_date_visit != "1970-01-01")) {
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
		$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
		$text = "\n\n";
		while ($RS && ($row=mysqli_fetch_array($RS))) {
				$text .= '<b>'.$this->getValueFromTable("lkp_title", "lkp_title_id", $row["ac_mem_title_ref"], "lkp_title_desc").' '.$row["ac_mem_name"].' '.$row["ac_mem_surname"].'</b>'."\n";
				$text .= ($row["ac_mem_postal"] > "")?($row["ac_mem_postal"]):($row["ac_mem_physical"]);
				$text .= "\n\n";
		}
		return $text;
	}

	function showSiteVisitConfirmationPayment() {
		$institution = $this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
		$SQL = "SELECT application_id, HEI_name, program_name, CHE_reference_code, site_ref	FROM Institutions_application, HEInstitution, siteVisit WHERE site_visit_payed=0 AND site_visit='Yes' AND application_ref=application_id AND HEI_id=institution_id AND AC_Meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID." AND institution_id=".$institution." ORDER BY HEI_name,program_name";
		$rs = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
		$i = 1;
		echo '<table>';
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$checked = "";
			$check_SQL = "SELECT * FROM `lnk_ACMembers_attend_meeting` WHERE ac_meeting_ref=".$ac_id." AND ac_member_ref=".$row["ac_member_ref"];
			$check_RS = mysqli_query($check_SQL);
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
		$RS = mysqli_query($SQL);
		echo '<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>';
		echo '<td align="center"><b>AC Meeting Date</b></td>';
		echo '<td align="center"><b>Total confirmed</b></td>';
		echo '<td align="center"><b>Total attended</b></td>';
		echo '<td align="center"><b>% Attendance</b></td>';
		echo '</tr>';
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$att_SQL = "SELECT * FROM `lnk_ACMembers_attend_meeting` WHERE ac_meeting_ref=".$row["ac_meeting_ref"];
			$att_RS = mysqli_query($att_SQL);
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
		$RS = mysqli_query($SQL);
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
//echo $SQL;
		$RS = mysqli_query($SQL);
		echo '<table class="lineunder" width="85%" align="center" cellpadding="2" cellspacing="2">';
		echo '<tr><td colspan="'.count($col_head).'">&nbsp;</td></tr>';
		echo '<tr><td colspan="'.count($col_head).'"><b>'.$table_head.'</b></td></tr>';
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
				$total += $row[$val];
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
			echo '<input type="hidden" name="'.$fake.'" value="'.$this->getValueFromTable($table, $keyFLD, $keyVal, $field).'">';
		}
	}

	function showInstProfileUploadedDocs ($id=0) {
		$tables = mysqli_list_tables($this->DBname);
		$tables_arr = array();
		while ($tables && ($row_tables=mysqli_fetch_array($tables, MYSQL_NUM))) {
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
			$RS = mysqli_query($SQL);
			while ($RS && ($row=mysqli_fetch_array($RS, MYSQL_NUM))) {
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
						echo '<li>';
						echo '<a href="'.$doc->url().'" target="_blank">'.$doc->getFilename().'</a>';
						echo '</li>';
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
		$RS = mysqli_query($SQL);

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
		$rs = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);
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
		$RS = mysqli_query($SQL);

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

		$this->createSubmittedApplicationsTempTable();

$SQL = <<<SQLselect
		SELECT
			HEInstitution.HEI_name,
			Institutions_application.application_id,
			Institutions_application.program_name as Program,
			IF(Institutions_application.CHE_reference_code='', "-- Not Submitted --", Institutions_application.CHE_reference_code) AS CHE_reference_code,
			CONCAT(name, ': ', users.email) as Process_User,
			processes.processes_desc as Process,
			tmp_ap.last_updated,
			tmp_ap.user_ref,
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
		$SQL = ($is_CHE)?($SQL):($SQL." AND processes_ref IN (5,46)");//$SQL." AND (" . implode(" AND ", $sqlArr).")"
		$SQL .= "GROUP BY HEInstitution.HEI_id,
			HEInstitution.HEI_name,Program,
			CHE_reference_code,Process_User,Process,
			tmp_ap.last_updated,
			tmp_ap.status
		ORDER by HEInstitution.HEI_Name, CHE_reference_code,
			Institutions_application.program_name, tmp_ap.last_updated";

		/*
			$SQL = "SELECT Persnr, Names, Surname, Work_Number, E_mail FROM ".implode (", ", $tableArray)." WHERE 1 ";
			$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
			$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL):($SQL);
			$SQL = (count($searchArr) > 0)?($SQL." AND (".implode(" OR ", $searchArr).")"):($SQL);
			$SQL .= "ORDER BY number_evals, Surname,Names";
		*/

			if ($rs = mysqli_query($SQL)) {

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
					$iframeText .= "<tr class='onblueb'><td colspan=\"7\"><b>Application Progress Report for </b>". implode('',$searchCrit) ."</td><td colspan=\"4\" align=\"right\"><b>Total Rows: ".mysqli_num_rows($rs)."</b></td></tr>";
					$iframeText .= "<tr class='onblueb'><td colspan=\"11\">&nbsp;</td>";
					$iframeText .= "<tr class='onblueb'><td><b>Institution</b></td><td><b>Program</b></td><td><b>CHE Ref No</b></td><td><b>User</b></td><td><b>Process</b></td><td><b>Date</b></td><td><b>Status</b></td><td><b>Amount</b></td><td><b>Paid</b></td><td><b>Admin Action</b></td></tr>\n";
				    while ($row = mysqli_fetch_array($rs)) {
						$admin_action = '&nbsp;';
						if ($row["application_id"]!= $prevProgram){
							$n+=1;
						}
						$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#c0c0c0");
						$iframeText .= "<tr bgcolor='" . $bgColor . "'>\n";

						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_id"];

						$iframeText .= "<td valign='top'>".'<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id").'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["HEI_name"]."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Program"] ."</td>\n";
						$iframeText .= "<td valign='top' nowrap>". '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"].'</a>' . "</td>\n";
						$iframeText .= "<td valign='top'>". $row["Process_User"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Process"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["last_updated"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["lkp_process_status_desc"] ."</td>\n";
		//				$iframeText .= "<td valign='top'>". $row["Nr_Invoice"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Invoice"] ."</td>\n";
						$iframeText .= "<td valign='top'>". $row["Paid"]."</td>\n";

						$admin_action = ((($this->currentUserID != $row['user_ref']) && ($row["Process"] == 'Accreditation Application Form') && ($this->currentUserID == $this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "user_ref")) && ($row["lkp_process_status_desc"] != 'complete'))?('<a href="javascript:adminTakeApp('.$row["active_processes_id"].');">Take back</a>'):('&nbsp;'));
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

	function createSubmittedApplicationsTempTable(){

	$sql = <<<SQLselect
	CREATE TEMPORARY TABLE tmp_ap (
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
   KEY  (`application_id`)
	)
SQLselect;


	$rs = mysqli_query($sql) or die(mysqli_error());

	$sql = <<<SQLinsert
		INSERT INTO tmp_ap
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

	$rs = mysqli_query($sql) or die(mysqli_error());

	$sql = <<<sSQL
		CREATE TEMPORARY TABLE tmp_submitted
		SELECT application_id, min(last_updated) as last_updated
		FROM tmp_ap
		WHERE processes_ref not in (5,46,66,100)
		GROUP BY application_id
		ORDER BY application_id
sSQL;
	$rs = mysqli_query($sql) or die(mysqli_error());

	}

	function getLocationOfActiveProcessesForApplication($app_id){
		$location = "";

		// Get location of active process / es
		$sSQL = <<<sSQL
			SELECT CONCAT(processes_desc,'-',u.name) as plocation
			FROM tmp_ap
			LEFT JOIN processes as p ON p.processes_id = processes_ref
			LEFT JOIN users AS u ON u.user_id = user_ref
			WHERE status = 0
			AND application_id = $app_id
sSQL;
			$rsSQL = mysqli_query($sSQL);

			$arr_location = array();
			while($srow = mysqli_fetch_array($rsSQL)){
				array_push($arr_location,$srow['plocation']);
			}
			$location .= implode(",<br>",$arr_location);

			return $location;
	}

	function reportSubmittedApplications($searchText='', $dateFrom='0',$dateTo='0', $searchFor='0', $reportType="", $reportParam="", $institution='0'){

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


			$this->createSubmittedApplicationsTempTable();

			$whereArr = array("1");

			$aSql = <<<aSql
				SELECT i.HEI_name, a.*
				FROM Institutions_application AS a
				LEFT JOIN HEInstitution as i ON HEI_id = a.institution_id
aSql;

			$whereArr = array("submission_date > '1970-01-01'","(a.CHE_reference_code > '')","a.institution_id NOT IN (1, 2)");

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



			if ($searchFor != '0') {
				array_push($whereArr, $this->getValueFromTable("lkp_search_for", "lkp_search_for_id", $searchFor, "lkp_search_for_sql")." ");
			}

			if ($institution != '0') {
				array_push($whereArr," institution_id = '".$institution."' ");
			}


	/*		if ($sortByYear) {
				array_push($whereArr, 'submission_date <= "'.$dateTo.'"');
			}
	*/
	//function to sort report by year

			if ($priv_publ) {
				array_push($whereArr, 'priv_publ = "'.$priv_publ.'"');
			}

			if ($outcome_group) {
				array_push($whereArr, 'AC_desision = "'.$outcome_group.'"');
			}

			$aSql .= " WHERE ".implode(" AND ", $whereArr);
			$aSql .=  " ORDER BY submission_date, a.AC_Meeting_date";


			if ($aRs = mysqli_query($aSql))
			{
				$tot_rows = mysqli_numrows($aRs);
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
						<td><b>Programme Name</b></td>";
					echo ($reportType == "submitted") ?	"<td><b>Submitted to CHE</b></td>" : "";
					echo ($this->body == "reportSubmittedApplications") ? "<td><b>Location</b></td>" : "";
					echo "			<td width='8%'><b>AC Meeting</b></td>
									<td><b>Status</b></td>
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
					if ($row["AC_desision"] > 0){
						$status = $this->getValueFromTable("lkp_desicion", "lkp_id", $row["AC_desision"], "lkp_title");
					}
					$ref = $row["CHE_reference_code"];
					if ($this->body == "reportSubmittedApplications")
					{
						$location = $this->getLocationOfActiveProcessesForApplication($row["application_id"]);
						// needed for link
						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$row["application_id"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_id"];
						$ref = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"]."</a>";
					}

					$ac_meeting_date = ($row["AC_Meeting_date"] > "1970-01-01") ? $row["AC_Meeting_date"] : "&nbsp;";

					echo "<tr bgcolor='" . $bgColor . "'>
								<td>".$row["submission_date"]."</td>
								<td>".$row["HEI_name"]."</td>
								<td>".$ref."</td>
								<td>".$row["program_name"]."</td>";
					echo ($reportType == "submitted") ?	"<td>".$row["submission_date"]."</td>" : "";
					echo ($this->body == "reportSubmittedApplications") ? "<td>".$location."</td>" : "";
					echo "		<td>".$ac_meeting_date."</td>
								<td>".$status."</td>
							</tr>";
				}

				echo "</table>";
			}
	}

	function reportAuditTrail($CHE_code='', $institution_ref=''){
		if (($CHE_code=="") && ($institution_ref=="0")) {
			echo "<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>";
			echo "<tr class='onblue'><td class='onblueb' align='center'> - Please enter at least one search parameter. -</td></tr>";
			echo "</table>";
		}

		else {

			$whereArr = array("1");
			$app_id = ($CHE_code) ? $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "application_id") : "";
			$inst_id = ($institution_ref) ? $institution_ref : $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "institution_id");
//if no inst_id found, then invalid ref_no must have been entered
			if ($inst_id != "")
			{
				$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");

				$aSql = <<<aSql
							SELECT DISTINCT application_ref,
							institution_ref,
							user_ref,
							process_desc,
							audit_subject,
							if (audit_subject='EMAIL' OR audit_subject='EMAIL NOT SENT' , audit_text,"") as audit_text,
							if (audit_subject='EMAIL' OR audit_subject='EMAIL NOT SENT' , workflow_audit_trail_id,"") as audit_id,
							DATE_FORMAT(date_updated,'%Y-%m-%d') as date_trim_updated
							FROM `workflow_audit_trail`
aSql;


				//$whereArr = array("(a.CHE_reference_code > '')","a.institution_id NOT IN (1, 2)");
				$whereArr = array("application_ref != 0");


				if ($CHE_code != ''){
					array_push($whereArr," application_ref = '".$app_id."' ");
				}

				if (($institution_ref != '') && ($institution_ref != '0')){
					array_push($whereArr," institution_ref = '".$institution_ref."' ");
				}

				$aSql .= " WHERE ".implode(" AND ", $whereArr);
				$aSql .=  " ORDER BY application_ref, date_updated";

				echo "<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>";
				echo "<tr class='onblue'><td class='onblueb'>Institution:</td><td colspan='5'>".$inst_name."</td></tr>";

				if ($aRs = mysqli_query($aSql))
				{
					$tot_rows = mysqli_numrows($aRs);
					$n=0;
					$bgColor = "#EAEFF5";
					$prev_app = "";
					$audit_subj = "";

					while ($row = mysqli_fetch_array($aRs))
					{
						$bgColor = "#EAEFF5";

						if ($row["application_ref"] != $prev_app)
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
									<td>".$this->getValueFromTable("Institutions_application", "application_id", $row["application_ref"], "CHE_Reference_code")."</td>
									<td>".$user_desc."</td>
									<td>".$row["process_desc"]."</td>
									<td>".$audit_subj."</td>
									<td>".$message."</td>
									<td>".$row["date_trim_updated"]."</td>
								</tr>";

						$prev_app = $row["application_ref"];
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

		$SQL = <<<SQL
				SELECT DISTINCT application_id, institution_id, program_name, CHE_reference_code, evalDocs_AC_Meeting, AC_desision, AC_conditions
				FROM `Institutions_application`, `evaluation_outside_system`, `evalReport`
				WHERE application_status=9 AND AC_Meeting_ref=0 AND application_id=evalReport.application_ref AND application_id=evaluation_outside_system.application_ref
SQL;
		$iframeText = "";
		$prevProgram = "";
		$n = 0;

		if ($RS = mysqli_query($SQL)) {
			$iframeText .= <<<iframeText
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

			$iframeText .= <<<iframeText
				</table>
iframeText;
		}

		echo $iframeText;
	}

	function showWelcomeAlertsForEditing () {
		$SQL = "SELECT * FROM `welcome_alerts` ORDER BY alert_date DESC ";
		$RS = mysqli_query($SQL);
echo $SQL;
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

	function checkInstitutionalProfileContactInfo () {
		$flag = TRUE;

		$fields = array();

		$SQL = "SELECT * FROM `institutional_profile_sites` WHERE `institution_ref`='".$this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id")."'";
		$RS = mysqli_query($SQL);
		$i = 0;
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$fields[$i]['site'] = $row['site_name'];
			$fields[$i]['address'] = $row['address'];
			$fields[$i]['postal_address'] = $row['postal_address'];
			$fields[$i]['contact_nr'] = $row['contact_nr'];
			$fields[$i]['contact_fax_nr'] = $row['contact_fax_nr'];
			$i++;
		}

		$sites = array();

		foreach ($fields AS $site) {
			foreach ($site AS $key=>$value) {
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

		$aSQL = "SELECT * FROM Institutions_application WHERE application_status IN (".$application_status.") AND AC_Meeting_ref=".$ac_meeting." ORDER BY institution_id";
//echo $aSQL;
		$rs = mysqli_query($aSQL);
		while ($row = mysqli_fetch_array($rs)) {
			$apps_arr[$row["application_id"]] = $row;
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

//displays the active AC meeting for managing AC meetings
function getACMeetingTableTop($ac_meeting_id) {
	$SQL = "SELECT * FROM AC_Meeting WHERE ac_id = ".$ac_meeting_id;
	$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		echo "<tr class='oncolourb'><td colspan='2' align='center'>Current AC Meeting:</td></tr>";
		while ($row = mysqli_fetch_array($rs)) {
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
	}
}

//displays basic application information
function getApplicationInfoTableTop($app_id, $path="") {
	$SQL = "SELECT * FROM Institutions_application WHERE application_id = ".$app_id;
	$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">';
		echo '<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='20%'>Reference number:</td>";
			echo "<td>".$applicationLink.$row["CHE_reference_code"]."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb'>Programme name:</td>";
			echo "<td>".$row["program_name"]."</td>";
			echo "</tr>";
		}
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
	$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">';
		echo '<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			$ref_code = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code");
			$ref_no = ($ref_code == "") ? "<i>A reference number has not yet been generated for this application</i>" : $ref_code;
			$programmeName = $applicationLink.$this->getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");
			$mode_lkp = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
			$mode_delivery = $this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $mode_lkp, "lkp_mode_of_delivery_desc");
			$site_name = $row['site_name']." - ".$row['location'];



			$html =<<< MYHTML
				<tr class='onblue'>
				<td class='oncolourb' width='30%' valign='top'>CHE Reference No.:</td>
				<td valign="top">$ref_no</a></td>
				</tr>
				<tr class='onblue'>
				<td class='oncolourb' width='30%' valign='top'>Programme name:</td>
				<td valign="top">$programmeName</a></td>
				</tr>
				<tr class='onblue'>
				<td class='oncolourb' width='30%' valign='top'>Mode of delivery:</td>
				<td valign="top">$mode_delivery</a></td>
				</tr>
				<tr class='onblue'>
				<td class='oncolourb' width='30%' valign='top'>Information being entered for<br>(site of delivery):</td>
				<td valign="top">$site_name</td>
				</tr>
MYHTML;
			echo $html;
		}
		echo '</table>';
		echo '<br>';
	}
}

// displays basic application information, viewable by institution.
// Includes CHE reference no., programme name, mode of delivery and sites of delivery for relevant programme
function getApplicationInfoTableTopForHEI_sites($app_id, $path="") {
	$SQL = "SELECT * FROM Institutions_application WHERE application_id = ".$app_id;
	$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_institutional_profile___institution_ref=".$this->getValueFromTable("Institutions_application", "application_id",$app_id, "institution_id")."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">';
		echo '<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">';
		while ($row = mysqli_fetch_array($rs)) {
			$ref_no = ($row["CHE_reference_code"] == "") ? "<i>A reference number has not yet been generated for this application</i>" : $row["CHE_reference_code"];

			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>CHE Reference No.:</td>";
			echo "<td>".$ref_no."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Programme name:</td>";
			echo "<td>".$applicationLink.$row["program_name"]."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Mode of delivery:</td>";
			echo "<td>".$this->getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $row["mode_delivery"], "lkp_mode_of_delivery_desc")."</a></td>";
			echo "</tr>";
			echo "<tr class='onblue'>";
			echo "<td class='oncolourb' width='30%' valign='top'>Site(s) of delivery:</td>";
			echo "<td>".$this->getSitesOfDeliveryPerApplication($app_id)."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '<br>';
	}
}

//checks whether AC meeting has passed yet
	function checkACmeetingPassed($ac_start_date) {
		$acMeetingPassed = $ac_start_date < date("Y-m-d");
		return $acMeetingPassed;
	}

//Rebecca:: displays the entire list of documentation for a specific meeting
function displayMeetingDocs($ac_meeting_id){

	$last_meeting_id = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "is_last_meeting");
	$last_meeting_str = ($last_meeting_id != 0) ? "<i>".$this->getValueFromTable("AC_Meeting", "ac_id", $last_meeting_id, "ac_meeting_venue")."<br>".$this->getValueFromTable("AC_Meeting", "ac_id", $last_meeting_id, "ac_start_date")."</i>" : "<i>No previous meetings have been captured.</i>";

	$SQL = "SELECT * FROM `AC_Meeting` WHERE ac_id = ".$ac_meeting_id;
	$rs = mysqli_query($SQL);

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
	$RS = mysqli_query($SQL);
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

	  $whereArray = array("1");
	 // if ($heiID != 0) { array_push ($whereArray, "HEI_id = ".$heiID); }

//main reportAccreditedInstitutions SQL
	  $SQL  = "SELECT * FROM HEInstitution";
	  //$SQL .= " WHERE ".implode(' AND ', $whereArray);
	  $SQL .= " WHERE HEI_id IN (1, 2, 20,54)";
	  $SQL .= " ORDER BY HEI_name";

	  $RS = mysqli_query($SQL);

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

	  $RS = mysqli_query($SQL);

	  switch ($type) {
	  	case "html" :	$doc = new octoDocGen ("accreditedInstitutions", "hei_id=".$heiID);
						$doc->url ("Download report as document");
						  while ($row = mysqli_fetch_array($RS)) {
							$mainTable = <<< TXT
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
						    $s_RS = mysqli_query($s_SQL);
							HEQConline::getSitesOfDeliveryPerInstitution ($s_RS, $type);
							$mainTable = <<< TXT
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
							$p_RS = mysqli_query($p_SQL);
							HEQConline::getProgrammeDetailsPerInstitution ($p_RS, $type);
							$mainTable = <<< TXT
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
								$s_RS = mysqli_query($s_SQL);
								echo HEQConline::getInstitutionDetails($row, $type);
								echo "</td></tr><tr><td><b>Additional sites of delivery:</b></td></tr><tr><td>";
								HEQConline::getSitesOfDeliveryPerInstitution ($s_RS, $type);
							$mainTable = <<< TXT
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
							$p_RS = mysqli_query($p_SQL);
							HEQConline::getProgrammeDetailsPerInstitution ($p_RS, $type);
							$mainTable = <<< TXT
								</td></tr>
							</table>
							<hr /><page />
TXT;
							echo $mainTable;
							}
							break;
		}
}

function displayCriterion2($app_id) {
}

function displayCriterion1($app_id) {

	$Q1_1 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_1_comment"), "docgen");
	$Q1_2 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_2_comment"), "docgen");
	$Q1_3 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_3_comment"), "docgen");

	$Q1_6 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_4_comment_v2"), "docgen");
	$Q1_7 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_5_comment"), "docgen");
	$Q1_8 = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_6_comment"), "docgen");
	$Q1_9_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_9_yn");
	$Q1_9 = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $Q1_9_id, "lkp_yn_desc");

	$Q1_10_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1_11_yn");
	$Q1_10 = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $Q1_10_id, "lkp_yn_desc");



	$otherLearningActivities = simple_text2html(DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "learning_activities_other_text"), "docgen");


	$text =<<< TEXT
	<br />
	<b>1) PROGRAMME DESIGN: (Criterion 1)</b>
	<br /><br />
	<table width="140%">
		<tr>
			<td>
				<b>Minimum standards:</b>
				<br />
				The programme is consonant with the institution's mission, forms part of institutional planning and resource allocation, meets national requirements, the needs of students and other stakeholders, and is intellectually credible. It is designed coherantly, and articulates well with other relevant programmes, where possible.
			</td>
		</tr>
	</table>
	<br /><br />
	<b>1.1 How does this programme fit in with the mission and plan of the institution? </b>
	<br />
	$Q1_1
	<br /><br />
	<b>1.2 Provide a rationale for this programme, taking into account the envisaged student intake and stakeholder needs. </b>
	<br />
	$Q1_2
	<br /><br />
	<b>1.3 Describe the articulation possibilities of this programme. </b>
	<br />
	$Q1_3
	<br /><br />

	<b>1.4 Provide the names of the modules/courses which lead to the programme - and for each course, specify:</b>
	<br />
	<table width="140%">
		<tr>
			<td><b>Module name</b></td>
			<td><b>NQF Level of the module</b></td>
			<td><b>Credits per module</b></td>
			<td><b>Compulsory/optional</b></td>
			<td><b>Year (1, 2, 3, 4)</b></td>
			<td><b>Total credits per year   </b></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<br />

	<b>1.5 LEARNING ACTIVITIES:
	<br />
	Complete the following table for the whole programme:</b>
	<table width="140%">
		<tr>
			<td><b>Correspondence (Y/N)</b></td>
			<td><b>E-learning (Y/N)</b></td>
			<td><b>Telematic (Y/N)</b></td>
			<td><b>Contact (Y/N)</b></td>
			<td><b>Types of learning activities</b></td>
			<td><b>% Learning time</b></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Lectures (face to face, limited interaction or technologically mediated)</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Tutorials: individual groups of 30 or less </td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Syndicate groups </td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Practical workplace experience (experiential learning/work-based learning etc) </td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Independent self-study of standard texts and references (study guides, books, journal articles) </td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Independent self study of specially prepared materials (case studies, multi-media, etc) </td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Other (specify)</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="7"><b>If you selected "Other" in the table above, please give a detailed explanation below:</b></td>
		</tr>
		<tr>
			<td colspan="7">$otherLearningActivities</td>
		</tr>
	</table>

	<br />
	<b>1.6 Specify the programme purpose and indicate how the proposed curriculum will contribute towards the intended outcomes. </b>
	<br />
	$Q1_6
	<br /><br />
	<b>1.7 Specify the rules of combination for the constituent modules/courses and, where applicable, progression rules from one year to the next. </b>
	<br />
	$Q1_7
	<br /><br />
	<b>1.8 Provide a brief explanation of how competences developed in the programme are aligned with the appropriate NQF level. </b>
	<br />
	$Q1_8
	<br /><br />
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
	<table>
		<tr>
			<td>Year(s) of study when experiential learning takes place:</td>
			<td></td>
		</tr>
		<tr>
			<td>Duration of the placement:</td>
			<td></td>
		</tr>
		<tr>
			<td>Credit Value:</td>
			<td></td>
		</tr>
		<tr>
			<td>Expected learning outcomes:</td>
			<td></td>
		</tr>
		<tr>
			<td>Assessment methods:</td>
			<td></td>
		</tr>
		<tr>
			<td>Monitoring procedures:</td>
			<td></td>
		</tr>
		<tr>
			<td>Placement is an institutional responsibility?</td>
			<td></td>
		</tr>
		<tr>
			<td>Who is responsible? (only if answered no in previous question)</td>
			<td></td>
		</tr>
	</table>
	<br /><br />
	<b>The following documentation to be uploaded as it pertains to this programme:</b>
	<br /><br />
	<i>NB: Failure to attach relevant implementation plans will result in submissions being returned to Institutions. </i>
	<br /><br />
	* Policy for the development of learning materials.
	<br />
	* Budget for the development of learning materials.
	<br />
	* Examples of contract arrangements with workplaces for student placements.
	<br />
	* Outline of all courses and modules (core, fundamental and optional) that constitute the programme.
	<br />
	* SAQA submission.
	<br />
	* List of prescribed and recommended readings.
	<br />
	* Any other documentation which will indicate your compliance with this criterion.
TEXT;
	echo $text;
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
	$intro =<<< TEXT
	<br />
	<b>B) APPLICATION FORM FOR PROGRAMME ACCREDITATION: </b>
	<br /><br />
	This part of the form requires an evaluation of the extent to which the proposed programme fulfils the HEQC accreditation criteria. Please note that the information provided should demonstrate compliance with the minimum standards. Failure to provide all the required information may result in the application being returned to the institution.
	<br /><br />
	Minimum standards provide the full text of the minimum standards programmes are expected to meet in relation to each criterion.
	<br /><br />
TEXT;
	echo $intro;
	HEQConline::displayCriterion1($app_id);
	echo "<page />";
	//criterion2
	HEQConline::displayTemplateRTFReport($app_id,"accForm6_v2");
	echo "<page />";

	//criterion5
	HEQConline::displayTemplateRTFReport($app_id,"accForm9_v2");
	echo "<page />";
	//criterion6
	HEQConline::displayTemplateRTFReport($app_id,"accForm14_v2");
}

function displaySectionA($app_id) {
	$intro =<<< INTRO
		<br /><br />
		<b>A) PROGRAMME INFORMATION</b>
		<br /><br />
		Please indicate all delivery sites for the proposed programme. (Tuition Centres to be used for Distance Education should not be listed in this form.)
		<br /><br />
		<table width="140%">
			<tr>
				<td><b>Site</b></td>
				<td><b>Contact name</b></td>
				<td><b>Contact surname</b></td>
				<td><b>Contact email</b></td>
				<td><b>Contact tel. no.</b></td>
				<td><b>Contact fax. no.</b></td>
			</tr>
INTRO;
	echo $intro;

	$SQL =<<< sSQL
			SELECT ia_criteria_per_site_id, institutional_profile_sites.*
			FROM ia_criteria_per_site, institutional_profile_sites
			WHERE application_ref = $app_id
			AND institutional_profile_sites_id = institutional_profile_sites_ref
sSQL;

	$rs = mysqli_query($SQL);
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


	$prog_name = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");
	$prog_type_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "prog_type");
	$prog_type = DBConnect::getValueFromTable("lkp_prog_type", "lkp_prog_type_id", $prog_type_id, "lkp_prog_type_desc");
	$mode_delivery_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "mode_delivery");
	$mode_delivery = DBConnect::getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $mode_delivery_id, "lkp_mode_of_delivery_desc");
	$senate_approval_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "senate_approved");
	$senate_approval = DBConnect::getValueFromTable("lkp_yes_no", "lkp_yn_id", $senate_approval_id, "lkp_yn_desc");
	$senate_approval_date = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "senate_approved_date");
	$designation = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "designation");
	$qualifier1 = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "1st_qualifier");
	$qualifier2 = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "2nd_qualifier");
	$CESM_qual_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "CESM_code1");
	$CESM_qual = DBConnect::getValueFromTable("SpecialisationCESM_code1", "CESM_code1", $CESM_qual_id, "Description");
	$NQF_level_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");
	$NQF_level = DBConnect::getValueFromTable("NQF_level", "NQF_id", $CESM_qual_id, "NQF_level");
	$credits = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "num_credits");
	$full_time = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "full_time");
	$part_time = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "part_time");

	$is_reg_doe_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "is_reg_doe");
	$is_reg_doe = DBConnect::getValueFromTable("lkp_reg_doe", "lkp_reg_doe_id", $is_reg_doe_id, "lkp_reg_doe_desc");
	$doe_reg_nr = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "doe_reg_nr");
	$doe_appl_date = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "doe_appl_date");
	$is_reg_saqa_nqf_id = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "is_reg_saqa_nqf");
	$is_reg_saqa_nqf = DBConnect::getValueFromTable("lkp_reg_SAQA_NQF", "lkp_reg_SAQA_NQF_id", $is_reg_saqa_nqf_id, "lkp_reg_SAQA_NQF_desc");
	$saqa_reg_nr = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "saqa_reg_nr");
	$saqa_appl_date = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "saqa_appl_date");
	$prog_start_date = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "prog_start_date");


	$programmeInfo =<<< PROGINFO
		<br />

		<table width="140%" border="0">
			<tr>
				<td width="40%"><b>Programme name:</b></td>
				<td>$prog_name</td>
			</tr>
			<tr>
				<td><b>Programme type:</b></td>
				<td>$prog_type</td>
			</tr>
			<tr>
				<td><b>Mode of Delivery:</b></td>
				<td>$mode_delivery</td>
			</tr>
			<tr>
				<td><b>Has the programme been approved by the relevant governance structure within the institution?</b></td>
				<td>$senate_approval</td>
			</tr>
			<tr>
				<td><b>Date of approval:</b></td>
				<td>$senate_approval_date</td>
			</tr>
			<tr>
				<td><b>Qualification Designation (e.g. BSc or Diploma) :</b></td>
				<td>$designation</td>
			</tr>
			<tr>
				<td><b>First Qualifier (e.g. Chemistry or Web Design) :</b></td>
				<td>$qualifier1</td>
			</tr>
			<tr>
				<td><b>Second Qualifier (e.g. Organic Chemistry or 3D) :</b></td>
				<td>$qualifier2</td>
			</tr>
			<tr>
				<td><b>CESM Classification (e.g. Education):</b></td>
				<td>$CESM_qual</td>
			</tr>
			<tr>
				<td><b>NQF Level:</b></td>
				<td>$NQF_level</td>
			</tr>

			<tr>
				<td><b>Number of credits:</b></td>
				<td>$credits</td>
			</tr>
			<tr>
				<td><b>Minimum time for completion - Full Time (years):</b></td>
				<td>$full_time</td>
			</tr>
			<tr>
				<td><b>Minimum time for completion - Part Time (years):</b></td>
				<td>$part_time</td>
			</tr>
		</table>
		<br /><br />
		<b>STATUS</b>
		<table width="140%" border="0">
			<tr>
				<td width="40%"><b>Have you applied for registration with the DoE for this programme?</b></td>
				<td>$is_reg_doe</td>
			</tr>
			<tr>
				<td><b>Existing providers: DoE registration number:</b></td>
				<td>$doe_reg_nr</td>
			</tr>
			<tr>
				<td><b>Please upload the DoE registration certificate:</b></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Please enter the date when application was made to DoE:</b></td>
				<td>$doe_appl_date</td>
			</tr>
			<tr>
				<td><b>Is the qualification registered by SAQA on the NQF?</b></td>
				<td>$is_reg_saqa_nqf</td>
			</tr>
			<tr>
				<td><b>SAQA Registration Number:</b></td>
				<td>$saqa_reg_nr</td>
			</tr>
			<tr>
				<td><b>Please upload the SAQA Registration Certificate</b></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Please enter the date when application was made to SAQA:</b></td>
				<td>$saqa_appl_date</td>
			</tr>
			<tr>
				<td><b>Date by which you plan to start offering the programme:</b></td>
				<td>$prog_start_date</td>
			</tr>
		</table>
PROGINFO;
	echo $programmeInfo;
}

//***Rebecca::2008-02-18 - displays populated application form

function displayPopulatedApplicationFormPerCriteria ($app_id=0, $type) {

	$intro =<<<INTRO
	<br />
	<b>APPLICATION FORM FOR PROGRAMME ACCREDITATION: </b>
	<br /><br />
	This part of the forms asks you to evaluate the extent to which the proposed programme fulfils the HEQC accreditation criteria. Please note that the information provided should demonstrate compliance with the minimum standards. Failure to provide all the required information may result in the application being returned to the institution.
INTRO;
	echo $intro;
	HEQConline::displaySectionA($app_id);
	echo "<page />";
	HEQConline::displaySectionB($app_id);
	echo "<page />";
	HEQConline::displaySectionC($app_id);
}

function displayTemplateRTFReport($app_id,$template){

	// Need to get the following from the workflows table for the current template and abort if no table.
	$templateTable = "Institutions_application";
	$templateTableKey = "application_id";

	$rtfhtml = "";
	$sql = "SELECT * FROM template_field WHERE template_name = '$template' ORDER BY fieldOrder";
	$rs = mysqli_query($sql);
	while ($row = mysqli_fetch_array($rs)){
		switch ($row["fieldType"]){
		case "TEXTAREA":
			$Q = simple_text2html(DBConnect::getValueFromTable($templateTable, $templateTableKey, $app_id, $row["fieldName"]), "docgen");
			break;
		case "FILE":
		default:
			$Q = "&nbsp;";
		}

		$rtfhtml .= <<<RTF
			<br /><br />
			<b>$row[fieldTitle]</b>
			<br />
			$Q
RTF;

	}
	echo $rtfhtml;
}

function getSitesOfDeliveryList($app_id) {
	$sites_SQL = "SELECT * FROM lkp_sites, Institutions_application WHERE application_ref = application_id AND application_id=".$app_id;
	$sites_rs = mysqli_query($sites_SQL);
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
			case "html" : 	$programme_details_html = <<<TXT
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

				$programme_outcomes_html = <<<TXT
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

			case "docgen" :	$programme_details_html = <<<TXT
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
				$programme_outcomes_html = <<<TXT
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
							$additional_sites_html = <<<TXT
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
						$additional_sites_html = <<<TXT
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


	function getSitesOfDeliveryPerApplication($app_id) {
		$SQL =<<< SQL
			SELECT ia_criteria_per_site_id, institutional_profile_sites.*
			FROM ia_criteria_per_site, institutional_profile_sites
			WHERE application_ref = $app_id
			AND institutional_profile_sites_id = institutional_profile_sites_ref
SQL;
		$s_RS = mysqli_query($SQL);
		$additional_sites_html = "";
		$count = 0;

		if (mysqli_num_rows($s_RS) > 0) {
			$additional_sites_html = "<ol>";
			while ($s_row = mysqli_fetch_array($s_RS))
			{
				$name = $s_row["site_name"];
				$location = $s_row["location"];

				$additional_sites_html .= <<<TXT
						<li>$name - $location</li>
TXT;
			}
			return "</ol>".$additional_sites_html;
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
			case "html" : $inst_details_html = <<<TXT
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
			case "docgen" :  $inst_details_html = <<<TXT
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
			<br /><br /><br /><br /><br /><br /><br />
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
		echo ($layout == "landscape") ? "<section landscape='yes'/>\n" : "";
		$displayGeneralPageSetup = <<<TXT
			<header><b>HEQC-online Accreditation System - $title</b></header>
			<footer><table border="0" width="140%"><tr><td align="left">
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
	$eRS = mysqli_query($eSQL);

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
	$RS  = mysqli_query($SQL);
	if ($row = mysqli_fetch_array($RS)) {
		return $row["application_sum_doc"];
	}
	else
		return 0;
}

function displayApplicationRequests($app_id){
	//displays drafts if within workflow; shows sent requests only if viewing from application progress report
	$where = ($this->view == 1) ? " AND request_status=2 " : "";

	$sql = <<<sqlrept
		SELECT *
		FROM appTable_requests
		WHERE application_ref = $app_id
		$where
		ORDER BY appTable_requests_id
sqlrept;
	$rs = mysqli_query($sql);

	$html = <<<titleTab
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
titleTab;
	if (mysqli_num_rows($rs) == 0){
		$html .= "<tr><td>No previous requests for information.</td></tr>";
	} else {
		while ($row = mysqli_fetch_array($rs)){
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

			$html .= <<<htmlrept

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
htmlrept;
		}
	}
	$html .= "</table>";

	return $html;
}

function getInstitutionAdministrator($app_id){

	$inst = $this->getValueFromTable("Institutions_application","application_id",$app_id,"institution_id");

	$sql = <<<adminSQL
		SELECT user_id
		FROM users, sec_UserGroups
		WHERE user_id = sec_user_ref
		AND active = 1
		AND sec_group_ref = 4
		AND institution_ref = $inst
adminSQL;

	$rs = mysqli_query($sql);
	$n = mysqli_num_rows($rs);
	if ($n == 0){
		$adm_arr = array(0,"No Institutional Administrator assigned for this institution.");
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
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
}

//2007-01-03: Rebecca - returns a list of the restrictions placed on an AC member
function getRestrictionsList($ac_mem_id) {
	$SQL = "SELECT * FROM lkp_AC_member_restrictions WHERE AC_member_id = ".$ac_mem_id;
	$rs = mysqli_query($SQL);
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
	$rs = mysqli_query($SQL);
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
	$hei_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	$priv_publ = $this->getValueFromTable("HEInstitution", "HEI_id", $hei_id, "priv_publ");
	return $priv_publ;
}

// 2008-02-14 Robin - Certain criteria are captured per site when capturing an application.
// This function creates a table of sites (with a link to edit) for a particular application and a specific criteria.

function buildSiteCriteriaEditforApplication($app_id,$criteria){
	$data = "No sites were found for this application. Please go and select the sites where this programme will be offered before continuing.";


	$sql = <<<getSites
		SELECT ia_criteria_per_site_id, institutional_profile_sites.*
		FROM ia_criteria_per_site, institutional_profile_sites
		WHERE application_ref = $app_id
		AND institutional_profile_sites_id = institutional_profile_sites_ref
getSites;

	$rs = mysqli_query($sql);
	if ($rs && mysqli_num_rows($rs) > 0){
			$data = <<<hhead
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

			$label = "_labelEditCriteria".$criteria."PerSite";
			$data .= <<<hrow
				<tr>
					<td width="4%">
						<a href='javascript:getForm("ia_criteria_per_site","$site_id", "$label");'>
						<img src="images/ico_change.gif" border=0>
						</a>
					</td>
					<td>$row[site_name] - $row[location]</td>
				</tr>
hrow;
		}
	}

	$html = <<<sites
		<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
		$data
		</table>
sites;

	return $html;
}

// Robin 18/02/2008. Determine whether any sites have been added for an application.
// Note: Selection of sites is a two stage process in two tables: lkp_sites for initial selection
//																	ia_criteria_per_site for confirmaed selection.
// Once selection is confirmed the user may not reselect sites. Hence this function to check if confirmed sites have been added.
function getNoOfSitesForApplication($app_id){
	$NoOfSites = 0;

	$sql = <<<siteSQL
	SELECT count(*) as NoOfSites
	FROM ia_criteria_per_site
	WHERE application_ref = $app_id
siteSQL;
//echo $sql;
	$rs = mysqli_query($sql);
	if ($rs){
		$row = mysqli_fetch_array($rs);
		$NoOfSites = $row["NoOfSites"];
	}

	return $NoOfSites;
}

function safeJS ($fld) {
	return (str_replace("$", "%24", $fld));
}

// END of Class
}
?>
