<?php

if (! isset($preventreloadCount) ) { $preventreloadCount = 0; }

if (! defined('SMTP_SERVER') ) die ('ERROR: EMAIL configuration error');

class workflow extends flowLogic {

	var $prev_flowID, $prev_workFlowID;
	var $is_password_field;
	var $dbTableInfoArray;
	var $dbTableCurrent, $curCreationDateField, $curLastUpdatedField;
	var $doChangeDueDate;
	var $viewPage;
	var $public_holidays;
	var $private_docs, $public_docs;
	var $grid_compare_id;
	var $altEmailAddr;
	var $addProcessText;
	var $evalFile, $evalLine;
	var $displayUserMessage;
	var $grid = null;
	public $db = null;
	public $AuditLog = null;
	public $Email = null;

	public function __construct($flowID) {
		//Load up the classes that we will be using
		$this->db = DB::getInstance();
		$this->AuditLog = AuditLog::getInstance($this->db);
		$this->Email = Email::getInstance($this->db, $this->AuditLog);

		$this->doChangeDueDate = false;

		$this->workflow_settings();

		parent::__construct();
		$this->getCurrentWorkFlow($flowID);

		if ($_SERVER['QUERY_STRING'] > "") {
			$this->AuditLog->writeLogInfo(100, "QUERY STRING", $_SERVER['QUERY_STRING']);
		}
	}

	function workflow_settings () {
		Settings::set('TmpDir', WRK_TMPDIR);
		Settings::set('imageOK', WRK_IMAGE_OK);
		Settings::set('imageWrong', WRK_IMAGE_WRONG);

		Settings::set('debug_mode', WRK_DEBUG_MODE);

		Settings::set('log_level', WRK_LOG_LEVEL);
		// See workflow_audit_level table for ranges. 9999 => All processes. Level set in processes table per process.
		Settings::set('audit_level', WRK_AUDIT_LEVEL);

		Settings::set('mayMail', WRK_MAYMAIL);
	}

	function getTemplateName ($workFlowID) {
		$templateName = "";

		$SQL = "SELECT * FROM work_flows WHERE work_flows_id = :workFlowID";
		$rs = $this->db->query($SQL, compact('workFlowID'));
		if ($rs && ($row = $rs->fetch())) {
			$templateName = $row["template"];
		}

		return $templateName;
	}

	function setActiveWorkFlow ($id) {
		$SQL = "SELECT * FROM active_processes WHERE".
					 " user_ref = :userId".
					 " AND status = 0".
					 " AND active_date <= now()".
					 " AND active_processes_id = :id";
		$rs = $this->db->query($SQL, array('userId' => Settings::get('currentUserID'), 'id' => $id));
		$row = $rs->fetch();

		if ($rs->rowCount() > 0) {
			if ($row["workflow_settings"] > "" ) {
				$this->parseWorkFlowString ($row["workflow_settings"]);
			}

			Settings::set('active_processes_id', $id);
			Settings::set('workFlow_settings.ACTPROC', Settings::get('active_processes_id'));

			if ($row["work_flow_ref"] > 0) {
				$SQL = "SELECT * FROM work_flows WHERE ".
					"work_flows_id = ". $row["work_flow_ref"];
				$this->readWorkFlowSettings ($SQL);
			} else {
				$this->startFlow ($row["processes_ref"]);
			}
		} else { // dod not fin a valid active process
			$this->startFlow (__HOMEPAGE);
		}
	}

	function setFormDBinfo($tableName, $IDFieldName, $fieldID="", $creationDate="", $lastUpdated="") {
			//word geroep vanuit template files
			//create a nuwe object en stel dbTableName na tableName
		$this->dbTableCurrent = $tableName;
		$this->curCreationDateField = $creationDate;
		$this->curLastUpdatedField = $lastUpdated;
		Settings::set('workFlow_settings.CURRENT_TABLE', $this->dbTableCurrent);

		if (empty($this->dbTableInfoArray[$tableName])) {
			$this->dbTableInfoArray[$tableName] = new dbTableInfo ($tableName, $IDFieldName, "NEW");
		}
		if ($this->dbTableInfoArray[$tableName]->dbTableKeyField == "") {
			$this->dbTableInfoArray[$tableName]->dbTableKeyField =$IDFieldName;
		}

		if ($fieldID>"") {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = $fieldID;
		}
		
		$this->db_settingsKey = "DBINF_".$tableName."___".$IDFieldName;
		if (isset ($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
			Settings::set('workFlow_settings.' . $this->db_settingsKey, $this->dbTableInfoArray[$tableName]->dbTableCurrentID);
		}
		if (Settings::isIsset('workFlow_settings.' . $this->db_settingsKey)) {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = Settings::get('workFlow_settings.' . $this->db_settingsKey);;
		}
		if ( empty($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = "NEW";
			Settings::set('workFlow_settings.' . $this->db_settingsKey, $this->dbTableInfoArray[$tableName]->dbTableCurrentID);
		}

		//Louwtjie: To always have the HEI_id available
		if (! isset($this->dbTableInfoArray["HEInstitution"])) {
			if (isset($this->dbTableInfoArray["Institutions_application"])) {
				$this->dbTableInfoArray["HEInstitution"] = new dbTableInfo ("HEInstitution", "HEI_id", $this->db->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"));
			}
		}

		$this->templateToDB($this->dbTableInfoArray[$tableName]);
	}

	function checkCMDpost () {
		if ( isset($_POST["CMD"]) ) {
			switch($_POST["CMD"]) {
				case "LOGIN":
					$this->userLogin ($_POST["oct_username"], $_POST["oct_passwd"]);
					break;
			}
		}
	}

	function parseOtherWorkFlowProcess ($id) {
		$dbTableInfoArray = array();
		$SQL = "SELECT workflow_settings FROM active_processes WHERE active_processes_id = :id";
		$rs = $this->db->query($SQL, compact('id'));

		if ($row = $rs->fetch()) {
			$arr = explode("&", $row[0]);
			foreach ($arr as $a) {
				$parts = explode("=", $a);
				if (! strncmp($parts[0], "DBINF_", 6) ) {
					$this->db_settingsKey = $parts[0];
					$p = explode("___", substr ($parts[0], 6));
					$dbTableInfoArray[$p[0]] = new dbTableInfo ($p[0], $p[1], $parts[1]);
				}
			}
		}

		//Louwtjie: To always have the HEI_id available
		if (! isset($dbTableInfoArray["HEInstitution"])) {
			if (isset($dbTableInfoArray["Institutions_application"])) {
				$dbTableInfoArray["HEInstitution"] = new dbTableInfo ("HEInstitution", "HEI_id", $this->db->getValueFromTable("Institutions_application", "application_id", $dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"));
			}
		}

		return $dbTableInfoArray;
	}

	function makeWorkFlowString ($curtable, $keyfield, $keyvalue) {
			// create a small part WorkFlowString.
		$wfs = "CURRENT_TABLE=".$curtable."&DBINF_".$curtable."___".$keyfield."=".$keyvalue;
		return ($wfs);
	}

	/*	Diederik (2004-04-15)
	    Take the current WorkFlow and add the new setting as a string
			Do not change the current settings, but only return a new string.
	*/
	function makeWorkFlowStringFromCurrent ($curtable, $keyfield, $keyvalue) {
		$newWorkFlow = Settings::get('workFlow_settings');

		// Change the copy of org WorkFlow
		$newWorkFlow["CURRENT_TABLE"] = $curtable;
		$settingsKey = "DBINF_".$curtable."___".$keyfield;

		$newWorkFlow[$settingsKey] = $keyvalue;

		return ( $this->getStringWorkFlowSettings ($newWorkFlow) );
	}

	function savePrevInfo ($processID, $workflowID) {
		$this->prev_flowID = $processID;
		$this->prev_workFlowID = $workflowID;
		$this->formHidden["PREV_WORKFLOW"] = $this->prev_flowID."|".$this->prev_workFlowID;
		Settings::set('workFlow_settings.PREV_WORKFLOW', $this->prev_flowID."|".$this->prev_workFlowID);
		if ( isset($_POST["FLOW_ID"]) ) {
			$this->formHidden["LAST_WORKFLOW_ID"] = $_POST["FLOW_ID"];
		}
	}

	function parseWorkFlowString ($str) {
		$arr = explode("&", $str);
		foreach ($arr as $a) {
			$parts = explode("=", $a);
			$parts[0] = urldecode($parts[0]);
			$parts[1] = urldecode($parts[1]);

			Settings::set('workFlow_settings.' . $parts[0], $parts[1]);
			if (! strncmp($parts[0], "DBINF_", 6) ) {
				$this->db_settingsKey = $parts[0];
				$p = explode("___", substr ($parts[0], 6));
				$this->dbTableInfoArray[$p[0]] = new dbTableInfo ($p[0], $p[1], $parts[1]);
			}
			if (! strncmp($parts[0], "CURRENT_TABLE", 13) ) {
				$this->dbTableCurrent = $parts[1];
				Settings::set('workFlow_settings.CURRENT_TABLE', $this->dbTableCurrent);
			}
			if (! strncmp($parts[0], "ACTPROC", 7) ) {
				Settings::set('active_processes_id', $parts[1]);
				Settings::set('workFlow_settings.ACTPROC', Settings::get('active_processes_id'));
			}
			if (! strncmp($parts[0], "PASSWORD", 8) ) {
				$this->is_password_field = $parts[1];
			}
			if (! strncmp($parts[0], "PREV_WORKFLOW", 13) ) {
				$prevArr = explode ("|", urldecode($parts[1]));
				if (count ($prevArr) >= 2) {
					$this->savePrevInfo ($prevArr[0], $prevArr[1]);
				}
			}
			if (! strncmp($parts[0], "LOGIC_SET", 9) ) {
				$this->logicFromString($parts[1]);
			}
		}
	}

	function checkWorkflowPost () {
		if ( isset($_POST["PREV_WORKFLOW"]) ) {
			$prevArr = explode ("|", $_POST["PREV_WORKFLOW"]);
			if (count ($prevArr) >= 2) {
				$this->savePrevInfo ($prevArr[0], $prevArr[1]);
			}
		}
		if ( isset($_POST["WORKFLOW_SETTINGS"]) ) {
			$this->parseWorkFlowString ($_POST["WORKFLOW_SETTINGS"]);
		}
	}

	function checkSaveFieldsPost () {
		$save_grid_fields_array = array();
		$insert_grid_fields_array = array();
		$insert_multi_grid_fields_array = array();
		$saveFields = array ();
		$saveMultipleFields = array ();
		$delMultipleFields = array ();
		$saveGRIDFields = array ();
		$newGridRows = array();
	
		// First do a few check that we need in the next foreach
		foreach($_POST as $key => $val) {
			if (! strncmp($key, "SHOULDSAVE", 8) ) {
				// cheack default false values for all the possible checkboxes
				$checkboxArray = explode ("_|_", $val);
				foreach ($checkboxArray as $key) {
					$saveFields[$key] = CHK_DEFAULT_FALSE;
				}
			}
		}
		print_r($saveFields);
		foreach($_POST as $key => $val) {
			if (! strncmp($key, "FLD_", 4) ) {
				$fldName = substr ($key, 4);
				if (isset($_POST["INFFT_".$fldName])) {
					$ftInf = explode ("_|_", $_POST["INFFT_".$fldName]);
					$this->db->setValueInTable($ftInf[0], $ftInf[1], $ftInf[2], $fldName, $val, $this->view);
				} else {
					$saveFields[$fldName] = $val;
				}
			}
			if (! strncmp($key, "FLDS_", 5) ) {
				$n = substr ($key, 5);
				if (empty($saveMultipleFields[$n])) $saveMultipleFields[$n] = $val;
			}
			// 20080229: Diederik
			if (! strncmp($key, "PWA_", 4) ) {
				if ($val>"") {
					$fldName = substr ($key, 4);
//					die ($_POST['TMPL_NAME']." ".$fldName." ".$val);
					$curTemplate = $_POST['TMPL_NAME'];
					$SQLfield = "SELECT * FROM template_field WHERE template_name = '$curTemplate' AND fieldName = '$fldName'";
					$RSfield = mysqli_query ($SQLfield);
					if ($fInfo = mysqli_fetch_assoc ($RSfield)) {
						$opts = explode (",", $fInfo['fieldValuesArray']);
						$saveFields[$fldName] = ($this->isAdminPassword($val)===true)?($opts[1]):($opts[0]);
					}
				}
			}

			if (! strncmp($key, "MRINF_", 6) ) {
				$n = substr ($key, 6);
				if (empty($delMultipleFields[$n])) $delMultipleFields[$n] = $val;
			}
			
			if (!strncmp($key, "MULTIGRID_", 10)){
				$explodeFields = explode("$", $key);
				$listFields = array_pop($explodeFields);
				$key = $explodeFields[0];
				unset($explodeFields[0]);
				list($action, $table, $lookupFld, $id) = explode ('|', $key);
				
				foreach($_POST as $field => $value){
					if(!strncmp($field, "FLD$", 4)){
						list($identifier, $fieldsToSave) = explode ('$', $field);
						$saveList[$fieldsToSave] = $value;
					}
				}
				
				if(!empty($saveList)){
					$this->saveMultiGrid($saveList, $table, $lookupFld, $id, $explodeFields, $listFields);
				}
			}
			
			/*
			Author:Reyno
			Date: 2004-4-4
			Revised: Louwtjie - 2005-02-09
			This is where we save the values of a grid to the database
			*/
			if (! strncmp($key, "GRID_", 5) ) {
				//set the save flag to false. We don't want to save now.
				$grid_save_flag = false;
				$field_val = array();
				//check if this is the end of a row in the DB.

				if(stristr($key, "MULTI") && stristr($key, "save")) {
					$thisKey = explode('_', $key);
					$thisKey = $thisKey[0] . '_' . $thisKey[1];
					$insert_multi_grid_fields_array[$thisKey][] = $key;
				}
				
				if(stristr($key, "MULTI") && !stristr($key, "save")) {
					list ($id, $keyField, $colom, $table) = explode ('$', $key);

					// remove the GRID_ part from id.
					$id = substr($id, 5, strlen($id));
					// push the values on to the save array.
					
					$thisKey = explode('$', $key);
					$thisKey = $thisKey[0];
					$insert_multi_grid_fields_array[$thisKey][] = $table."|".$keyField."|".$id."|".$colom."|".$val;
				}
				
				if(stristr($key, "INSERT") && stristr($key, "save") && !stristr($key, "MULTI")) {
					$thisKey = explode('_', $key);
					$thisKey = $thisKey[0] . '_' . $thisKey[1];
					$insert_grid_fields_array[$thisKey][] = $key;
				}
				
				if(stristr($key, "INSERT") && !stristr($key, "save") && !stristr($key, "MULTI")) {
					list ($id, $keyField, $colom, $table) = explode ('$', $key);

					// remove the GRID_ part from id.
					$id = substr($id, 5, strlen($id));
					// push the values on to the save array.
					
					$thisKey = explode('$', $key);
					$thisKey = $thisKey[0];
					$insert_grid_fields_array[$thisKey][] = $table."|".$keyField."|".$id."|".$colom."|".$val;
				}
				
				if (!stristr($key, "save") && !stristr($key, "deleted") && !stristr($key, "INSERT") && !stristr($key, "MULTI")) {
					//We are still busy with the same row in DB. Extract the table, keyfield, values etc from post var.
					list ($id, $keyField, $colom, $table) = explode ('$', $key);

					//remove the GRID_ part from id.
					$id = substr($id, 5, strlen($id));
					//push the values on to the save array.
					$save_grid_fields_array[$key] = $table."|".$keyField."|".$id."|".$colom."|".$val;
				} elseif (stristr($key, "deleted") && !stristr($key, "MULTI")) {
					$deletedIds = explode('|', $val);

					list($ignore, $table, $keyField) = explode('|', $key);

					foreach($deletedIds as $deletedId) {
						if(!stristr($deletedId, 'INSERT')) {
							$this->gridDeleteRow($table, $keyField, $deletedId);
						}
					}
				}
				
			}
		}
		
		if(!empty($insert_multi_grid_fields_array)){
			$insertArrayMulti = array();
			$deleteMultiSQL = '';
			$multiCount = 0;
			foreach($insert_multi_grid_fields_array as $grid_insert_array){
				$keyPos = null;
				foreach($grid_insert_array as $stringKey => $insertString){
					$keyPos = (strpos($insertString, '_|_')) ? $stringKey : $keyPos;
				}
				$theInsertKey = $grid_insert_array[$keyPos];
				unset($grid_insert_array[$keyPos]);
				foreach($grid_insert_array as $insertValue){
					$field_val = explode("|", $insertValue);
					//check if the actual value to be saved is not empty.
					if ((($field_val[4] > "")) || ($field_val[4] > 0)) {
						//set the flag for saving
						$grid_save_flag = true;
					}
					if ($grid_save_flag){
						if(stristr($field_val[2], 'MULTI') && empty($newGridRows[$field_val[2]])) {
							$multiCount++;
							$applicationKey = explode("_|_", $theInsertKey);
							$fields = $applicationKey[1];
							$values = $applicationKey[2];
							$deleteKeyFieldArray = explode("$", $fields);
							$deleteKeyValueArray = explode("$", $values);
							$fieldsToSave = explode("$", $fields);
							$valuesToSave = explode("$", $values);
							$fieldsToSave[] = $field_val[3];
							$valuesToSave[] = $field_val[4];
							
							$dataToSave = array(
								'fields' => $fieldsToSave,
								'values' => $valuesToSave
							);

							$deleteMultiSQL = $this->gridInsertMultiRowClean($field_val[0], $deleteKeyFieldArray[0], $deleteKeyValueArray[0]);
							$insertArrayMulti[$field_val[2]]['insert'][] = $this->gridInsertMultiRowGetSQL($field_val[0], $dataToSave);
							$insertArrayMulti[$field_val[2]]['update'][] = array($field_val[0], 'id', $field_val[3], $field_val[4], $this->view, $dataToSave);
						}
					}
				}
			}
			if(!empty($insertArrayMulti)){
				$errorMail = false;
				
				if(!isset($_POST['UPDATE_INDICATOR_MULTI_GRID'])){
					$errorMail = false;
					$deleteMultiRs = $this->db->query($deleteMultiSQL) or $errorMail = true;
					$this->AuditLog->writeLogInfo(10, "MULTISQL", $deleteMultiSQL . "  --> " . $this->db->lastError[2], $errorMail);
					
					foreach($insertArrayMulti as $multiKey => $multiData){
						//insert
						$errorMail = false;
						$rsMULTIinsert = $this->db->query($multiData['insert'][0]);
						$primaryKey = $this->db->lastInsertId();
						unset($multiData['update'][0]);
						//update
						foreach($multiData['update'] as $multiUpdateData){
							$updateSQL = $this->gridInsertUpdateMultiRowGetSQL($multiUpdateData[0], $multiUpdateData[1], $primaryKey, $multiUpdateData[2], $multiUpdateData[3], $multiUpdateData[4]);
							$rsMULTIupdate = $this->db->query($updateSQL);
						}
					}
				}
				else{
					$updateInfo = $_POST['UPDATE_INDICATOR_MULTI_GRID'];
					foreach($insertArrayMulti as $multiKey => $multiData){
						foreach($multiData['update'] as $multiUpdateData){
							$updateSQL = $this->gridUpdateMultiSQL($multiUpdateData[5], $multiUpdateData[0], $updateInfo);
							// $this->pr($updateSQL);
							$rsMULTIupdate = $this->db->query($updateSQL);
						}
					}
				}
			}
		}
		
		if(!empty($insert_grid_fields_array)){
			foreach($insert_grid_fields_array as $grid_insert_array){
				$sizeArray = count($grid_insert_array);
				$theInsertKey = $grid_insert_array[$sizeArray - 1];
				unset($grid_insert_array[$sizeArray - 1]);
				foreach($grid_insert_array as $insertValue){
					$field_val = explode("|", $insertValue);
					//check if the actual value to be saved is not empty.
					if ((($field_val[4] > "") && ($field_val[4] != "0")) || ($field_val[4] > 0)) {
						//set the flag for saving
						$grid_save_flag = true;
					}
					
					if ($grid_save_flag){
						if(stristr($field_val[2], 'INSERT') && empty($newGridRows[$field_val[2]])) {
							$applicationKey = explode("$", $theInsertKey);
							$newGridRows[$field_val[2]] = $this->gridInsertRow($field_val[0], $applicationKey[1], $applicationKey[2], $field_val[3], $field_val[4]);
						}
						else {
							$pk = !empty($newGridRows[$field_val[2]])  ? $newGridRows[$field_val[2]] : $field_val[2];
							$this->db->setValueInTable($field_val[0], $field_val[1], $pk, $field_val[3], $field_val[4], $this->view);
						}
					}
				}
			}
		
		}
		
 		if(!empty($save_grid_fields_array)){
			// $this->pr($save_grid_fields_array);
			foreach($save_grid_fields_array as $thisKey => $grid_save_val){
				$field_val = explode("|", $grid_save_val);
				//check if the actual value to be saved is not empty.
				if ((($field_val[4] > "") && ($field_val[4] != "0")) || ($field_val[4] > 0)) {
					//set the flag for saving
					$grid_save_flag = true;
				}
				
				if($grid_save_flag){
					if(stristr($field_val[2], 'INSERT') && empty($newGridRows[$field_val[2]])) {
						$applicationKey = explode("$", $thisKey);
						$newGridRows[$field_val[2]] = $this->gridInsertRow($field_val[0], $applicationKey[1], $applicationKey[2], $field_val[3], $field_val[4]);
					}
					else {
						$pk = !empty($newGridRows[$field_val[2]])  ? $newGridRows[$field_val[2]] : $field_val[2];
						$this->db->setValueInTable($field_val[0], $field_val[1], $pk, $field_val[3], $field_val[4], $this->view);
					}
				}else{
					if (count($field_val) > 0) {
						$keyFldValue = $field_val[2];
						$SQL ="DELETE FROM `" . $field_val[0] . "` WHERE " . $field_val[1] . " = :keyFldValue";
						$del_rs = $this->db->query($SQL, compact('keyFldValue'));
					}
				}
			}
		
		}
		$save_grid_fields_array = array();

		if (count ($saveFields) > 0) {
			if ( isset($this->dbTableCurrent) && isset($this->dbTableInfoArray[$this->dbTableCurrent]) ) {
				if ( $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID != "NEW" ) {
					$SQL = "UPDATE ".$this->dbTableCurrent." SET ";
					$sqlFieldVal = array();
					$sqlVAL = array();
					foreach ($saveFields as $key => $val) {
						if ( (isset($this->is_password_field)) && ($key == $this->is_password_field) ) {
							$sqlFieldVal[$key] = $val;
							array_push($sqlVAL, $key . ' = PASSWORD(:' . $key . ')');
						} else if ( !empty($saveFields[$key]) ) {
							$sqlFieldVal[$key] = $val;
							array_push($sqlVAL, $key . ' = :' . $key);
						}
					}

					if (isset($this->curLastUpdatedField) && ($this->curLastUpdatedField > "")) array_push ($sqlVAL, $this->curLastUpdatedField." = NOW()");

					$SQL = $SQL. implode (", ", $sqlVAL);
					$SQL = $SQL. " WHERE ".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField." = '".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID."'";
					$errorMail = false;
					$result = $this->db->query($SQL, $sqlFieldVal) or $errorMail = true;
					if ( is_object($result) ) {
						$error = $result->errorInfo();
					  }
					if (empty($error[2])) {$error[2] = '';}

					$this->AuditLog->writeLogInfo(10, "SQL", $SQL . "  --> " . $error[2], $errorMail);
					$this->AuditLog->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);

				} else {
					// we have a NEW record in the database
					$cols = array ();
					$vals = array ();

					foreach ($saveFields as $key => $val) {
						array_push($cols, $key);

						if ( (isset($this->is_password_field)) && ($key == $this->is_password_field) ) {
							array_push($vals, 'PASSWORD(:'.$key.')');
						} else {
							array_push($vals, ':'.$key);
						}
					}

					if (isset($this->curCreationDateField) && ($this->curCreationDateField > "")) {
						array_push ($cols, $this->curCreationDateField);
						array_push ($vals, "NOW()");
					}

					// check if we need to save the active user in the database
					$SQL = "SELECT write_current_user_to_db FROM processes WHERE processes_id = ".Settings::get('flowID');
					$rs = $this->db->query($SQL);
					$row = $rs->fetch();
					if ($row[0] > "" && (Settings::get('currentUserID') > 0) ) {
						$tblInfo = explode ("|", $row[0]);
						if ($tblInfo[0] == $this->dbTableCurrent) {
							array_push ($cols, $tblInfo[1]);
							array_push ($vals, Settings::get('currentUserID'));
						}
					}

					// Save the actual fields to the database
					if ( (count ($vals) > 0) && (count ($vals) == count ($cols)) ) {
						$SQL = "INSERT INTO ".$this->dbTableCurrent." (". implode(", ", $cols) .") VALUES (". implode(", ", $vals) .")";

						$logType = "SQL-ERROR";
						$errorMail = true;

						$try_sql = true; //if there is no DELETE_RECORD it should still try insert the query above.
					} else {
						// 20060623: DIederik if we do not have proper currentUser
						//           do not execute the SQL
						$try_sql = false;
						$this->AuditLog->writeLogInfo(100, "SQL_SKIP_POST DATA", "SQL CURRENT USER ".__FILE__.":".__LINE__."\n\n".var_export($_POST, true), false);
					}

					if (isset($_POST["DELETE_RECORD"]) && ($_POST["DELETE_RECORD"] > "")) {
						$delete = explode ("|", $_POST["DELETE_RECORD"]);
						if ( (($delete[2] == "NEW") && ($delete[0] == $this->dbTableCurrent)) ) {
							$try_sql = false;
							$logType = "SQL-SKIPDEL";
						}
					}
					
					if ($try_sql  && $this->db->query($SQL, $saveFields)) {
						$logType = "SQL";
						$errorMail = false;
						$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID = $this->db->lastInsertId();
						Settings::set('workFlow_settings.' . $this->db_settingsKey, $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);

					}

					$this->AuditLog->writeLogInfo(10, $logType, $SQL."  --> " . $this->db->lastError[2], $errorMail);
					$this->AuditLog->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
				}
			}
		}

		// First remove all MRINF fields from DB
		if (count ($delMultipleFields) > 0) {
			foreach ($delMultipleFields as $key => $val) {
				$mrinf = explode ("_|_", $val);
				$SQL = "DELETE FROM ".$mrinf[0]." WHERE ".$mrinf[1]." = '".$mrinf[2]."'";
				mysqli_query($SQL);
			}
		}

		// Save all MRINF fields from DB
		if (count ($saveMultipleFields) > 0) {

			foreach ($saveMultipleFields as $key => $val) {

				// Robin: 18 Feb 2008
				// Bug: If multiple select is on the page of a new record then the record is saved with ref 0 instead of the
				//		inserted records id.
				// Fix: Check if the ref value is NEW and if there is a new inserted ref. Set the ref to the new value.

				if ($mrinf[2]=='NEW' && $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID > 0){
					$mrinf[2] = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;
				}

				foreach ($val as $rFld) {
					$SQL = "INSERT INTO ".$mrinf[0].
								 " (".$mrinf[1].", ".$mrinf[3].") VALUES ('".$mrinf[2]."', '".$rFld."')";
					mysqli_query($SQL);
				}
			}
		}
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
		if(trim($keyFldValue) != '') {
			$SQL = "SELECT count(*) as counter FROM `".$table."` WHERE ".$keyFld."=".$keyFldValue;
			$rs = mysqli_query($SQL);
			$count = mysqli_fetch_assoc($rs);

			if($count['counter'] == 1) {
				$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld."=".$keyFldValue;
				$rs = mysqli_query($SQL);
			}
		}
	}
	
	/*
		Carlos
		Insert multiple values in lookuptables with grids
		2013-05-16
	*/
	
	function gridInsertUpdateMultiRowGetSQL($table, $keyField, $keyValue, $chField, $chValue, $view = 0){
		$SQL = "UPDATE `$table` " .
			 "SET $chField =  " . $chValue . " " . 
			 "WHERE `$keyField` = " . $keyValue;
		
		return $SQL;
	}
	
	function gridUpdateMultiSQL($data, $table, $updateInfo){
		$where = array();
		$set = array();
		$updateInfoArray = explode('__', $updateInfo);
		$found = false;
		
		foreach($updateInfoArray as $updateFieldInfo){
			$updateFieldStringArray = explode('_|_', $updateFieldInfo);
			foreach($updateFieldStringArray as $updateFieldString){
				$updateFieldString = explode('$', $updateFieldString);
				if(false !== ($key = array_search($updateFieldString[0], $data['fields']))){
					$where[] = $data['fields'][$key] . ' = ' . $data['values'][$key];
					unset($data['fields'][$key]);
					unset($data['values'][$key]);
				}
			}
		}
		
		if(!empty($data['fields'])){
			foreach($data['fields'] as $key => $field){
				$set[] = $field . ' = ' . '\'' . $data['values'][$key] . '\'';
			}
		}
		
		$SQL = 'UPDATE ' . $table . ' ' .
			 'SET ' . implode(', ', $set) . ' ' .
			 'WHERE ' . implode(' AND ', $where);
		return $SQL;
	}
	
	function gridInsertMultiRowGetSQL($table, $data){
		$fields = implode(", ", $data['fields']);
		$values = '\'' . implode("', '", $data['values']) . '\'';
		
		$sql = 'INSERT INTO `' . $table . '` (' . $fields . ') ';
		$sql .= ' VALUES (' . $values . ');';
		
		return $sql;
	}
	
	function gridInsertMultiRow($SQL){
		$rsMULTI = $this->db->query($insertMultiSQL);
		return $this->db->lastInsertId();
	}
	
	function gridInsertMultiRowClean($table, $lookupFld, $id){
		$sql = "DELETE FROM " . $table . " WHERE " . $lookupFld . " = " . $id . ';';
		return $sql;
	}

	/*
	Louwtjie
	2004-10-27
	inserts a new program row in a grid
	*/
	function gridInsertRow($table, $keyFld, $keyFldValue, $keyFld2="", $keyFldValue2=0){
		
		$fld2_key = ($keyFld2>"")?(", ".$keyFld2):("");
		$fld2_value = (!empty($keyFldValue2))?(", '".$keyFldValue2."'"):("");

		$SQL ="INSERT INTO `".$table."` (".$keyFld.$fld2_key.")";
		$SQL .= " VALUES ('".$keyFldValue."'".$fld2_value.")";
		$rs = $this->db->query($SQL);

		return $this->db->lastInsertId();
	}

	function checkCurrentFlowIDpost($flowID) {
		$ret = false;

			// BUG: want hier is nie a && nie ...?
		if ( isset($_POST["FLOW_ID"]) || isset($_POST["GOTO"]) ) {
			if($_POST["GOTO"] == 155 || $_POST["FLOW_ID"] == 155) {
				header('Location:../../users/jump_back?sid='.session_id());
			}
			$this->setCurrentFlow ($_POST["FLOW_ID"]);
			$ret = true;
		} else {
			// As ons hier uitkom was daar nog nooit 'n flow nie,
			// dus is ons NUUT tot die stelsel.  (geen settings nie)
			$this->startFlow ($flowID);
		}

		return ($ret);
	}

	// 2011-12-12: Check session with posted data to prevent reload
	// 	 and send to home page
	function preventReload () {
		global $preventReloadCount;

		// This may only be called once
		if ($preventReloadCount++ > 1) return (false);

		if (!isset($_SESSION['LAST_UUIDs'])) $_SESSION['LAST_UUIDs'] = array();

		$uuid = uniqid('', true); // 2011-12-12: U-UID for duplicate POSTs to prevent re-processing on refresh
		$this->formHidden["LAST_UUID"] = $uuid;

/*		echo "<pre>LAST_UUID: ".
						( 
										(isset($_POST['LAST_UUID']))?
										($_POST['LAST_UUID']):
										('None')
						).
						"\nUUID: {$uuid}\nCOUNT: {$preventReloadCount}\nSESSION: ".print_r($_SESSION['LAST_UUIDs'])."</pre>";
 */

		if (isset($_POST['LAST_UUID']) && strlen($_POST['LAST_UUID']) > 20) {
				if (in_array($_POST['LAST_UUID'], $_SESSION['LAST_UUIDs'])) {
										
					$this->viewPage = -1;
					$_SESSION['LAST_UUIDs'] = array(); 
					$this->clearWorkflowSettings ();
					$this->displayUserMessage = "You are required to use the navigation provider by the system. Either navigate via the menus or the action bar.  Please do not use the <i>back</i> or the <i>refresh</i> button of your web browser.<br>";
					$this->startFlow (__HOMEPAGE);
					return (true);

							//	Here we need to:
							//	- Make sur eit does not save data
							//	- Clear the _SESSION['LAST_UUIDs']
							//	- Redirect to home page
			}

				$this->saveReloadData ($_POST['LAST_UUID']);
		}

		

		//		echo "<br><br>\n#".$_POST['LAST_UUID'].'#'; print_r($_SESSION['LAST_UUIDs']);
		return (false);
	}

	// 2011-12-12: Create a session of reload data, when doing insert, etc
	//   We assume we have a session by now.
	function saveReloadData ($uuid) {
		$uuids = (isset($_SESSION['LAST_UUIDs']))?($_SESSION['LAST_UUIDs']):(array());
		if (in_array($uuid, $uuids)) return;

		array_push ($uuids, $uuid);

		while (count($uuids) > 10) array_shift ($uuids);

		$_SESSION['LAST_UUIDs'] = $uuids;
	}

	function getCurrentWorkFlow ($flowID) {
		Settings::set('workFlow_settings', array());
		$this->dbTableInfoArray = array();

		// wat doen 0 en wat doen -1?
		if (empty($this->formHidden["VIEW"])) $this->formHidden["VIEW"] = "0";
		if (isset($_POST["VIEW"]) && ($_POST["VIEW"]!=0) ) {
			$this->viewPage = $_POST["VIEW"];
		}
		$this->formHidden["MOVETO"] = "";
		$this->formHidden["GOTO"] = "";
		$this->formHidden["CHANGE_TO_RECORD"] = "";
		$this->formHidden["PROCESS_TO_USER"] = ""; // TO change the current process to a new user

		// 2011-12-19 Robin - Affecting heqfdemo. Can't access candidacy interface - it goes to home page when click on Search.
		//if ( $this->preventReload() ) return;

		/*
		Louwtjie: 2004-05-17
		VALIDATION should be set at a validation page so that you
		can go back directly to the validation page after filling in a required field.
		*/
		$this->formHidden["VALIDATION"] = "";

/*
		Diederik
		2004-04-07:
			checkSaveFieldsPost should be before checkCurrentFlowIDpost to be able
			to save the changes before we check what to do with the new process.
		2004-004-15
		  Remove checkCurrentFlow() from checkCurrentFlowIDpost() because we would
		  like to run this AFTER checkSaveFieldsPost.  This cancels (2004-04-07)
		  as the Save is now after the IDPost.  Actual checkCurrentFlow() should
		  have been after checkSaveFieldsPost from the start.
*/

		$this->checkCMDpost ();
		$this->checkWorkflowPost ();

		// use doCheckCurFlow to check if we should run checkCurrentFlow()
		$doCheckCurFlow = $this->checkCurrentFlowIDpost ($flowID);
		if ($this->viewPage == 0) $this->checkSaveFieldsPost ();
		if ($doCheckCurFlow) {
			$this->checkCurrentFlow ();
		}

		$this->checkChangeToRecord ();
		if (! ($this->viewPage != 0) ) $this->checkDeleteRecord ();
		$this->updateActiveProcesses ();
	}

	function checkCurrentFlow () {
		/* Louwtjie (2004-04-12)
				To check if the process should go to a new user.
		*/
//		if (isset($_POST["PROCESS_TO_USER"]) && ($_POST["PROCESS_TO_USER"] > "")) {
//			$this->changeActiveProcesses (Settings::get('flowID'), $_POST["PROCESS_TO_USER"], Settings::get('workFlowID'));
//		}

		/*
		Louwtjie:2004-05-17
		Read VALIDATION to check if we were at a validation page.
		*/
		if ((isset($_POST["VALIDATION"])) && ($_POST["VALIDATION"] > "")) {
			// Date: 3 Nov 2008
			// Who: Robin
			// Added && ($_POST["MOVETO"] != "") to prevent Back to validation link being created when:
			// 	- the user is on a validation page
			//  - the user clicks on a top menu item e.g. Tools / Institutional Profile (taking it out of the flow)
			// because the Back to Validation link loses its active process and hence returns as NEW
			if (($_POST["MOVETO"] != "next") && ($_POST["MOVETO"] != "previous") && ($_POST["MOVETO"] != "")) {
				$this->createAction ("validation", "Back to Validation", "href", "javascript:moveto('".$_POST["VALIDATION"]."');", "ico_next.gif");
				$this->scriptTailInt .= "showHideAction('previous', false);\nshowHideAction('next', false);";
			}
		}

		if ($this->viewPage>0) {
			$this->formHidden["VIEW"] = $this->viewPage;
			$this->createAction ("exit", "Exit View Mode", "href", "javascript:exitView(26);", "ico_prev.gif");
			$this->scriptTailInt .= "showHideAction('previous', false);\nshowHideAction('next', false);";
			$this->setMoveToFlow ($this->viewPage);
			// BUG: switch the warning off when we are viewing pages
			error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		}

		if (isset($_POST["GOTO"]) && ($_POST["GOTO"]>"") ) {
			$info = $this->readProcessInfo();
			if ($info["keep_workflow_settings"] == "no") {
				$this->completeActiveProcesses ();
				$this->clearWorkflowSettings ();
			}
			// BUG: die volgende lyne (if) het nooit onder 'n if geval nie
			// BUG: GOTO was <= 2 Louwtjie bug met additional SITES
			//2005-07-05: we took the if ( $_POST["GOTO"] == 2  ) out because we always want to update the active proc and delete if no worklfow settings
			$this->updateActiveProcesses ();

			/* 2006-05-28: We now only clear the settings if we go
			     back to the HOME page.  We think there should be a reason why we
					 did start to clean all the setting since the end of 2005.  Now
					 we will only clear if the GOTO is NOT zore (0).  When we do not test
					 for the non zero it was dropping the process when we canceled on
					 the additional sites in the inst profile.
			*/
			if ( $_POST["GOTO"] != 0  ) {
				$this->clearWorkflowSettings ();
			}
			$this->startFlow ($_POST["GOTO"]);
		} else {
			if (isset($_POST["MOVETO"]) && ($_POST["MOVETO"]>"") ) {
				$oriantation = $_POST["MOVETO"];
			} else {
				$oriantation = "next";
			}
			switch ($oriantation) {
				case "next";
					$this->setNextFlow ();
					break;
				case "previous";
				case "prev";
					$this->setPreviousFlow ();
					break;
				case "stay";
					// we do not have to move. (but we do not want the default case)
					break;
				default:
					$this->setMoveToFlow ($oriantation);
			}
		}
	}

	function checkChangeToRecord () {
		global $heqcEncrypt;

		if (isset($_POST["CHANGE_TO_RECORD"]) && ($_POST["CHANGE_TO_RECORD"] > "")) {
			$change = explode ("|", $heqcEncrypt->decrypt($_POST["CHANGE_TO_RECORD"]));
			switch (count ($change)) {
				case 1:
					$tblName = $this->dbTableCurrent;
					$tblID = $change[0];
					break;
				case 2:
					$tblName = $change[0];
					$tblID = $change[1];
					break;
			}
			if ( empty($this->dbTableInfoArray[$tblName]) ) {
				$this->dbTableInfoArray[$tblName] = new dbTableInfo ($tblName, "", $tblID);
			} else {
				$this->dbTableInfoArray[$tblName]->dbTableCurrentID = $tblID;
			}
		}
	}

	function checkDeleteRecord() {
		if (isset($_POST["DELETE_RECORD"]) && ($_POST["DELETE_RECORD"] > "")) {
			$delete = explode ("|", $_POST["DELETE_RECORD"]);
			$params = array(
				'value' => $delete[2]
			);
			$del_SQL = "DELETE FROM " . $delete[0] . " WHERE " . $delete[1] . " = :value;";
			$errorMail = false;
			$this->db->query($del_SQL, $params) or $errorMail = true;
			$this->AuditLog->writeLogInfo(10, "SQL-DELREC", $del_SQL."  --> ".$this->db->lastError[2], $errorMail);
		}

/*
			if ($delete[2] == "NEW") {
				$delete[2] = mysqli_insert_id();
			}else {

// what is the purpose of "active" in the select below?
//			$SQL = "SELECT active FROM ".$delete[0]." WHERE ".$delete[1]."='".$delete[2]."'";
//			$rs = mysqli_query($SQL);
//			if ($row = mysqli_fetch_array($rs)) {
//				if ($row["active"] == 0) {
					$del_SQL = "DELETE FROM ".$delete[0]." WHERE ".$delete[1]."='".$delete[2]."'";
					$del_rs = mysqli_query($del_SQL);
//				}
			}
		}
*/
	}

	function setMoveToFlow ($to) {
		// Even with MOVETO, remember the previous process numbers
		$this->savePrevInfo (Settings::get('flowID'), Settings::get('workFlowID'));

		if (substr($to, 0, 1) == '_') {
			$label = substr($to, 1);
			$SQL = "SELECT count(*) as count FROM work_flows WHERE processes_ref = :flowID AND command = :label AND workFlowType_ref = 6";
			$params = array(
				'flowID' => Settings::get('flowID'),
				'label' => $label
			);
			$result = $this->db->query($SQL, $params)->fetch();

			if ($result['count'] == 0) {
				$SQL = "SELECT * FROM work_flows WHERE command = :label AND workFlowType_ref = 6";
				$params = array(
					'label' => $label
				);				
			} else {
				$SQL = "SELECT * FROM work_flows WHERE processes_ref = :flowID AND command = :label AND workFlowType_ref = 6";
			}
		} else {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id  = :to";
			$params = array('to' => $to);
		}
				
		$this->readWorkFlowSettings($SQL, $params);
	}

	function setPreviousFlow () {
		$current_process_id = Settings::get('flowID');
		
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = :flowID".
			" AND sec_no < :work_sec_no ORDER BY sec_no DESC LIMIT 0,1";
			
		$params = array(
			'flowID' => Settings::get('flowID'),
			'work_sec_no' => $this->work_sec_no
		);
		
		if (! $this->readWorkFlowSettings ($SQL, $params, "P")) {
			$SQL = "SELECT * FROM processes WHERE processes_id = :flowID";
			$rs = $this->db->query($SQL, array('flowID' => Settings::get('flowID')));
			if ($row = $rs->fetch()) {
				if ($row["may_go_previous"] == "yes") {
					$SQL = "SELECT * FROM processes WHERE currentFlow_next_process_ref = :flowID";
					$rs = $this->db->query($SQL, array('flowID' => Settings::get('flowID')));
					if ($row = $rs->fetch()) {
						$this->endOfFlow ($row["processes_id"]);
						$this->updateActiveProcesses ();
					}
				}
			}
		}
	}

	function clearWorkflowSettings () {
		Settings::set('workFlow_settings', array());
		$this->dbTableInfoArray = array();
		$this->dbTableCurrent = "";
		Settings::set('active_processes_id', 0);
	}

	function functionSettings ($settings) {
		if ( strncasecmp ($settings, "@IF", 3) == 0 ){
			$start = strpos  ($settings, "(");
			$end   = strrpos ($settings, ")");
			$funcFull = substr ($settings, $start+1, ($end-$start-1));
			$funcIF = explode (",", $funcFull);
			if ( count ($funcIF) == 3) {
				$this->mis_eval_pre(__LINE__, __FILE__);
				$result = eval('return('.$funcIF[0].');');
				$this->mis_eval_post("return(".$funcIF[0].");");
				$new_user_settings = (($result)?($funcIF[1]):($funcIF[2]));
				$new_user = $this->db->getDBsettingsValue ($new_user_settings);
			} else {
				$this->AuditLog->writeLogInfo (3, "CurrentFlow", "Error in function: ".$settings);
				die ("CurrentFlow - Error in function: ".$settings);
			}
		} else {
			$new_user = $this->db->getDBsettingsValue($settings);
		}
		return ($new_user);
	}

	function setNextFlow () {
		$current_process_id = Settings::get('flowID');
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = :flowID".
			" AND sec_no > :work_sec_no ORDER BY sec_no LIMIT 0,1";
		$params = array(
			'flowID' => Settings::get('flowID'),
			'work_sec_no' => $this->work_sec_no
		);
		if (! $this->readWorkFlowSettings($SQL, $params, "N")) {
			$SQL = "SELECT * FROM processes WHERE processes_id = :flowID";
			$rs = $this->db->query($SQL, array('flowID' => Settings::get('flowID')));
			if ($row = $rs->fetch()) {
					// Check if we need to set any fields
					/*	WHAT IS THIS????	*/
				if ($row["post_process_field"]>"" ) {
					$setField = explode ("|", $row["post_process_field"]);
					if (count($setField) == 3) {
						$this->setValueInTableInActiveProcess ($setField[0], $setField[1], $setField[2], $this->view);
					}
				}

					// First check if we need to spawn the process as they would need
					// the current workflow settings by default.
				if ( $row["spawnUser_settings_key"]>"" ) {
					if ( $row["spawnUser_next_process_ref"]>0 ) {
						// old way - now we test for an IF
						// $new_user = $this->getDBsettingsValue($row["spawnUser_settings_key"]);
						$new_user = $this->functionSettings ($row["spawnUser_settings_key"]);
						$id = $this->addActiveProcesses ($row["spawnUser_next_process_ref"], $new_user, 0, 0, true);
						// BUG: the spawn user does not get his own text, but the normal flow text
						$this->mailProcessAppointment ($id, $current_process_id, $new_user);
					}
				}

				// 2004-05-07: Diederik - If we go to home, we do not needs settings
				if ( ($row["currentFlow_next_process_ref"]<=2) && ($row["currentFlow_next_process_ref"]!=0) ) {
					$row["keep_workflow_settings"] = "no";
				}

				if ($row["keep_workflow_settings"] == "no") {
						// It seems that the process endid here
					$this->completeActiveProcesses ();
						// If we do not need to ceep the workflow settings,
						// it needs to be cleard
					$this->clearWorkflowSettings ();
					// $this->startFlow (__HOMEPAGE);  // BUG: Go to Home Page
				}

				if (! ($row["currentFlow_settings_key"]>"") ) {
					$this->startFlow ($row["currentFlow_next_process_ref"]);
					$this->updateActiveProcesses ();
				} else {
					// use functions so that proccess could give jobs to different people
					// if it starts wirg @if it is a function

					$new_user = $this->functionSettings ($row["currentFlow_settings_key"]);

					if (Settings::get('currentUserID') == $new_user) {
						$this->startFlow ($row["currentFlow_next_process_ref"]);
					} else {
						$procID = $this->checkSkipFlow($row["currentFlow_next_process_ref"]);
						$this->changeActiveProcesses ($procID, $new_user);
						$this->mailProcessAppointment (Settings::get('active_processes_id'), $current_process_id, $new_user);
						$this->clearWorkflowSettings ();
						$this->startFlow (__HOMEPAGE);  // BUG: go to Home Page
									// SHOULD WE GO TO HOME HERE????
					}
				}
			}
		}
	}

	function setCurrentFlow ($id) {
		$SQL = "SELECT * FROM work_flows WHERE work_flows_id = :id";
		$this->readWorkFlowSettings($SQL, compact('id'));
	}

	function skipThisFlow () {
		$this->checkCurrentFlow ();
		$this->readTemplate ();
	}

	function startFlow ($processID) {
		$processID = $this->checkSkipFlow($processID);

		$SQL = "SELECT * FROM work_flows WHERE processes_ref = :processID ORDER BY sec_no LIMIT 0,1";
		$params = compact('processID');

		if ($processID == 0) {
			$processID = $this->prev_flowID;
			$SQL = "SELECT * FROM work_flows ".
			  "WHERE processes_ref = :processID".
				 " AND work_flows_id = :flowID".
				" ORDER BY sec_no LIMIT 0,1";
			$params['flowID'] = $this->prev_workFlowID;
		} else {
			$this->doChangeDueDate = true;
		}
		$this->readWorkFlowSettings($SQL, $params);

		// Before we start, remember the previous process numbers
		$this->savePrevInfo (Settings::get('flowID'), Settings::get('workFlowID'));
	}

	function endOfFlow ($processID) {
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = :processID ORDER BY sec_no DESC LIMIT 0,1";
		$this->readWorkFlowSettings($SQL, compact('processID'));
	}

	// Short verion of currentTableFieldInfo
	function readTFV ($value) {
		$tmp = $this->currentTableFieldInfo ($value);

		//inserted this "if" to deal with private and public providers. BUG:
		if ($value == "InstitutionType") $tmp = $this->db->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_desc", $tmp, "lnk_priv_publ_id");
		return $tmp;
		//return ( $this->currentTableFieldInfo ($value) );
	}

	function checkSkipFlow($processID) {
		$SQL = "SELECT * FROM processes WHERE processes_id = :processID";
		$rs = $this->db->query($SQL, compact('processID'));

		$row = $rs->fetch();
		if ($row["currentFlow_skip_test"] > "")	 {
			$this->mis_eval_pre(__LINE__, __FILE__);
			$doSkip = eval($row["currentFlow_skip_test"]);
			$this->mis_eval_post($row["currentFlow_skip_test"]);
			if (!strcasecmp ($doSkip, "true")) {
				$processID = $row["currentFlow_next_process_ref"];
				$processID = $this->checkSkipFlow($processID);
			}
		}

		return $processID;
	}

	function readWorkFlowSettings($SQL, $params = array(), $direction="N") {
		$good = false;
		$rs = $this->db->query($SQL, $params);
		
		$rows = $rs->fetchAll();
		if (isset($rows[0])) {
			if ($rows[0]["workFlowType_ref"] > 1) {
					// If this is NOT a Template go until we get one
				$rs = $this->wrk_getWorkFlowRS($this->execLogic($rows[0]["work_flows_id"], $direction));
				$rows = $rs->fetchAll();
					// now RS should be a template or nothing
			}
		}

		if (isset($rows[0])) {
			$row = $rows[0];
			Settings::set('flowID', $row["processes_ref"]);
			Settings::set('template', $row["template"]);
			Settings::set('validation', $row["validation"]);
			Settings::set('workFlowID', $row["work_flows_id"]);
			$this->work_sec_no = $row["sec_no"];
			Settings::set('securityLevel', $row["securityLevel"]);

			if (Settings::get('securityLevel') > 0 && Settings::isEmpty('active_processes_id')) {
				$this->createNewActiveProcesses ();
			}
			$good = true;
		}
		return $good;
	}

	function createNewActiveProcesses () {
		if (Settings::isIsset('currentUserID') && Settings::get('flowID') > 2) {
				// 1 & 2 is generic screens with no flow info
			$id = $this->addActiveProcesses (Settings::get('flowID'), Settings::get('currentUserID'), Settings::get('workFlowID'));
			Settings::set('active_processes_id', $id);
			Settings::set('workFlow_settings.ACTPROC', Settings::get('active_processes_id'));
		}
	}

	function updateActiveProcesses () {
		if (! ($this->viewPage>0) ) {
			if (Settings::isIsset('currentUserID') && Settings::isIsset('active_processes_id') && Settings::get('currentUserID') > 0 && Settings::get('flowID') != 1) {
				$params = array(
					'flowID' => Settings::get('flowID'),
					'workflowID' => Settings::get('workFlowID'),
					'workflowSettings' => $this->getStringWorkFlowSettings(),
					'userID' => Settings::get('currentUserID'),
					'activeProcessId' => Settings::get('active_processes_id')
				);

				$dueDate = '';
				$expiryDate = '';
				if ($this->doChangeDueDate) {
					$this->doChangeDueDate = false;
					$dueDate = ", due_date = :dueDate";
					$params['dueDate'] = $this->getDueDate(Settings::get('flowID'));
					$expiryDate = ", expiry_date = :expiryDate";
					$params['expiryDate'] = $this->getExpiryDate(Settings::get('flowID'));
				}

				$this->AuditLog->writeLogInfo(100, "SETTINGS", "The following active_processes has been updated:\n\nID: ".Settings::get('active_processes_id')."\nPROCESS: ".Settings::get('flowID')."\nWORK_FLOW_REF: ".Settings::get('workFlowID')."\nWORKFLOW_SETTINGS: ".$this->getStringWorkFlowSettings ()."\n\nUSER_REF: ".Settings::get('currentUserID')."\nSTATUS: did not change");

				$SQL = "UPDATE active_processes SET" .
					" processes_ref = :flowID" .
					", work_flow_ref = :workflowID" .
					", workflow_settings = :workflowSettings" .
					", user_ref = :userID" .
					", last_updated = now()" .
					$dueDate .
					$expiryDate .
					" WHERE active_processes_id = :activeProcessId";
				$rs = $this->db->query($SQL, $params);

				$this->AuditLog->writeAuditTrail(Settings::get('active_processes_id'),"updateActiveProcesses","Previous: Process-".$this->prev_flowID." Workflow-".$this->prev_workFlowID);
			}
		}
	}

	function completeActiveProcesses () {
		if (Settings::isIsset('currentUserID') && Settings::isIsset('active_processes_id') && Settings::get('currentUserID') > 0 && Settings::get('flowID') > 2 && Settings::get('active_processes_id')>0) {

			$this->AuditLog->writeLogInfo(100, "SETTINGS", "The following active_processes has been completed:\n\nID:".Settings::get('active_processes_id'));

			$SQL = "UPDATE active_processes SET".
				" status = 1".
				" WHERE active_processes_id = ".Settings::get('active_processes_id');
			$this->db->query($SQL) or die ($this->db->lastError[2]);

			$this->AuditLog->writeAuditTrail(Settings::get('active_processes_id'),"completeActiveProcesses","Active process: ".Settings::get('active_processes_id')." completed");
		}
	}

	function getDueDate($process, $otherDate="", $dateType="processes_due_duration") {
		$ret = "1970-01-01";

		$SQL = "SELECT * FROM processes WHERE processes_id = :process";
		$rs = $this->db->query($SQL, compact('process'));
		if ($row = $rs->fetch()) {
			if ($row[$dateType] > 0) {
				if ($otherDate > "") {  // we have another date to work from
					$theDate = strtotime($otherDate);
				} else {
					$theDate = mktime();
				}

				$ret = date("Y-m-d", mktime(0, 0, 0, date("m", $theDate), date("d", $theDate) + $row[$dateType], date("Y", $theDate)));
			}
		}

		return $ret;
	}

	/* 2004-05-13: Diederik
	   Extention of function getDueDate which
	   works on coloumn processes_expiry_duration
	*/
	function getExpiryDate($process, $otherDate="") {
		$ret = $this->getDueDate ($process, $otherDate, "processes_expiry_duration");
		return ($ret);
	}

	function changeActiveProcesses ($process, $user, $work_flow=0) {
		if (Settings::isIsset('currentUserID') && Settings::isIsset('active_processes_id') && Settings::get('currentUserID') > 0 && Settings::get('active_processes_id')>0) {
			$this->addProcessText .= "The process (".$this->db->getValueFromTable("processes", "processes_id", $process, "processes_desc").") has been handed over to ".$this->db->getValueFromTable("users", "user_id", $user, "email")."\n<br>\n";
			$dueDate = "";
			$expiryDate = "";
			// die volgende if is uit want ons GLO hy moet dit altyd doen.
			// volgende 3 lyne
			$this->doChangeDueDate = false;
			$dueDate = ", due_date = \"".$this->getDueDate($process)."\"";
			$expiryDate = ", expiry_date = \"".$this->getExpiryDate($process)."\"";

			$this->AuditLog->writeLogInfo(100, "SETTINGS", "The following active_processes has been updated:\n\nID: ".Settings::get('active_processes_id')."\nPROCESS: ".$process."\nWORK_FLOW_REF: ".$work_flow."\nWORKFLOW_SETTINGS: ".$this->getStringWorkFlowSettings ()."\n\nUSER_REF: ".$user."\nSTATUS: none (default from DB)");

			$SQL = "UPDATE active_processes SET".
				" processes_ref = ".$process.
				", work_flow_ref = ".$work_flow.
				", workflow_settings = \"".system_escape_i($this->getStringWorkFlowSettings (), $this->conn).'"'.
				", user_ref = ".$user.
				", last_updated = now()".
				$dueDate.
				$expiryDate.
				" WHERE active_processes_id = ".Settings::get('active_processes_id');
				//.Settings::get('workFlowID').
			$rs = mysqli_query($SQL) or die($SQL);

			$this->AuditLog->writeAuditTrail(Settings::get('active_processes_id'),"changeActiveProcesses", "Previous: User-".Settings::get('currentUserID'). " Process-".Settings::get('flowID')." Workflow-".Settings::get('workFlowID'));

		} else {
			//added this "if" so that the system doesn't give/create home processes.
			if ($process != 2) {
				$this->addActiveProcesses ($process, $user);
			}
		}
	}

	/*
	*/
	function addActiveProcesses ($process, $user, $flow_ref=0, $status=0, $doActiveDate=false, $newWorkFlow="<<EXISTING>>", $displayEOP=true) {
		//CHECK IF PROCESS IS NOT THE "EndOfProc (100)" PROCESS
		if ( $displayEOP && $process != 100) {
			$this->addProcessText .= "The process (".$this->db->getValueFromTable("processes", "processes_id", $process, "processes_desc").")<br>\nhas been given to ".$this->db->getValueFromTable("users", "user_id", $user, "email")."\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n";
		}
		$activeDate = "1970-01-01";
		$dueDate = $this->getDueDate($process);
		$expiryDate = $this->getExpiryDate($process);

		if ($newWorkFlow == "<<EXISTING>>") {
			$newWorkFlow = $this->getStringWorkFlowSettings ();
		}
		if ($doActiveDate) {
			$dateEvel = $this->db->getValueFromTable("processes", "processes_id", $process, "spawnUser_activeDate");
			if ($dateEvel > "") {
				$this->mis_eval_pre(__LINE__, __FILE__);
				$activeDate = eval($dateEvel);
				$this->mis_eval_post($dateEvel);
				// We have a activeDate, we should change the due date
				$dueDate = $this->getDueDate($process, $activeDate);
				$expiryDate = $this->getExpiryDate($process, $activeDate);
			}
		}

		$this->AuditLog->writeLogInfo(100, "SETTINGS", "The following active_processes has been created:\n\nPROCESS: ".$process."\nWORK_FLOW_REF: ".$flow_ref."\nWORKFLOW_SETTINGS: ".$newWorkFlow."\n\nUSER_REF: ".$user."\nSTATUS: ".$status);

		$SQL = "INSERT INTO active_processes ".
					 "(processes_ref, work_flow_ref, workflow_settings , user_ref, status,last_updated, active_date, due_date,expiry_date) VALUES (".$process.", $flow_ref, \"".$newWorkFlow."\", ".$user.", $status, now(), \"$activeDate\", \"$dueDate\",\"$expiryDate\")";

		$rs = $this->db->query($SQL);
		$id = $this->db->lastInsertId();

		$this->AuditLog->writeAuditTrail($id,"addActiveProcesses","Previous: Active Process-".Settings::get('active_processes_id')." User-".Settings::get('currentUserID'). " Process-".Settings::get('flowID').", Workflow-".Settings::get('workFlowID'));

		return ( $id );
	}

	function saveWorkFlowSettings () {
		$str = $this->getStringWorkFlowSettings ();
		if ($str > "") {
			$this->formHidden["WORKFLOW_SETTINGS"] = htmlentities($str);
		}
	}

	/* Diederik
		2004-04-15: enable this function to use alt WorkFlow Settings
	*/
	function getStringWorkFlowSettings ($otherWorkFlow=NULL, $actproc=true) {
		$useWorkFlow = Settings::get('workFlow_settings');
		if ($otherWorkFlow!=NULL) {
			$useWorkFlow = $otherWorkFlow;
		}
		if (!$actproc && isset($useWorkFlow["ACTPROC"])) {
			unset ($useWorkFlow["ACTPROC"]);
		}
		$str = "";
		if ( count ($useWorkFlow) > 0 ) {
			$arr = array ();
			foreach ($useWorkFlow as $key => $val) {
				array_push($arr, urlencode($key)."=".urlencode($val));
			}
			$str = implode ("&", $arr);
		}
		return ($str);
	}

	//$ownFromAdr is to specify wheather to use own person's e-mail address for mail header
	function misMail ($userid, $subject, $message, $cc="", $ownFromAdr=true) {
		$ToAddress = $this->db->getValueFromTable("users", "user_id", $userid, "email");
		$this->Email->misMailByName($ToAddress, $subject, $message, ($cc>"")?($cc):(""), $ownFromAdr);
	}

	//This functions sends e-mail to an evaluator
	function misEvalMail ($userid, $subject, $message, $cc="", $ownFromAdr=true) {
		$ToAddress = $this->db->getValueFromTable("Eval_Auditors", "Persnr", $userid, "E_mail");
		$this->Email->misMailByName($ToAddress, $subject, $message, ($cc>"")?($cc):(""), $ownFromAdr);
	}


	function htmlEmail ($address, $subject, $message, $attachments="", $isHTML=true) {

     /*
       HTMLMAIL does not have the right from headers
		   and needs some work
		*/
    $message = 'The HTMLEMAIL function does not have the right from address\nlook at the misMailByName for assistance';
    $this->Email->misMailByName ("heqc@octoplus.co.za", "HTMLEMAIL need work", $message);

		$mail = new PHPMailer();

		$mail->IsMail();
/*		$mail->Host = $this->Host;
		$mail->From = $this->From;
		$mail->FromName = $this->FromName;
		$mail->AddReplyTo($mail->From, $mail->FromName);
*/		$mail->WordWrap = 75;
		$mail->IsHTML($isHTML);

		$mail->AddAddress($address);

		if ((!is_array($attachments)) && ($attachments > "")) {
			$attachments = array($attachments);
		}

		$attachment_list = array ("jpg"=>"image/jpeg", "gif"=>"image/gif", "png"=>"image/png");

		if (is_array($attachments) && (count($attachments) > 0)) {
			foreach ($attachments AS $att) {
				$type = substr($att, (strlen($att)-3), strlen($att));
				if (array_key_exists($type, $attachment_list)) {
					$mail->AddEmbeddedImage($att, basename($att), $att, "base64", $attachment_list[$type]);
				}else {
					$mail->AddAttachment($att);
				}
			}
		}

		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = "";

		if (Settings::get('mayMail')) {
			$mail->Send();
			$this->AuditLog->writeLogInfo(10, "EMAIL", "An e-mail with subject ".$mail->Subject." was sent to ".$address.". The body of the e-mail was:\n\n".$mail->Body);
		}
	}

	function getCurrentDate($format="") {
		if (! ($format > "") ) {
			$format = "Y-m-d H:i:s";
		}
		return date($format);
	}

	function makeDateFromTimestamp($str, $timeStamp="") {
		if (strlen($timeStamp)>0) {
			$timeStamp = strtotime($timeStamp);
		}else {
			$timeStamp = mktime();
		}
		return date($str, $timeStamp);
	}

	function validateForm($reportArr, $table, $key, $value) {
		$SQL = "SELECT * FROM `".$table."` WHERE ".$key."='".$value."'";
		$rs = mysqli_query($SQL);

		if ($row = mysqli_fetch_assoc($rs)) {
			foreach ($reportArr as $key => $value) {
				$validateOK = false;
				$lnk1 = ""; $lnk2 = "";

				$validateValue = (isset($value[3]))?($value[3]):("");
				$validateType = (isset($value[2]))?($value[2]):(">");

				switch ($validateType) {
					case ">":
						if ($row[$key] > $validateValue) $validateOK =  true;
						break;
					case "=":
						if ($row[$key] == $validateValue) $validateOK =  true;
						break;
					case "<":
						if ($row[$key] < $validateValue) $validateOK =  true;
						break;
					case "!=":
						if ($row[$key] != $validateValue) $validateOK =  true;
						break;
				}
				if ($validateOK) {
					$image = Settings::get('imageOK');
				} else {
					if ($value[1] > 0) {
						$lnk1 = '<a href="javascript:moveto('.$value[1].');">';
						$lnk2 = "</a>";
					}
					$image = Settings::get('imageWrong');
					// BUG: Next should not be commented out.
					//$this->formActions["next"]->actionMayShow = false;
				}
				echo '<tr><td class="oncolour">'.$lnk1.'<img src="images/'.$image.'" border=0>'.$lnk2."&nbsp;".$value[0].'</td></tr>'."\n";
			}
		}
	}

	// Robin 19/02/2008 Replaced this function with a new validation function: validateFields() that can handle
	// multiple child records that need to be validated.
	function validateFields_old ($fieldTemplate){
		$SQL = "SELECT * FROM template_field WHERE template_name='".$fieldTemplate."'";
		$RS = mysqli_query($SQL);
		$match = array();
		while ($row = mysqli_fetch_array($RS)) {
			$regexpSQL = "SELECT fieldValidationRegExp FROM template_field_validation, template_field WHERE template_field.fieldValidationName=template_field_validation.fieldValidationName AND template_field_id='".$row["template_field_id"]."'";
			$regexpRS = mysqli_query($regexpSQL);
			while ($regexpRow = mysqli_fetch_array($regexpRS)) {
//changed to get info from work_flows instead of template info table
				$tblSQL = "SELECT template_dbTableName FROM work_flows WHERE template='".$fieldTemplate."'";
				$tblRS = mysqli_query($tblSQL);
				if ($tblRow = mysqli_fetch_array($tblRS)) {
//changed $this->dbTableCurrent to $tblRow[0]: 2004-08-20
					$valueSQL = "SELECT ".$row["fieldName"]." FROM ".$tblRow[0]." WHERE ".$this->dbTableInfoArray[$tblRow[0]]->dbTableKeyField."='".$this->dbTableInfoArray[$tblRow[0]]->dbTableCurrentID."'";
					$valueRS = mysqli_query($valueSQL);
					if ($valueRS && ($valueRow = mysqli_fetch_array($valueRS))) {
						$lnk1 = $lnk2 = $message = "";
						if (preg_match($regexpRow[0], $valueRow[0])) {
							$image = Settings::get('imageOK');
						} else {
							$SQLdesc = "SELECT fieldValidationDesc FROM `template_field_validation`, template_field WHERE template_field.fieldValidationName=template_field_validation.fieldValidationName AND template_field_id='".$row["template_field_id"]."'";
							$RSdesc = mysqli_query($SQLdesc);
							if ($rowdesc = mysqli_fetch_array($RSdesc)){ $message = $rowdesc["fieldValidationDesc"];}
							$image = Settings::get('imageWrong');
							$moveToSQL = "SELECT work_flows_id FROM work_flows WHERE template='".$fieldTemplate."'";
							$moveToRS = mysqli_query($moveToSQL);
							if ($moveToRow = mysqli_fetch_array($moveToRS)) {
								$lnk1 = '<a href="javascript:moveto('.$moveToRow[0].');">';
								$lnk2 = "</a>";
							}
							$this->formActions["next"]->actionMayShow = false;
						}
						echo '<tr><td align="center" class="oncolour">'.$lnk1.'<img src="images/'.$image.'" border=0>'.$lnk2."</td><td class='oncolour'>".$this->showFieldDisplayName($fieldTemplate, $row["fieldName"]).'&nbsp;&nbsp;&nbsp;<font color="red">'.$message.'</font></td></tr>'."\n";					}
				}
			}
		}
	}

	// Robin 19/02/2008 Replaces validation function: validateFields_old().
	function validateFields ($fieldTemplate, $dataKey="", $dataId="", $type=""){		
		//changed to get info from work_flows instead of template info table
		$tblSQL = "SELECT template_dbTableName, work_flows_id FROM work_flows WHERE template='".$fieldTemplate."'";
		$tblRS = $this->db->query($tblSQL);
		if ($tblRS  && $tblRS->rowCount() > 0 ) {
			$tblRow = $tblRS->fetch();
			$currentTableKey = ($dataKey > "") ? $dataKey : $this->dbTableInfoArray[$tblRow[0]]->dbTableKeyField;
			$currentTableId = ($dataId > "") ? $dataId : $this->dbTableInfoArray[$tblRow[0]]->dbTableCurrentID;
		} else {
			// no validation can be done if no base table because you cannot get hold of the captured field values.
			return false;
		}

		$SQL = "SELECT * FROM template_field WHERE template_name = :fieldTemplate ORDER BY fieldOrder";	

		$RS = $this->db->query($SQL, compact('fieldTemplate'));
		if($type = "manual"){
			$this->manualValidation($fieldTemplate, $tblRow[0], $currentTableId,$tblRow["work_flows_id"]);
		}
		$match = array();
		while ($row = $RS->fetch()) {
			$regexpSQL = <<<regexpSQL
				SELECT fieldValidationRegExp, fieldValidationDesc, fieldValidationTitle,
					fieldValidationCondition, fieldDisplayName
				FROM template_field_validation, template_field
				WHERE template_field.fieldValidationName=template_field_validation.fieldValidationName
				AND template_field_id='$row[template_field_id]'
regexpSQL;
			$regexpRS = $this->db->query($regexpSQL);
			while ($regexpRow = $regexpRS->fetch()) {
				$evalRes =  true;

				$condition = $regexpRow["fieldValidationCondition"];

				// Validate field only if its condition is satisfied.
				if ($condition > ""){
					$evalStr = "return (($condition)?(true):(false));";

					//$this->mis_eval_pre(__LINE__, __FILE__);
					$evalRes = eval($evalStr);
					if ($evalRes === false) {
						//commented out by Rebecca (2008-03-20) - condition will sometimes fail, we don't want it emailing us every time.
						//$this->AuditLog->writeLogInfo (3, "EVALd error", "Cannot evaluate code: ".mysqli_real_escape_string($evalStr), true);
					}
					//$this->mis_eval_post($evalStr);
				}
				$trClass = '';
				$alt = '';
				if ($evalRes) {
					//changed $this->dbTableCurrent to $tblRow[0]: 2004-08-20
					$valueSQL = "SELECT ".$row["fieldName"]." FROM ".$tblRow[0].
								" WHERE ".$currentTableKey."='".$currentTableId."'";

					$valueRS = $this->db->query($valueSQL);
					if ($valueRS && ($valueRow = $valueRS->fetch())) {
						$lnk1 = $lnk2 = $message = "";
							
						if (preg_match($regexpRow[0], $valueRow[0])) {
							$image = Settings::get('imageOK');
							$trClass = "success";
							$alt = 'this field has been completed';
						} else {
							$message = $regexpRow["fieldValidationDesc"];
							$image = Settings::get('imageWrong');
							$trClass = "error";
							$alt = 'click to go to the page';
							$jscript = $this->scriptGetForm ($tblRow[0], $currentTableId, $tblRow["work_flows_id"]);
							$lnk1 = "<a href='".$jscript."'>";
							$lnk2 = "</a>";
							$this->formActions["next"]->actionMayShow = false;
						}

	//					$showField = $this->showFieldDisplayName($fieldTemplate, $row["fieldName"]);
						$showField = $regexpRow["fieldDisplayName"];
						$showTitle = $regexpRow["fieldValidationTitle"];
						$htmlRow = <<<htmlRow
						 	<tbody>
								<tr class = $trClass>
									<td>$lnk1<img src="images/$image" alt = '$alt'>$lnk2</td>
									<td>$showTitle $showField</td>
									<td><font color="red">$message</font></td>
								</tr>
							</tbody>
htmlRow;
						echo $htmlRow;
					}
				}
			}
		}
	}


	// Robin 19/02/2008 Handles validation for one to many relationships
	function validateFieldsperChild ($childTitle, $fieldTemplate, $parent_ref_key, $parent_ref_val){
		//changed to get info from work_flows instead of template info table
		$tblSQL = "SELECT template_dbTableName, template_dbTableKeyField, work_flows_id
			FROM work_flows WHERE template='".$fieldTemplate."'";
		$tblRS = mysqli_query($tblSQL);
		if ($tblRS  && mysqli_num_rows($tblRS) > 0 ) {
			$tblRow = mysqli_fetch_array($tblRS);
		} else {
			// no validation can be done if no base table because you cannot get hold of the captured field values.
			return false;
		}

		// get all the child rows
		$childSQL = "SELECT ". $tblRow["template_dbTableKeyField"].
			" FROM ".$tblRow["template_dbTableName"].
			" WHERE ".$parent_ref_key."='".$parent_ref_val."'";
		$childRS = mysqli_query($childSQL);
		$nChild = mysqli_num_rows($childRS);
		if ($childRS && ($nChild > 0)) {
			while ($childRow = mysqli_fetch_array($childRS)){
				// bug - Robin 6/6/2008. Sites can be deleted in institutional profile. Need to disbale this.
				// If site was selected for an application then reference is not deleted and all application
				// for that site is not deleted. Get out of sync between applications per site and actual sites.
				// Adding workaround to ignore if site not found
				if (isset($childTitle["$childRow[0]"])){
					echo '<tr><td class= "oncolour" colspan="2">'.$childTitle["$childRow[0]"].'</td></tr>';
					$this->validateFields($fieldTemplate,$tblRow["template_dbTableKeyField"], $childRow[0]);
				}

			}
		}

	}

	// Added parameter $fieldTemplate RTN 8/6/2005
	function showFieldDisplayName ($fieldTemplate,$fieldName) {
		$SQL = "SELECT fieldDisplayName FROM template_field WHERE fieldName='".$fieldName."' AND template_name='".$fieldTemplate."'" ;
		$RS = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($RS)) {
			return $row[0];
		}
	}

	function mailProcessAppointment ($active_proc_id, $proc_id, $user_id, $spawn=false) {
		if ($proc_id > __HOMEPAGE) {
			$flow_message = (($spawn)?("spawnUser"):("currentFlow"))."_message";

			// get the settings of the proc_id
			$SQL = "SELECT * FROM processes WHERE processes_id = ".$proc_id;
			$rs = $this->db->query($SQL);
			$row = $rs->fetch();
			
			// Compose the e-mail message
			$desc_fields = explode ("|", $row["desc_fields"]);
			$subject = $row["processes_desc"];
			if (isset($desc_fields[0])) {
				$subject .= " - ".$this->table_field_info($active_proc_id, $desc_fields[0]);
			}

			$message = $this->db->getDBsettingsValue("process_appoint_message")."\n\n";
			foreach ($desc_fields as $field) {
				$message .= $this->table_field_info($active_proc_id, $field, true);
				$message .= ": ";
				$message .= $this->table_field_info($active_proc_id, $field);
				$message .= "\n";
			}
			$message .= "\n".$row[$flow_message]."\n\nHEQC Accreditation System";

			// send the e-mail
			$this->misMail ($user_id, $subject, $message, $cc="");
		}
	}

	// short for function table_field_info to use current Proccess ID
	function currentTableFieldInfo ($desc_field) {
		return ( $this->table_field_info (0, $desc_field) );
	}

	function table_field_info ($active_proc_id, $desc_field, $desc=false) {
		$ret = "";
		if ($active_proc_id != 0) { 	// read from proccess
			$tables = $this->parseOtherWorkFlowProcess ($active_proc_id);
		} else {											// do NOT read from other, but current
			$tables = $this->dbTableInfoArray;
		}
		$SQL = "SELECT * FROM table_field_info WHERE name = :desc_field";

		$rs = $this->db->query($SQL, compact('desc_field'));
		if ( isset($tables) && ($row = $rs->fetch()) ) {
			if (isset ($tables[$row["table_name"]]) ) {
				if (!$desc) {
					$SQL = "SELECT ".$row["field_name"].
								" FROM ".$row["table_name"].
								" WHERE ".$tables[$row["table_name"]]->dbTableKeyField.
								" = '".$tables[$row["table_name"]]->dbTableCurrentID."'";

					$rs2 = $this->db->query($SQL);
					if ($row2 = $rs2->fetch()) {

						$ret = $row2[0];
						// added the following line to make provision for public and private providers. The $row2[0] value is actually a lookup value for this field.
						// Should replace this by adding a lookup table in table_fields table
						if ($row["field_name"] == "priv_publ") $ret = $this->db->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row2[0], "lnk_priv_publ_desc");
						if ($desc_field == 'SiteVisitInstitution') $ret = $this->db->getValueFromTable("HEInstitution", "HEI_id", $row2[0], "HEI_name");
					}
				} else {
						// should only give the field description
					$ret = $row["description"];
				}
			}
		}
		return ($ret);
	}

	function workflowDescription ($active_processes_id, $proc_id) {
		$ret = "";
		// get the settings of the proc_id
		$SQL = "SELECT * FROM processes WHERE processes_id = ".$proc_id;
		$rs = $this->db->query($SQL);
		$row = $rs->fetch();

		$ret = $row["processes_desc"];
		// Compose the e-mail message
		$desc_fields = explode ("|", $row["desc_fields"]);
//		if (isset($desc_fields[0])) {
//			$desc_maybe = $this->table_field_info($active_processes_id, $desc_fields[0]);
//			if ($desc_maybe > "") {
//			$ret .= " - ".$desc_maybe;
//			}
//		}

		$taskName = $this->db->getValueFromTable("work_flows", "template", Settings::get('template'), "taskName");
		if ($taskName > "") {
			$ret .= " - ".$taskName;
		}
		$progDisc = $this->table_field_info($active_processes_id, "ProgrammeName");
		$HEQC_ref = $this->table_field_info($active_processes_id, "HEQC_ref");
		if ($progDisc > "" || $HEQC_ref > "") {
			$ret .= " (".$HEQC_ref." - ".$progDisc.")";
		}

		//BUG vir Internal Support
		if (Settings::get('debug_mode')) {
			$ret .= " (".Settings::get('flowID')." - ".Settings::get('template').")";
		}

		return ($ret);
	}

	function navTrailDisplay ($active_processes_id, $proc_id) {
		$ret = "";
		// get the settings of the proc_id
		$SQL = "SELECT * FROM processes WHERE processes_id = ".$proc_id;
		$rs = mysqli_query($SQL);
		$row = mysqli_fetch_array ($rs);

		$ret = ($this->NavigationBar) ? $this->NavigationBar : $row["processes_desc"];

		//DEBUG vir Internal Support - shows template and proc
		if (Settings::get('debug_mode')) {
			$ret .= " (".Settings::get('flowID')." - ".Settings::get('template').")";
		}

		return ($ret);
	}

	function showEmailAsHTML($tmp_name, $text_desc){
		$message = nl2br ($this->getTextContent ($tmp_name, $text_desc));
		echo '<table class=oncoloursoft width="80%" align=center cellpadding=2 cellspacing=2><tr><td>';
		echo "<fieldset>";
		echo "<legend><span class='specialb'><i>Message</i></span></legend>";
		echo $message;
		echo "</td></tr></table>";
		return $message;
	}

	function getTextContent ($tmp_name, $text_desc, $varArray="",$r="") {

		$SQL = "SELECT * FROM template_text WHERE template_ref = '$tmp_name' AND template_text_desc = '$text_desc'";

		$rs = $this->db->query($SQL);

		$text = "";
		if ($row = $rs->fetch()) {
			if ($varArray!="") {
				foreach ($varArray AS $key => $val) {
					// BUG: $val moet ge-escape word
					$e = '$'.$key.' = "'.$val.'";';
					$this->mis_eval_pre(__LINE__, __FILE__);
					eval($e);
					$this->mis_eval_post($e);
				}
			}
			$this->mis_eval_pre(__LINE__, __FILE__);
			eval($row["text_programming"]);
			$this->mis_eval_post($row["text_programming"]);

			$raw_text = '$text = "'.$row["text_actual"].'";';

			$this->mis_eval_pre(__LINE__, __FILE__);
			eval($raw_text);
			$this->mis_eval_post($raw_text);
		}

		return ($text);
	}

	function getTextContentWithoutProgramming ($tmp_name, $text_desc, $displayType="") {
		$SQL = "SELECT * FROM template_text WHERE template_ref = '$tmp_name' AND template_text_desc = '$text_desc'";
		$rs = mysqli_query ($SQL);

		$text = "";
		if ($row = mysqli_fetch_array($rs)) {
			$text = $row['text_actual'];

			if ($displayType == "docgen") {
				$match = preg_match('/(<ol>|<\/ol>|<ul>|<\/ul>)/', $text);
				if ($match == 1) {
					$text = preg_replace('/(<ol>|<\/ol>|<ul>|<\/ul>)/', "", $text);
				}

				$match = preg_match('/<li>/', $text);
				if ($match == 1) {
					$text = preg_replace('/<li>/', '<font face="sym">&amp;#U183</font><tab />', $text);
				}

				$match = preg_match('/<\/li>/', $text);
				if ($match == 1) {
					$text = preg_replace('/<\/li>/', '<br /><br />', $text);
				}
			}
		}

		return ($text);
	}

	function checkUserInDatabase($title_ref, $email, $surname, $name, $groupID=0) {
		$user_id = 0;

		// Check if the e-mail address is in user database.
		// 2009-07-27 Robin - email is unique in the user table so this works if the email address passed is the one in the user table.
		// Bug: If same individual has > 1 login (dual role e.g. Institutional administrator and evaluator) and the wrong email address
		// is passed.
		$SQL = "SELECT * FROM `users` WHERE email='".$email."'";
		$RS = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($RS)) {
			$user_id = $row["user_id"];
				// If so, check if the user is ACTIVE.
			if (!$row["active"]) {
					//If not ACTIVE, make the user active
				$activeSQL = "UPDATE `users` SET active=1 WHERE user_id='".$user_id."'";
				$activeRS = mysqli_query($activeSQL);
			}
		/* If the user is not in the database, add the user with its surname and name into the database as ACTIVE.  New users should have an institution ID of 0 and the INST TEMP DESC should be Evaluator.  Password should also be empty  Users with empry password should not be able to sign on.  The should go to forgot password.  An e-mail should be sent to the new user to go to forgot password, to receive a new password.
		*/

		} else {
			$fake_inst='';

			switch ($groupID) {
			case '14' : $fake_inst = 'AC_Member_CHE';
						break;
			case '15' : $fake_inst = 'Evaluator';
						break;
			}

			$insertSQL = "INSERT INTO `users` (user_id, name, surname, title_ref, password, email, active, institution_ref, institution_name) VALUES (NULL, '".$name."', '".$surname."', '".$title_ref."', '', '".$email."', 1, 0, '".$fake_inst."')";
			$insertRS = mysqli_query($insertSQL);
			$user_id = mysqli_insert_id();
				// BUG: message moet uit die database kom.
			//$message = "Please go to http://heqc-online.che.ac.za/ and click on forgot password to receive your new password.";
			$message = $this->getTextContent ("workFlowFunctions", "checkUserInDatabase");
			$this->misMail($user_id, "You are registered with the HEQC-online accreditation system", $message);
		}

/*
	$gSQL below added by Rebecca on 13 August 2007: so as to add user to Evaluator/AC members group (if not already in).
	This function is not used by anything besides obsolete templates, as well as those we are currently working with

*/
		$gSQL = "SELECT * FROM `sec_UserGroups` WHERE sec_user_ref='".$user_id."' AND sec_group_ref = '".$groupID."'";
		$gRS = mysqli_query($gSQL);
		//if ((!($g_row = mysqli_fetch_array($gRS))) && ($groupID=0)) {
		if (!($g_row = mysqli_fetch_array($gRS))) {
			$insertSQL = "INSERT INTO `sec_UserGroups` (sec_UserGroups_id, sec_user_ref, sec_group_ref) VALUES (NULL, '".$user_id."', '".$groupID."')";
			$insertRS = mysqli_query($insertSQL);
		}
		// make sure $user_id is the existing or new id depending on what happened above
		return ($user_id);
	}

	/*	Diederik
			Create: 2004-04-07
			Update:
			Description:
			Read info from the process table.
			If no parameter is given, read the Active Process info
			Return: the array from the database.
	*/
	public function readProcessInfo($ID = 0) {
		$processID = Settings::get('flowID');
		if ($ID > 0) $processID = $ID;

		$SQL = "SELECT * FROM processes WHERE processes_id = :processID";
		$rs = $this->db->query($SQL, compact('processID'));
		if ($rs->rowCount() == 0) {
			return null;
		}

		return $rs->fetch();
	}

	/* 2004-05-19 - Diederik
	   Send-l with attachments
	   filelist is a array of
	     arrays with (fileondisk, filename, contenttype)
		  20070823: mimemail is discontinued.
	*/
	function mimemail ($to, $from, $subject, $message, $filelist="") {
		// from not use
		$this->Email->misMailByName ($to, $subject, $message, "", true, $filelist);
	}

	function wrk_getWorkFlowRS($workFlowID) {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id = :workFlowID LIMIT 0,1";
			return $this->db->query($SQL, compact('workFlowID'));
	}

	function wrk_getNextWorkFlow($workFlowID, $direction="N") {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id = :workFlowID LIMIT 0,1";
			$rs = $this->db->query($SQL, compact('workFlowID'));
			$row = $rs->fetch();
			$order    = "";
			$operator = ">";
			if ($direction != "N") {
				$order    = "  DESC";
				$operator = "<";
			}

			$SQL = "SELECT * FROM work_flows WHERE processes_ref = :processes_ref AND sec_no ".$operator." :sec_no ORDER BY sec_no".$order." LIMIT 0,1";
			$rs = $this->db->query($SQL, array('processes_ref' => $row['processes_ref'], 'sec_no' => $row['sec_no']));
			$row = $rs->fetch();
			return $row["work_flows_id"];
	}

	function cleanTempActiveProccesses () {
		if (Settings::get('currentUserID') > 0) {
			$SQL = "SELECT active_processes_id FROM active_processes, processes WHERE processes_ref = processes_id AND keep_workflow_settings = 'temp' AND status = 0 AND user_ref = ".Settings::get('currentUserID')." ORDER BY last_updated DESC";
			$rs = $this->db->query($SQL);
			$procList = array ();
			while ($row = $rs->fetch() ) {
				array_push ($procList, $row["active_processes_id"]);
				$this->AuditLog->writeAuditTrail($row["active_processes_id"],"cleanTempActiveProccesses","Status set to 1 (completed)");
			}
			if ( count ($procList) > 0 ) {
				$SQLin = implode (", ", $procList);
				$this->AuditLog->writeLogInfo(100, "SETTINGS", "The following active_processes has been disabled ".$SQLin);
				$SQL = "UPDATE active_processes SET status=1 WHERE active_processes_id IN ($SQLin)";
				$this->db->query($SQL);
			}
			$SQL = "UPDATE active_processes SET status=1 WHERE processes_ref >= 0 AND processes_ref <= 2 AND status = 0 AND user_ref = ".Settings::get('currentUserID');
			$rs = $this->db->query($SQL);
		}
	}
	
	function fetchValidations($fieldTemplate, $fieldName){
		$return = array();
		
		$sql = "SELECT * FROM template_field LEFT JOIN template_field_validation ON (template_field_validation.fieldValidationName = template_field.fieldValidationName) WHERE template_name = :fieldTemplate AND template_field.fieldName = :fieldName ORDER BY fieldOrder";
		$rs = $this->db->query($sql, compact('fieldTemplate', 'fieldName'));
		
		while($row = $rs->fetch()){
			if(!empty($row['fieldValidationName'])){
				$return['fieldDisplayName'] = $row['fieldDisplayName'];
				$return['fieldValidationName'] = $row['fieldValidationName'];
				$return['fieldValidationDesc'] = $row['fieldValidationDesc'];
				$return['fieldValidationRegExp'] = $row['fieldValidationRegExp'];
			}
		}
		
		return $return;
	}
	
	function saveMultiGrid($saveList, $table, $lookupFld, $id, $saveFields, $listFields){
		$errorMail = false;
		$listFields = array_flip(explode('|', $listFields));
		$saveFields = array_flip($saveFields);
		$sqlInsert = "INSERT INTO " . $table;
		$sqlInsertArray = array();
		
		$sql = "DELETE FROM " . $table . " WHERE " . $lookupFld . " = " . $id . ';';
		$rs = $this->db->query($sql) or $errorMail = true;
		$this->AuditLog->writeLogInfo(10, "MULTISQL", $sql . "  --> " . $this->db->lastError[2], $errorMail);
		
		foreach($saveList as $fields => $value){
			$tempListFields = $listFields;
			$valuesArray = explode('|', $fields);
			$fieldValues = array();
			$valuesArray = array_flip($valuesArray);
			$finalValueFields = array();
			$finalValueFields[$lookupFld] = $id;
			
			foreach($valuesArray as $valueSave => $num){
				if(isset($tempListFields[$valueSave])){
					$finalValueFields[$valueSave] = $value;
					unset($tempListFields[$valueSave]);
					unset($valuesArray[$valueSave]);
				}
			}
			
			foreach($tempListFields as $fieldToSave => $num){
				if(isset($saveFields[$fieldToSave])){
					unset($tempListFields[$fieldToSave]);
				}
			}
			
			$valuesArray = array_flip($valuesArray);
			
			foreach($tempListFields as $value => $fieldID){
				$finalValueFields[$value] = $valuesArray[$fieldID];
			}
			
			if(!empty($finalValueFields)){
				$fields = array_keys($finalValueFields);
				$values = "'" . implode("', '", $finalValueFields) . "'";
				$fields = implode(", ", $fields);
				$sqlInsertArray[" (" . $fields . ") "][] = " (" . $values . ")";
			}
		}
		
		if(!empty($sqlInsertArray)){
			foreach($sqlInsertArray as $fields => $values){
				$sql = $sqlInsert;
				$sql .= $fields;
				$sql .= ' VALUES ' . implode(", ", $values);
				$sql .= ';';
				$rs = $this->db->query($sql);
			}
		}
	}

	function updateWorkFlowSettingforIPlan ($tableName, $IDFieldName, $fieldID="", $creationDate="", $lastUpdated="") {
		//word geroep vanuit template files
		//create a nuwe object en stel dbTableName na tableName
	$this->dbTableCurrent = $tableName;
	$this->curCreationDateField = $creationDate;
	$this->curLastUpdatedField = $lastUpdated;
	Settings::set('workFlow_settings.CURRENT_TABLE', $this->dbTableCurrent);

	if (empty($this->dbTableInfoArray[$tableName])) {
		$this->dbTableInfoArray[$tableName] = new dbTableInfo ($tableName, $IDFieldName, "NEW");
	}
	if ($this->dbTableInfoArray[$tableName]->dbTableKeyField == "") {
		$this->dbTableInfoArray[$tableName]->dbTableKeyField =$IDFieldName;
	}

	if ($fieldID>"") {
		$this->dbTableInfoArray[$tableName]->dbTableCurrentID = $fieldID;
	}

	$this->db_settingsKey = "DBINF_".$tableName."___".$IDFieldName;
	if (isset ($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
		Settings::set('workFlow_settings.' . $this->db_settingsKey, $this->dbTableInfoArray[$tableName]->dbTableCurrentID);
	}
	if (Settings::isIsset('workFlow_settings.' . $this->db_settingsKey)) {
		$this->dbTableInfoArray[$tableName]->dbTableCurrentID = Settings::get('workFlow_settings.' . $this->db_settingsKey);;
	}
	if ( empty($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
		$this->dbTableInfoArray[$tableName]->dbTableCurrentID = "NEW";
		Settings::set('workFlow_settings.' . $this->db_settingsKey, $this->dbTableInfoArray[$tableName]->dbTableCurrentID);
	}
}

// the end
}
