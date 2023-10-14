<?php

if (! isset($preventreloadCount) ) { $preventreloadCount = 0; }

if (! defined('SMTP_SERVER') ) die ('ERROR: EMAIL configuration error');

class workFlow extends flowLogic {
	var $flowID, $workFlowID, $validation, $work_sec_no, $workFlow_settings;
	var $prev_flowID, $prev_workFlowID;
	var $active_processes_id, $is_password_field;
	var $dbTableInfoArray;
	var $dbTableCurrent, $curCreationDateField, $curLastUpdatedField;
	var $doChangeDueDate;
	var $viewPage;
	var $mayMail;
	var $log_level;
	var $audit_level;
	var $debug_mode;
	var $public_holidays;
	var $private_docs, $public_docs;
	var $grid_compare_id;
	var $altEmailAddr;
	var $addProcessText;
	var $evalFile, $evalLine;
	var $displayUserMessage;

	function __construct ($flowID) {
		$this->doChangeDueDate = false;

		$this->workflow_settings ();

		$this->flowLogic();
		$this->getCurrentWorkFlow ($flowID);
		//we don't really need a postscript function
		//$this->runPostScript();
		if ($_SERVER['QUERY_STRING'] > "") {
			$this->writeLogInfo(100, "QUERY STRING", $_SERVER['QUERY_STRING']);
		}
		// not sure what workflow settings this script has or may use....
	}
	
	function workFlow($flowID){
            self::__construct($flowID);
	}

	function workflow_settings () {
		$this->TmpDir = WRK_TMPDIR;
		$this->imageOK = WRK_IMAGE_OK;
		$this->imageWrong = WRK_IMAGE_WRONG;

		$this->debug_mode = WRK_DEBUG_MODE;

		$this->log_level = WRK_LOG_LEVEL;
		// See workflow_audit_level table for ranges. 9999 => All processes. Level set in processes table per process.
		$this->audit_level = WRK_AUDIT_LEVEL;

		$this->mayMail = WRK_MAYMAIL;
	}

	//we don't really need a postscript function
/*	function runPostScript() {
		if ( isset($_POST["FLOW_ID"]) ) {
			$curFlow = $_POST["FLOW_ID"];

			if ( isset($_POST["WORKFLOW_SETTINGS"]) ) {
				$this->parseWorkFlowString ($_POST["WORKFLOW_SETTINGS"]);
			}
			$codePage = "proc/".$this->getTemplateName($_POST["FLOW_ID"]).".post.php";
			if (file_exists($codePage)) {
				include ($codePage);
			}
		}
	}*/

	function getTemplateName ($workFlowID) {
		$templateName = "";

		$SQL = "SELECT * FROM work_flows WHERE work_flows_id = ?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $workFlowID);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query ($SQL);
		if ($rs && ($row = mysqli_fetch_assoc($rs))) {
			$templateName = $row["template"];
		}

		return ($templateName);
	}

	function setActiveWorkFlow ($id) {
		$SQL = "SELECT * FROM active_processes WHERE".
					 " user_ref = ?".
					 " AND status = 0".
					 " AND active_date <= now()".
					 " AND active_processes_id = ?";
                $conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("ss", $this->currentUserID, $id);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		$row = mysqli_fetch_array ($rs);

		if (mysqli_affected_rows($conn) > 0) {
			if ($row["workflow_settings"] > "" ) {
				$this->parseWorkFlowString ($row["workflow_settings"]);
			}

			$this->active_processes_id = $id;
			$this->workFlow_settings["ACTPROC"] = $this->active_processes_id;

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
		$this->workFlow_settings["CURRENT_TABLE"] = $this->dbTableCurrent;

		if (empty($this->dbTableInfoArray[$tableName])) {
			$this->dbTableInfoArray[$tableName] = new dbTableInfo ($tableName, $IDFieldName, "NEW");
		}
//print_r($this->dbTableInfoArray);
		if ($this->dbTableInfoArray[$tableName]->dbTableKeyField == "") {
			$this->dbTableInfoArray[$tableName]->dbTableKeyField =$IDFieldName;
		}

		if ($fieldID>"") {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = $fieldID;
		}

		$this->db_settingsKey = "DBINF_".$tableName."___".$IDFieldName;
		if (isset ($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
			$this->workFlow_settings[$this->db_settingsKey] = $this->dbTableInfoArray[$tableName]->dbTableCurrentID;
		}
		if (isset ($this->workFlow_settings[$this->db_settingsKey]) ) {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = $this->workFlow_settings[$this->db_settingsKey];
		}
		if ( empty($this->dbTableInfoArray[$tableName]->dbTableCurrentID) ) {
			$this->dbTableInfoArray[$tableName]->dbTableCurrentID = "NEW";
			$this->workFlow_settings[$this->db_settingsKey] = $this->dbTableInfoArray[$tableName]->dbTableCurrentID;
		}

		//Louwtjie: To always have the HEI_id available
		if (! isset($this->dbTableInfoArray["HEInstitution"])) {
			if (isset($this->dbTableInfoArray["Institutions_application"])) {
				$this->dbTableInfoArray["HEInstitution"] = new dbTableInfo ("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"));
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

	function parseOtherWorkFlowProcess ($activeFlow) {
		$dbTableInfoArray = array();
		$SQL = "SELECT workflow_settings FROM active_processes WHERE active_processes_id = ?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $activeFlow);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($rs)) {
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
				$dbTableInfoArray["HEInstitution"] = new dbTableInfo ("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id"));
			}
		}

		return ($dbTableInfoArray);
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
		$newWorkFlow = $this->workFlow_settings;

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
		$this->workFlow_settings["PREV_WORKFLOW"] = $this->prev_flowID."|".$this->prev_workFlowID;
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

			$this->workFlow_settings[$parts[0]] = $parts[1];
			if (! strncmp($parts[0], "DBINF_", 6) ) {
				$this->db_settingsKey = $parts[0];
				$p = explode("___", substr ($parts[0], 6));
				$this->dbTableInfoArray[$p[0]] = new dbTableInfo ($p[0], $p[1], $parts[1]);
			}
			if (! strncmp($parts[0], "CURRENT_TABLE", 13) ) {
				$this->dbTableCurrent = $parts[1];
				$this->workFlow_settings["CURRENT_TABLE"] = $this->dbTableCurrent;
			}
			if (! strncmp($parts[0], "ACTPROC", 7) ) {
				$this->active_processes_id = $parts[1];
				$this->workFlow_settings["ACTPROC"] = $this->active_processes_id;
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
//$this->printVars($_POST);
		$save_grid_fields_array = array();
		$saveFields = array ();
		$saveMultipleFields = array ();
		$delMultipleFields = array ();
		$saveGRIDFields = array ();
		$newGridRows = array();
		
		$conn = $this->getDatabaseConnection();

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

		foreach($_POST as $key => $val) {

			if (! strncmp($key, "FLD_", 4) ) {
				$fldName = substr ($key, 4);
				if (isset($_POST["INFFT_".$fldName])) {
					$ftInf = explode ("_|_", $_POST["INFFT_".$fldName]);
					$this->setValueInTable ($ftInf[0], $ftInf[1], $ftInf[2], $fldName, $val);
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
					$SQLfield = "SELECT * FROM template_field WHERE template_name = ? AND fieldName = ?";
					$sm = $conn->prepare($SQLfield);
                                        $sm->bind_param("ss", $curTemplate, $fldName);
                                        $sm->execute();
                                        $RSfield = $sm->get_result();
					//$RSfield = mysqli_query ($SQLfield);
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
				if (!stristr($key, "save") && !stristr($key, "deleted")) {
					//We are still busy with the same row in DB. Extract the table, keyfield, values etc from post var.
					list ($id, $keyField, $colom, $table) = explode ('$', $key);

					//remove the GRID_ part from id.
					$id = substr($id, 5, strlen($id));
					//push the values on to the save array.
					array_push($save_grid_fields_array, $table."|".$keyField."|".$id."|".$colom."|".$val);
				} elseif (stristr($key, "deleted")) {
					$deletedIds = explode('|', $val);

					list($ignore, $table, $keyField) = explode('|', $key);

					foreach($deletedIds as $deletedId) {
						if(!stristr($deletedId, 'INSERT')) {
							$this->gridDeleteRow($table, $keyField, $deletedId);
						}
					}
				} else {
					//We are now at the end of a row in the DB and should save the values now.
					foreach ($save_grid_fields_array AS $grid_save_val) {
						//extract all values for saving.
						$field_val = explode("|", $grid_save_val);
						//check if the actual value to be saved is not empty.
						if ((($field_val[4] > "") && ($field_val[4] != "0")) || ($field_val[4] > 0)) {
							//set the flag for saving
							$grid_save_flag = true;
						}
					}
					//check if we must save
					if ($grid_save_flag) {
						//save all the fields to DB.
						foreach ($save_grid_fields_array AS $grid_save_val) {
							$field_val = explode("|", $grid_save_val);
							if(stristr($field_val[2], 'INSERT') && empty($newGridRows[$field_val[2]])) {
								$applicationKey = explode("$", $key);
								$newGridRows[$field_val[2]] = $this->gridInsertRow($field_val[0], $applicationKey[1], $applicationKey[2], $field_val[3], $field_val[4]);
							}
							else {
								$pk = !empty($newGridRows[$field_val[2]])  ? $newGridRows[$field_val[2]] : $field_val[2];
								$this->setValueInTable($field_val[0], $field_val[1], $pk, $field_val[3], $field_val[4]);
							}
						}
					}
					//if the flag was not set, the entire row was empty so we need to delete it out of the DB.
					if (!$grid_save_flag) {
						if (count($field_val) > 0) {
							//$del_rs = mysqli_query("DELETE FROM `".$field_val[0]."` WHERE ".$field_val[1]."='".system_escape($field_val[2])."'");
							$fv = system_escape_i($field_val[2], $conn);
							$sm = $conn->prepare("DELETE FROM `".$field_val[0]."` WHERE ".$field_val[1]."=?");
                                                        $sm->bind_param("s", $fv);
                                                        $sm->execute();
                                                        $del_rs = $sm->get_result();
						}
					}
					//empty the save array for the next row in DB.
					$save_grid_fields_array = array();
				}
			}
		}

		if (count ($saveFields) > 0) {
			if ( isset($this->dbTableCurrent) && isset($this->dbTableInfoArray[$this->dbTableCurrent]) ) {
				if ( $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID != "NEW" ) {
					$SQL = "UPDATE ".$this->dbTableCurrent." SET ";
					$sqlVAL = array();
					foreach ($saveFields as $key => $val) {
						$val = system_escape_i ($val, $conn);
						if ( (isset($this->is_password_field)) && ($key == $this->is_password_field) ) {
							array_push($sqlVAL, $key . ' = PASSWORD("' .$val. '")');
						} else {
							array_push($sqlVAL, $key . ' = "' .$val. '"');
						}
					}

					if (isset($this->curLastUpdatedField) && ($this->curLastUpdatedField > "")) array_push ($sqlVAL, $this->curLastUpdatedField." = NOW()");

					$SQL = $SQL. implode (", ", $sqlVAL);
					$SQL = $SQL. " WHERE ".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField." = '".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID."'";
					$errorMail = false;

					mysqli_query($conn, $SQL) or $errorMail = true;
					$this->writeLogInfo(10, "SQL", $SQL."  --> ".mysqli_error($conn), $errorMail);
					$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);

				} else {
					// we have a NEW record in the database
					$cols = array ();
					$vals = array ();

					foreach ($saveFields as $key => $val) {
						$val = system_escape_i ($val, $conn);

						array_push($cols, $key);

						if ( (isset($this->is_password_field)) && ($key == $this->is_password_field) ) {
							array_push($vals, 'PASSWORD("'.$val.'")');
						} else {
							array_push($vals, '"'.$val.'"');
						}
					}

					if (isset($this->curCreationDateField) && ($this->curCreationDateField > "")) {
						array_push ($cols, $this->curCreationDateField);
						array_push ($vals, "NOW()");
					}

					// check if we need to save the active user in the database
					$SQL = "SELECT write_current_user_to_db FROM processes WHERE processes_id = ?";
					$sm = $conn->prepare($SQL);
                                        $sm->bind_param("s", $this->flowID);
                                        $sm->execute();
                                        $rs = $sm->get_result();
					//$rs = mysqli_query ($SQL);
					$row = mysqli_fetch_array ($rs);
					if ($row[0] > "" && ($this->currentUserID > 0) ) {
						$tblInfo = explode ("|", $row[0]);
						if ($tblInfo[0] == $this->dbTableCurrent) {
							array_push ($cols, $tblInfo[1]);
							array_push ($vals, $this->currentUserID);
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
						$this->writeLogInfo(100, "SQL_SKIP_POST DATA", "SQL CURRENT USER ".__FILE__.":".__LINE__."\n\n".var_export($_POST, true), false);
					}

					if (isset($_POST["DELETE_RECORD"]) && ($_POST["DELETE_RECORD"] > "")) {
						$delete = explode ("|", $_POST["DELETE_RECORD"]);
						if ( (($delete[2] == "NEW") && ($delete[0] == $this->dbTableCurrent)) ) {
							$try_sql = false;
							$logType = "SQL-SKIPDEL";
						}
					}

					if ($try_sql  && mysqli_query($conn, $SQL)) {
						$logType = "SQL";
						$errorMail = false;
						$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID = mysqli_insert_id ();
						$this->workFlow_settings[$this->db_settingsKey] = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;

					}

					$this->writeLogInfo(10, $logType, $SQL."  --> ".mysqli_error($conn), $errorMail);
					$this->writeLogInfo(100, "POST DATA", var_export($_POST, true), $errorMail);
				}
			}
		}

		// First remove all MRINF fields from DB
		if (count ($delMultipleFields) > 0) {
			foreach ($delMultipleFields as $key => $val) {
				$mrinf = explode ("_|_", $val);
				$SQL = "DELETE FROM ".$mrinf[0]." WHERE ".$mrinf[1]." = '".$mrinf[2]."'";
				mysqli_query($conn, $SQL);
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
					mysqli_query($conn, $SQL);
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
			$SQL = "SELECT count(*) as counter FROM `".$table."` WHERE ".$keyFld."=?";
			$conn = $this->getDatabaseConnection();
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $keyFldValue);
                        $sm->execute();
                        $rs = $sm->get_result();
			//$rs = mysqli_query($SQL);
			$count = mysqli_fetch_assoc($rs);

			if($count['counter'] == 1) {
				$SQL ="DELETE FROM `".$table."` WHERE ".$keyFld."=".$keyFldValue;
				$rs = mysqli_query($SQL);
			}
		}
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
		$conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);

		return mysqli_insert_id();
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

	// Robin: 2006-11-16
	// Audit Record written AFTER any insert or update to active processes. That's why getting info from table instead of $this.
	function writeAuditTrail($id,$subject,$descr){
		if ($id != 0){
			$SQL = "SELECT * FROM active_processes WHERE active_processes_id = ?";
			$conn = $this->getDatabaseConnection();
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $id);
                        $sm->execute();
                        $RS = $sm->get_result();
                        
			//$RS = mysqli_query($SQL);
			$row = mysqli_fetch_array($RS);

			$prc_ref = isset($row["process_ref"]) ? $row["process_ref"] : $this->flowID;
			$aud_lev = $this->getValueFromTable("processes", "processes_id", $prc_ref, "audit_level");

			if ($this->audit_level >= $aud_lev) {
				$prc_desc = $this->getValueFromTable("processes", "processes_id", $prc_ref, "processes_desc");
				$wkf_ref = isset($row["work_flows_ref"]) ? $row["work_flows_ref"] : $this->workFlowID;
				$usr_ref = isset($row["user_ref"]) ? $row["user_ref"] : $this->currentUserID;
				$wkf_set = isset($row["workflow_settings"]) ? $row["workflow_settings"] : '';
				$aud_date = date("Y-m-d G:i:s");
				$app_ref = isset($this->workFlow_settings['DBINF_Institutions_application___application_id']) ? $this->workFlow_settings['DBINF_Institutions_application___application_id'] :'';
				$reacc_app_ref = isset($this->workFlow_settings['DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id']) ? $this->workFlow_settings['DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id'] :'';
				$ins_ref = isset($this->workFlow_settings['DBINF_HEInstitution___HEI_id']) ? $this->workFlow_settings['DBINF_HEInstitution___HEI_id'] :'';
				if ($reacc_app_ref > 0){
					$ins_ref = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_app_ref, "institution_ref");
				}
				$ses_id = session_id();
				$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
				$SQL = <<<sqlInsert
					INSERT INTO `workflow_audit_trail`
					(`active_processes_ref`, `application_ref`, `institution_ref`,`user_ref`, `reacc_application_ref`,`session_id`, `ip_number`,
					`process_ref`,`process_desc`,`work_flows_ref`,`audit_subject`,`audit_text`,`audit_level`,
					`date_updated`,`workflow_settings`)
					VALUES ('$id', '$app_ref', '$ins_ref', '$usr_ref', '$reacc_app_ref', '$ses_id','$REMOTE_ADDR',
					'$prc_ref', '$prc_desc', '$wkf_ref', '$subject','$descr','$aud_lev',
					'$aud_date','$wkf_set')
sqlInsert;
				mysqli_query($conn, $SQL);
			}
		}
	}

	/*
	 * Louwtjie: 2004-08-02
	 * Function for writing debug information (log file) into the DB.
	*/
	function writeLogInfo($level, $subject, $log_var, $mail=false) {
		if ($this->log_level >= $level) {
			$proc = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "processes_ref");
                        $conn = $this->getDatabaseConnection();
			$log_var = system_escape_i ($log_var, $conn);
			$SQL = "INSERT INTO `workflow_log_file` VALUES (NULL, '".$level."','".$this->currentUserID."', '".system_escape_i($_SERVER['REMOTE_ADDR'], $conn)."', '".$proc."', NOW(), '".$this->template."', '".$subject."'";
			$SQL .= ", ";
			if (is_string($log_var)) {
				$SQL .= "'".$log_var."'";
			}
			if (is_array($log_var)) {
				$SQL .= "'";
				foreach ($log_var AS $key=>$value) {
					if ($key == "WORKFLOW_SETTINGS") {
						$workflows = array();
						$workflows = explode("&", $value);
						if (count($workflows) > 0) {
							foreach ($workflows AS $k=>$v) {
								if (!(stristr($v, "LOGIC_SET"))) $SQL .= $k.": ".$v."\n";
							}
						}
					}else {
						$SQL .= $key.": ".$value."\n";
					}
				}
				$SQL .= "'";
			}
			$SQL .= ")";
			$conn = $this->getDatabaseConnection();
			$RS = mysqli_query($conn, $SQL); // or die("<br><br>Cannot write to log file: ".$SQL);
			if ($mail) {
				$this->misMailByName("heqc@octoplus.co.za", "(".CONFIG.") error report", "ID: ".mysqli_insert_id ($conn)."\n".$log_var, "", "HEQC error log");
			}
		}
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
		$this->workFlow_settings = array ();
		$this->dbTableInfoArray  = array();

		// wat doen 0 en wat doen -1?
		if (empty($this->formHidden["VIEW"])) $this->formHidden["VIEW"] = "0";
		if (isset($_POST["VIEW"]) && ($_POST["VIEW"]!=0) ) {
			$this->viewPage = $_POST["VIEW"];
		}
		$this->formHidden["MOVETO"] = "";
		$this->formHidden["GOTO"] = "";
		$this->formHidden["CHANGE_TO_RECORD"] = "";
		$this->formHidden["PROCESS_TO_USER"] = ""; // TO change the current process to a new user

		if ( $this->preventReload() ) return;

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
//			$this->changeActiveProcesses ($this->flowID, $_POST["PROCESS_TO_USER"], $this->workFlowID);
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
                $conn = $this->getDatabaseConnection();
		if (isset($_POST["DELETE_RECORD"]) && ($_POST["DELETE_RECORD"] > "")) {
			$delete = system_escape_i (explode ("|", $_POST["DELETE_RECORD"]), $conn);
			$del_SQL = "DELETE FROM ".$delete[0]." WHERE ".$delete[1]."='".$delete[2]."'";
			$errorMail = false;
			mysqli_query($conn, $del_SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-DELREC", $del_SQL."  --> ".mysqli_error(), $errorMail);
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
		$this->savePrevInfo ($this->flowID, $this->workFlowID);
                $conn = $this->getDatabaseConnection();
		if (substr($to, 0, 1) == '_') {
			$label = substr($to, 1);
			$SQL = "SELECT * FROM work_flows WHERE processes_ref = ? AND command = ? AND workFlowType_ref = 6";
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $this->flowID, $label);
                        $sm->execute();
                        $rs = $sm->get_result();
			//$rs = mysqli_query($SQL);
			if (mysqli_num_rows ($rs) == 0) {
				$SQL = "SELECT * FROM work_flows WHERE command = '".$label."' AND workFlowType_ref = 6";
			}
		}	else {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id  = ".$to;
		}
				//	processes_ref = ".$this->flowID." AND
		$this->readWorkFlowSettings ($SQL);
	}

	function setPreviousFlow () {
                $conn = $this->getDatabaseConnection();
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = ".$this->flowID.
			" AND sec_no < ".$this->work_sec_no." ORDER BY sec_no DESC LIMIT 0,1";
		if (! $this->readWorkFlowSettings ($SQL, "P")) {
			$SQL = "SELECT * FROM processes WHERE processes_id = ?";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->flowID);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($SQL);
			if ($row = mysqli_fetch_array($rs)) {
				if ($row["may_go_previous"] == "yes") {
					$SQL = "SELECT * FROM processes WHERE currentFlow_next_process_ref = ?";
					$sm = $conn->prepare($SQL);
                                        $sm->bind_param("s", $this->flowID);
                                        $sm->execute();
                                        $rs = $sm->get_result();
					//$rs = mysqli_query($SQL);
					if ($row = mysqli_fetch_array($rs)) {
						$this->endOfFlow ($row["processes_id"]);
						$this->updateActiveProcesses ();
					}
				}
			}
		}
	}

	function clearWorkflowSettings () {
		$this->workFlow_settings = array();
		$this->dbTableInfoArray = array();
		$this->dbTableCurrent = "";
		$this->active_processes_id = 0;
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
				$new_user = $this->getDBsettingsValue ($new_user_settings);
			} else {
				$this->writeLogInfo (3, "CurrentFlow", "Error in function: ".$settings);
				die ("CurrentFlow - Error in function: ".$settings);
			}
		} else {
			$new_user = $this->getDBsettingsValue($settings);
		}
		return ($new_user);
	}

	function setNextFlow () {
                $conn = $this->getDatabaseConnection();
		$current_process_id = $this->flowID;
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = ".$this->flowID.
			" AND sec_no > ".$this->work_sec_no." ORDER BY sec_no LIMIT 0,1";
		if (! $this->readWorkFlowSettings ($SQL, "N")) {
			$SQL = "SELECT * FROM processes WHERE processes_id = ?";
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->flowID);
                        $sm->execute();
                        $rs = $sm->get_result();
                                        
			//$rs = mysqli_query($SQL);
			if ($row = mysqli_fetch_array($rs)) {

					// Check if we need to set any fields
					/*	WHAT IS THIS????	*/
				if ($row["post_process_field"]>"" ) {
					$setField = explode ("|", $row["post_process_field"]);
					if (count($setField) == 3) {
						$this->setValueInTableInActiveProcess ($setField[0], $setField[1], $setField[2]);
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

					if ($this->currentUserID == $new_user) {
						$this->startFlow ($row["currentFlow_next_process_ref"]);
					} else {
						$procID = $this->checkSkipFlow($row["currentFlow_next_process_ref"]);
						$this->changeActiveProcesses ($procID, $new_user);
						$this->mailProcessAppointment ($this->active_processes_id, $current_process_id, $new_user);
						$this->clearWorkflowSettings ();
						$this->startFlow (__HOMEPAGE);  // BUG: go to Home Page
									// SHOULD WE GO TO HOME HERE????
					}
				}
			}
		}
	}

	function setCurrentFlow ($id) {
		$SQL = "SELECT * FROM work_flows WHERE work_flows_id = ". $id;
		$this->readWorkFlowSettings ($SQL);
	}

	function skipThisFlow () {
		$this->checkCurrentFlow ();
		$this->readTemplate ();
	}

	function startFlow ($processID) {
		$processID = $this->checkSkipFlow($processID);

		$SQL = "SELECT * FROM work_flows WHERE processes_ref = ".
			$processID ." ORDER BY sec_no LIMIT 0,1";

		if ($processID == 0) {
			$processID = $this->prev_flowID;
			$SQL = "SELECT * FROM work_flows ".
			  "WHERE processes_ref = ".$processID.
				 " AND work_flows_id = ".$this->prev_workFlowID.
				" ORDER BY sec_no LIMIT 0,1";
		} else {
			$this->doChangeDueDate = true;
		}
		$this->readWorkFlowSettings ($SQL);

		// Before we start, remember the previous process numbers
		$this->savePrevInfo ($this->flowID, $this->workFlowID);
	}

	function endOfFlow ($processID) {
		$SQL = "SELECT * FROM work_flows WHERE processes_ref = ".
			$processID ." ORDER BY sec_no DESC LIMIT 0,1";
		$this->readWorkFlowSettings ($SQL);
	}

	// Short verion of currentTableFieldInfo
	function readTFV ($value) {
		$tmp = $this->currentTableFieldInfo ($value);
		//inserted this "if" to deal with private and public providers. BUG:
		if ($value == "InstitutionType") $tmp = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_desc", $tmp, "lnk_priv_publ_id");
		return $tmp;
		//return ( $this->currentTableFieldInfo ($value) );
	}

	function checkSkipFlow($processID) {
		$SQL = "SELECT * FROM processes WHERE processes_id = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $processID);
                $sm->execute();
                $rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		$row = mysqli_fetch_array($rs);
		if ($row["currentFlow_skip_test"] > "")	 {
			$this->mis_eval_pre(__LINE__, __FILE__);
			$doSkip = eval($row["currentFlow_skip_test"]);
			$this->mis_eval_post($row["currentFlow_skip_test"]);
			if (!strcasecmp ($doSkip, "true")) {
				$processID = $row["currentFlow_next_process_ref"];
				$processID = $this->checkSkipFlow($processID);
			}
		}
		return ($processID);
	}

	function readWorkFlowSettings ($SQL, $direction="N") {
		$good = false;
		$conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);

		if ($rs && ($row = mysqli_fetch_array ($rs) )) {
			if ($row["workFlowType_ref"] > 1) {
					// If this is NOT a Template go until we get one
				$rs = $this->wrk_getWorkFlowRS($this->execLogic($row["work_flows_id"], $direction));
					// now RS should be a template or nothing
			} else {
				mysqli_data_seek($rs, 0);
			}
		}

		if ($rs && ($row = mysqli_fetch_array ($rs) )) {
			$this->flowID = $row["processes_ref"];
			$this->template = $row["template"];
			$this->validation = $row["validation"];
			$this->workFlowID = $row["work_flows_id"];
			$this->work_sec_no = $row["sec_no"];
			$this->securityLevel = $row["securityLevel"];

			if ( ($this->securityLevel > 0) && (empty($this->active_processes_id)) ) {
				$this->createNewActiveProcesses ();
			}
			$good = true;
		}
		return ($good);
	}

	function createNewActiveProcesses () {
		if (isset($this->currentUserID) && ($this->flowID > 2)) {
				// 1 & 2 is generic screens with no flow info
			$id = $this->addActiveProcesses ($this->flowID, $this->currentUserID, $this->workFlowID);
			$this->active_processes_id = $id;
			$this->workFlow_settings["ACTPROC"] = $this->active_processes_id;
		}
	}

	function updateActiveProcesses () {
		if (! ($this->viewPage>0) ) {
                        $conn = $this->getDatabaseConnection();
			if (isset($this->currentUserID) && (isset($this->active_processes_id)) && ($this->currentUserID > 0) && ($this->flowID != 1)) {
				$dueDate = "";
				$expiryDate = "";
				if ($this->doChangeDueDate) {
					$this->doChangeDueDate = false;
					$dueDate = ", due_date = \"".$this->getDueDate($this->flowID)."\"";
					$expiryDate = ", expiry_date = \"".$this->getExpiryDate($this->flowID)."\"";
				}

				$this->writeLogInfo(100, "SETTINGS", "The following active_processes has been updated:\n\nID: ".$this->active_processes_id."\nPROCESS: ".$this->flowID."\nWORK_FLOW_REF: ".$this->workFlowID."\nWORKFLOW_SETTINGS: ".$this->getStringWorkFlowSettings ()."\n\nUSER_REF: ".$this->currentUserID."\nSTATUS: did not change");

				$SQL = "UPDATE active_processes SET".
					" processes_ref = ".$this->flowID.
					", work_flow_ref = ".$this->workFlowID.
					", workflow_settings = \"".system_escape_i($this->getStringWorkFlowSettings (), $conn).'"'.
					", user_ref = ".$this->currentUserID.
					", last_updated = now()".
					$dueDate.
					$expiryDate.
					" WHERE active_processes_id = ?";
					
                                $sm = $conn->prepare($SQL);
                                $sm->bind_param("s", $this->active_processes_id);
                                $sm->execute();
                                $rs = $sm->get_result();
                                        
				//$rs = mysqli_query($SQL);

				$this->writeAuditTrail($this->active_processes_id,"updateActiveProcesses","Previous: Process-".$this->prev_flowID." Workflow-".$this->prev_workFlowID);
			}
		}
	}

	function completeActiveProcesses () {
		if (isset($this->currentUserID) && (isset($this->active_processes_id)) && ($this->currentUserID > 0) && ($this->flowID > 2) && ($this->active_processes_id>0)) {

			$this->writeLogInfo(100, "SETTINGS", "The following active_processes has been completed:\n\nID:".$this->active_processes_id);
                        
			$SQL = "UPDATE active_processes SET".
				" status = 1".
				" WHERE active_processes_id = ?";
			$conn = $this->getDatabaseConnection();
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->active_processes_id);
                        $sm->execute();
                        $rs = $sm->get_result();
			//mysqli_query($SQL) or die (mysqli_error($conn));

			$this->writeAuditTrail($this->active_processes_id,"completeActiveProcesses","Active process: ".$this->active_processes_id." completed");
		}
	}

	function getDueDate($process, $otherDate="", $dateType="processes_due_duration") {
		$ret = "1970-01-01";

		$SQL = "SELECT * FROM processes WHERE processes_id = ?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $process);
                $sm->execute();
                $rs = $sm->get_result();
		//$rs = mysqli_query ($SQL);
		if ($row = mysqli_fetch_array($rs)) {
			if ($row[$dateType]>0) {
				if ($otherDate > "") {  // we have another date to work from
					$theDate = strtotime($otherDate);
				} else {
					$theDate = mktime();
				}
				$ret = date("Y-m-d", mktime(0, 0, 0, date("m", $theDate)  , date("d", $theDate)+$row[$dateType], date("Y", $theDate)) );
			}
		}
		return ($ret);
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
		if (isset($this->currentUserID) && (isset($this->active_processes_id)) && ($this->currentUserID > 0) && ($this->active_processes_id>0)) {
			$this->addProcessText .= "The process (".$this->getValueFromTable("processes", "processes_id", $process, "processes_desc").") has been handed over to ".$this->getValueFromTable("users", "user_id", $user, "email")."\n<br>\n";
			$dueDate = "";
			$expiryDate = "";
			// die volgende if is uit want ons GLO hy moet dit altyd doen.
			// volgende 3 lyne
			$this->doChangeDueDate = false;
			$dueDate = ", due_date = \"".$this->getDueDate($process)."\"";
			$expiryDate = ", expiry_date = \"".$this->getExpiryDate($process)."\"";

			$this->writeLogInfo(100, "SETTINGS", "The following active_processes has been updated:\n\nID: ".$this->active_processes_id."\nPROCESS: ".$process."\nWORK_FLOW_REF: ".$work_flow."\nWORKFLOW_SETTINGS: ".$this->getStringWorkFlowSettings ()."\n\nUSER_REF: ".$user."\nSTATUS: none (default from DB)");

			$conn = $this->getDatabaseConnection();
			$SQL = "UPDATE active_processes SET".
				" processes_ref = ".$process.
				", work_flow_ref = ".$work_flow.
				", workflow_settings = \"".system_escape_i($this->getStringWorkFlowSettings (), $conn).'"'.
				", user_ref = ".$user.
				", last_updated = now()".
				$dueDate.
				$expiryDate.
				" WHERE active_processes_id = ?";
				//.$this->workFlowID.
                        
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->active_processes_id);
                        $sm->execute();
                        $rs = $sm->get_result();
			
			if(!$rs) die($SQL);

			$this->writeAuditTrail($this->active_processes_id,"changeActiveProcesses", "Previous: User-".$this->currentUserID. " Process-".$this->flowID." Workflow-".$this->workFlowID);

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
			$this->addProcessText .= "The process (".$this->getValueFromTable("processes", "processes_id", $process, "processes_desc").")<br>\nhas been given to ".$this->getValueFromTable("users", "user_id", $user, "email")."\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n<br>\n";
		}
		$activeDate = "1970-01-01";
		$dueDate = $this->getDueDate($process);
		$expiryDate = $this->getExpiryDate($process);

		if ($newWorkFlow == "<<EXISTING>>") {
			$newWorkFlow = $this->getStringWorkFlowSettings ();
		}
		if ($doActiveDate) {
			$dateEvel = $this->getValueFromTable("processes", "processes_id", $process, "spawnUser_activeDate");
			if ($dateEvel > "") {
				$this->mis_eval_pre(__LINE__, __FILE__);
				$activeDate = eval($dateEvel);
				$this->mis_eval_post($dateEvel);
				// We have a activeDate, we should change the due date
				$dueDate = $this->getDueDate($process, $activeDate);
				$expiryDate = $this->getExpiryDate($process, $activeDate);
			}
		}

		$this->writeLogInfo(100, "SETTINGS", "The following active_processes has been created:\n\nPROCESS: ".$process."\nWORK_FLOW_REF: ".$flow_ref."\nWORKFLOW_SETTINGS: ".$newWorkFlow."\n\nUSER_REF: ".$user."\nSTATUS: ".$status);

		$SQL = "INSERT INTO active_processes ".
					 "(processes_ref, work_flow_ref, workflow_settings , user_ref, status,last_updated, active_date, due_date,expiry_date) VALUES (".$process.", $flow_ref, \"".$newWorkFlow."\", ".$user.", $status, now(), \"$activeDate\", \"$dueDate\",\"$expiryDate\")";

                $conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);
		$id = mysqli_insert_id ($conn);

		$this->writeAuditTrail($id,"addActiveProcesses","Previous: Active Process-".$this->active_processes_id." User-".$this->currentUserID. " Process-".$this->flowID.", Workflow-".$this->workFlowID);

		return ( $id );
	}

	function saveWorkFlowSettings () {
		$str = $this->getStringWorkFlowSettings ();
		if ($str > "") {
			$this->formHidden["WORKFLOW_SETTINGS"] = $str;
		}
	}

	/* Diederik
		2004-04-15: enable this function to use alt WorkFlow Settings
	*/
	function getStringWorkFlowSettings ($otherWorkFlow=NULL, $actproc=true) {
		$useWorkFlow = $this->workFlow_settings;
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
		$ToAddress = $this->getValueFromTable("users", "user_id", $userid, "email");
		$this->misMailByName($ToAddress, $subject, $message, ($cc>"")?($cc):(""), $ownFromAdr);
	}

	//This functions sends e-mail to an evaluator
	function misEvalMail ($userid, $subject, $message, $cc="", $ownFromAdr=true) {
		$ToAddress = $this->getValueFromTable("Eval_Auditors", "Persnr", $userid, "E_mail");
		$this->misMailByName($ToAddress, $subject, $message, ($cc>"")?($cc):(""), $ownFromAdr);
	}

	function misMailByName ($email, $subject, $message, $cc="", $ownFromAdr=true, $filelist="", $isHTML=false) {

		$mail = new PHPMailer();

//note that if you have added a CC and are testing, you will not see that it is being cc'd
		if (defined('WRK_ALT_EMAIL')) {
			$email = WRK_ALT_EMAIL;
			$cc = '';
		}

	  // changed from address to persons own address.

		$mail->From = $this->getDBsettingsValue("server_from_address");
		$mail->FromName = $this->getDBsettingsValue("server_from_name");

		if ($ownFromAdr) {
			$FromReplyTo = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
			$FromReplyToName = $this->getValueFromTable("users", "user_id", $this->currentUserID, "surname") .", ". $this->getValueFromTable("users", "user_id", $this->currentUserID, "name");
		}
		if (isset($FromReplyTo) && $FromReplyTo != "") {
			$mail->AddReplyTo ($FromReplyTo, $FromReplyToName);
		}

		$signature = $this->getDBsettingsValue("email_che_signature");

		if ($cc > "") {
			$mail->AddCC ($cc);
		}

			$debugText = "";
			if ($this->debug_mode) {
				$debugText = "(".$this->flowID."/".$this->template.") ";
			}
			$mail->Subject = $this->getDBsettingsValue("default_email_subject")." ".$debugText.$subject;

			// add signature to email
			$message = $message . $signature;

			if ($this->mayMail) {
				$mail->Host      = SMTP_SERVER;
				$mail->Mailer    = "smtp";
				$mail->WordWrap = 75;


				$htmlMessage = $message;

				if ($isHTML != true) {
								$htmlMessage = "<HTML><HEAD><STYLE>BODY {font-family: Verdana;font-size: 10pt;}</STYLE></HEAD>\n<BODY>\n".str_replace ("\n", "<br />\n", htmlentities  ($message))."\n</BODY>\n</HTML>";
								$isHTML = true;
				}

				$mail->Body   = $htmlMessage; 

				$mail->IsSMTP();
				$mail->IsHTML ($isHTML);
				$mail->AddAddress ($email);

				// add attachments
				if (is_array($filelist)) {
					foreach ($filelist AS $filearr) {
						if (! is_array($filearr) ) {
							$filearr = array($filearr);
						}
						$fileatt = $filearr[0]; // Path to the file
						$fileatt_name = ((isset($filearr[1]))?($filearr[1]):(basename($fileatt))); // Filename that will be used for the file as the attachment
						$fileatt_type = ((isset($filearr[2]))?($filearr[2]):("application/octet-stream")); // File Type

						$mail->AddAttachment($fileatt, $fileatt_name, "base64", $fileatt_type);

						unset($fileatt);
						unset($fileatt_type);
						unset($fileatt_name);
					}
				}

				$title = "EMAIL";
				if (! $mail->Send() ) {
					$title = "EMAIL NOT SENT";
				}
				$this->writeLogInfo(10, $title, "An e-mail with subject ".$subject." was sent to ".$email.". The body of the e-mail was:\n\n".$message);
				$this->writeAuditTrail($this->active_processes_id, $title, "Subject-".$subject." Sent-".$email." Body:\n\n".$message);

			}
			$mail->ClearAddresses();
			$mail->ClearAttachments();
	}

	function htmlEmail ($address, $subject, $message, $attachments="", $isHTML=true) {

     /*
       HTMLMAIL does not have the right from headers
		   and needs some work
		*/
    $message = 'The HTMLEMAIL function does not have the right from address\nlook at the misMailByName for assistance';
    $this->misMailByName ("heqc@octoplus.co.za", "HTMLEMAIL need work", $message);

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

		if ($this->mayMail) {
			$mail->Send();
			$this->writeLogInfo(10, "EMAIL", "An e-mail with subject ".$mail->Subject." was sent to ".$address.". The body of the e-mail was:\n\n".$mail->Body);
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
		$SQL = "SELECT * FROM `".$table."` WHERE ".$key."=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $value);
                $sm->execute();
                $rs = $sm->get_result();
		//$rs = mysqli_query($SQL);

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
					$image = $this->imageOK;
				} else {
					if ($value[1] > 0) {
						$lnk1 = '<a href="javascript:moveto('.$value[1].');">';
						$lnk2 = "</a>";
					}
					$image = $this->imageWrong;
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
		$SQL = "SELECT * FROM template_field WHERE template_name=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $fieldTemplate);
                $sm->execute();
                $RS = $sm->get_result();
		//$RS = mysqli_query($SQL);
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
							$image = $this->imageOK;
						} else {
							$SQLdesc = "SELECT fieldValidationDesc FROM `template_field_validation`, template_field WHERE template_field.fieldValidationName=template_field_validation.fieldValidationName AND template_field_id=?";
							
							$sm = $conn->prepare($SQLdesc);
                                                        $sm->bind_param("s", $row["template_field_id"]);
                                                        $sm->execute();
                                                        $RSdesc = $sm->get_result();
							
							//$RSdesc = mysqli_query($SQLdesc);
							if ($rowdesc = mysqli_fetch_array($RSdesc)){ $message = $rowdesc["fieldValidationDesc"];}
							$image = $this->imageWrong;
							$moveToSQL = "SELECT work_flows_id FROM work_flows WHERE template=?";
							
							$sm = $conn->prepare($moveToSQL);
                                                        $sm->bind_param("s", $fieldTemplate);
                                                        $sm->execute();
                                                        $moveToRS = $sm->get_result();
							
							//$moveToRS = mysqli_query($moveToSQL);
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
	function validateFields ($fieldTemplate, $dataKey="", $dataId=""){

		//changed to get info from work_flows instead of template info table
		$tblSQL = "SELECT template_dbTableName, work_flows_id FROM work_flows WHERE template=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($tblSQL);
                $sm->bind_param("s", $fieldTemplate);
                $sm->execute();
                $tblRS = $sm->get_result();
                                        
		//$tblRS = mysqli_query($tblSQL);
		if ($tblRS  && mysqli_num_rows($tblRS) > 0 ) {
			$tblRow = mysqli_fetch_array($tblRS);
			$currentTableKey = ($dataKey > "") ? $dataKey : $this->dbTableInfoArray[$tblRow[0]]->dbTableKeyField;
			$currentTableId = ($dataId > "") ? $dataId : $this->dbTableInfoArray[$tblRow[0]]->dbTableCurrentID;
		} else {
			// no validation can be done if no base table because you cannot get hold of the captured field values.
			return false;
		}

		$SQL = "SELECT * FROM template_field WHERE template_name=? ORDER BY fieldOrder";
		
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $fieldTemplate);
                $sm->execute();
                $RS = $sm->get_result();
		
		//$RS = mysqli_query($SQL);
		$match = array();
		while ($row = mysqli_fetch_array($RS)) {
			$regexpSQL = <<<regexpSQL
				SELECT fieldValidationRegExp, fieldValidationDesc, fieldValidationTitle,
					fieldValidationCondition, fieldDisplayName
				FROM template_field_validation, template_field
				WHERE template_field.fieldValidationName=template_field_validation.fieldValidationName
				AND template_field_id=?
regexpSQL;

                        $sm = $conn->prepare($regexpSQL);
                        $sm->bind_param("s", $row['template_field_id']);
                        $sm->execute();
                        $regexpRS = $sm->get_result();
                
			//$regexpRS = mysqli_query($regexpSQL);
			while ($regexpRow = mysqli_fetch_array($regexpRS)) {
				$evalRes =  true;

				$condition = $regexpRow["fieldValidationCondition"];

				// Validate field only if its condition is satisfied.
				if ($condition > ""){
					$evalStr = "return (($condition)?(true):(false));";

					//$this->mis_eval_pre(__LINE__, __FILE__);
					$evalRes = eval($evalStr);
					if ($evalRes === false) {
						//commented out by Rebecca (2008-03-20) - condition will sometimes fail, we don't want it emailing us every time.
						//$this->writeLogInfo (3, "EVALd error", "Cannot evaluate code: ".mysqli_real_escape_string($evalStr), true);
					}
					//$this->mis_eval_post($evalStr);
				}

				if ($evalRes) {
					//changed $this->dbTableCurrent to $tblRow[0]: 2004-08-20
					$valueSQL = "SELECT ".$row["fieldName"]." FROM ".$tblRow[0].
								" WHERE ".$currentTableKey."=?";

                                        //$conn = $this->getDatabaseConnection();
                                        $sm = $conn->prepare($valueSQL);
                                        $sm->bind_param("s", $currentTableId);
                                        $sm->execute();
                                        $valueRS = $sm->get_result();
					//$valueRS = mysqli_query($valueSQL);
					if ($valueRS && ($valueRow = mysqli_fetch_array($valueRS))) {
						$lnk1 = $lnk2 = $message = "";

						if (preg_match($regexpRow[0], $valueRow[0])) {
							$image = $this->imageOK;
						} else {
							$message = $regexpRow["fieldValidationDesc"];
							$image = $this->imageWrong;
							$jscript = $this->scriptGetForm ($tblRow[0], $currentTableId, $tblRow["work_flows_id"]);
							$lnk1 = "<a href='".$jscript."'>";
							$lnk2 = "</a>";
							$this->formActions["next"]->actionMayShow = false;
						}
	//					$showField = $this->showFieldDisplayName($fieldTemplate, $row["fieldName"]);
						$showField = $regexpRow["fieldDisplayName"];
						$showTitle = $regexpRow["fieldValidationTitle"];
						$htmlRow = <<<htmlRow
						 	<tr>
								<td align="center" class="oncolour">$lnk1<img src="images/$image" border=0>$lnk2</td>
								<td class='oncolour'>$showTitle $showField</td>
								<td class='oncolour'><font color="red">$message</font></td>
							</tr>
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
			FROM work_flows WHERE template=?";
			
                $conn = $this->getDatabaseConnection();
                $sm = $conn->prepare($tblSQL);
                $sm->bind_param("s", $fieldTemplate);
                $sm->execute();
                $tblRS = $sm->get_result();
                
		//$tblRS = mysqli_query($tblSQL);
		if ($tblRS  && mysqli_num_rows($tblRS) > 0 ) {
			$tblRow = mysqli_fetch_array($tblRS);
		} else {
			// no validation can be done if no base table because you cannot get hold of the captured field values.
			return false;
		}

		// get all the child rows
		$childSQL = "SELECT ". $tblRow["template_dbTableKeyField"].
			" FROM ".$tblRow["template_dbTableName"].
			" WHERE ".$parent_ref_key."=?";
			
		$sm = $conn->prepare($childSQL);
                $sm->bind_param("s", $parent_ref_val);
                $sm->execute();
                $childRS = $sm->get_result();
                
		//$childRS = mysqli_query($childSQL);
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
		$SQL = "SELECT fieldDisplayName FROM template_field WHERE fieldName=? AND template_name=?" ;
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $fieldName, $fieldTemplate);
                $sm->execute();
                $RS = $sm->get_result();
                
		//$RS = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($RS)) {
			return $row[0];
		}
	}

	function mailProcessAppointment ($active_proc_id, $proc_id, $user_id, $spawn=false) {
		if ($proc_id > __HOMEPAGE) {
			$flow_message = (($spawn)?("spawnUser"):("currentFlow"))."_message";

			// get the settings of the proc_id
			$SQL = "SELECT * FROM processes WHERE processes_id = ?";
			
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $proc_id);
                        $sm->execute();
                        $rs = $sm->get_result();
                
                
			//$rs = mysqli_query($SQL);
			$row = mysqli_fetch_array ($rs);

			// Compose the e-mail message
			$desc_fields = explode ("|", $row["desc_fields"]);
			$subject = $row["processes_desc"];
			if (isset($desc_fields[0])) {
				$subject .= " - ".$this->table_field_info($active_proc_id, $desc_fields[0]);
			}

			$message = $this->getDBsettingsValue("process_appoint_message")."\n\n";
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
		$SQL = "SELECT * FROM table_field_info WHERE name = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $desc_field);
                $sm->execute();
                $rs = $sm->get_result();

		//$rs = mysqli_query($SQL);
		if ( isset($tables) && ($row = mysqli_fetch_array ($rs)) ) {
			if (isset ($tables[$row["table_name"]]) ) {
				if (!$desc) {
					$SQL = "SELECT ".$row["field_name"].
								" FROM ".$row["table_name"].
								" WHERE ".$tables[$row["table_name"]]->dbTableKeyField.
								" = '".$tables[$row["table_name"]]->dbTableCurrentID."'";
                                                                $conn = $this->getDatabaseConnection();
								$rs2 = mysqli_query($conn, $SQL);
					if ($row2 = mysqli_fetch_array ($rs2)) {

						$ret = $row2[0];
						// added the following line to make provision for public and private providers. The $row2[0] value is actually a lookup value for this field.
						if ($row["field_name"] == "priv_publ") $ret = $this->getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", $row2[0], "lnk_priv_publ_desc");
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
		$SQL = "SELECT * FROM processes WHERE processes_id = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $proc_id);
                $sm->execute();
                $rs = $sm->get_result();
                
		//$rs = mysqli_query($SQL);
		$row = mysqli_fetch_array ($rs);

		$ret = $row["processes_desc"];
		// Compose the e-mail message
		$desc_fields = explode ("|", $row["desc_fields"]);
//		if (isset($desc_fields[0])) {
//			$desc_maybe = $this->table_field_info($active_processes_id, $desc_fields[0]);
//			if ($desc_maybe > "") {
//			$ret .= " - ".$desc_maybe;
//			}
//		}

		$taskName = $this->getValueFromTable("work_flows", "template", $this->template, "taskName");
		if ($taskName > "") {
			$ret .= " - ".$taskName;
		}
		$progDisc = $this->table_field_info($active_processes_id, "ProgrammeName");
		$HEQC_ref = $this->table_field_info($active_processes_id, "HEQC_ref");
		if ($progDisc > "" || $HEQC_ref > "") {
			$ret .= " (".$progDisc." - ".$HEQC_ref.")";
		}

		//BUG vir Internal Support
		if ($this->debug_mode) {
			$ret .= " (".$this->flowID." - ".$this->template.")";
		}

		return ($ret);
	}

	function navTrailDisplay ($active_processes_id, $proc_id) {
		$ret = "";
		// get the settings of the proc_id
		$SQL = "SELECT * FROM processes WHERE processes_id = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $proc_id);
                $sm->execute();
                $rs = $sm->get_result();
                
		//$rs = mysqli_query($SQL);
		$row = mysqli_fetch_array ($rs);

		$ret = ($this->NavigationBar) ? $this->NavigationBar : $row["processes_desc"];

		//DEBUG vir Internal Support - shows template and proc
		if ($this->debug_mode) {
			$ret .= " (".$this->flowID." - ".$this->template.")";
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

		$SQL = "SELECT * FROM template_text WHERE template_ref = ? AND template_text_desc = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $tmp_name, $text_desc);
                $sm->execute();
                $rs = $sm->get_result();

		//$rs = mysqli_query ($SQL);

		$text = "";
		if ($row = mysqli_fetch_array($rs)) {
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
		$SQL = "SELECT * FROM template_text WHERE template_ref = ? AND template_text_desc = ?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $tmp_name, $text_desc);
                $sm->execute();
                $rs = $sm->get_result();
		//$rs = mysqli_query ($SQL);

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
		$SQL = "SELECT * FROM `users` WHERE email=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $email);
                $sm->execute();
                $RS = $sm->get_result();
                
		//$RS = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($RS)) {
			$user_id = $row["user_id"];
				// If so, check if the user is ACTIVE.
			if (!$row["active"]) {
					//If not ACTIVE, make the user active
				$activeSQL = "UPDATE `users` SET active=1 WHERE user_id='".$user_id."'";
				$sm = $conn->prepare($activeSQL);
                                $sm->bind_param("s", $user_id);
                                $sm->execute();
                                $activeRS = $sm->get_result();
				//$activeRS = mysqli_query($activeSQL);
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

			$insertSQL = "INSERT INTO `users` (user_id, name, surname, title_ref, password, email, active, institution_ref, institution_name) VALUES (NULL, ?, ?, ?, '', ?, 1, 0, ?)";
			//$insertRS = mysqli_query($insertSQL);
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($insertSQL);
                        $sm->bind_param("sssss", $name, $surname, $title_ref, $email, $fake_inst);
                        $sm->execute();
                        $rs = $sm->get_result();
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
		$gSQL = "SELECT * FROM `sec_UserGroups` WHERE sec_user_ref=? AND sec_group_ref = ?";
		$sm = $conn->prepare($gSQL);
                $sm->bind_param("ss", $user_id, $groupID);
                $sm->execute();
                $gRS = $sm->get_result();
                
		//$gRS = mysqli_query($gSQL);
		//if ((!($g_row = mysqli_fetch_array($gRS))) && ($groupID=0)) {
		if (!($g_row = mysqli_fetch_array($gRS))) {
			$insertSQL = "INSERT INTO `sec_UserGroups` (sec_UserGroups_id, sec_user_ref, sec_group_ref) VALUES (NULL, ?, ?)";
			$sm = $conn->prepare($insertSQL);
                        $sm->bind_param("ss", $user_id, $groupID);
                        $sm->execute();
                        $insertRS = $sm->get_result();
			//$insertRS = mysqli_query($insertSQL);
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
	function readProcessInfo ($ID=0) {
		$ret = NULL;

		$processID = $this->flowID;
		if ($ID > 0) $processID = $ID;

		$SQL = "SELECT * FROM processes WHERE processes_id = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $processID);
                $sm->execute();
                $rs = $sm->get_result();
                
		//$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($rs)) {
			$ret = $row;
		}
		return ($ret);
	}

	/* 2004-05-19 - Diederik
	   Send-l with attachments
	   filelist is a array of
	     arrays with (fileondisk, filename, contenttype)
		  20070823: mimemail is discontinued.
	*/
	function mimemail ($to, $from, $subject, $message, $filelist="") {
		// from not use
		$this->misMailByName ($to, $subject, $message, "", true, $filelist);
	}

	function wrk_getWorkFlowRS($workFlowID) {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id = ? LIMIT 0,1";
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $workFlowID);
                        $sm->execute();
                        $rs = $sm->get_result();
			return ($rs);
	}

	function wrk_getNextWorkFlow($workFlowID, $direction="N") {
			$SQL = "SELECT * FROM work_flows WHERE work_flows_id = ? LIMIT 0,1";
			
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $workFlowID);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($SQL);
			$row = mysqli_fetch_array($rs);
			$order    = "";
			$operator = ">";
			if ($direction != "N") {
				$order    = "  DESC";
				$operator = "<";
			}
			$SQL = "SELECT * FROM work_flows WHERE processes_ref = ? AND sec_no ".$operator." ? ORDER BY sec_no".$order." LIMIT 0,1";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $row["processes_ref"], $row["sec_no"]);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($SQL);
			$row = mysqli_fetch_array($rs);
			return ($row["work_flows_id"]);
	}

	function cleanTempActiveProccesses () {
		if ($this->currentUserID > 0) {
                        $conn = $this->getDatabaseConnection();
                        
			$SQL = "SELECT active_processes_id FROM active_processes, processes WHERE processes_ref = processes_id AND keep_workflow_settings = 'temp' AND status = 0 AND user_ref = ? ORDER BY last_updated DESC";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->currentUserID);
                        $sm->execute();
                        $rs = $sm->get_result();
			
			//$rs = mysqli_query($SQL);
			$procList = array ();
			while ($row = mysqli_fetch_array ($rs) ) {
				array_push ($procList, $row["active_processes_id"]);
				$this->writeAuditTrail($row["active_processes_id"],"cleanTempActiveProccesses","Status set to 1 (completed)");
			}
			if ( count ($procList) > 0 ) {
				$SQLin = implode (", ", $procList);
				$this->writeLogInfo(100, "SETTINGS", "The following active_processes has been disabled ".$SQLin);
				$SQL = "UPDATE active_processes SET status=1 WHERE active_processes_id IN ($SQLin)";
				mysqli_query($conn, $SQL);
			}
			$SQL = "UPDATE active_processes SET status=1 WHERE processes_ref >= 0 AND processes_ref <= 2 AND status = 0 AND user_ref = ?";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $this->currentUserID);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($SQL);
		}
	}

// the end
}

?>
