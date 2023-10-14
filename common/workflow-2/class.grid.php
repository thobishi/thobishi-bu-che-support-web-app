<?php

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

class grid extends workFlow {

	public function __construct($flowID) {
		
		parent::__construct($flowID);
	
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
		$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld." = :keyFldValue";
		$rs = $this->db->query($SQL, compact('keyFldValue'));
	}

	function doPopulateGridFromTemplateTable ($parentTable, $parentTable_id, $childTable, $childKeyFld, $childRef, $fieldsArr, $lookup_template_table) {
		$RS = $this->db->query("SELECT * FROM `".$lookup_template_table."`");
		$num_rows = $RS->rowCount();
		while ($template_table_row = $RS->fetch()) {
			$init_RS = $this->db->query("INSERT INTO `".$childTable."` (".$childRef.") VALUES (:parentTable_id)", compact('parentTable_id'));
			$lastId = $this->db->lastInsertId();
			foreach ($fieldsArr AS $initK=>$initV) {
				$params = array(
					'tempTableRow' => $template_table_row[$initK],
					'parentTable_id' => $parentTable_id,
					'lastId' => $lastId
				);
				$this->db->query("UPDATE `".$childTable."`  SET ".$initK." = :tempTableRow WHERE ".$childRef."= :parentTable_id AND ".$childKeyFld."= :lastId");
			}
		}
	}

	/*
	 * Louwtjie: function to create the headings for the grid functions.
	*/
	function createHTMLGridHeading ($headingArr, $emptyCol=0) {
		echo '<thead>';
		echo '<tr>';
		foreach ($headingArr as $value){
			$style = "";
			if (stristr($value,":vertical")) {
				$value = substr($value, 0, strpos($value,":vertical"));
				$style = " filter: flipv fliph; writing-mode: tb-rl; text-align:left";
			}
			echo "<th style='".$style."' class='oncolourb' align='center'>\n";
			echo $value;
			echo "</th>\n";
		}
		if ($emptyCol > 0) echo "<th>&nbsp;</th>";
		echo '</tr>';
		echo '</thead>';
	}

	/*
	 * Louwtjie: function to create the main SQL for the grid functions.
	*/
	function createHTMLGridSQL ($table, $keyFLD, $unique_flds_array) {
		$params = array();
		$main_SQL = "SELECT * FROM ".$table;
		if (count($unique_flds_array) > 0) {
			$andArr = array();
			for ($i=0; $i < count($unique_flds_array); $i++) {
				$params[$i] = $unique_flds_array[$i][1];
				array_push($andArr, $unique_flds_array[$i][0]."= ?");
			}
			$main_SQL .= " WHERE ".implode (" AND ", $andArr);
		}
		$main_SQL .= " ORDER BY ".$keyFLD;
		$RS = $this->db->query($main_SQL, $params);
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

			$lkp_RS = $this->db->query($lkp_SQL);
			while ($lkp_RS && ($row=$lkp_RS->fetch)) {
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

			$rs = $this->db->query($SQL) or $errorMail = true;
			$error = $rs->errorInfo();
			$this->AuditLog->writeLogInfo(10, "SQL", $SQL."  --> " . $error[2], $errorMail);
			$this->AuditLog->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
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
		$rs = $this->db->query($SQL) or $errorMail = true;
		$this->AuditLog->writeLogInfo(10, "SQL", $SQL."  --> ". $this->db->lastError[2], $errorMail);
		$this->AuditLog->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
	}

	/*
	 * Louwtjie: function to create the fields for the grid functions.
	*/
	function createHTMLGridFields ($row, $table, $keyFLD, $fieldArr, $cols=40, $rows=5, &$fieldName, &$fieldValue, &$fieldType, &$fieldSize, &$fieldStatus, &$fld_lkp_desc, &$fld_lkp_key, &$fld_lkp_table, &$fld_lkp_condition, &$fld_lkp_order_by,&$fieldNullValue, $exceptions) {
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
		
		$exceptionField = '';
		if(!empty($exceptions) && in_array($html_arr['name'], $exceptions['valueFields'])){
			$exceptionField = $html_arr['name'];
		}
		
		//Here, we start building the rows, field by field.
		//the following is the bare minimum properties for a field.
		$fieldName = (empty($exceptions)) ? ((isset($html_arr["name"]))?('GRID_'.$row[$keyFLD].'$'.$keyFLD.'$'.$html_arr["name"].'$'.$table):("")) : ((isset($html_arr["name"])) ? ('GRID_' . ($this->exceptionsCheck($exceptions, $row, $row[$keyFLD], $exceptionField)) . '$' . ($exceptions['updateField']) . '$' . $html_arr["name"] . '$' . ($exceptions['table'])) : (""));
		$fieldValue = (isset($html_arr["name"])) ? (isset($row[$html_arr["name"]]) ? $row[$html_arr["name"]] : $this->lookupException($exceptions, $row, $exceptionField)) : ("");
		$fieldType = (isset($html_arr["type"]))?($html_arr["type"]):("");
		$fieldSize = (isset($html_arr["size"]))?($html_arr["size"]):("");
		$fieldStatus = (isset($html_arr["status"]))?($html_arr["status"]):("");
		
		//the following needs to be checked if it is a select or radio type field.

		$fld_lkp_desc = (isset($html_arr["description_fld"]))?($html_arr["description_fld"]):("");
		$fld_lkp_key = (isset($html_arr["fld_key"]))?($html_arr["fld_key"]):("");
		$fld_lkp_table = (isset($html_arr["lkp_table"]))?($html_arr["lkp_table"]):("");
		$fld_lkp_condition = (isset($html_arr["lkp_condition"]))?($html_arr["lkp_condition"]):("");
		$fld_lkp_order_by = (isset($html_arr["order_by"]))?($html_arr["order_by"]):("");
		$fieldNullValue = (isset($html_arr["default"]))?($html_arr["default"]):("");
		//we don't have solid rules for checkbox values so if it doesn't have a value, assign value 1 to the checkbox
		//check if the page is just viewed or needs to be saved.
		
		if ($fieldType == "checkbox" && isset($row[$html_arr["name"]])) {
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
		if (($fieldType == "checkbox") && ($fieldValue == 1 || (isset($row[$html_arr["name"]]) && $row[$html_arr["name"]] == 1))) {
			$this->formFields[$fieldName]->fieldOptions = "checked";
		}

		//create the select or radio type field's options
		if (($fieldType == "select") || ($fieldType == "radio")) {
			$fld_lkp_SQL = "SELECT ".$fld_lkp_desc.", ".$fld_lkp_key." FROM ".$fld_lkp_table." WHERE ".$fld_lkp_condition." ORDER BY ".$fld_lkp_order_by."";
			$fld_lkp_RS = $this->db->query($fld_lkp_SQL);
			while ($fld_lkp_RS && ($fld_lkp_row = $fld_lkp_RS->fetch())) {
//zoology   		
				$this->formFields[$fieldName]->fieldValuesArray[$fld_lkp_row[$fld_lkp_key]] = $fld_lkp_row[$fld_lkp_desc];
				$this->formFields[$fieldName]->fieldNullValue = $fieldNullValue;
			}
		}
		 
		return $html_arr;
	}
	
	function buildExceptionsWhere($exceptions, $row){
		$where = $exceptions['updateField'] . ' = ' . $row[$exceptions['lookupTableField']];
		$where .= (isset($exceptions['and'])) ? ' AND ' . $exceptions['and'] : '';
		$where .= (isset($exceptions['or'])) ? ' OR ' . $exceptions['or'] : '';
		
		return $where;
	}
	
	function exceptionsCheck($exceptions, $row, $rowID, $exceptionField){
		$return = "MULTI";
		
		if(!empty($exceptions) && !empty($row)){
			$where = $this->buildExceptionsWhere($exceptions, $row);
			$sql = 'SELECT ' . $exceptionField . ' FROM ' . $exceptions['table'] . ' WHERE ' . $where;
			$rs = $this->db->query($sql);
			$rowCount = $rs->rowCount();
			$return = $return . $rowID;
		}
		
		return $return;
	}
	
	function lookupException($exceptions, $row, $exceptionField){
		$return = '';
		
		if(!empty($exceptions) && !empty($row)){
			$where = $this->buildExceptionsWhere($exceptions, $row);
			$sql = 'SELECT ' . $exceptionField . ' FROM ' . $exceptions['table'] . ' WHERE ' . $where;
			$rs = $this->db->query($sql);
			while ($lkp_row = $rs->fetch()) {
				$return = $lkp_row[$exceptionField];
			}
		}
		
		return $return;
	}

	/*Louwtjie:
		Make Grid from Database
	*/
	function gridShow ($table, $key_fld, $unique_flds, $fields_arr, $html_table_headings_arr, $lkp_row_table="", $lkp_row_id="", $lkp_row_desc="", $lkp_row_ref="", $lkp_row_desc_col=1, $cols=40, $rows=5, $createFileUpload=false, $fileUpload_name="", $exceptions=array()) {
		
		$unique_flds_array = $this->doubleExplode ($unique_flds);
		
		//First of all, we need to select the rows out of the database to see whether it exists or not.
		//That's why we need the unique fields to uniquely identify the rows we're working with.
		$RS = $this->createHTMLGridSQL ($table, $lkp_row_ref, $unique_flds_array);
		
		//actual number of rows we have in DB
		$actual_rows = $RS->rowCount();

		$lookup_sql = "SELECT ".$lkp_row_id." FROM ".$lkp_row_table;
		$lookup_rows = $this->db->query($lookup_sql)->rowCount();

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
		while ($row = $RS->fetch()) {
			// $this->pr($row);
			// $this->pr($fields_arr);
			$rowID = $row[$key_fld];
			echo "<tr>\n";
			for ($i=0; $i < count($fields_arr); $i++) {
				//check if we have a column with text from lookup table
				//if we are at the correct column, print the lookup text.
				$count = $i+1;
				if ($lkp_row_table > "") {
					if ($count == $lkp_row_desc_col) {
						$lkp_SQL = "SELECT ".$lkp_row_desc." FROM ".$lkp_row_table." WHERE ".$lkp_row_id."='".$row[$lkp_row_ref]."'";
						$lkp_rs = $this->db->query($lkp_SQL);
						if ($lkp_rs && ($lkp_row = $lkp_rs->fetch())) {
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
				$fieldNullValue = "";
				$html_arr = $this->createHTMLGridFields ($row, $table, $key_fld, $fields_arr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by,$fieldNullValue, $exceptions);

				echo "<td valign='top'>\n";
				
				//exceptions to save a checkbox that is not checked
				if($fieldType == "checkbox" && !empty($exceptions)){
					echo '<input name="' . $fieldName . '" type="HIDDEN" value="0" />';
				}
				//print the field after all properties have been set.
				$this->showField($fieldName);

				//if this is the last field, print the hidden save field aswell
				if(!empty($exceptions)){
					echo '<input type="HIDDEN" name="' . $this->createMultiGridInsert($exceptions, $rowID) . '" value="1">' . "\n";
				}
				elseif(($i == (count($fields_arr)-1)) && !($createFileUpload)) {
					echo "<input type='HIDDEN'  name='GRID_save_".$rowID."' value='1'>\n";
				}

				echo "</td>\n";
				//temporary solution for checkboxes to become unchecked.
				if (($fieldType == "checkbox") && !($this->view == 1) && !(in_array($fieldName, $exceptions['valueFields']))) {
					if(empty($exceptions)){
						$this->db->setValueInTable($table, $key_fld, $row[$key_fld], $html_arr["name"], 0, $this->view);
					}
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
	
	function createMultiGridInsert($exceptions, $rowID){
		$return = '';
		$fields = implode('$', $exceptions['saveFields']);
		
		if(!empty($exceptions['saveValues'])){
			foreach($exceptions['saveValues'] as &$value){
				$value = (empty($value)) ? $rowID : $value;
			}
		}
		
		$values = implode('$', $exceptions['saveValues']);

		$return = 'GRID_MULTI' . $rowID . '_save_' . $exceptions['table'] . '_|_' . $fields . '_|_' . $values;
		
		return $return;
	}
	
	function existsExceptionsData($exceptions){
		$updateArray = array();
		$value = '';
		
		if(!empty($exceptions)){
			$primaryKey = '';
			$primaryKeySQL = 'SHOW KEYS FROM ' . $exceptions['table'] . ' WHERE Key_name = \'PRIMARY\'';
			$primRS = $this->db->query($primaryKeySQL);
			$rowPrim = $primRS->fetch();
			$primaryKey = ($primRS->rowCount() > 0) ? $rowPrim['Column_name'] : 'id';
			$lkpFld = $exceptions['saveFields'][0];
			$lkpValue = $exceptions['saveValues'][0];
			$sql = 'SELECT ' . $lkpFld . ', ' . $primaryKey . ', ' . $exceptions['saveFields'][1] . ' FROM ' . $exceptions['table'] . ' WHERE ' . $lkpFld . ' = :lkpValue';
			$rs = $this->db->query($sql, compact('lkpValue'));
			while($row = $rs->fetch()){
				$updateArray[] = $primaryKey . '$' . $row[$primaryKey] . '_|_' . $exceptions['saveFields'][1] . '$' . $row[$exceptions['saveFields'][1]] . '_|_' . $lkpFld . '$' . $row[$lkpFld];
			}
			$value = (!empty($updateArray)) ? implode('__', $updateArray) : '';
		}
		
		echo (!empty($value)) ? '<input name="UPDATE_INDICATOR_MULTI_GRID" type="HIDDEN" value="' . $value . '" />' : '';
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
		$fieldNullValue = "";
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);

		$num_rows = $RS->rowCount();

		if ($dfltRow == 1){
			//we must have at least 1 row in the database:
			if (! ($num_rows > 0) ) {
				$this->createHTMLGridInsertWithoutLookup ($table, $unique_flds_array);
			}
		}
		$this->createHTMLGridHeading ($headingArr, 1);

		//we do the main query again, maybe we inserted some rows.
	
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array,$ordFLD);
		while ($RS && ($row = $RS->fetch())) {
			$rowID = $row[$keyFLD];
			echo "<tr>\n";
			for ($i=0; $i < count($fieldArr); $i++) {
				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $fieldArr[$i], 40, 5, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition,$fld_lkp_order_by,$fieldNullValue, '');

				echo "<td valign='top'>\n";
				//print the field after all properties have been set.
				$this->showField($fieldName);

				//if this is the last field, print the hidden save field aswell
				if ($i == (count($fieldArr)-1)) {
					echo "<input type='HIDDEN'  name='GRID_".$rowID."_save_".$table."' value='1'>\n";
				}

				echo "</td>\n";
				//temporary solution for checkboxes to become unchecked.
				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->db->setValueInTable($table, $key_fld, $row[$keyFLD], $html_arr["name"], 0, $this->view);
				}
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
		$fieldNullValue = "";
		
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);

		//we must have at least 1 row in the database:
		if (! ($RS->rowCount() > 0) ) {
			$this->createHTMLGridInsertWithoutLookup ($table, $unique_flds_array);
		}

		$tableStyle = array("oncolourswitcha", "oncolourswitchb");
		$tableStyleCount = 0;

		$skipHead = 0;
		$RS = $this->createHTMLGridSQL ($table, $keyFLD, $unique_flds_array);
		echo '<table class="table table-bordered"><tr><td>';
		while ($RS && ($row=$RS->fetch())) {
			if ($tableStyleCount == 0) {
				$tableStyleCount = 1;
			}else	if ($tableStyleCount == 1) {
				$tableStyleCount = 0;
			}

			$count = 0;
			echo "	<table cellpadding='2' class='table table-hover table-bordered table-striped' width='100%'>\n";
			foreach ($fieldArr AS $value) {
				echo "		<tr>\n";
				echo "			<td width='33%' valign='top' align='right'><b>".$headingArr[$count]."</b></td>\n";
				echo "			<td valign='top'>";

				$html_arr = $this->createHTMLGridFields ($row, $table, $keyFLD, $value, $cols, $rows, $fieldName, $fieldValue, $fieldType, $fieldSize, $fieldStatus, $fld_lkp_desc, $fld_lkp_key, $fld_lkp_table, $fld_lkp_condition, $fld_lkp_order_by,$fieldNullValue, '');
				$this->showField ($fieldName);

				if (($fieldType == "checkbox") && !($this->view == 1)) {
					$this->db->setValueInTable($table, $key_fld, $row[$keyFLD], $html_arr["name"], 0, $this->view);
				}

				echo "			</td>\n";
				echo "		</tr>\n";
				
				$count++;
			}
			
			echo '<tr><td colspan=2><input type="HIDDEN" name="GRID_' . $row[$keyFLD] . '_save_' . $table . '" value="1"></td></tr>';
			
// 2008-09-18 Robin
// Added $table to field name because if two grids on the same page. They could have same keys e.g. both record 1 in two different tables.
// This causes a conflict and the second grid is not updated.
//			echo "<input type='HIDDEN'  name='GRID_save_".$table."_".$rowID."' value='1'>\n";

			// display link to delete a record if requested
			if ($showButtons){
				echo "<tr>";
				echo "<td colspan=2 class=\"deleteGridTable\" valign='top'>\n";
				echo "<a class='tableByRowDel' id='" . $table . "-" . $row[$keyFLD] . "-del' href='javascript:changeCMD(\"del|" . $table."|".$keyFLD."|".$row[$keyFLD]."\");moveto(\"stay\")'>&nbsp;Delete Entry&nbsp;</a>";
				echo "</td>";
				echo "</tr>";
			}
			echo "	</table>";
		}
		
		$addLinkText = ($addRowText>"")?(" - ".$addRowText):("");
		if ($showButtons) {
			// Max of two extra unique fields can be inserted into a grid.
			$unique_pair_array = array();
			foreach($unique_flds_array as $uf){
				array_push($unique_pair_array, implode('|',$uf)) ;
			}
			$unique_fields_str = implode('|',$unique_pair_array);
		
			echo "<tr>";
			echo "<td align='right'><a class='tableByRowAdd' id='" . $table . "-add' href='javascript:changeCMD(\"new|".$table."|".$unique_fields_str."\");moveto(\"stay\")' ref='".$unique_flds_array[0][0]."$".$unique_flds_array[0][1]."'>&nbsp;Add" . $addLinkText . "&nbsp;</a></td>";
			echo "</tr>";
			
		}
		echo  "</td></tr>";
		
		echo '<tr style="display:none;"><td><input name="GRID_deleted|'.$table.'|'.$keyFLD.'" id="'.$table.'-deleted" type="hidden"/></td></tr>';
		
		echo "</table>";
		
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
			$rs = $this->db->query("SELECT count(*) as `count` FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id)->fetch();
			$rows_rs = $rs['count'];
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
		$rs = $this->db->query($SQL);
		$message = "Click on the 'Add' link in the rightmost column of this table in order to add a row in which to supply the relevant information. <i>Note that you can add multiple rows</i>.";
		$field_size = $sizeOfFld;
		while ($row = $rs->fetch()) {
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
					$select_RS = $this->db->query($sql);
					while ($select_RS && ($select_row=$select_RS->fetch())) {
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
					$select_RS = $this->db->query($select_SQL);
					while ($select_RS && ($select_row=$select_RS->fetch())) {
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
					$RS = $this->db->query($SQL_del);
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
		$count = $this->db->query("SELECT count(*) as count FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id)->fetch();
		if (($add_initial_DB_row) && !($count['count'])) {
			$init_RS = $this->db->query("INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
		}

		$SQL = "SELECT * FROM `".$childTable."` WHERE ".$where." ".$childRef."=".$parentTable_id." ORDER BY ".$childKeyFld;

		$rs = $this->db->query($SQL);
		$array_keys = array_keys($fieldsArr);
		if (is_array($tableHeading) && (count($tableHeading) > 0)) {
			$tmp = array_keys($tableHeading);
		}

		$content = "\n\n";
		$content .=  "<table width='95%' align='center' class='oncoloursoft' cellpadding='2' cellspacing='2' border='1'><tr><td>\n";

		$tableStyle = array("oncolourswitcha", "oncolourswitchb");
		$tableStyleCount = 0;

		$skipHead = 0;

		while ($row = $rs->fetch()) {
			if ($tableStyleCount == 0) {
				$tableStyleCount = 1;
			}else	if ($tableStyleCount == 1) {
				$tableStyleCount = 0;
			}
			$content .= "	<table class='".$tableStyle[$tableStyleCount]."' width='100%'>\n";
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
						$select_RS = $this->db->query($select_SQL);
						while ($select_RS && ($select_row=$select_RS->fetch())) {
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
						$select_RS = $this->db->query($select_SQL);
						while ($select_RS && ($select_row=$select_RS->fetch())) {
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
						$RS = $this->db->query($SQL_del);
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

		$RS = $this->db->query($main_SQL);

		//actual number of rows we have in DB
		$actual_rows = $RS->rowCount();

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
				$rs = $this->db->query($SQL);
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
		$RS = $this->db->query($main_SQL);
		while ($row = $RS->fetch()) {
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
		NEW MULTIGRID FUNCTIONALITY START
	*/
	
	function getMultipleRCGridValues($tableInfo){
		$gridValues = array();
		
		$sql = "SELECT * FROM `" . $tableInfo['name'] . "` WHERE " . $tableInfo['WHERE'];

		$rs = $this->db->query($sql);
		
		while($row = $rs->fetch()){
			foreach($tableInfo['fields_grouped'] as $group){
				$index = array();
				foreach($group as $field){
					if(isset($row[$field])){
						$insertValue = (in_array($field, $tableInfo['value_fields'])) ? $field : $row[$field];
						array_push($index, $insertValue);
					}
				}
				$index = implode('|', $index);
				foreach($tableInfo['value_fields'] as $saveValue){
					if(in_array($saveValue, $group) && $row[$saveValue] != 0){
						$gridValues[$index] = $row[$saveValue];
					}
				}
			}
		}
		
		return $gridValues;
	}
	
	function getSaveFieldDetails($template, $fieldsTosave){
		$saveFieldDetails = array();
		$fields = implode("', '", $fieldsTosave);
		$sql = "SELECT * FROM template_field WHERE template_name = '" . $template . "' AND fieldName IN('" . $fields . "');";
		
		$rs = $this->db->query($sql);
		
		while($row = $rs->fetch()){
			$saveFieldDetails[$row['fieldName']]['type'] = $row['fieldType'];
			$saveFieldDetails[$row['fieldName']]['size'] = $row['fieldSize'];
			$saveFieldDetails[$row['fieldName']]['class'] = $row['fieldClass'];
		}
		
		return $saveFieldDetails;
	}
	
	function createFieldsToPass($fieldsGrouped){
		$return = array();
		
		foreach($fieldsGrouped as $fields){
			foreach($fields as $field){
				array_push($return, $field);
			}
		}
		
		$return = (!empty($return)) ? array_unique($return) : $return;
		
		return $return;
	}
	
	function mapRowKeys($lookupRows){
		$rowMaps = array();
		
		foreach($lookupRows as $label => $table){
			$rs = $this->db->query("SELECT * FROM `" . $table . "`");
			
			while($row = $rs->fetch()){
				$rowMaps[$row[0]] = $row[1];
			}
		}
		
		return $rowMaps;
	}
	
	function multipleRCGrid($lookupCols, $lookupRows, $fieldsTosave, $totals, $tableInfo, $template){
		$columnInfo = array();
		$rowInfo = array();
		$populateValues = $this->getMultipleRCGridValues($tableInfo);
		$saveFieldDetails = $this->getSaveFieldDetails($template, $fieldsTosave);
		$fieldsToPass = $this->createFieldsToPass($tableInfo['fields_grouped']);
		$rowMaps = $this->mapRowKeys($lookupRows);
		
		if(!empty($lookupCols)){
			foreach($lookupCols as $colOrder => $lookupColumn){
				$columnInfo[$colOrder] = $this->multipleRCGridFetch($lookupColumn);
			}
		}
		
		if(!empty($lookupRows)){
			foreach($lookupRows as $heading => $lookupRow){
				$rowInfo[$heading] = $this->multipleRCGridFetch($lookupRow);
			}
		}
		
		$theadData = $this->multipleaRCGridCreateHead($columnInfo, $rowInfo, $fieldsTosave, $totals);
		
		$table = '<table class="table table-hover table-bordered table-striped multigrid">';
		$table .= '<thead>';
		$table .= $theadData['head'];
		$table .= '</thead>';
		$table .= '<tbody>';
		$table .= $this->multipleaRCGridCreateBody($columnInfo, $rowInfo, $fieldsTosave, $totals, $theadData['grid'], $tableInfo, $populateValues, $saveFieldDetails, $rowMaps);
		$table .= '</tbody>';
		$table .= '</table>';
		$table .= '<input type="HIDDEN"  name="MULTIGRID_save|' . $tableInfo['name'] . '|' . $tableInfo['key'] . '|' . $tableInfo['key_value'] . '$' . implode("$", $fieldsTosave) . '$' . implode('|', $fieldsToPass) . '" value="1" />';
		echo $table;
	}
	
	function multipleRCGridFetch($lkp_table){
		$return = array();
		$rs = $this->db->query("SELECT * FROM `" . $lkp_table . "`");
		
		while($row = $rs->fetch()){
			array_push($return, $row);
		}
		
		return $return;
	}
	
	function multipleaRCGridCreateHead($columnInfo, $rowInfo, $fieldsTosave, $totals){
		$tHead = array(
			'head' => '',
			'data' => array()
		);
		$numberCols = count($columnInfo);
		$numberRows = count($rowInfo);
		$numberSave = count($fieldsTosave);
		$topRowCount = $totals['top_row_count']['total'];
		$grid = array();
		
		$tHead['head'] .= '<tr>';
		if($numberRows > 0){
			$tHead['head'] .= $this->createRowLkpHead($rowInfo, ($topRowCount));
		}
		$firstCol = $columnInfo[0];
		foreach($firstCol as $firstColInfo){
			$tHead['head'] .= '<th colspan="' . $totals['top_row_count']['span'] . '">' . $firstColInfo[1] . '</th>';
			$grid[$firstColInfo[0]] = array();
		}
		$tHead['head'] .= '</tr>';
		
		unset($columnInfo[0]);
		
		$lastTotal = 0;
		if(!empty($columnInfo)){
			$lastRow = 0;
			foreach($columnInfo as $columnNo => $columns){
				$tHead['head'] .= '<tr>';
				$total = $totals['col_levels'][$columnNo]['total'];
				$span = $totals['col_levels'][$columnNo]['span'];
				for($i = 0; $i < $total; $i++){
					foreach($columns as $column){
						$tHead['head'] .= '<th colspan="' . ($span) . '">' . $column[1] . '</th>';
						foreach($grid as $id => &$data){
							$data[$column[0]] = array();
						}
						$lastTotal++;
					}
				}
				$tHead['head'] .= '</tr>';
				$lastRow = $columnNo;
			}
		}
		
		if(!empty($fieldsTosave)){
			$tHead['head'] .= '<tr>';
			$span = 1;
			for($i = 0; $i < $lastTotal; $i++){
				$count = $lastRow + 1;
				foreach($fieldsTosave as $name => $saveField){
					$display = (isset($totals['col_levels'][$count]['display'])) ? $totals['col_levels'][$count]['display'] : true;
					$tHead['head'] .= ($display) ? '<th colspan="' . ($span) . '">' . $name . '</th>' : '';
					foreach($grid as $level => &$levelData){
						foreach($levelData as $levelDataKey => &$data){
							$data[$saveField] = '';
						}
					}
					$count++;
				}
			}
			$tHead['head'] .= '</tr>';
			
			if($lastTotal == 0){
				foreach($fieldsTosave as $name => $saveField){
					foreach($grid as $level => &$data){
						$data[$saveField] = '';
					}
				}
			}
		}
		
		$tHead['grid'] = $this->multipleaRCGridCreateGrid($grid, $totals['depth']);
		
		return $tHead;
	}
	
	function createRowLkpHead($rowInfo, $cols){
		$return = '';
		
		$headings = array_keys($rowInfo);
		
		foreach($headings as $heading){
			$return .= '<th rowspan="' . $cols . '">' . $heading .  '</th>';
		}
		
		return $return;
	}
	
	function multipleaRCGridCreateBody($columnInfo, $rowInfo, $fieldsTosave, $totals, $colData, $tableInfo, $populateValues, $saveFieldDetails, $rowMaps){
		$tbody = '';
		$rowCount = 0;
		$rowData = array();
		$newRows = array();
		$numberRows = count($rowInfo);
		$totalColumns = count($colData) + $numberRows;
		$grid = array();
		
		foreach($rowInfo as $label => $rows){
			$label = $rowCount;
			$newRows[$rowCount] = $rows;
			$rowCount++;
		}
		
		foreach($newRows[0] as $rows){
			$grid[$rows[0]] = array();
		}
		
		if(!isset($newRows[1])){
			foreach($newRows as $rowNum => $rows){
				foreach($rows as $row){
					$grid[0][$row[0]] = $colData;
				}
			}
		}
		
		unset($newRows[0]);
		
		if(!empty($newRows)){
			foreach($newRows as $rowNum => $rows){
				foreach($rows as $row){
					foreach($grid as $id => &$gridData){
						$gridData[$row[0]] = $colData;
					}
				}
			}
		}
		
		/*
			Needs to be generic depending on the number of rows
			Only does 2 levels
		*/
		
		// $this->pr($grid);
		
		foreach($grid as $levelOne => $levelOneData){
			$count = 0;
			foreach($levelOneData as $levelTwo => $levelTwoData){
				$tbody .= '<tr>';
				$keyField = (isset($rowMaps[$levelOne])) ? '|' . $levelOne . '|' . $levelTwo : '|' . $levelTwo;
				$dataArray = $this->populateValuesRCMultiGrid($levelTwoData, $keyField, $populateValues, $saveFieldDetails);
				$inputValues = implode('</td><td>', $dataArray);
				if(isset($rowMaps[$levelOne])){
					$tbody .= ($count == 0) ? '<td rowspan="' . count($levelOneData) . '">' . (isset($rowMaps[$levelOne]) ? $rowMaps[$levelOne] : $levelOne) . '</td>' : '';
				}
				$tbody .= '<td>' . (isset($rowMaps[$levelTwo]) ? $rowMaps[$levelTwo] : $levelTwo) . '</td>';
				$tbody .= '<td>';
				$tbody .= $inputValues;
				$tbody .= '</td>';
				$tbody .= '</tr>';
				$count++;
			}
		}
		
		return $tbody;
	}
	
	function multipleaRCGridCreateGrid($data, $depth){
		$grid = array();
		
		/*
			Needs to be generic depending on the depth of columns
		*/
		
		$grid = $this->recursiveCols($data, $depth);
		
		return $grid;
	}
	
	function recursiveCols($array, $depth){
		$grid = array();
		$count = 0;
		
		switch($depth){
			case '2':
				foreach($array as $levelOneID => $levelOneData){
					foreach($levelOneData as $levelTwoID => $levelTwoData){
						$grid[$count] = $levelOneID . '|' . $levelTwoID;
						$count++;
					}
				}
				break;
			case '3':
				foreach($array as $levelOneID => $levelOneData){
					foreach($levelOneData as $levelTwoID => $levelTwoData){
						foreach($levelTwoData as $levelThreeID => $levelThreeData){
							$grid[$count] = $levelOneID . '|' . $levelTwoID . '|' . $levelThreeID;
							$count++;
						}
					}
				}
				break;
		}
		
		return $grid;
	}
	
	function populateValuesRCMultiGrid($array, $string, $populateValues, $saveFieldDetails){
		foreach($array as $key => &$value){
			$fieldType = '';
			$fieldClass = '';
			$fieldSize = 0;
			$value .= $string;
			foreach($saveFieldDetails as $field => $info){
				if(strrpos($value, $field)){
					$fieldType = $info['type'];
					$fieldSize = $info['size'];
					$fieldClass = $info['class'];
				}
			}
			$fieldName = 'FLD$' . $value;
			$fieldValue = (isset($populateValues[$value])) ? $populateValues[$value] : 0;
			$this->createInput($fieldName, $fieldType, $fieldValue, $fieldSize, 0, $fieldClass);
			$value = $this->showField($fieldName, true);
		}
		
		return $array;
	}

// END of Class
}