<?php

/**
 * application class specific to this application
 *
 * this class has non-genric functions specific to this workflow application.
 * @author Diederik de Roos, Louwtjie du Toit, Reyno vd Hooven
*/

class CHEprojects extends workFlow {

	var $relativePath;
	/**
	 * default constructor
	 *
	 * this function calls the {@link workFlow} function.
	 * @author Diederik de Roos
	 * @param integer $flowID
	*/
	function CHEprojects ($flowID) {
		$this->readPath ();
		$this->workFlow ($flowID);
		$this->populatePublicHolidays ();
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
	<?
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
<td class="oncolourb" align="center">Reference Number</td>
<td class="oncolourb" align="center">Last Updated</td>
</tr>
<?
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
<?	$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
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
	processes page (in Reference Number column). It traverses
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
<?
		}
	}
	if (mysqli_num_rows($rs) > 0) mysqli_data_seek($rs, 0);
	if (mysqli_num_rows($rs) < 1) {
		echo '<tr class="onblue"><td colspan="3" align=center>There are currently no active processes</td></tr>';
	}
?>
</table>
<?
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

		$main_SQL = "SELECT * FROM `".$table."`";
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

		if ($fieldType == "select"){
			$this->formFields[$fieldName]->fieldNullValue = "---";
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
		$lookup_rows = mysqli_num_rows(mysqli_query("SELECT ".$lkp_row_id." FROM ".$lkp_row_table));

		//Now, we check to see if we've got any rows to work with. If there are rows, it means that we've already created the entries
		//in the database and now we need some values for them as well as to print them in our table.

		//first see if we're dealing with a fixed grid else ajust the number_rows accordingly.
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
				$fld_lkp_desc = "";
				$fld_lkp_key = "";
				$fld_lkp_table = "";
				$fld_lkp_condition = "";
				$fld_lkp_order_by = "";

				$html_arr = $this->createHTMLGridFields ($row, $table, $key_fld, $fields_arr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by);

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
	function gridShowRowByRow ($table, $keyFLD, $unique_flds, $fieldArr, $headingArr, $cols=40, $rows=5, $add="", $del="") {
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

		//we must have at least 1 row in the database:
		if (! ($num_rows > 0) ) {
			$this->createHTMLGridInsertWithoutLookup ($table, $unique_flds_array);
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
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",".$doc->getDocID().",\"".$fld."\",\"\");'>Click here to select the file that you need to upload</a></td>";
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
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",0,\"".$fld."\",\"\");'>Click here to select the file that you need to upload</a></td>";
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

	function showWelcomeAlertsForEditing () {
		$SQL = "SELECT * FROM `welcome_alerts` ORDER BY alert_date DESC ";
		$RS = mysqli_query($SQL);
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

	function reportProjectDetail($searchText="",$budget_year=""){
		echo "Project Detail Report";
	}

	/* Robin 6/7/2007
	   Returns the number of rows archived for a budget year
	*/
	function archiveFinancialProjectData($year){

		$rc = 0;

		$SQL = "SELECT 0 FROM project_financial WHERE budget_year = '".$year."'";

		$RS = mysqli_query($SQL) or die(mysqli_error($RS));
		if ($RS && mysqli_num_rows($RS) > 0){

			/* Archive rows for budget year */
			$iSQL = "INSERT into project_financial_archive
					SELECT NULL,NOW(),project_financial.*
					FROM project_financial
					WHERE budget_year = '".$year."'";

			$iRS = mysqli_query($iSQL) or die(mysqli_error());
			$rc = mysqli_affected_rows();

			/* delete rows for budget year from project_financial table */
			if ($rc > 0){
				$dSQL = "DELETE FROM project_financial WHERE budget_year = '".$year."'";
				$dRS = mysqli_query($dSQL) or die(mysqli_error());
			}
		}

		return $rc;
	}


	/* Robin 6/7/2007 - old : Incorrect data extracted from wrong table. Replaced by function loadLedgerTransactions.
	   Returns the number of rows inserted from pastel financial data
		- for selected projects
		- and a budget year
	*/
	function loadFinancialProjectData($year){
		$rc = 0;

		$SQL = "INSERT INTO project_financial
			SELECT NULL ,
				$year,
				mid( AccNumber, 5 ) AS proj_code,
				mid( AccNumber, 1, 4 ) AS acc_type,
				pastel_financial_data.*
				FROM pastel_financial_data
			WHERE EXISTS (
				SELECT 0
				FROM project_required_list
				WHERE mid( pastel_financial_data.AccNumber, 5 ) =
						project_required_list.proj_code
				)";

		$RS = mysqli_query($SQL);
		if ($RS){
			$rc = mysqli_affected_rows();
		}

		return $rc;

	}

	/*****************************************************************************************************************
	Robin 6/9/2007
	Archives all previous ledger transactions before a new load. In each load ALL data is loaded for a set list of
	projects and line items irrespective of date. Thus a new load replaces the previous data.
	I'm not sure that the archive is even necessary especially if there is not functionality in the system to read
	or run reports from the archive.
	I'm still archiving initially in case I need to check something in a previous load.
	*****************************************************************************************************************/
	function archiveLedgerTransactions($budget_year){
		$rc = 0;

		/* generate archive no */

		$sql = "SELECT max(archive_no) as archive_no FROM project_ledger_transactions_archive";
		$rs = mysqli_query($sql) or die(mysqli_error());
		$row = mysqli_fetch_array($rs);
		$archive_no = ($row["archive_no"] > 0) ? $row["archive_no"] : 1;

		/* Archive rows */
		$iSQL = <<<ins
			INSERT into project_ledger_transactions_archive
				SELECT NULL, NOW(), $archive_no, project_ledger_transactions.*
				FROM project_ledger_transactions;
ins;


		$iRS = mysqli_query($iSQL) or die(mysqli_error());
		$rc = mysqli_affected_rows();

		/* delete rows for budget year from project_financial table */
		if ($rc > 0){
			$dSQL = "DELETE FROM project_ledger_transactions  WHERE budget_year = '".$budget_year."'";

			$dRS = mysqli_query($dSQL) or die(mysqli_error());
		}

		return $rc;
	}

	/***********************************************************************************************************
	Robin 6/9/2007
	This function extracts financial data downloaded from table LedgerTransactions in Pastel:
	- for a required list of projects that is editable in the system (users can add or remove projects)
	- for a required set of line item codes that may be used by projects that is editable in the system (users
	  can add or remove line items)
	I'm extracting data in this way so that the financial report can be run whether or not a user has provided
	the detail project register data in the system and it allows flexibility to add new projects or line items.
	************************************************************************************************************/
	function loadLedgerTransactions($budget_year){
		$rc = 0;

		$findate = $this->getFinancialYearStartDate($budget_year);


		/* Only load data for Projects that we have in the register */
		$psql = "SELECT proj_code FROM project_required_list WHERE budget_year = '".$budget_year."'";


		$prs = mysqli_query($psql) or die(mysqli_error());
		$proj_arr = array();
		while ($prow = mysqli_fetch_array($prs)){
			 array_push($proj_arr,$prow["proj_code"]);
		}

		/* Only load data for Accounts specified for Projects */
		$asql = "SELECT line_item_code FROM project_required_line_item WHERE budget_year = '".$budget_year."'";


		$ars = mysqli_query($asql) or die(mysqli_error());
		$acc_arr = array();
		while ($arow = mysqli_fetch_array($ars)){
			 array_push($acc_arr,$arow["line_item_code"]);
		}

		$SQL = "INSERT INTO project_ledger_transactions (
				budget_year,
				proj_code,
				acc_type,
				AccNumber, DDate, Amount, date_loaded)
			SELECT '".$budget_year."' AS budget_year,
				mid( AccNumber, 5 ) AS proj_code,
				mid( AccNumber, 1, 4 ) AS acc_type,
				AccNumber, DDate, Amount, now()
				FROM pastel_ledger_transactions
			WHERE mid( AccNumber, 5 )
				IN (". implode(",",$proj_arr) . ")
			AND mid( AccNumber, 2, 3 )
				IN (". implode(",",$acc_arr) . ")
			AND DDate >= '".$findate["start"] . "'
			AND DDate <= '".$findate["end"] . "'" ;

		$RS = mysqli_query($SQL) or die(mysqli_error());
		if ($RS){
			$rc = mysqli_affected_rows();
		}

		return $rc;

	}

	function getQuarter($qdate){

		$Q = 0;
		$sql = "SELECT quarter FROM lkp_financial_month WHERE lkp_month_id = MONTH('$qdate')";
		$rs = mysqli_query($sql);

		if ($rs){
			$row = mysqli_fetch_array($rs);
			$Q = $row["quarter"];
		}

		return $Q;
	}

	function getBudget($budget_year,$proj_id){
		$budget["planned"] = 0;
		$budget["revised"] = 0;
		$SQL = "SELECT budget_year, planned_budget, revised_budget FROM project_budget_per_year WHERE project_ref=".$proj_id." AND budget_year = '".$budget_year."'" ;
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0) {
			$row = mysqli_fetch_array($rs);
			$budget["planned"] = $row["planned_budget"];
			$budget["revised"] = $row["revised_budget"];
		}
		return $budget;
	}

	function getFinancialYearStartDate($budget_year){
		$findate = array();

		$financial_first_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 1, "lkp_month_id");
		$financial_last_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 12, "lkp_month_id");
		$year1 = substr($budget_year,0,4);
		$year2 = substr($budget_year,5,4);

		$findate["start"]  = $year1 . "-" . $financial_first_month . "-01";
		$findate["end"]    = $year2 . "-" . $financial_last_month . "-" . cal_days_in_month(CAL_GREGORIAN, $financial_last_month, $year2);

		return $findate;
	}

	function calculateQuarterlyBudget($budget_year,$budget,$start_date,$end_date){

//		$financial_first_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 1, "lkp_month_id");
//		$financial_last_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 12, "lkp_month_id");
//		$year1 = substr($budget_year,0,4);
//		$year2 = substr($budget_year,5,4);
//		$start  = $year1 . "-" . $financial_first_month . "-01";
//		$end 	= $year2 . "-" . $financial_last_month . "-" . cal_days_in_month(CAL_GREGORIAN, $financial_last_month, $year2);

		$findate = $this->getFinancialYearStartDate($budget_year);

		$qB[1] = 0;
		$qB[2] = 0;
		$qB[3] = 0;
		$qB[4] = 0;

		// Assumption that end date always later than start date
		// Return if dates are out of budget year range
		if ($start_date > $findate["end"] || $end_date < $findate["start"]){
			return $qB;
		}

		$startQ = 1;
		$endQ 	= 4;

		if ($end_date < $findate["end"]){
			$endQ = $this->getQuarter($end_date);
		}

		if ($start_date > $findate["start"]){
			$startQ = $this->getQuarter($start_date);
		}

		$numQ = $endQ - $startQ + 1;

		if ($numQ > 0) $qBudget = $budget / $numQ;

		$i = 0;
		while ($i < $numQ){
			$qB[$startQ] = $qBudget;
			$i++;
			$startQ++;
		}

		return $qB;
	}

	function calculateQuarterlyExpenditure($budget_year,$project){

		$year1 = substr($budget_year,0,4);
		$year2 = substr($budget_year,5,4);

		$qB[1] = 0;
		$qB[2] = 0;
		$qB[3] = 0;
		$qB[4] = 0;

		$start[1] = $year1 . "-04-01";
		$start[2] = $year1 . "-07-01";
		$start[3] = $year1 . "-10-01";
		$start[4] = $year1+1 . "-01-01";
		$end[1] = $year1 . "-06-30";
		$end[2] = $year1 . "-09-31";
		$end[3] = $year1 . "-12-31";
		$end[4] = $year1+1 . "-03-31";

		$qB[1] = CHEprojects::getExpenditure($project,$start[1],$end[1]);
		$qB[2] = CHEprojects::getExpenditure($project,$start[2],$end[2]);
		$qB[3] = CHEprojects::getExpenditure($project,$start[3],$end[3]);
		$qB[4] = CHEprojects::getExpenditure($project,$start[4],$end[4]);

		return $qB;
	}


	// Robin 16 July 2007
	// Returns the expenditure for a project and year, 0 if no row, -1 if error.
	function calculateExpenditure($budget_year, $project){
		$expenditure = 0;

//		$financial_first_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 1, "lkp_month_id");
//		$financial_last_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 12, "lkp_month_id");

		$financial_first_month = 4;
		$financial_last_month = 3;


		$year1 = substr($budget_year,0,4);
		$year2 = substr($budget_year,5,4);

		$start_date = $year1 . "-" . $financial_first_month . "-01";
		$end_date 	= $year2 . "-" . $financial_last_month . "-" . cal_days_in_month(CAL_GREGORIAN, $financial_last_month, $year2);

/*
		$sqlF = <<<sqlF
			SELECT sum(Amount) as expenditure
			FROM project_ledger_transactions as f
			WHERE f.proj_code = '$project'
			AND f.DDate >= '$start_date' AND f.DDate <= '$end_date'
sqlF;

		$rsF = mysqli_query($sqlF) or die(mysqli_error());
		if (1 == mysqli_num_rows($rsF)){
			$rowF = mysqli_fetch_array($rsF);
			$expenditure = $rowF["expenditure"];
		}
		if (mysqli_num_rows($rsF) > 1){
			// error. There should only be one row per year, per project.
			// Rather show -1 than the wrong value.
			$expenditure = -1;
		}
*/
		$expenditure = CHEprojects::getExpenditure($project,$start_date,$end_date);
		$expenditure = ($expenditure) ? $expenditure : 0;
		return $expenditure;
	}

	function getExpenditure($project,$start_date,$end_date){
		$expenditure = 0;

		$sqlF = <<<sqlF
			SELECT sum(Amount) as expenditure
			FROM project_ledger_transactions as f
			WHERE f.proj_code = '$project'
			AND f.DDate >= '$start_date' AND f.DDate <= '$end_date'
sqlF;
//echo "<br>" . $sqlF;
		$rsF = mysqli_query($sqlF) or die(mysqli_error());
		if (1 == mysqli_num_rows($rsF)){
			$rowF = mysqli_fetch_array($rsF);
			$expenditure = $rowF["expenditure"];
		}
		if (mysqli_num_rows($rsF) > 1){
			// error. There should only be one row per year, per project.
			// Rather show -1 than the wrong value.
			$expenditure = -1;
		}

		return $expenditure;
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

	function getSecurityAccess($userid){
		$sec = array("status"=>false,"filter"=>"");

		$gsql = "SELECT * FROM sec_Groups";
		$grs = mysqli_query($gsql);
		while ($grp = mysqli_fetch_array($grs)){
			$grp_id = $grp["sec_group_id"];
			if (CHEprojects::is_userPartOfGroup($grp_id,$userid)){
				$sec["status"]=true;

				switch ($grp_id){
				case "2":  // Directorate group - may only access projects for their directorate
					$sql = "SELECT institution_ref FROM users WHERE user_id = $userid";
					$rs = mysqli_query($sql);
					$n = mysqli_num_rows($rs);
					$dir_list = array();
					while ($row = mysqli_fetch_array($rs)){
						if ($row["institution_ref"] > ""){
							array_push($dir_list,"'".$row["institution_ref"]."'");
						}
					}
					$sec["filter"] = "directorate_ref in (" . implode(',',$dir_list) . ")";	//Build list to filter projects
					break;
				}
			}
		}
		if ($sec["status"] == false) die("Please contact your System Administrator to set up your access rights in the system.");
		return $sec;
	}

	function getDirectorate ($dir){

		$htm = "";

		$sql = <<<sqlM
			SELECT *
			FROM lkp_directorate
			WHERE lkp_directorate_id = $dir
sqlM;

		$rs = mysqli_query($sql) or die(mysqli_error());
		$row = mysqli_fetch_array($rs);
		$dir_desc = $row["directorate_description"];

		return $dir_desc;
	}

	function getProjectList($project_source, $filter="", $budget_year=""){

		$whereArr = array();
		$where = "";

		if (is_array($filter) && count($filter)>= 1){
			$whereArr = $filter;
		} else {
			if ($filter > "") array_push($whereArr, $filter);
		}

		if ($budget_year > ""){
			array_push($whereArr, "budget_year = '".$budget_year."'");
		}

		$where = implode(" AND ",$whereArr);

		$where = ($where > '') ? "WHERE " . $where : "";

		switch ($project_source){
		case 2:
			$sqlP = <<<SQL
			SELECT *
			FROM `project_required_list` as d
			$where
			ORDER BY d.directorate_ref, proj_code, budget_year;
SQL;
			break;
		case 1:
		default:
			$where = str_replace("directorate_ref","d.directorate_ref",$where);
			$sqlP = <<<SQL
			SELECT d.directorate_ref, d.project_id, r.proj_code, r.proj_description
			FROM `project_detail` as d
			LEFT JOIN project_required_list as r ON (r.project_ref = d.project_id)
			$where
			ORDER BY d.directorate_ref, r.proj_code, budget_year;
SQL;
		}
		return $sqlP;

	}

	//Rebecca: 2007-09-27
	//Displays the planned budget per year in a table
	function displayBudgetPerYear($proj_id) {
		$budget_table = "";

		$ySQL = <<<PSQL
			SELECT r.budget_year, r.proj_code, r.proj_description, b.planned_budget, b.revised_budget
			FROM project_required_list as r
			LEFT JOIN project_budget_per_year as b
				ON (r.budget_year = b.budget_year AND r.project_ref = b.project_ref)
			WHERE r.project_ref = $proj_id
PSQL;
//echo $ySQL;
		$yRS = mysqli_query($ySQL);
		if ($yRS && mysqli_num_rows($yRS) > 0){

				$budget_table = "<table border='0' width='95%' cellpadding=1 cellspacing=2>";
				$budget_table .= <<<htm
					<tr class='onblueb'>
						<td width="12%">Year</td>
						<td width="10%">Project<br>code</td>
						<td width="28%">Project<br>description</td>
						<td width="15%">Planned Budget</td>
						<td width="15%">Revised Budget</td>
						<td width="20%">Expenditure (ytd)</td>
					</tr>
htm;
				while ($yRow = mysqli_fetch_array($yRS)) {
					$by = $yRow['budget_year'];
					$proj_code = $yRow["proj_code"];
					$proj_desc = $yRow["proj_description"];
					$expenditure = $this->calculateExpenditure($by, $proj_code);
					$budget_table .= "<tr bgcolor='white'>";
					$budget_table .= "<td>".$by."</td><td>".$proj_code."</td><td>".$proj_desc."</td><td>R ".sprintf("%d",$yRow['planned_budget'])."</td><td>R ".sprintf("%d",$yRow['revised_budget'])."</td><td>R ".sprintf("%.2f",$expenditure)."</td>";
					$budget_table .= "</tr>";
				}
				$budget_table .= "</table>";


		} else {

			$budget_table = "<table><tr><td>Project not listed for financial information.</td></tr></table>";

		}

		return $budget_table;
	}

function displayBudgetSummaryTable($format, $sql, $budget_yearID) {
	$budgetTable = "";
	switch ($format) {
		case "table" :
					$budgetHead =<<<TABLE
					<br><span class="specialh">Project Indicators budget summary:</span><hr width="60%" align="left">
					<table border='0' width='100%' cellpadding='2' cellspacing='2'>
						<tr>
							<td class="onblueb" valign="top">Project Title</td>
							<td class="onblueb" valign="top">Budget year(s)</td>
							<td class="onblueb" valign="top">Project code</td>
							<td class="onblueb" valign="top">Planned budget</td>
							<td class="onblueb" valign="top">Revised budget</td>
							<td class="onblueb" valign="top">Expenditure</td>
							<td class="onblueb" valign="top">% Spent</td>
						</tr>
TABLE;
					break;
		case "docgen" :
					$budgetHead =<<<TABLE
					<p after="5" />
					<table width="100%" border="t,b,l,r">
						<tr bgcolor="5">
							<td align="center"><b>Project Title</b></td>
							<td align="center"><b>Budget year(s)</b></td>
							<td align="center"><b>Project code</b></td>
							<td align="center"><b>Planned budget</b></td>
							<td align="center"><b>Revised budget</b></td>
							<td align="center"><b>Expenditure</b></td>
							<td align="center"><b>% Spent</b></td>
						</tr>
TABLE;
						break;
	}

	echo $budgetHead;

	$rs = mysqli_query($sql);
	if (mysqli_num_rows($rs) > 0)
	{

		while ($row = mysqli_fetch_array($rs))
		{

			$projTitle = $row["project_short_title"];
			$proj_id = $row["project_id"];
//			$projCode = $row["proj_code"];

//			$budgetArr = array();
			$whereArr = array("1");

			if ($budget_yearID != ""){
				if ($budget_yearID > 0) {
					$budget_year = CHEprojects::getValueFromTable("lkp_budget_year", "lkp_budget_year_id", $budget_yearID, "lkp_budget_year");
					array_push($whereArr,"r.budget_year LIKE '".$budget_year."'");
				}
			}
			$where = " AND " . implode(" AND ", $whereArr);

			$budgetSQL = <<<FINANCE
				SELECT r.budget_year,
						r.proj_code,
						b.planned_budget,
						b.revised_budget
				FROM project_required_list as r
				LEFT JOIN `project_budget_per_year` as b
					ON (r.project_ref = b.project_ref AND r.budget_year = b.budget_year)
				WHERE r.project_ref = $proj_id
				$where
FINANCE;
//			$budgetSQL .= $where;
//echo $budgetSQL;
			$budgetRs = mysqli_query($budgetSQL) or die(mysqli_error());

			$budget = "";
			$code   = "";
			$planned = "";
			$revisedStr = "";
			$expStr = "";
			$pSpent = "";
			$counter = 0;

			// Only print a row if the budget and finacial information is available for that project.
			if (mysqli_num_rows($budgetRs) > 0){

				while ($yRow = mysqli_fetch_array($budgetRs))
				{

					$budgYear = $yRow["budget_year"];
					$proj_code = $yRow["proj_code"];

					$budgetBr = ($counter > 0) ? "<br />" : "";
					$codeBr = ($counter > 0) ? "<br />" : "";
					$plannedBr = ($counter > 0) ? "<br />R " : "";
					$revisedBr = ($counter > 0) ? "<br />R " : "";
					$expBr = ($counter > 0) ? "<br />R " : "";
					$pSpentBr = ($counter > 0) ? "<br />" : "";

					$budget .= $budgetBr.$budgYear;
					$code .= $codeBr.$proj_code;

					$planned .= $plannedBr.sprintf('%.2f',$yRow["planned_budget"]);
					$revised = sprintf('%.2f',$yRow["revised_budget"]);
	//						$revised = sprintf('%.2f',CHEprojects::getValueFromTable("project_budget_per_year", "project_ref", $proj_id, "revised_budget"));
					$revisedStr .= $revisedBr.$revised;
					$exp = sprintf('%.2f',CHEprojects::calculateExpenditure($budgYear, $proj_code));
					$expStr .= $expBr.$exp;
					$pSpent .= ($revised > 0) ? $pSpentBr.sprintf("%d", ($exp / $revised)*100)."%" : $pSpentBr."0%";

					$counter++;

				} //end while finance

				switch($format) {
					case "table" :
							$budgetTable .=<<<TABLE
									<tr class="onblue">
										<td valign="top">$projTitle</td>
										<td valign="top" align="center">$budget</td>
										<td valign="top" align="right">$code</td>
										<td valign="top" align="right">R $planned</td>
										<td valign="top" align="right">R $revisedStr</td>
										<td valign="top" align="right">R $expStr</td>
										<td valign="top" align="center">$pSpent</td>
									</tr>
TABLE;
							break;
					case "docgen" :
							$budgetTable .=<<<TABLE
									<tr>
										<td>$projTitle</td>
										<td width="11%">$budget</td>
										<td width="15%" align="right">$code</td>
										<td width="15%" align="right">R $planned</td>
										<td width="15%" align="right">R $revisedStr</td>
										<td width="15%" align="right">R $expStr</td>
										<td width="8%" align="center">$pSpent</td>
									</tr>
TABLE;
						break;
				} //end switch
			} // end if financial info exists

		} //end while project
	}


	switch($format) {
		case "table" :
				$budgetTable .=<<<TABLE
				</table>
				<br>
TABLE;
				break;
		case "docgen" :
				$budgetTable .=<<<TABLE
				</table>
				<br />
TABLE;
				break;
	}
	echo $budgetTable;
}

function displayProjectIndicatorReport($format, $sec, $budget_yearID, $budget_year) {
		$sql = "SELECT * FROM project_detail as d";
		$whereArr = array(1);

		// Users are restricted as to which projects they may see
		if ($sec["filter"] > ""){
			array_push ($whereArr, $sec["filter"]);
		}

		$where = " WHERE " . implode(" AND ", $whereArr);
		$order = " ORDER BY directorate_ref, project_short_title";
		$sql .=  $where . $order;
		$pageCount = 0;

		CHEprojects::displayBudgetSummaryTable($format, $sql, $budget_yearID);

		$rs = mysqli_query($sql);
		if (mysqli_num_rows($rs) > 0)
		{
			switch($format) {
				case "table"  :	echo '<br /><span class="specialh">Performance Indicators tables:</span><hr width="60%" align="left">';
								break;
				case "docgen" : echo '<section landscape="yes" />';
								break;

			}

			while ($row = mysqli_fetch_array($rs))
			{

				$page = ($pageCount == 0) ? "" : "<page />";
				$dir_description = CHEprojects::getDirectorate($row["directorate_ref"]);
//				$projCode = $row["proj_code"];
				$projShort = $row["project_short_title"];
				$projFull = $row["project_full_title"];
				$projID = $row["project_id"];
				$budgetArr = array("1");

			if ($budget_yearID != ""){
				if ($budget_yearID > 0) {
					array_push($budgetArr,"r.budget_year = '".$budget_year."'");
				}
			}
			$where = " AND " . implode(" AND ", $budgetArr);

			$ySQL =<<< SQL
				SELECT r.budget_year,
				r.project_ref,
				r.proj_code,
				d.capacity_development,
				d.stakeholder_feedback,
				d.outputs_deliverables
				FROM project_required_list as r
				LEFT JOIN project_detail_per_year AS d
					ON (r.project_ref = d.project_ref AND r.budget_year = d.budget_year)
				WHERE r.project_ref=$projID
				$where
SQL;
			$yRS = mysqli_query($ySQL);
			$count = 0;
			if (mysqli_num_rows($yRS) > 0) {
				switch($format) {
					case "table" :	$html =<<< TXT
									<table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
									<tr valign="top" align="left" class="onblue">
									<td width="15%" class="specialb">Programme:</td><td>$dir_description</td>
									</tr>
									<tr valign="top" align="left" class="onblue">
									<td class="specialb">Project Title (Short):</td><td>$projShort</td>
									</tr>
									<tr valign="top" align="left" class="onblue">
									<td class="specialb">Project Title:</td><td>$projFull</td>
									</tr>
									<tr>
										<td colspan="2">
TXT;
									break;
					case "docgen" :	$html =<<< TXT
									$page
									<table width="145%" border="t,b,l,r" align="center">
										<tr valign="top" align="left" bgcolor="5">
											<td width="15%"><b>Programme:</b></td><td><b>$dir_description</b></td>
										</tr>
										<tr valign="top" align="left" bgcolor="5">
											<td><b>Project Title (Short):</b></td><td><b>$projShort</b></td>
										</tr>
										<tr valign="top" align="left" bgcolor="5">
											<td><b>Project Title:</b></td><td><b>$projFull</b></td>
										</tr>
										<tr>
											<td colspan="2">
TXT;
									break;
				}
				$pageCount++;
				echo $html;
			}
// Robin 22 Sep 2008
// Commented out because it is showing projects that are not specified for the year.
//			else {
//				switch($format) {
//					case "table" :	$html =<<< TXT
//									<table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
//									<tr valign="top" align="left" class="onblue">
//									<td width="15%" class="specialb">Directorate:</td><td>$dir_description</td>
//									</tr>
//									<tr valign="top" align="left" class="onblue">
//									<td class="specialb">Project Title (Short):</td><td>$projShort</td>
//									</tr>
//									<tr valign="top" align="left" class="onblue">
//									<td class="specialb">Project Title:</td><td>$projFull</td>
//									</tr>
//									<tr>
//										<td colspan="2">- No data for the selected budget year -</td>
//									</tr>
//									</table>
//									<br>
//TXT;
//									break;
//					case "docgen" :	$html =<<< TXT
//									$page
//									<table width="145%" border="t,b,l,r" align="center">
//										<tr valign="top" align="left" bgcolor="5">
//											<td width="15%"><b>Directorate:</b></td><td><b>$dir_description</b></td>
//										</tr>
//										<tr valign="top" align="left" bgcolor="5">
//											<td><b>Project Title (Short):</b></td><td><b>$projShort</b></td>
//										</tr>
//										<tr valign="top" align="left" bgcolor="5">
//											<td><b>Project Title:</b></td><td><b>$projFull</b></td>
//										</tr>
//										<tr>
//											<td colspan="2">- No data for the selected budget year -</td>
//										</tr>
//										</table>
//TXT;
//									break;
//				}
//				echo $html;
//			}

			while ($yRow = mysqli_fetch_array($yRS)) {

				$budget_year = $yRow["budget_year"];
				$count++;
				$horizontalRule = ($count == 1) ? "" : "<br /><hr width='95%'>";

				$budget = CHEprojects::getBudget($budget_year, $yRow["project_ref"]);
				$revised_budget = $budget["revised"];
				$revised_budgetStr = sprintf("%.2f", $revised_budget);
				$planned_budget = $budget["planned"];
				$planned_budgetStr = sprintf("%.2f", $planned_budget);
//				$year = ($budget_year > "") ? $budget_year : $budget_year;
				$expenditure = CHEprojects::calculateExpenditure($budget_year, $yRow["proj_code"]);
				$expenditureStr = sprintf("%.2f", $expenditure);
				$pSpent = ($revised_budget > 0) ? sprintf("%d", ($expenditure / $revised_budget)*100) : "0";
				$capacity_development = ($yRow["capacity_development"] > "") ? simple_text2html($yRow["capacity_development"]) : "- No data -";
				$stakeholder_feedback = ($yRow["stakeholder_feedback"] > "") ? simple_text2html($yRow["stakeholder_feedback"]) : "- No data -";
				$outputs_deliverables = ($yRow["outputs_deliverables"] > "") ? simple_text2html($yRow["outputs_deliverables"]) : "- No data -";

				switch($format) {
					case "table" :	$html =<<< TXT
						$horizontalRule
						<table border="0" width="100%">
						<tr valign="top" align="left">
							<td class="specialb" width="20%">Budget year(s):</td><td>$budget_year</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb">Planned budget:</td><td>R $planned_budgetStr</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb">Revised budget:</td><td>R $revised_budgetStr</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb">Expenditure:</td><td>R $expenditureStr</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb">Percent spent:</td><td>$pSpent%</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb" colspan="2">Capacity development:</td>
						</tr>
						<tr>
							<td colspan="2">$capacity_development</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb" colspan="2">Stakeholder feedback:</td>
						</tr>
						<tr>
							<td colspan="2">$stakeholder_feedback</td>
						</tr>
						<tr valign="top" align="left">
							<td class="specialb" colspan="2">Outputs/deliverables:</td>
						</tr>
						<tr>
							<td colspan="2">$outputs_deliverables</td>
						</tr>
						</table>
TXT;
						break;

					case "docgen" :	$html =<<< TXT
						<table border="0" width="140%">
						<tr valign="top" align="left" bgcolor="5">
							<td width="20%"><b>Budget year(s):</b></td><td>$budget_year</td>
						</tr>
						<tr valign="top" align="left">
							<td><b>Planned budget:</b></td><td>R $planned_budgetStr</td>
						</tr>
						<tr valign="top" align="left">
							<td><b>Revised budget:</b></td><td>R $revised_budgetStr</td>
						</tr>
						<tr valign="top" align="left">
							<td><b>Expenditure:</b></td><td>R $expenditureStr</td>
						</tr>
						<tr valign="top" align="left">
							<td><b>Percent spent:</b></td><td>$pSpent%</td>
						</tr>
						<tr valign="top" align="left">
							<td colspan="2"><b>Capacity development:</b></td>
						</tr>
						<tr>
							<td colspan="2">$capacity_development</td>
						</tr>
						<tr valign="top" align="left">
							<td colspan="2"><b>Stakeholder feedback:</b></td>
						</tr>
						<tr>
							<td colspan="2">$stakeholder_feedback</td>
						</tr>
						<tr valign="top" align="left">
							<td colspan="2"><b>Outputs/deliverables:</b></td>
						</tr>
						<tr>
							<td colspan="2">$outputs_deliverables</td>
						</tr>
						</table>
TXT;
						break;
				}

				echo $html;
			} //end while ($yRow)
			if (mysqli_num_rows($yRS) > 0) {
				switch($format) {
					case "table" : 	echo '</td></tr></table><br />';
									break;
					case "docgen":	echo '</td></tr></table>';

									break;
				}
			}

		}//end while ($row

	}//end if (mysqli_num_rows($rs)

}

function getProjectCodes($proj_id){
	$prjcode_arr = array();

	$sql = <<<PROJCODE
		SELECT *
		FROM project_required_list
		WHERE project_ref = $proj_id;
PROJCODE;

	$rs = mysqli_query($sql);
	$i = 0;
	while ($row = mysqli_fetch_array($rs)){
		$prjcode_arr[$i] = $row["budget_year"].": ".$row["proj_code"]."<br>";
		$i++;
	}

	return $prjcode_arr;
}

	function mkDropdownArray ($name, $tbl, $fld, $ord) {

		$jscript_array = "$name = new Array();";

		$sql = <<<SQL
			SELECT $fld
			FROM $tbl
			ORDER BY $ord
SQL;
		$rs = mysqli_query($sql);

		$oldFilter = "";
		while ($row = mysqli_fetch_array($rs)) {
			if ($oldFilter != $row[0]) {
				$jscript_array .= $name."[\"".$row[0]."\"] = new Array();";
				$oldFilter = $row[0];
			}
			$jscript_array .= $name . '["'. $row[0] .'"]["'.$row[1].'"] = new Array("' . trim($row[2]) . '");';
		}

		return $jscript_array;
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

	function displayIndicatorHeader ($ind_id){
		$n = 0;
		$html = "No valid Indicator is currently selected.";

		$sql = <<<CBOSQL
		SELECT *
		FROM lkp_indicator
		WHERE lkp_indicator_id = $ind_id 
CBOSQL;

	$rs = mysqli_query($sql);
	if ($rs) $n = mysqli_num_rows($rs);
	if ($n > 0){
		$row = mysqli_fetch_array($rs);

		$html = <<<CBOHEAD
			<br>
			<span class="speciale">&nbsp;Import list for indicator: $row[indicator_desc]</span>
			<hr>
CBOHEAD;
	}
	echo $html;
	}
// END of Class
}
?>