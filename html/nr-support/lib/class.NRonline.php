<?php

/**
 * application class specific to this application
 *
 * this class has non-genric functions specific to this workflow application.
 * @author Diederik de Roos, Louwtjie du Toit, Reyno vd Hooven
*/

class NRonline extends miscellaneous {
	
	var $relativePath;
	var $domReady = '';
	var $reportSearchFields = array(
		'report_contact' => array(
			'hei_id' => array('function' => 'filter_equal'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id')
		),
		'report_inst_progress' => array(
			'nr_programme_name' => array('function' => 'filter_compare'),
			'nr_programme_abbr' => array('function' => 'filter_compare'),
			'heqsf_reference_no' => array('function' => 'filter_compare'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id')
		),
		'detail_report_nr_progress' => array(
			'hei_id' => array('function' => 'filter_equal'),
			'nr_programme_name' => array('function' => 'filter_equal'),
			/*'nr_national_review_id' => 'filter_equal',*/
			'heqsf_reference_no' => array('function' => 'filter_compare'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id')
		),
		'report_nr_progress' => array(
			'hei_id' => array('function' => 'filter_equal'),
			'nr_programme_name' => array('function' => 'filter_equal'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id'),
			'heqsf_reference_no' => array('function' => 'filter_compare')
		),	
		'report_nr_progress_edit' => array(
			'hei_id' => array('function' => 'filter_equal'),
		),			
		'inst_reports_recommendation' => array(
			'hei_id' => array('function' => 'filter_equal'),
			'nr_programme_name' => array('function' => 'filter_compare')
		),
		'nrc_member_report' =>array(
			'hei_id' => array('function' => 'filter_equal'),
			'nr_meeting_start_date' => array('function' => 'filter_compare'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id',
											'table' => 'nr_programmes')		
		),
		'rg_meeting_report' =>array(
			'hei_id' => array('function' => 'filter_equal'),
			'rg_meeting_start_date' => array('function' => 'filter_compare'),
			'nr_national_review_id' => array('function' => 'filter_equal',
											'default' => 'current_nr_national_review_id',
											'table' => 'nr_programmes')		
		)		
	);
	var $userSearchFields = array(
		'manageUsers' =>array(
			'email' => array('function' => 'filter_compare'),
			'name' => array('function' => 'filter_compare'),
			'surname' => array('function' => 'filter_compare'),
			'sec_group_id' => array('function' => 'filter_equal'),
			'active' => array('function' => 'filter_equal')
		)
	);

/**
 * default constructor
 *
 * this function calls the {@link workFlow} function.
 * @author Diederik de Roos
 * @param integer $flowID
 */
	public function __construct($flowID) {
		$this->readPath();
		parent::__construct($flowID);

		$this->populatePublicHolidays();
	}

	function readPath() {
		global $path;

		Settings::set('relativePath', (isset($path))?($path):(""));
	}

	/*
	function to draw the table at the top of the screen with national review information.
	*/
	function showNRTableTop ($applicationID=0) {
		if ( !($applicationID > 0) ) {
			$applicationID = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		}
	}

	public function showBootstrapField($fieldName, $label) {
		ob_start();
		$this->showField($fieldName);
		$field = ob_get_contents();
		ob_end_clean();
		preg_match('/id="([a-zA-Z_0-9]+)"/', $field, $matches);
		$fieldId = isset($matches[1]) ? $matches[1] : '';

		$output = '<div class="control-group">';
		$output .= '<label class="control-label" for="' . $fieldId . '">' . $label . '</label>';
		$output .= '<div class="controls">';
		$output .= $field;
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
		return $output;
	}

	public function showSaveAndContinue($moveTo) {
		$output = '<div class="form-actions">';
		$output .= '<button type="button" onclick="moveto(\'' . $moveTo . '\');" class="btn btn-primary save_continue">Save and continue</button>';
		$output .= '</div>';

		echo $output;
		return $output;
	}
	
	public function cssPrintFile($fileName) {
		$output = '<link rel="stylesheet" type="text/css" media="print" href="css_print/' .$fileName. '">';
		echo $output;
		return $output;
	}

	/* 2004-05-07
	   Diederik
	   Function to show a list of active proccesses.
	*/
	function showProcesses ($order = "last_updated") {
		if ($order == "p"){
			$order = "processes.processes_desc, active_processes.last_updated";
		} else {
			$order = "active_processes.last_updated";
		}	
		
		$processes = $this->getProcess($order);
		echo $this->element('processes', compact('processes'));
	}
	
	function doPopulateGridFromTemplateTable ($parentTable, $parentTable_id, $childTable, $childKeyFld, $childRef, $fieldsArr, $lookup_template_table) {
		$RS = $this->db->query("SELECT * FROM `".$lookup_template_table."`");
		$num_rows = $RS->rowCount();

		while ($RS && ($template_table_row=$RS->fetch())) {
			$init_RS = $this->db->query("INSERT INTO `".$childTable."` (".$childRef.") VALUES (".$parentTable_id.")");
			$last_id = $this->db->lastInsertId();
			foreach ($fieldsArr AS $initK=>$initV) {
				$this->db->query("UPDATE `".$childTable."`  SET ".$initK."='".$template_table_row[$initK]."' WHERE ".$childRef."=".$parentTable_id." AND ".$childKeyFld."='".$last_id."'");
			}
		}
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
				$app_id = ($CHE_code) ? $this->db->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "application_id") : "";
				$app_field = "application_ref";
				$inst_id = ($institution_ref) ? $institution_ref : $this->db->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_code, "institution_id");
			}
			
			// Re-accreditation audit trail
			if ($reacc_ind == 1){
				$app_id = ($CHE_code) ? $this->db->getValueFromTable("Institutions_application_reaccreditation", "referenceNumber", $CHE_code, "Institutions_application_reaccreditation_id") : "";
				$app_field = "reacc_application_ref";
				$inst_id = ($institution_ref) ? $institution_ref : $this->db->getValueFromTable("Institutions_application_reaccreditation", "referenceNumber", $CHE_code, "institution_ref");
			}

//if no inst_id found, then invalid ref_no must have been entered
			if ($inst_id != "")
			{
				$inst_name = $this->db->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");
				$aSql = <<<aSql
							SELECT DISTINCT $app_field,
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

				if ($aRs = mysqli_query($aSql))
				{
					$tot_rows = mysqli_numrows($aRs);
					$n=0;
					$bgColor = "#EAEFF5";
					$prev_app = "";
					$audit_subj = "";

					while ($row = mysqli_fetch_array($aRs))
					{
						$app = ($reacc_ind == 0) ? $row["application_ref"] : $row["reacc_application_ref"];
						$ref_no = ($reacc_ind == 0) ? $this->db->getValueFromTable("Institutions_application", "application_id", $app, "CHE_Reference_code") : $this->db->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $app, "referenceNumber");
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


						$user_desc = $this->db->getValueFromTable("users", "user_id", $row["user_ref"], "name")." ".$this->db->getValueFromTable("users", "user_id", $row["user_ref"], "surname");

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
	$rs = mysqli_query($sql);
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

// Robin 2008-02-25
// Some templates may be child templates - thus they will return many records for the parent key.
function displayTemplateRTFReportperChild($template, $parent_field, $parent_val){

	// Get the base table name and key for the template.
	$wkfSql = <<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;

	$wkfrs = mysqli_query($wkfSql);
	if ($wkfrow = mysqli_fetch_array($wkfrs)){
		$templateTable = $wkfrow["template_dbTableName"];
		$templateTableKey = $wkfrow["template_dbTableKeyField"];
	}

// Get the IDs for each of the child records and run the template report.
	$childsql = <<<CHILDSQL
		SELECT $templateTableKey
		FROM $templateTable
		WHERE $parent_field = '$parent_val'
CHILDSQL;
	$childrs = mysqli_query($childsql);
	while ($childrow = mysqli_fetch_array($childrs)){
		if ($templateTableKey == "ia_criteria_per_site_id") {
			$site_id = HEQCOnline::getValueFromTable("ia_criteria_per_site", "ia_criteria_per_site_id", $childrow["$templateTableKey"], "institutional_profile_sites_ref");
			$siteName = HEQCOnline::getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $site_id, "site_name");
		}

		echo "<br /><br /><br /><b><u>Information for site of delivery: $siteName</u></b>";
		HEQCOnline::displayTemplateRTFReport($childrow["$templateTableKey"],$template);

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

function displayTemplateRTFReport($id_val,$template){

	// Get the base table name and key for the template.
	$wkfSql = <<<WKFSQL
			SELECT template_dbTableName, template_dbTableKeyField
			FROM work_flows
			WHERE template = '$template'
			AND workFlowType_ref = 1
WKFSQL;


	$wkfrs = mysqli_query($wkfSql);
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

	$sql = <<<FIELDS
			SELECT * FROM template_field
			WHERE template_name = '$template'
			AND fieldDBconnected = 1
			ORDER BY fieldOrder
FIELDS;
//added 3 infront of _registrarDeclaration
	$rs = mysqli_query($sql);
	while ($row = mysqli_fetch_array($rs)){

		$fieldName = $row['fieldName'];
		if (strpos($fieldName, '%_registrarDeclaration_%') === false) {

			$fieldCondition = $row['fieldValidationCondition'];
			$evalRes = false;

			// Show field only if it meets condition
			if ($fieldCondition != ""){
				$fieldCondition = HEQConline::convertForDocGen($fieldCondition, '$id_val');
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
							$docName = ($fieldval != 0) ? HEQConline::getValueFromTable("documents", "document_id", $val, "document_name") : "Document not uploaded";
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

	function displayGeneralPageSetup($title, $layout="") {
		//echo ($layout == "landscape") ? "<section landscape='yes'/>\n" : "";
		$displayGeneralPageSetup = <<<TXT
			<header><b>HEQC-online Accreditation System - $title</b></header>
			<footer><table border="0" width="100%"><tr><td align="left">
			<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.png" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />
			</td></tr></table></footer>
TXT;
		echo $displayGeneralPageSetup;
	}
	
	function pr($array){
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

//***end html/docgen functions

	function roleProcessData($role){
		
	}

	function safeJS ($fld) {
		return (str_replace("$", "%24", $fld));
	}

	function showProcessesCustom ($processRef, $element="processes") {
		$processes = $this->getProcess('active_processes.last_updated', $processRef);
		echo $this->element($element, compact('processes'));
		
		return;
	}

	function getStatusOfTables($lkp_value){
		$totalCompleted = 0;
	
		$checks = array(
			//check if content exists
			'nr_programme_sites' => 'nr_programme_id',
			'nr_programme_budget' => 'nr_programme_id',
			'nr_programme_bursaries' => 'nr_programme_id',
			'nr_programme_bursary_amounts' => 'nr_programme_id',
			'nr_programme_academic_qualifications' => 'nr_programme_id',
			'nr_programme_academic_demographics_nat' => 'nr_programme_id',
			'nr_programme_academic_demographics_rac' => 'nr_programme_id',
			'nr_programme_students' => 'nr_programme_id'
		);

		foreach($checks as $table => $lkp_key){
			$SQL = "SELECT count(" . $lkp_key . ") as Total FROM `" . $table . "` WHERE " . $lkp_key . " = '" . $lkp_value . "'";
			$RS = $this->db->query($SQL);
			while($row = $RS->fetch()){
				$totalCompleted = ($row[0] > 0) ? ($totalCompleted + 1) : $totalCompleted;
			}
		}
	
		$return = array(
			'totalRows' => count($checks),
			'totalCompleted' => $totalCompleted
		);
		
		return $return;
	}
	
	function getStatusOfSection($table, $id, $sections){
		$sections = (is_array($sections)) ? implode("', '", $sections) : $sections;
		$SQL = "SELECT fieldName FROM `template_field` WHERE template_name IN('" . $sections . "') AND fieldStatus != 2";
		$RS = $this->db->query($SQL);
		$fields = array();
		$totalRows = 0;
		$totalCompleted = 0;
		
		while($row = $RS->fetch()){
			$fields[] = $row['fieldName'];
			$totalRows++;
		}
		
		if(!empty($fields)){
			$stringFields = implode(", ", $fields);
			$SQL = "SELECT " . $stringFields . " FROM `" . $table . "` WHERE id = '" . $id . "'";
			$RS = $this->db->query($SQL);
			
			while($row = $RS->fetch()){
				foreach($fields as $fieldName){
					if(isset($row[$fieldName]) && !empty($row[$fieldName])){
						$totalCompleted++;
					}
				}
			}
		}
		
		$return = array(
			'totalRows' => $totalRows,
			'totalCompleted' => $totalCompleted
		);
		
		return $return;
	}
	
	function getSelfEvalStatus($prog_id,$role){
		$rev_id = '';
		$total = 0;
		$completed = 0;
		$SQL = "SELECT nr_national_review_id FROM `nr_programmes` WHERE id =  '" . $prog_id . "'";
		$RS = $this->db->query($SQL);
		
		while($row = $RS->fetch()){
			$rev_id = $row['nr_national_review_id'];
		}
		
		if(!empty($rev_id)){
			$SQL = "SELECT count(*) as 'total' FROM `nr_national_review_criteria` WHERE nr_national_review_id =  '" . $rev_id . "'";
			$RS = $this->db->query($SQL);
			
			while($row = $RS->fetch()){
				$total = $row['total'];
			}
			
			$SQL = "SELECT * FROM `nr_programme_ratings` WHERE nr_programme_id =  '" . $prog_id . "'";
			$RS = $this->db->query($SQL);

				
			while($row = $RS->fetch()){
				switch($role){
					case 'institutional_administrator':
						if($row['lkp_rating_id'] == 'ni' && $row['rating_improvement_plan'] == 1){
							$completed++;
						}elseif($row['lkp_rating_id'] != '0' && !empty($row['lkp_rating_id']) && $row['lkp_rating_id'] != 'ni'){
							$completed++;
						}
					break;				
					case 'panel_chair':
						if($row['panel_rating_id'] != '0' && !empty($row['panel_rating_id'])){
							$completed++;
						}
					break;
					case 'recommendation_writer':
						if($row['recomWriter_rating_id'] != '0' && !empty($row['recomWriter_rating_id'])){
							$completed++;
						}
					break;
				}
			}
										

		}
		
		return $completed . '/' . $total;
	}
	function DBValidationRowExist($dbtable, $keyField,$fieldMatch,$jscript){		
		$sql = 'SELECT ' .$keyField.' FROM ' .$dbtable.' WHERE ' .$keyField. ' = ' .$fieldMatch ;
		$rs= $this->db->query($sql);
		$rowCount = $rs->rowCount();
		$exist = ($rowCount > "") ? true : false;
		if($exist){
			$validatonRows = $this->validationRows("success", $jscript);
			$validatonRows[3] = (isset($validatonRows[3])) ? $validatonRows[3] : "";
			$validatonRows[2] = (isset($validatonRows[2])) ? $validatonRows[2] : "";
			$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],"Validation successfull");
		}
		else{
			$validatonRows = $this->validationRows("error", $jscript);
			$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],"Data not filled in");
			$this->formActions["next"]->actionMayShow = false;
		}
	}
	
	function manualValidation($fieldTemplate,$table,$currentTableId,$tableWorkflowId){
		$message ="";
		$lnk1 = $lnk2="";
		$trClass='';
		$dbtable ='';
		$dbtablelkp ='';
		$dbtablelkpId ='';
		$keyField ='';
		$keyFieldTable ='';
		$fieldCheck = '';
		$validatonRows ='';
		$fieldCheckName = array();
		$fieldArr = array();
		$fieldMatch = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
		$jscript = $this->scriptGetForm ($table, $currentTableId, $tableWorkflowId);		
		switch ($fieldTemplate){
			case 'ser_budget_student':
				$this->DBValidationRowExist("nr_programme_bursaries","nr_programme_id",$fieldMatch,$jscript);
			break;
			case 'ser_budget_student_totals':
				$this->DBValidationRowExist("nr_programme_bursary_amounts","nr_programme_id",$fieldMatch,$jscript);
			break;			
			case 'ser_academic_qualifications':
				$this->DBValidationRowExist("nr_programme_academic_qualifications","nr_programme_id",$fieldMatch,$jscript);
			break;			
			case 'ser_academic_demographic':
				$this->DBValidationRowExist("nr_programme_academic_demographics_nat","nr_programme_id",$fieldMatch,$jscript);
			break;
			case 'ser_academic_demographic_race':
				$this->DBValidationRowExist("nr_programme_academic_demographics_rac","nr_programme_id",$fieldMatch,$jscript);
			break;
			case 'ser_student_demographic':
				$this->DBValidationRowExist("nr_programme_students","nr_programme_id",$fieldMatch,$jscript);
			break;
			case 'ser_panelCriteriaEvaluation':
			case 'ser_recommWriterCriteriaEvaluation':
			case 'ser_recommendation_criteria':
			case 'ser_prelim_analysis_criteria':
				$dbtable = "nr_programme_ratings";
				$fieldCheck = (($fieldTemplate == 'ser_panelCriteriaEvaluation') || ($fieldTemplate == 'ser_prelim_analysis_criteria')) ? 'panel_rating_id' : 'recomWriter_rating_id';
				$keyField = " nr_programme_id";
				$lkpKey = "lkp_criteria_id";
				array_push($fieldCheckName,'Rating for Criterion ');
				array_push($fieldCheckName,'No rating selected');				
				$sql = 'SELECT '.$lkpKey.', '.$fieldCheck. ' FROM ' .$dbtable. ' WHERE ' .$keyField . ' = ' .$fieldMatch;
				$RS = $this->db->query($sql);
				$rowCount = $RS->rowCount();
				if($rowCount == 0){
					$message = $fieldCheckName[1];
					$validatonRows = $this->validationRows("error", $jscript);
					$this->formActions["next"]->actionMayShow = false;
					$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message); 
				}else{
					while($row = $RS->fetch()){
						if($row[$fieldCheck]!= "0" && $row[$fieldCheck]> " "){										
							$message = $fieldCheckName[0].' '.$row[$lkpKey] ;
							$validatonRows = $this->validationRows("success", $jscript);
						}else{
							$message = $fieldCheckName[0].' '.$row[$lkpKey];
							$validatonRows = $this->validationRows("error", $jscript);
							$this->formActions["next"]->actionMayShow = false;
						}
							$validatonRows[3] = (isset($validatonRows[3])) ? $validatonRows[3] : "";
							$validatonRows[2] = (isset($validatonRows[2])) ? $validatonRows[2] : "";
							$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message); 
							
					}
				}
				// $this->setAssignedSERCompletionStatus($fieldTemplate, $fieldMatch, $this->formActions["next"]->actionMayShow);
			break;				
			case 'ser_data':
				$dbtable = "nr_programme_ratings";
				$dbtablelkp = "nr_national_review_criteria";
				$dbtablelkpId = "nr_national_review_id";
				$keyField = "nr_programme_id";
				$fieldCheck ='lkp_criteria_id';
				$keyFieldTable = 'nr_programmes';	
				array_push($fieldCheckName,'Rating improvement plan for Criterion');
				array_push($fieldCheckName,'Rating Criteria for Criterion');
				
				
				array_push($fieldArr,'lkp_rating_id');
				array_push($fieldArr,'rating_improvement_plan');
				if(!empty($fieldArr)){
					$sqlNrCriteria = 'SELECT DISTINCT ' .$fieldCheck. ' FROM ' .$dbtablelkp.', '.$keyFieldTable. ' WHERE ' .$dbtablelkp. '.' .$dbtablelkpId . ' = ' .$keyFieldTable. '.' .$dbtablelkpId ;
					$RSNrCriteria = $this->db->query($sqlNrCriteria);
					$rowNrCriteriaArr  = array();
					$sql= 'SELECT * FROM ' .$dbtable.' WHERE ' .$keyField. ' = ' .$fieldMatch ;
					$RS = $this->db->query($sql);
					while($row = $RSNrCriteria->fetch() ){	
						$rowNrCriteriaArr[] = $row[$fieldCheck];
					}
					
					$notFoundArr = array();
					$set = array();
					while($row = $RS->fetch()){
						if(empty($row)){
							foreach($rowNrCriteriaArr as $rowNrCriteria){
								$message = $fieldCheckName[1].' '.$rowNrCriteria ;
								$validatonRows = $this->validationRows("error", $jscript);
								$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message);
								$this->formActions["next"]->actionMayShow = false;
							}
						}
						$set[] = $row[$fieldCheck];
						 
						if(($row[$fieldArr[0]] != "0" && $row[$fieldArr[0]] != "ni") || ($row[$fieldArr[0]] == "ni"  && $row[$fieldArr[1]]== 1)){
							$message = $fieldCheckName[1].' '.$row[$fieldCheck] ;
							$validatonRows = $this->validationRows("success", $jscript);
						}
						else if(( $row[$fieldArr[0]] != "0" && ($row[$fieldArr[0]] == "ni") && ($row[$fieldArr[1]]!= 1))){
							$message = $fieldCheckName[0].' '.$row[$fieldCheck];
							$validatonRows = $this->validationRows("error", $jscript);
							$this->formActions["next"]->actionMayShow = false;
						}else{
							$message = $fieldCheckName[1].' '.$row[$fieldCheck];
							$validatonRows = $this->validationRows("error", $jscript);
							$this->formActions["next"]->actionMayShow = false;
						}
						$validatonRows[3] = (isset($validatonRows[3])) ? $validatonRows[3] : "";
						$validatonRows[2] = (isset($validatonRows[2])) ? $validatonRows[2] : "";
						$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message); 
					}
					$notFoundArr = array_diff($rowNrCriteriaArr, $set);
					
					foreach($notFoundArr as $notFound){
						$message = $fieldCheckName[1].' '.$notFound ;
						$validatonRows = $this->validationRows("error", $jscript);
						$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message);
						$this->formActions["next"]->actionMayShow = false;
					}

				}				
				
			break;
			
			case 'ser_budget_income':
			case 'ser_budget_expenses':
				$dbtable = "nr_programme_budget";
				$fieldCheck ='year';
				$keyField = " nr_programme_id";
				array_push($fieldCheckName,'Year at row ');
				array_push($fieldCheckName,'No year selected ');				
				$sql = 'SELECT '.$fieldCheck. ' FROM ' .$dbtable. ' WHERE ' .$keyField . ' = ' .$fieldMatch;
				$RS = $this->db->query($sql);
				$rowNumber = 0;
				$rowCount = $RS->rowCount();
				if($rowCount == 0){
					$message = $fieldCheckName[1];
					$validatonRows = $this->validationRows("error", $jscript);
					$this->formActions["next"]->actionMayShow = false;
					$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message); 
				}else{
					while($row = $RS->fetch()){
						$rowNumber = $rowNumber +1;
						if($row[$fieldCheck]!= "0"){
							$message = $fieldCheckName[0].' '.$rowNumber ;
							$validatonRows = $this->validationRows("success", $jscript);
						}else{
							$message = $fieldCheckName[0].' '.$rowNumber;
							$validatonRows = $this->validationRows("error", $jscript);
							$this->formActions["next"]->actionMayShow = false;
						}
							$validatonRows[3] = (isset($validatonRows[3])) ? $validatonRows[3] : "";
							$validatonRows[2] = (isset($validatonRows[2])) ? $validatonRows[2] : "";
							$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message); 
					}
				}
			break;
			case 'ser_profile_sites_delivery':
				$dbtable = "nr_programme_sites";
				$keyField = " nr_programme_id";
				array_push($fieldArr,'lkp_site_type_id');
				array_push($fieldArr,'site_name');
				array_push($fieldArr,'physical_address');
				array_push($fieldArr,'postal_address');
				array_push($fieldArr,'site_email');
				array_push($fieldArr,'site_tel_no');
				array_push($fieldArr,'site_fax_no');
				array_push($fieldArr,'site_mobile_no');
				
				array_push($fieldCheckName,'Site type');
				array_push($fieldCheckName,'Name (Institution or other)');
				array_push($fieldCheckName,'Physical address (Faculty or other)');
				array_push($fieldCheckName,'Postal address');
				array_push($fieldCheckName,'Email');
				array_push($fieldCheckName,'Telephone number');
				array_push($fieldCheckName,'Fax number');
				array_push($fieldCheckName,'Mobile number');
				array_push($fieldCheckName,'Only one Main Site of delivery is allowed');
				
				$regexpRow = array();
				array_push($regexpRow,"/^[+]?[(]?[ ]?\d{2,3}[)]?[ ]?\d{3}[\-]?[ ]?\d{4,6}$/"); //phone number
				array_push($regexpRow,"/^[A-z0-9'_\\-\\.]+[@]{1}[A-z0-9_\\-]+([\\.][A-z0-9_\\-]+){1,4}$/");//email
				array_push($regexpRow,"/[[:print:]]+/");//address or name
				
				$sql = 'SELECT * FROM ' .$dbtable. ' WHERE ' .$keyField. ' = ' .$fieldMatch;
				
				$RS = $this->db->query($sql);
				$num_rows = $RS->rowCount();
				if(empty($num_rows)){
					foreach ($fieldCheckName as $fieldName){
						$message = $fieldName;
						$validatonRows = $this->validationRows("error", $jscript);
						$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message);
						$this->formActions["next"]->actionMayShow = false;
					}
				}else{
					$rowNumber = 0;
					$duplicate = 0;
					$messages = array();
			
					while ($row = $RS->fetch()){
						$rowNumber = $rowNumber + 1;
						$messages[$fieldArr[0]]['message'][$rowNumber] = $fieldCheckName[0] . ' ' . $rowNumber;
						$messages[$fieldArr[1]]['message'][$rowNumber] = $fieldCheckName[1] . ' ' . $rowNumber;
						$messages[$fieldArr[2]]['message'][$rowNumber] = $fieldCheckName[2] . ' ' . $rowNumber;
						$messages[$fieldArr[3]]['message'][$rowNumber] = $fieldCheckName[3] . ' ' . $rowNumber;
						$messages[$fieldArr[4]]['message'][$rowNumber] = $fieldCheckName[4] . ' ' . $rowNumber;
						$messages[$fieldArr[5]]['message'][$rowNumber] = $fieldCheckName[5] . ' ' . $rowNumber;
						$messages[$fieldArr[6]]['message'][$rowNumber] = $fieldCheckName[6] . ' ' . $rowNumber;
						$messages[$fieldArr[7]]['message'][$rowNumber] = $fieldCheckName[7] . ' ' . $rowNumber;	
						
						//lkp_site_type_id
						$messages[$fieldArr[0]]['error'][$rowNumber] = (isset($row[$fieldArr[0]]) && !empty($row[$fieldArr[0]]) && $row[$fieldArr[0]] != "0") ? false : true;
						
						if($row[$fieldArr[0]] == "main"){ //check if there are duplicates main sites of delivery
							$duplicate = $duplicate + 1;
						}
						
						//site_name
						$messages[$fieldArr[1]]['error'][$rowNumber] = (isset($row[$fieldArr[1]]) && !empty($row[$fieldArr[1]]) && (preg_match($regexpRow[2], $row[$fieldArr[1]]))) ? false : true;						
						
						$messages[$fieldArr[2]]['error'][$rowNumber] = (isset($row[$fieldArr[2]]) && !empty($row[$fieldArr[2]]) && (preg_match($regexpRow[2], $row[$fieldArr[2]]))) ? false : true;// Physical address
						
						$messages[$fieldArr[3]]['error'][$rowNumber] = (isset($row[$fieldArr[3]]) && !empty($row[$fieldArr[3]]) && (preg_match($regexpRow[2], $row[$fieldArr[3]]))) ? false : true;// Postal address
						
						$messages[$fieldArr[4]]['error'][$rowNumber] = (isset($row[$fieldArr[4]]) && !empty($row[$fieldArr[4]]) && (preg_match($regexpRow[1], $row[$fieldArr[4]]))) ? false : true;// Email
						
						$messages[$fieldArr[5]]['error'][$rowNumber] = (isset($row[$fieldArr[5]]) && !empty($row[$fieldArr[5]]) && (preg_match($regexpRow[0], $row[$fieldArr[5]]))) ? false : true;// Telephone number
						
						$messages[$fieldArr[6]]['error'][$rowNumber] = (isset($row[$fieldArr[6]]) && !empty($row[$fieldArr[6]]) && (preg_match($regexpRow[0], $row[$fieldArr[6]]))) ? false : true;// fax number
						
						$messages[$fieldArr[7]]['error'][$rowNumber] = (isset($row[$fieldArr[7]]) && !empty($row[$fieldArr[7]]) && (preg_match($regexpRow[0], $row[$fieldArr[7]]))) ? false : true;// mobile number
						
					}
					
					if(!empty($messages)){
						if($duplicate >= 2){
							$validatonRows = $this->validationRows("error", $jscript);
							$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],"Only one Main Site of delivery is allowed");
							$this->formActions["next"]->actionMayShow = false;
						}
						if($duplicate == 0){
							$validatonRows = $this->validationRows("error", $jscript);
							$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],"You must have one Main site of delivery");
							$this->formActions["next"]->actionMayShow = false;
						}
						foreach($messages as $field => $fieldData){
							for ( $i = 1; $i <= $num_rows ; $i++){
								$message =  $fieldData['message'][$i];
							
								if($fieldData['error'][$i] ){
									$validatonRows = $this->validationRows("error", $jscript);
									$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message);
									$this->formActions["next"]->actionMayShow = false;
								}
								else{
									$validatonRows = $this->validationRows("success", $jscript);
									$validatonRows[3] = (isset($validatonRows[3])) ? $validatonRows[3] : "";
									$validatonRows[2] = (isset($validatonRows[2])) ? $validatonRows[2] : "";
									$this->htmlvalidationRow($validatonRows[1],$validatonRows[2],$validatonRows[0],$validatonRows[3],$message);	
								}
							}
						}
						
						
					}	
					
				}
			break;
		}
		
		if($this->formActions["next"]->actionMayShow == false){
			$this->removeNext();
		}
	}
	
	function removeNext(){
		echo '<script>';
		echo '$("#action_next").remove();';
		echo '</script>';
	}

	function validationRows($Class, $jscript){
			$lnk1="";
			$lnk2 = "";
			$image = "";
			$trClass = "";
		$rowsArr = array();
		if($Class == "success"){
			$image = Settings::get('imageOK');
			$trClass = "success";
		}
		else{
			$image = Settings::get('imageWrong');
			$trClass = "error";
			$lnk1 = "<a href='".$jscript."'>";
			$lnk2 = "</a>";
		}
		array_push($rowsArr,$image);
		array_push($rowsArr,$trClass);
		array_push($rowsArr,$lnk1);
		array_push($rowsArr,$lnk2);
		
		return $rowsArr;
	}
	function htmlvalidationRow($trClass,$lnk1,$image,$lnk2,$message){
		$htmlRow = <<<htmlRow
		<tbody>
			<tr class = $trClass>
				<td>$lnk1<img src="images/$image">$lnk2</td>
				<td>$message</td>
				<td></td>
			</tr>
		</tbody>
htmlRow;
		echo $htmlRow;		
	}
	
	function array_depth($array) {
		$max_depth = 1;

		foreach ($array as $value) {
			if (is_array($value)) {
				$depth = $this->array_depth($value) + 1;

				if ($depth > $max_depth) {
					$max_depth = $depth;
				}
			}
		}

		return $max_depth;
	}
	
	function getProgrammeAdministrator($prog_id=0, $inst="", $reacc_prog_id=0){

		// NOTE: Getting institution from an accreditation application
		if ($prog_id > 0){
			$inst = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"hei_id");
		}

		// NOTE: Getting institution from a RE_ACCREDITATION application
		// if ($reacc_app_id > 0){
			// $inst = $this->db->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_app_id,"institution_ref");
		// }
		$sql = "SELECT user_id
		FROM users, sec_UserGroups
		WHERE user_id = sec_user_ref
		AND active = 1
		AND sec_group_ref = 2
		AND institution_ref = $inst";
		$RS = $this->db->query($sql);
		$rowCount = $RS->rowCount();
		if ($rowCount == 0){
			$adm_arr = array(0,"No active user is assigned as Institutional Administrator for this institution.");
		}
		if ($rowCount == 1){
			$row = $RS->fetch();
			$adm_arr = array($row["user_id"],"Institutional Administrator");
		}
		if ($rowCount > 1){
			$adm_arr = array(0,"More than one Institutional Administrator exists. Please notify HEQC-Online Support to attend to this immediately.  There should only be one Institutional Administrator.");
		}

		return $adm_arr;
	}
	
	function getGroupProcesses($group){
		$return = array();
		$SQL = "SELECT process_ref FROM `lnk_SecGroup_process` WHERE secGroup_ref =  '" . $group . "'";
		$RS = $this->db->query($SQL);
		
		while($row = $RS->fetch()){
			$return[] = $row['process_ref'];
		}
		
		return $return;
	}

	function checkUserLogin($user, $pass){
		$limit = 5; 
		$SQL = "SELECT orig_password, login_number FROM `users` WHERE user_id = :user AND orig_password = PASSWORD(:pass);";
		$RS = $this->db->query($SQL, compact('user', 'pass'));
		$login = true;
		$message = '';
		
		while($row = $RS->fetch()){
			if($row['login_number'] > $limit){
				$login = false;
				$message = 'You have reached your login limit. Please click on the "Forgot your password?" link to obtain a new password.';
				$pass = $this->makePassword(8,4);
				$SQL = "UPDATE `users` " . 
							 "SET password=PASSWORD(:pass)" . 
							 "WHERE user_id = " . Settings::get('currentUserID');
				$this->db->query($SQL, compact('pass'));
			}else{
				$message = 'You have ' . ($limit - $row['login_number']) .' successful login attempt(s) left. Please change your password as soon as possible.';
			}
		}
		
		echo $this->element('loginMessage', compact('message', 'login', 'class'));
	}
	
	function getInstContactDetails($filter=array(), $template){
		extract($this->createfilterCriteria($filter, $this->reportSearchFields[$template]));
		$details = array();
		
		$SQL = "SELECT nr_programmes.hei_code, nr_programmes.hei_name, nr_programmes.nr_programme_abbr, nr_programmes.head_title, nr_programmes.head_initials, nr_programmes.head_firstname, nr_programmes.head_surname, nr_programmes.head_email, nr_programmes.head_telephone_no, nr_programmes.head_fax_no, nr_programmes.head_mobile_no, nr_programmes.contact_title, nr_programmes.contact_initials, nr_programmes.contact_firstname, nr_programmes.contact_surname, nr_programmes.contact_email, nr_programmes.contact_telephone_no, nr_programmes.contact_fax_no, nr_programmes.contact_mobile_no, users.user_id, users.name, users.surname, users.title_ref, users.email, users.contact_nr, users.contact_cell_nr, sec_UserGroups.sec_group_ref, sec_Groups.sec_group_id, sec_Groups.sec_group_desc, userTitle.lkp_title_desc as userTitleDesc, userTitle.lkp_title_id, contactTitle.lkp_title_desc as contactTitleDesc, contactTitle.lkp_title_id, headTitle.lkp_title_desc as headTitleDesc, headTitle.lkp_title_id
		FROM
			nr_programmes
		LEFT JOIN users ON users.institution_ref = nr_programmes.hei_id
		LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
		LEFT JOIN sec_Groups ON sec_Groups.sec_group_id = sec_UserGroups.sec_group_ref
		LEFT JOIN lkp_title as userTitle ON userTitle.lkp_title_id = users.title_ref
		LEFT JOIN lkp_title as contactTitle ON contactTitle.lkp_title_id = nr_programmes.contact_title
		LEFT JOIN lkp_title as headTitle ON headTitle.lkp_title_id = nr_programmes.head_title
		WHERE
			users.active = 1
			AND
			sec_Groups.sec_group_id = 2";
		
		$SQL = (!empty($where)) ? $SQL . $where : $SQL;
		
		$RS = $this->db->query($SQL, $params) or die($this->db->lastError[2]);
		
		$typeUser = array(
			'Institutional administrator' => array(
				'userTitleDesc' => 'title',
				'contact_cell_nr' => 'mobile',
				'contact_nr' => 'tel.',
				'email' => 'email',
				'name' => 'name',
				'surname' => 'surname',
				'hei_code' => 'hei_code',
				'hei_name' => 'hei_name',
				'nr_programme_abbr' => 'nr_programme_abbr'
			),
			'Head of department' => array(
				'headTitleDesc' => 'title',
				'head_mobile_no' => 'mobile',
				'head_telephone_no' => 'tel',
				'head_email' => 'email',
				'head_firstname' => 'name',
				'head_surname' => 'surname',
				'head_initials' => 'initials',
				'head_fax_no' => 'fax',
				'hei_code' => 'hei_code',
				'hei_name' => 'hei_name',
				'nr_programme_abbr' => 'nr_programme_abbr'
			),
			'Contact person' => array(
				'contactTitleDesc' => 'title',
				'contact_mobile_no' => 'mobile',
				'contact_telephone_no' => 'tel.',
				'contact_email' => 'email',
				'contact_firstname' => 'name',
				'contact_surname' => 'surname',
				'contact_initials' => 'initials',
				'contact_fax_no' => 'fax',
				'hei_code' => 'hei_code',
				'hei_name' => 'hei_name',
				'nr_programme_abbr' => 'nr_programme_abbr'
			)
		);
		
		while($row = $RS->fetch()){
			foreach($typeUser as $type => $fields){
				foreach($fields as $dbField => $fieldTitle){
					if(isset($row[$dbField])){
						$instName = (!empty($row['hei_name'])) ? $row['hei_name'] : $row['hei_code'];
						$details[$instName][$type][$fieldTitle] = $row[$dbField];
					}
				}
			}
		}
		
		return $details;
	}
	
	function filter_equal($field, $filter, &$params){
		$params[$field] = $filter[$field];
		return " = :" . $field;
	}
	
	function filter_compare($field, $filter, &$params){
		$params[$field] = '%' . $filter[$field] . '%';
		return " LIKE :" . $field;
	}
	
	function createfilterCriteria($filter, $searchFields){
		$where = "";
		$params = array();
		
		foreach($searchFields as $field => $function){
			$table = (!empty($function['table'])) ? $function['table'] . "." : "";
			if(isset($filter[$field]) && !empty($filter[$field])){
				$where .= " AND " . $table . $field . $this->{$function['function']}($field, $filter, $params);
			}
			else // check for default
			{
				if (isset($function['default']) && !empty($function['default'])){
					$default = $this->db->getValueFromTable("settings", "s_key", $function['default'], "s_value");
					if (!empty($default)){
						$where .= " AND " . $table . $field . $this->{$function['function']}($field, array($field => $default), $params);
					}
				}
			}
		}

		return compact('where', 'params');
	}
	
	function generateFilterValues($report, $filter){
		$url = '';
		$fields = isset($this->reportSearchFields[$report]) ? $this->reportSearchFields[$report] : '';
		
		if(!empty($fields)){
			foreach($fields as $field => $function){
				if(isset($filter[$field]) && !empty($filter[$field])){
					$url .= $field . '=' . urlencode($filter[$field]) . '&';
				}
			}
		}
		
		return trim($url, '&');
	}
	
	function getInstProgressDetails($filter=array(), $template){
		extract($this->createfilterCriteria($filter, $this->reportSearchFields[$template]));
		$process = 8;
		$details = array();
		
		$inst_id = $this->db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "institution_ref");
		
		$SQL = "SELECT nr_programmes.id, nr_programmes.hei_code, nr_programmes.hei_name, nr_programmes.nr_programme_abbr, nr_programmes.nr_programme_name, nr_programmes.heqsf_reference_no, nr_programmes.date_submitted, nr_programmes.ser_doc
		FROM
			nr_programmes
		WHERE
			nr_programmes.hei_id = '" . $inst_id . "'";
		
		$SQL = (!empty($where)) ? $SQL . $where : $SQL;
		
		$RS = $this->db->query($SQL, $params) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$row['active_process_person'] = $this->getSERProcessDetails($row['id'], 'id', 'nr_programmes');
			$row['link_report'] = $this->createDocLink($row['ser_doc'], 'SER');
			array_push($details, $row);
		}
		
		return $details;
	}
	
	function createDocLink($docID, $text){
		$link = '';
		$doc = new octoDoc($docID);
		
		if ($doc->isDoc()){
			$link = '<a href="' . $doc->url() . '" target="_blank">' . $text . '</a>';
		}
		
		return $link;
	}
	
	function getSERProcessDetails($lkpValue, $lkpField, $lkpTable){
		$return = 'Closed';
		$searchKEY = 'DBINF_' . $lkpTable . '___' . $lkpField;
		$SQL = "SELECT active_processes.user_ref, active_processes.workflow_settings, active_processes.processes_ref, processes.processes_desc
		FROM
			active_processes
		LEFT JOIN processes ON processes.processes_id = active_processes.processes_ref
		WHERE
			active_processes.status = 0";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$user = '';
			Settings::set('workFlow_settings', array());
			$this->parseWorkFlowString($row['workflow_settings']);
			$workFlowSettings = Settings::get('workFlow_settings');
			$fieldValue = isset($workFlowSettings[$searchKEY]) ? $workFlowSettings[$searchKEY] : '';
			if(!empty($fieldValue) && $lkpValue == $fieldValue){
				$user = $this->db->getValueFromTable('users', 'user_id', $row['user_ref'], 'CONCAT(name, " ", surname) as nameSurname');
				$return = $row['processes_desc'] . ' (' . $user . ')';
			}
		}
		
		return $return;
	}
	
	function getNRProgressDetails($filter=array(), $template, $panelStyle = ""){
		extract($this->createfilterCriteria($filter, $this->reportSearchFields[$template]));
		$details = array();
		
		$SQL = "SELECT nr_programmes.id, nr_programmes.hei_code, nr_programmes.hei_name, nr_programmes.nr_programme_abbr, nr_programmes.nr_programme_name, nr_programmes.heqsf_reference_no, nr_programmes.date_submitted, nr_programmes.ser_doc, nr_programmes.signoff_doc, nr_programmes.analyst_report_doc, nr_programmes.chair_report_doc, nr_programmes.recommendation_report_doc, nr_programmes.heqc_recommendation_report_doc, nr_programmes.heqc_nrc_report_doc
		FROM
			nr_programmes
		WHERE
			nr_programmes.id != ''";
		
		$SQL = (!empty($where)) ? $SQL . $where : $SQL;

		$RS = $this->db->query($SQL, $params) or die($this->db->lastError[2]);
		
		
		$curentUserEdit_id = Settings::get('currentUserID');
		$rg_groupId = $this->getGroupId('RC member');
		$nrc_groupId = $this->getGroupId('NRC member');
		
		$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	
		$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);

		$recommLinkTitle = ($isNRC_member || $isRgMember) ? 'SER, Review panel and Recommendation criteria compared' : 'SER and Review panel criteria compared';
		
		while($row = $RS->fetch()){
			$url = "javascript:showSERreadOnly(" . $row['id'] . ");";	
			$onlineTable = '<a href="' . $url . '">Profile of the programme</a>';
			$comparisonLink = $this->scriptGetForm('nr_programmes', $row['id'], '_label_ser_recommWriter_Criteria');
			// $urlRecommCriteria = "javascript:recommCriteriaComparison(" . $row['id'] . ");";
			$urlRecommCriteria = "javascript:recommCriteriaComparison(\"" . $row['id'] . "\", \"" . $recommLinkTitle . "\");";		
			
			$row['active_process_person'] = $this->getSERProcessDetails($row['id'], 'id', 'nr_programmes');
			$row['screening'] = $this->getScreeningDetails('programme_ref', $row['id']);
			$row['prelimAnalysis'] = $this->getPrelimAnalysisDetails('id', $row['id']);
			$row['link_report'] = $this->createDocLink($row['ser_doc'], 'SER');
			
			$row['serSubmissionArr']['ser'] = $this->createDocLink($row['ser_doc'], 'SER');
			$row['serSubmissionArr']['sign_off'] = $this->createDocLink($row['signoff_doc'], 'Sign-off');
			$row['serSubmissionArr']['data_table'] = $onlineTable;
			
			$row['onlineTable'] = $onlineTable;			
			$row['panelDetails'] = $this->getPanelDetails('id', $row['id'],$panelStyle);
			$row['recommDetails'] = $this->getRecommDetails('id', $row['id']);
			$row['additionalDocArr'] = $this->getPrelimAdditionalInfo($row['id']);
			$row['comparisonLink'] = "<a href='" . $urlRecommCriteria . "'>" . $recommLinkTitle . "</a>";
			$row['rgMeetingDetails'] = $this->getInstRGProgMeetings($row['id']);
			$row['nrMeetingDetails'] = $this->getInstNRCProgMeetings($row['id']);
			array_push($details, $row);
		}
		
		return $details;
	}
	
	function getRecommDetails($lkpField, $lkpValue){
		$recomm = array();
		$return = '';
		
		$SQL = "SELECT nr_programmes.recommendation_user_ref, nr_programmes.recommendation_start_date, nr_programmes.recommendation_end_date, nr_programmes.recommendation_report_doc, nr_programmes.recommendation_report_due_date
		FROM
			nr_programmes
		WHERE
			" . $lkpField . "= '" . $lkpValue . "'
		ORDER BY nr_programmes.id ASC
			";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"recommendation_completed");
			$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"recommendationSubmittedByAdmin_ind");		
		
			$recomm['recommWriter'] = $this->db->getValueFromTable('users', 'user_id', $row['recommendation_user_ref'], 'CONCAT(name, " ", surname, " (", email, ")" ) as nameSurname');
			$recomm['link_recomm_report'] = ($recommendationCompleted == '1' || $recommendationSubmittedByAdmin_ind == '1') ? $this->createDocLink($row['recommendation_report_doc'], 'Recomm Report') : '';
			$recomm['accessDates'] = ($row['recommendation_start_date'] == "1970-01-01" || $row['recommendation_end_date'] == "1970-01-01" ) ? "Not assigned" : $row['recommendation_start_date'] . " to " . $row['recommendation_end_date'];
			$recomm['due-date'] = ($row['recommendation_report_due_date'] == "1970-01-01") ? "Not assigned" : $row['recommendation_report_due_date'];
		}
		return $recomm;
	}	
	
	function getInstitutionInfo($field){
		$instID = $this->db->getValueFromTable('users', 'user_id', Settings::get('currentUserID'), 'institution_ref');
		$instDetails = $this->db->getValueFromTable('nr_programmes', 'hei_id', $instID, $field);
		
		return $instDetails;
	}
	
	function getPanelDetails($lkpField, $lkpValue, $panelStyle = ""){
		$panel = array();
		
		$SQL = "SELECT nr_programmes.chair_report_doc, nr_programmes.chair_report_due_date, nr_programmes.panel_start_date, nr_programmes.panel_end_date, nr_programmes.site_visit_date
		FROM
			nr_programmes
		WHERE
			" . $lkpField . "= '" . $lkpValue . "'
		ORDER BY nr_programmes.id ASC";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
		
		$siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"siteVisit_completed");					
		$siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"siteVisitSubmittedByAdmin_ind");	
		
			$panel['members'] = $this->getPanelMembers($lkpValue,$panelStyle);
			$panel['link_panel_report'] = ($siteVisit_completed == '1' || $siteVisitSubmittedByAdmin_ind == '1') ? $this->createDocLink($row['chair_report_doc'], 'Chair Report') : '';
			$panel['accessDates'] = ($row['panel_start_date'] == "1970-01-01" || $row['panel_end_date'] == "1970-01-01" ) ? "Not assigned" : $row['panel_start_date'] . " to " . $row['panel_end_date'];
			$panel['site_visit_date'] = (($row['site_visit_date'] == "1970-01-01") ? "Not assigned" : $row['site_visit_date']);
		}
		return $panel;		
	}
	
	function getPrelimAnalysisDetails($lkpField, $lkpValue){
		$prelim = array();
		$return = '';
		
		$SQL = "SELECT nr_programmes.analyst_user_ref, nr_programmes.analyst_start_date, nr_programmes.analyst_end_date, nr_programmes.analyst_report_doc
		FROM
			nr_programmes
		WHERE
			" . $lkpField . "= '" . $lkpValue . "'
		ORDER BY nr_programmes.id ASC
			";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$analystReportSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"analystReportSubmittedByAdmin_ind");
			$prelimAnalysis_completed = $this->db->getValueFromTable("nr_programmes","id",$lkpValue,"prelimAnalysis_completed");
			
			$prelim['analyst'] = $this->db->getValueFromTable('users', 'user_id', $row['analyst_user_ref'], 'CONCAT(name, " ", surname) as nameSurname');
			$prelim['link_analyst_report'] = ($prelimAnalysis_completed == '1' || $analystReportSubmittedByAdmin_ind == '1') ? $this->createDocLink($row['analyst_report_doc'], 'Desktop Evaluation Report') : '';
			$prelim['accessDates'] = ($row['analyst_start_date'] == "1970-01-01" || $row['analyst_end_date'] == "1970-01-01" ) ? "Not assigned" : $row['analyst_start_date'] . " to " . $row['analyst_end_date'];
		}
		return $prelim;
	}
	
	function getScreeningDetails($lkpField, $lkpValue,$form =""){
		$screening = array();
		$return = '';
		
		$SQL = "SELECT screening.active_user_ref, screening.screening_id, screening.programme_ref, screening.date_screening_signed, screening.checklist_report_doc
		FROM
			screening
		WHERE
			" . $lkpField . "= '" . $lkpValue . "'
		ORDER BY screening.screening_id ASC
			";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$row['screener'] = $this->db->getValueFromTable('users', 'user_id', $row['active_user_ref'], 'CONCAT(name, " ", surname) as nameSurname');
			$row['link_report'] = $this->createDocLink($row['checklist_report_doc'], 'Scr Report');
			array_push($screening, $row);
		}
		
		if(!empty($screening)){
			foreach($screening as $screen){
				$return .= $screen['screener'] . ', ' . (($screen['date_screening_signed'] != '1970-01-01') ? $screen['date_screening_signed'] : 'In progress') . ', ' . ((!empty($screen['link_report']) ? $screen['link_report'] : 'No report')) . ' <hr>';
			}
		}
		$result = ($form > '' && !empty($screening)) ? $screening :  (trim($return, "<hr>"));
		return $result;
	}
	
	function getMenuProcesses($lkpField, $processes){
		$return = array();
		
		$processes = "'" . implode("', '", $processes) . "'";

		
		$SQL = "SELECT processes.processes_id, processes.processes_desc, processes.menu_perant
		FROM
			processes
		WHERE
			" . $lkpField . " IN (" . $processes . ")
		ORDER BY processes.menu_sequence_number ASC
			";
		
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		$count = 0;
		
		while($row = $RS->fetch()){
			$return[$count]['processes_ref'] = $row['menu_perant'];
			$return[$count]['processes_id'] = $row['processes_id'];
			$return[$count]['processes_desc'] = $row['processes_desc'];
			$count++;
		}
		
		return $return;
	}

	function displayProgrammeInfo(){
		$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
		$adminArr = $this->getProgrammeAdministrator($prog_id);
		$infoArr = $this->db->customArrRequest("nr_programme_name, hei_name, hei_code, nr_national_review_id, chair_report_due_date, panel_start_date, panel_end_date","nr_programmes","","id = $prog_id");
		$admInfoArr = $this->db->customArrRequest("name, surname, email, contact_nr","users","","user_id = $adminArr[0]");
		$nr_programme_name = $infoArr["nr_programme_name"];
		$institution_name = $infoArr["hei_name"];
		$institution_code = $infoArr["hei_code"];
		$nr_national_review_id = $infoArr["nr_national_review_id"];
		$contactNr = (!empty($admInfoArr["contact_nr"])) ? ', ' . $admInfoArr["contact_nr"] : '';
		
		echo $this->element('programme_info', compact('nr_programme_name', 'institution_name', 'institution_code', 'nr_national_review_id', 'admInfoArr', 'contactNr','chair_report_due_date', 'panel_start_date', 'panel_end_date'));
	}
	
	function displayRoleDueDates($role){
		$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
		$dueDateField = '';
		$startDateField = '';
		$endDateField = '';
		
		switch($role){
			case "chair":
				$dueDateField = 'chair_report_due_date';
				$startDateField = 'panel_start_date';
				$endDateField = 'panel_end_date';
			break;
			case "recommendation":
				$dueDateField = 'recommendation_report_due_date';
				$startDateField = 'recommendation_start_date';
				$endDateField = 'recommendation_end_date';			
			break;			
		}
		if($dueDateField > '' && $startDateField > '' && $endDateField > '' ){
			$infoArr = $this->db->customArrRequest("$dueDateField, $startDateField, $endDateField","nr_programmes","","id = $prog_id");
			
			$dueDate = $infoArr[$dueDateField];
			$startDate = $infoArr[$startDateField];
			$endDate = $infoArr[$endDateField];
			echo $this->element('dueDatesInfo', compact('dueDate', 'startDate', 'endDate'));
		}
	}	
	function previewReadOnly($settings="", $path="",$topdf= "",$pdffileName= "",$nr_type=""){
		if($settings == ""){
			$settings = $this->getStringWorkFlowSettings(Settings::get('workFlow_settings'));
		}
		switch ($nr_type) {
	 	case 'BSW':
			$formsToPreview = array("ser_upload_files_preview","ser_profile","ser_contact_head","ser_contact","ser_profile_sites_delivery","ser_budget_income","ser_budget_expenses","ser_budget_student","ser_budget_student_totals","ser_academic_qualifications","ser_academic_demographic","ser_academic_demographic_race","ser_student_demographic","ser_data");
			break;
	 	default:  // Always set to the latest national review SER process
			$formsToPreview = array("ser_upload_files_preview","ser_profile");
		 }
				
		if(!function_exists('doOutPutBuffer')) {
			function doOutPutBuffer ($buffer) {
				$h = fopen ("/tmp/nr_mis_output.html", "w+");
				$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:openFileWin|changeCMD|winContentText.*).*\>)(.*)(\<\/a\>)/U","/\<div class=\"form-actions\">.*\<\/div\>/U");
				$replace_array = array("", "\\2");       
				$html = $buffer;							
				$html = preg_replace ($search_array, $replace_array, $buffer);
				fwrite($h, $html);
				return $html;
			}
		}

		if($topdf){
			$pdf = new WkHtmlToPdf(array(
				'no-outline',         // Make Chrome not complain
				'margin-top'    => 0,
				'margin-right'  => 0,
				'margin-bottom' => 0,
				'margin-left'   => 0,
				'orientation' => 'Landscape'
			));

			$pdf->setPageOptions(array(
				'disable-external-links',
				'disable-internal-links',
				'user-style-sheet' => $path.'css_print/pdf.css'
			));
			foreach ($formsToPreview as $form) {
				ob_start("doOutPutBuffer");
				$app = new NRonline (1);
				$app->parseWorkFlowString($settings);
				$app->view = 1;
				$app->formStatus = FLD_STATUS_DISABLED;
				$app->readTemplate($form);
				echo '<html>'  . $app->createHTML($app->body, $path) . '</html>';
				$pdf->addPage(ob_get_contents());
				unset ($app);
				ob_end_clean();
			}
			$pdffileName >"" ? $pdf->send($pdffileName) : $pdf->send();
		}
		else{
			ob_start("doOutPutBuffer");
			foreach ($formsToPreview as $form) {
				$app = new NRonline (1);
				$app->parseWorkFlowString($settings);
				$app->view = 1;
				$app->formStatus = FLD_STATUS_DISABLED;
				$app->readTemplate($form);
				$app->createHTML($app->body, $path);
				unset ($app);
			}
			ob_end_flush();
		}
	}
	
	function getInstitutionName($progID){
		$SQL = "SELECT hei_name  FROM nr_programmes WHERE id = ".$progID;
		$RS = $this->db->query($SQL);
		$row = $RS->fetch();
		$institutioNname = (isset($row['hei_name']) && !empty($row['hei_name'])) ? $row['hei_name'] : "";
		return $institutioNname;
	}
	
	function makeDropdownOfGroupUsers($grp){
		$items = "";	
		$SQL = "SELECT email, user_id FROM users, sec_UserGroups WHERE sec_group_ref = $grp AND sec_user_ref=user_id AND active = 1";
		$RS = $this->db->query($SQL);
		while ($row = $RS->fetch()) {
			$sel = "";
			if (Settings::get('currentUserID') == $row["user_id"]) $sel = "SELECTED";
			$items .= '<option value="'.$row["user_id"].'" '.$sel.'>'.$row["email"].'</option>';
		}
		$dd = <<<DROPDOWN
			<select name="user_ref">
			$items
			</select>
DROPDOWN;
		return $dd;
	}
	
		// 2010-10-27 Robin - function to pass processes back and forwards if given the new user and process and last workflow.
	function changeProcessAndUser($new_proc, $new_user, $mail_subject="",$mail_content="", $flow=0,$newWorkFlow=""){
		// In order to prevent a refresh from re-inserting the active process - only do the processing if the current active process status is 0 (active).
		// If status is '1' then it has already been closed and the processing has been done.
		$active_processes_id = Settings::get('active_processes_id');
		$currentUserID = Settings::get('currentUserID');
		$current_process_status = -1;  // initialise
		$current_process_status = $this->db->getValueFromTable('active_processes','active_processes_id',$active_processes_id,'status');
		if ($current_process_status == 0){
		
			// If an email text is provided then email the user
			if ($mail_subject > "" || $mail_content > ""){
				$cc = $this->db->getValueFromTable("users", "user_id", $currentUserID, "email");
				$to = $this->db->getValueFromTable("users", "user_id", $new_user, "email");

				$this->Email->misMailByName($to, $mail_subject, $mail_content, $cc);
			}
		
			$id = $this->addActiveProcesses ($new_proc, $new_user, $flow);
			$this->completeActiveProcesses();

		}
	}
	
	function getSelectedPanel($grps, $fieldName, $id){
		$selectedUsers = array();
		if($id != 'NEW'){
			$SQL = "SELECT *
				FROM lnk_prelim_analysis_user
				WHERE nr_programme_id  = :id";
			$RS = $this->db->query($SQL, compact('id')) or die($this->db->lastError[2]);
			while($row = $RS->fetch()){
				if (empty($selectedUsers[$row['sec_group_ref']])) {
					$selectedUsers[$row['sec_group_ref']] = array();
				}
				$selectedUsers[$row['sec_group_ref']][] = $row['user_ref'];
			}
		}
		$return = array();
		$return['table'] = '';
		
		foreach($grps as $groupID){
			$return['list'][$groupID] = array();
		}
		
		$grps = implode(",", $grps);
		$SQL = "SELECT *
			FROM users
			LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
			LEFT JOIN lkp_title ON lkp_title.lkp_title_id = title_ref
			LEFT JOIN sec_Groups ON sec_Groups.sec_group_id = sec_UserGroups.sec_group_ref
			WHERE sec_group_ref IN(" . $grps . ") AND sec_user_ref = user_id AND users.active = 1
			ORDER BY users.institution_ref ASC
		";
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		$return['table'] .= '<table class="table table-hover table-bordered table-striped panelUsersTable">';
		$return['table'] .= '<thead>';
		$return['table'] .= '<tr><th>Role</th><th>Title</th><th>Name</th><th>Surname</th><th>Institution</th><th>Email</th><th>Telephone number</th><th>Action</th></tr>';
		$return['table'] .= '</thead>';
		$return['table'] .= '<tbody>';
		while($row = $RS->fetch()){
			$id = $row['user_id'].'_'.$row['sec_group_ref'];
			$text = $row['name'] . ' ' . $row['surname']  . ' (' . $row['email'] . ')';
			array_push($return['list'][$row['sec_group_ref']], array('text' => $text, 'id' => $id));
			
			$checked = '';
			$class = 'hidden';
			if (isset($selectedUsers[$row['sec_group_ref']]) && in_array($row['user_id'], $selectedUsers[$row['sec_group_ref']])) {
				$checked = ' CHECKED';
				$class = '';
			}
			
			$return['table'] .= '<tr class="' . $class . '">';
			$return['table'] .= '<td>' . $row['sec_group_desc'] . '</td>';
			$return['table'] .= '<td>' . $row['lkp_title_desc'] . '</td>';
			$return['table'] .= '<td>' . $row['name'] . '</td>';
			$return['table'] .= '<td>' . $row['surname'] . '</td>';
			$return['table'] .= '<td>' . $row['institution_name'] . '</td>';
			$return['table'] .= '<td>' . $row['email'] . '</td>';
			$return['table'] .= '<td>' . $row['contact_nr'] . '</td>';
			$return['table'] .= '<td><input type="checkbox" class="hidden" name="' . $fieldName . '" value="' . $id . '" ' . $checked . '><input type="button" class="btn delButton" value="Remove"></td>';
			$return['table'] .= '</tr>';
		}
		$return['table'] .= '</tbody>';
		$return['table'] .= '</table>';
		return $return;
	}
	
	function savePanelUsers($field, $lkpValue, $lkpTable, $lkpField){
		$valuesToSave = (isset($_POST[$field])) ? $_POST[$field] : '';

		if(!empty($valuesToSave)){
			$SQL ="DELETE FROM `" . $lkpTable . "` WHERE " . $lkpField . " = " . $lkpValue;
			$rsDelete = $this->db->query($SQL, array());
			$insertSQL = '';
			foreach($valuesToSave as $valueSave){
				$userRef = substr($valueSave, 0, strpos($valueSave, '_'));
				$group_ref = substr($valueSave, -1, strpos($valueSave, '_'));
			
				$insertSQL = "INSERT INTO " . $lkpTable . " (user_ref, sec_group_ref, nr_programme_id) VALUES (" . $userRef . ", " . $group_ref . ", " . $lkpValue . " );";
				$rsInsert = (!empty($insertSQL)) ? $this->db->query($insertSQL, array()) : '';
			}
		}
	}
	
	function getPrelimAnalysisData($table, $lkpField, $lkpFieldValue, $conditions){
		$return = array();
		
		$SQL = "SELECT *
			FROM " . $table . "
			WHERE " . $lkpField . "=" . $lkpFieldValue . " AND " . $conditions;
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
			array_push($return, $row);
		}
		
		return $return;
	}
	
	function getPanelMembers($programmeID, $style =''){
		$SQL = "SELECT * 
				FROM lnk_prelim_analysis_user 
				LEFT JOIN users ON lnk_prelim_analysis_user.user_ref = users.user_id
				WHERE nr_programme_id = :programmeID";
		$rs = $this->db->query($SQL,compact('programmeID'));
		$userArr = array();
		$name = " ";
		while ($row = $rs->fetch()) {
			array_push($userArr, $row);
		}
		if(!empty($userArr)){
			$totalUsers = count($userArr) - 1;
			foreach($userArr as $index => $user){
				switch($style){
					case "list":
					echo '<ul>';
					$name .= "<li>". $user['name'] . " " . $user['surname'] ."\n(".$user['email'] .")"."</li>";
					echo '</ul>';
					break;
					case "role":
						$role = ($user['sec_group_ref'] == 6 ) ? " (chair)" : "";
						$name .= "<li>". $user['name'] . " " . $user['surname'] .$role."</li>";
					break;
					default:
					$name .= $user['name'] . " " . $user['surname'] ."\n(".$user['email'] .")";
					$name .= ($index < $totalUsers) ? ', ' : '';
				}
			}
		}
		return $name;
	}
	
	function getPrelimPanelData($table, $conditions,$lnkTable, $lnkField, $lnkFieldValue, $lnkLeftJoinCond){
		$return = array();
		
		$SQL = "SELECT *
			FROM " . $table . "
			LEFT JOIN " . $lnkTable . " ON " . $lnkLeftJoinCond . "
			WHERE " . $conditions . " AND " . $lnkField . " = " . $lnkFieldValue .
			" GROUP BY nr_programmes.id";
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			array_push($return, $row);
		}
		
		return $return;
	}

	function getRecommendationData($table, $conditions, $lnkField, $lnkFieldValue){
		$return = array();
		
		$SQL = "SELECT *
			FROM " . $table . "
			WHERE " . $conditions . " AND " . $lnkField . " = " . $lnkFieldValue .
			" GROUP BY nr_programmes.id";
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
			$additionalDocArr = $this->getPrelimAdditionalInfo($row['id']);
			$row['additionalDoc_list'] = $additionalDocArr;
			array_push($return, $row);
		}
		
		return $return;
	}
	
	function displayCriteriaComparison($prog_id = "", $topdf ="", $pdffileName =""){
		// $path="../";
		$prog_id = ($prog_id > "") ? $prog_id : $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
		$infoArr = $this->db->customArrRequest("nr_programme_name, hei_name","nr_programmes","","id = $prog_id");
		$nr_programme_name = $infoArr["nr_programme_name"];
		$institution_name = $infoArr["hei_name"];
		$sql = "SELECT *
				FROM nr_programme_ratings
				WHERE nr_programme_id = :prog_id";
		$RS = $this->db->query($sql,compact('prog_id')) or die($this->db->lastError[2]);
		$ratingArr = array();
		while($row = $RS->fetch()){
			array_push($ratingArr,$row);
		}	
		$curentUserEdit_id = Settings::get('currentUserID');
		$rg_groupId = $this->getGroupId('RC member');
		$nrc_groupId = $this->getGroupId('NRC member');
		
		$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	
		$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);		
		
		if(!empty($ratingArr)){
			foreach($ratingArr as $index => $rating){
				$criteriaDesc = $this->db->getValueFromTable('lkp_criteria','id',$rating['lkp_criteria_id'],'short_desc');
				$criterion_title = $this->db->getValueFromTable('lkp_criteria','id',$rating['lkp_criteria_id'],'criterion_title');
				$institutionRatingDesc = $this->db->getValueFromTable('lkp_ratings','id',$rating['lkp_rating_id'],'lkp_ratings_desc');
				$panelRatingDesc = $this->db->getValueFromTable('lkp_ratings','id',$rating['panel_rating_id'],'lkp_ratings_desc');
				$recommRatingDesc = $this->db->getValueFromTable('lkp_ratings','id',$rating['recomWriter_rating_id'],'lkp_ratings_desc');
				$ratingArr[$index]['short_desc'] = $criteriaDesc;
				$ratingArr[$index]['criterion_title'] = $criterion_title;
				$ratingArr[$index]['institutionRatingDesc'] = $institutionRatingDesc;
				$ratingArr[$index]['panelRatingDesc'] = $panelRatingDesc;
				$ratingArr[$index]['recommRatingDesc'] = $recommRatingDesc;
			}
			echo $this->element('criteriaComparison_information', compact('institution_name', 'nr_programme_name','ratingArr', 'isNRC_member','isRgMember' ));
		}
		
		if($topdf){
			$pdf = new WkHtmlToPdf(array(
				'no-outline',         // Make Chrome not complain
				'margin-top'    => 0,
				'margin-right'  => 0,
				'margin-bottom' => 0,
				'margin-left'   => 0,
				'orientation' => 'Landscape'
			));

			$pdf->setPageOptions(array(
				'disable-external-links',
				'disable-internal-links'
				// ,
				// 'user-style-sheet' => $path.'css_print/pdf.css'
			));
				$pdf->addPage('../html/elements/criteriaComparison_information.html.php', compact('institution_name', 'nr_programme_name','ratingArr'));
			
			$pdffileName >"" ? $pdf->send($pdffileName) : $pdf->send();
		}
				
		
	}
	
	function getSERRecommendationDetails($filter=array(), $template){
		extract($this->createfilterCriteria($filter, $this->reportSearchFields[$template]));
		$details = array();
		
		$SQL = "SELECT nr_programmes.id, nr_programmes.hei_id, nr_programmes.hei_name, nr_programmes.chair_report_doc, nr_programmes.nr_programme_name, nr_programmes.analyst_report_doc, nr_programmes.recommendation_report_doc, nr_programmes.ser_doc, nr_programmes.signoff_doc, nr_programmes.heqc_recommendation_report_doc
		FROM
			nr_programmes
		WHERE
			nr_programmes.id != ''";
		
		$SQL = (!empty($where)) ? $SQL . $where : $SQL;
		
		$RS = $this->db->query($SQL, $params) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			// $url = "javascript:showSERreadOnly(" . $row['id'] . ");";						
			// $serReport = $this->createDocLink($row['ser_doc'], 'SER');
			// $SignOff = $this->createDocLink($row['signoff_doc'], 'Sign-off');
			// $onlineTable = '<a href="' . $url . '">data tables</a>';
			// $comparisonLink = $this->scriptGetForm('nr_programmes', $row['id'], '_label_ser_recommWriter_Criteria');
			// $row['serSubmission'] = $serReport . ' | ' .  $SignOff . ' | ' . $onlineTable;
			// $row['prelimReport'] = $this->createDocLink($row['analyst_report_doc'], 'Prelim-report');
			// $row['panelReport'] = $this->createDocLink($row['chair_report_doc'], 'Panel report');
			// $row['criteriaComparison'] = "<a href='" . $comparisonLink . "'>SER and Panel compared</a>";
			// $row['recomendationReport'] = $this->createDocLink($row['recommendation_report_doc'], 'Recomm report');
			$rowDetails = $this->nrProgDocDetails($row['id'], $row['ser_doc'], $row['signoff_doc'], $row['analyst_report_doc'], $row['chair_report_doc'], $row['recommendation_report_doc'], $row['heqc_recommendation_report_doc'],'', $row['heqc_nrc_report_doc']);
			$row['additionalDocArr'] = $this->getPrelimAdditionalInfo($row['id']);
			$row['rowDetails'] = $rowDetails;
			array_push($details, $row);
		}
		return $details;
	}
	function nrProgDocDetails($id, $ser_doc, $signoff_doc, $analyst_report_doc, $chair_report_doc, $recommendation_report_doc, $heqc_recommendation_report_doc,$recommendationPage = "", $heqc_nrc_report_doc, $improvement_doc){
		$row = array();
	
		$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$id,"recommendation_completed");
		$siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$id,"siteVisit_completed");
		
		$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$id,"recommendationSubmittedByAdmin_ind");
		$siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$id,"siteVisitSubmittedByAdmin_ind");		
		
		$analystReportSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$id,"analystReportSubmittedByAdmin_ind");
		$prelimAnalysis_completed = $this->db->getValueFromTable("nr_programmes","id",$id,"prelimAnalysis_completed");		
		
		$url = "javascript:showSERreadOnly(" . $id . ");";						
		$serReport = $this->createDocLink($ser_doc, 'SER');
		$SignOff = $this->createDocLink($signoff_doc, 'Sign-off');
		$onlineTable = '<a href="' . $url . '">Profile of the programme</a>';
		
		$comparisonLink = $this->scriptGetForm('nr_programmes', $id, '_label_ser_recommWriter_Criteria');
		
		$curentUserEdit_id = Settings::get('currentUserID');
		$rg_groupId = $this->getGroupId('RC member');
		$nrc_groupId = $this->getGroupId('NRC member');
		
		$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	
		$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);

		$recommLinkTitle = ($isNRC_member || $isRgMember) ? 'SER, Review panel and Recommendation criteria compared' : 'SER and Review panel criteria compared';		

		// $urlRecommCriteria = "javascript:recommCriteriaComparison(" . $id . ");";
		
		$urlRecommCriteria = "javascript:recommCriteriaComparison(\"" . $id . "\", \"" . $recommLinkTitle . "\");";
		$comparisonUrl = ($recommendationPage > "" ) ? $urlRecommCriteria :  $comparisonLink;
			
		$row['serSubmission'] = $serReport . ' | ' .  $SignOff . ' | ' . $onlineTable;
		$row['prelimReport'] = ($prelimAnalysis_completed == '1' || $analystReportSubmittedByAdmin_ind == '1') ? $this->createDocLink($analyst_report_doc, 'Desktop Evaluation report') : '';
		$row['panelReport'] = ($siteVisit_completed == '1' || $siteVisitSubmittedByAdmin_ind == '1') ? $this->createDocLink($chair_report_doc, 'Review panel report') : '' ;
		$row['criteriaComparison'] =  "<a href='" . $comparisonUrl . "'> " . $recommLinkTitle ." </a>";
		$row['recomendationReport'] = ($recommendationCompleted == '1' || $recommendationSubmittedByAdmin_ind == '1') ? $this->createDocLink($recommendation_report_doc, 'Recomm report') : '' ;
		$row['heqcRecomendationReport'] = $this->createDocLink($heqc_recommendation_report_doc, 'Final HEQC report');
		$row['heqc_NRC_report'] = $this->createDocLink($heqc_nrc_report_doc, 'National Review Committee report');
		$row['improvement_doc'] = $this->createDocLink($improvement_doc, 'Improvement document');
		return $row;
	}
	//updated functionnality of makeDropdownOfGroupUsers
	function manageGroupMemberDropdown($grp, $selection = ""){
		$userSelectArr = array();
		$userAvailArr = array();
		$userIdArr = array();
		$name = "";
		$SQLSelected = "SELECT user_id, name, surname, email  FROM users, sec_UserGroups WHERE sec_group_ref = :grp AND sec_user_ref=user_id AND active = 1 ORDER BY name";
		
		$RSSelected = $this->db->query($SQLSelected, compact('grp'));
		
		while ($row = $RSSelected->fetch()) {
			// $restrictions = $this->getMeetingRestriction($row['user_id']);
			// $row['restriction'] = $restrictions[$row['user_id']]['progName'];
			array_push($userSelectArr, $row);
			array_push($userIdArr, $row['user_id']);
		}
		$placeHolders = (!empty($userIdArr)) ? implode(', ', array_fill(0, count($userIdArr), '?')) : 0;

		$SQLAvailable = "SELECT user_id, name, surname, email FROM users WHERE user_id NOT IN ($placeHolders) ORDER BY name ";
		$RSAvailable = $this->db->query($SQLAvailable, $userIdArr);
		while ($rowAvail = $RSAvailable->fetch()) {
			array_push($userAvailArr, $rowAvail);
		}
				
		$selectName = ($selection == "selected") ? "userSelected[]" : "userAvailable[]";
		$selectionArr = ($selection == "selected") ? $userSelectArr : $userAvailArr;
		$title = ($selection == "selected") ? "Selected users" : "Available users to select";
		if(!empty($selectionArr) || $selectionArr == $userSelectArr){
			echo '<div class = "select-container span5">';
			echo "<h3>" . $title . "</h3>";
			echo '<div class = "' . $selection .'">';
			echo '<select id = "' . $selection . '" name= "' . $selectName . '" class= "' . $selection . '" multiple = "multiple" size = "10">';
			foreach($selectionArr as $key => $user){							
				echo "<option value = " .$user['user_id']. ">". $user['name'] . " " . $user['surname'] ."\n(".$user['email'] .")"."</option>";
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';
		}
					
	}	
	function manageGroupProcessDropdown($grp, $selection = ""){
		$tmpIdArr = array();
		$processSelectedArr = array();
		$processAvailableArr = array();
		$sqlSelected = "SELECT processes_id,processes_desc FROM processes, lnk_SecGroup_process WHERE process_ref = processes_id AND secGroup_ref = :grp ORDER BY processes_desc";
		$rsSelected = $this->db->query($sqlSelected, compact('grp'));
		while($rowSelected = $rsSelected->fetch()){
			array_push($tmpIdArr, $rowSelected['processes_id']);
			array_push($processSelectedArr, $rowSelected);
		}		

		$placeHolders = (!empty($processSelectedArr)) ? implode(', ', array_fill(0, count($tmpIdArr), '?')) : 0;
		
		$sqlAvailable = "SELECT processes_id, processes_desc FROM processes WHERE processes_id NOT IN ($placeHolders) ORDER BY processes_desc";
		$rsAvailable = $this->db->query($sqlAvailable, $tmpIdArr);
		while($rowAvailable = $rsAvailable->fetch()){
			array_push($processAvailableArr, $rowAvailable);
		}
		
		$selectName = ($selection == "selectedProcess") ? "selectedProcess[]" : "availableProcess[]";
		$selectionArr = ($selection == "selectedProcess") ? $processSelectedArr : $processAvailableArr;
		$title = ($selection == "selectedProcess") ? "Selected processes" : "Available processes to select";
		
		if(!empty($selectionArr) || $selectionArr == $processSelectedArr){
			echo '<div class = "select-container span5">';
			echo "<h3>" . $title . "</h3>";
			echo '<div class = "' . $selection .'">';
			echo '<select id = "' . $selection . '" name= "' . $selectName . '" class= "' . $selection . '" multiple = "multiple" size = "10">';
			foreach ($selectionArr as $process){
				echo "<option value = " .$process['processes_id']. ">" . $process['processes_desc'] . "</option>";
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';			
		}
		
		
	}
	function saveUserGroups($selectedUserArr, $groupId){
		$sql = "DELETE FROM sec_UserGroups WHERE sec_group_ref = :groupId";
		$rs = $this->db->query($sql, compact('groupId'));
		
		if(!empty($selectedUserArr)){
			foreach($selectedUserArr as $selectedUserId){
				$sqlInsert = "INSERT INTO sec_UserGroups (sec_user_ref, sec_group_ref) VALUES (:selectedUserId, :groupId)";
				$rsInsert = $this->db->query($sqlInsert, compact('selectedUserId', 'groupId'));
			}
		}
	}
	
	function saveGroupProcess($selectedProcessArr, $groupId){
		$sql = "DELETE FROM lnk_SecGroup_process WHERE secGroup_ref = :groupId";
		$rs = $this->db->query($sql, compact('groupId'));
		
		if(!empty($selectedProcessArr)){
			foreach($selectedProcessArr as $selectedProcessId){
				$sqlInsert = "INSERT INTO lnk_SecGroup_process (process_ref, secGroup_ref) VALUES (:selectedProcessId, :groupId)";
				$rsInsert = $this->db->query($sqlInsert, compact('selectedProcessId', 'groupId'));
			}
		}
	}	
	function getActiveProcessesDetails(){
		$detailArr = array();
		$SQL = "SELECT surname, name,email, active_processes_id, processes_id, last_updated FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status = 0 ORDER BY last_updated";
		
		$rs = $this->db->query($SQL);
		while($row = $rs->fetch()){
			$row['processDescription'] = $this->workflowDescription($row["active_processes_id"],$row["processes_id"]);
			$OtherFlowProcessArr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);
			if(!empty($OtherFlowProcessArr)){
				foreach($OtherFlowProcessArr as $key => $OtherFlowProcess){
					if($key == "nr_programmes"){
						$hei_name = $this->db->getValueFromTable($OtherFlowProcess->dbTableName, $OtherFlowProcess->dbTableKeyField, $OtherFlowProcess->dbTableCurrentID, "hei_name");
						$school_name = $this->db->getValueFromTable($OtherFlowProcess->dbTableName, $OtherFlowProcess->dbTableKeyField, $OtherFlowProcess->dbTableCurrentID, "school_name");;
						 $row['InstitutionName'] = $hei_name . " (" . $school_name. ")";
					}
				}
			}
			$link = $this->scriptGetForm ('active_processes', $row["active_processes_id"], '_admin_manageActiveProcessesEdit');
			$edit = "<a href='" . $link . "'><img src = 'images/edit.png' alt='Change User' /></a>";
			$row['changeUserEdit'] = $edit;
			array_push($detailArr, $row);
		}
		return $detailArr;
	}
	
	function getSettingsDetails(){
		$detailArr = array();
		$sql = "SELECT * FROM settings ORDER BY s_key";
		$rs = $this->db->query($sql);
		
		while($row = $rs->fetch()){
			$link = $this->scriptGetForm ('settings', $row["s_key"], '_admin_manageSettingsEdit');
			$edit = "<a href='" . $link . "'><img src = 'images/edit.png' alt='Edit Setting' /></a>";
			$row['editSetting'] = $edit;
			array_push($detailArr, $row);
		}
		return $detailArr;
	}
	
	function getUsersDetails($filter=array(), $template){
		extract($this->createfilterCriteria($filter, $this->userSearchFields[$template],array('active')));
		$and_condtions = (!empty($where)) ? $where : '';
		$detailArr = array();
		$sql = "SELECT user_id, lkp_title_desc, name, surname, email, contact_nr, contact_cell_nr, GROUP_CONCAT(sec_group_ref), lkp_active.lkp_active_desc 
				FROM users LEFT JOIN lkp_title on (lkp_title.lkp_title_id = users.title_ref) 
				LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
				LEFT JOIN sec_Groups ON sec_UserGroups.sec_group_ref = sec_Groups.sec_group_id
				LEFT JOIN lkp_active ON lkp_active_id = users.active
				WHERE 1
				$and_condtions
				GROUP BY users.user_id ORDER BY users.surname";
		// $sql = (!empty($where)) ? $sql . $where : $sql;
		// $this->pr($where);
		// $this->pr($sql);
		$rs = $this->db->query($sql, $params);
		if ($rs->rowCount() > 0){
			while ($row = $rs->fetch()) {	
				$link = $this->scriptGetForm ('users', $row["user_id"], '_admin_manageUsersEdit');
				$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";
				$linkPassword = $this->scriptGetForm ('users', $row["user_id"], '_admin_ChangeUserPassword');
				$editPassword = "<a href='" . $linkPassword . "'><img src='images/change.png' alt='Change Password' /></a>";
				
				$row['editUser'] = $edit;
				$row['changePassword'] = $editPassword;
				if(!empty($row['GROUP_CONCAT(sec_group_ref)'])){
					$groupIdArr = explode(",", $row['GROUP_CONCAT(sec_group_ref)']);
					$row['userGroups'] = $this->getMultipleGroupsName($groupIdArr);
				}
				
				array_push($detailArr, $row);
			}
		}
		// $this->pr($detailArr);
		return $detailArr;
	}
	
	function getPrelimAdditionalInfo($prog_id){
		$detailArr = array();
		$sql = "SELECT * FROM nr_programme_additional_docs WHERE nr_programme_id = :prog_id";
		$rs = $this->db->query($sql, compact('prog_id'));
		
		while($row = $rs->fetch()){
			$link = $this->scriptGetForm ('nr_programme_additional_docs', $row["additional_docs_id"], '_label_ser_prelim_upload_additional_docs');
				$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";
			$row['docLink'] = $this->createDocLink($row['additional_doc'], $row['additional_doc_title']);
			$row['editDoc'] = $edit;
			$value = $row['additional_docs_id'];
			$delete = '<input type="checkbox" class="hidden" name="additional_doc[]" value="' . $value . '"><img src="images/delete.png" alt="Delete" class = "delButton" />';
			$row['deleteDoc'] = $delete;
			// <input type="button" class="btn btn-danger delButton" value="DELETE">
			array_push($detailArr, $row);
		}
		 return $detailArr;
	}
	
	function saveProgrammeDocs($additional_docArr){
		if(!empty($additional_docArr)){			
			foreach($additional_docArr as $additional_docs_id){
				$sql = "DELETE FROM nr_programme_additional_docs WHERE additional_docs_id = :additional_docs_id";
				$rs = $this->db->query($sql, compact('additional_docs_id'));

			}
		}
	}
	function getGroupId($sec_group_desc){
		$return = '';
		$sql = "SELECT sec_group_id FROM sec_Groups WHERE sec_group_desc LIKE (:sec_group_desc)";
		$rs = $this->db->query($sql, compact('sec_group_desc'));
		while($row = $rs->fetch()){
			$return = $row['sec_group_id'];
		}
		
		return $return;
	}
	function manageProgrammeDropdown($where_conditions, $title, $selection="", $meetingType ="", $progSelectName="", $progAvailName=""){	
		$id = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;
		$progSelectArr = array();
		$progAvailArr = array();
		$progIdArr = array();
		if ($meetingType > "" && $meetingType == "rg_meeting"){
			$SQLSelected = "SELECT rg_meeting_programmes.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN rg_meeting_programmes ON nr_programmes.id = rg_meeting_programmes.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE rg_meeting_programmes.rg_meeting_id = :id
						 ORDER BY hei_name ";
		}else if($meetingType > "" && $meetingType == "rg_restriction"){
			$SQLSelected = "SELECT rg_meeting_programmes_restrictions.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN rg_meeting_programmes_restrictions ON nr_programmes.id = rg_meeting_programmes_restrictions.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE rg_meeting_programmes_restrictions.user_id = :id
						 ORDER BY hei_name ";		
		}else if($meetingType > "" && $meetingType == "nr_restriction"){
			$SQLSelected = "SELECT nr_meeting_programmes_restrictions.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN nr_meeting_programmes_restrictions ON nr_programmes.id = nr_meeting_programmes_restrictions.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE nr_meeting_programmes_restrictions.user_id = :id
						 ORDER BY hei_name ";		
		}else if($meetingType > "" && $meetingType == "rg_assignment"){
			$SQLSelected = "SELECT rg_meeting_programmes_assignment.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN rg_meeting_programmes_assignment ON nr_programmes.id = rg_meeting_programmes_assignment.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE rg_meeting_programmes_assignment.user_id = :id
						 ORDER BY hei_name ";		
		}else if($meetingType > "" && $meetingType == "nrc_assignment"){
			$SQLSelected = "SELECT nr_meeting_programmes_assignment.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN nr_meeting_programmes_assignment ON nr_programmes.id = nr_meeting_programmes_assignment.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE nr_meeting_programmes_assignment.user_id = :id
						 ORDER BY hei_name ";		
		}else{
			$SQLSelected = "SELECT nr_meeting_programmes.id, nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
						LEFT JOIN nr_meeting_programmes ON nr_programmes.id = nr_meeting_programmes.nr_programme_id
						LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
						WHERE nr_meeting_programmes.nr_meeting_id = :id
						 ORDER BY hei_name ";
		}
		// echo $SQLSelected;
		$RsSelected = $this->db->query($SQLSelected,compact('id'));
		
		while ($rowSelected = $RsSelected->fetch()) {
			array_push($progSelectArr, $rowSelected);
			array_push($progIdArr, $rowSelected['id']);
		}
		
		
		$placeHolders = (!empty($progIdArr)) ? implode(', ', array_fill(0, count($progIdArr), '?')) : 0;
		$where_conditions .= (($id != "NEW")  ? " AND nr_programmes.id NOT IN ($placeHolders)" : '');
		$params = $id != "NEW" ? $progIdArr : array();
		
		$SQLAvailable = "SELECT DISTINCT nr_programmes.id, hei_name, nr_national_review_id FROM nr_programmes
					LEFT JOIN nr_national_reviews ON nr_programmes.nr_national_review_id = nr_national_reviews.id
					$where_conditions 
					ORDER BY hei_name";
		
		$RSAvailable = $this->db->query($SQLAvailable, $params);
		$rowCountAvailable = $RSAvailable->rowCount();
		if($rowCountAvailable > 0){
			while ($row = $RSAvailable->fetch()) {
				array_push($progAvailArr, $row);
			}
		}

		if($progSelectName >"" && $selection =="" && $progAvailName ==""){		
			$selectName = $progSelectName."[]";
			$selectionArr = $progSelectArr;
			$selection = $progSelectName;
		}else if($progAvailName >"" && $selection =="" && $progSelectName ==""){
			$selectName = $progAvailName."[]";
			$selectionArr = $progAvailArr;
			$selection = $progAvailName;			
		}else{
			$selectName = ($selection == "selected") ? "progSelected[]" : "progAvailable[]";
			$selectionArr = ($selection == "selected") ? $progSelectArr : $progAvailArr;			
		}
		if(!empty($selectionArr) || $selectionArr == $progSelectArr || $selectionArr == $progAvailArr){
			echo '<div class = "select-container span5">';
			echo "<h3>" . $title . "</h3>";
			echo '<div class = "' . $selection .'">';
			echo '<select id = "' . $selection . '" name= "' . $selectName . '" class= "' . $selection . '" multiple = "multiple" size = "10">';
			foreach($selectionArr as $key => $prog){							
				echo "<option value = " .$prog['id']. ">". $prog['hei_name'] . " - " . $prog['nr_national_review_id']."</option>";
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';
		}
	}
	
	function saveMeetingProg($progSelectedArr, $meeting_id, $meetingType = ""){
		if($meetingType> "" && $meetingType == "rg_meeting"){
			$table = "rg_meeting_programmes";
			$fields = "(rg_meeting_id, nr_programme_id )";
			$whereField = "rg_meeting_id";
		}else{
			$table = "nr_meeting_programmes";
			$fields = "(nr_meeting_id, nr_programme_id )";
			$whereField = "nr_meeting_id";
		}
		$sql = "DELETE FROM $table WHERE $whereField = :meeting_id";
		$rs = $this->db->query($sql, compact('meeting_id'));
		if(!empty($progSelectedArr)){
			foreach($progSelectedArr as $selectedProgId){
				$sqlInsert = "INSERT INTO $table $fields VALUES (:meeting_id, :selectedProgId)";
				$rsInsert = $this->db->query($sqlInsert, compact('selectedProgId', 'meeting_id'));
			}
		}		
	}
	
	function getMeetingDetails($left_table= "", $left_table_field="", $wher = "", $and ="",$user_id ="", $filter=array(), $template="", $meetingType ="", $restrictions = array()){
		$detailArr = array();
		if(!empty($filter)){
			extract($this->createfilterCriteria($filter, $this->reportSearchFields[$template]));
		}
		$meetingID = ($meetingType >"" && $meetingType == "rg_meeting") ? " rg_meetings" : " nr_meetings";
		// $left_join = ($left_table > "") ? " LEFT JOIN ". $left_table ." ON nr_meetings.id = ". $left_table ."." . $left_table_field ."" : "";
		$left_join = ($left_table > "") ? " LEFT JOIN ". $left_table ." ON " .$meetingID. ".id = ". $left_table ."." . $left_table_field ."" : "";
		$filterWher = (isset($where) && $where > "") ? $where :'';

		$whereCondition = ($wher > "") ? " WHERE " . $wher . " = :user_id" : " WHERE 1 ";
		$andCondition = ($and> "") ? " AND " . $and . "" : "";
		$old_params = ($whereCondition > "" && $whereCondition != " WHERE 1 ") ? compact('user_id') : array();
		$combine_params = ((isset($params)) && !empty($params)) ? array_merge($params, $old_params) : $old_params;
		// $this->pr($combine_params);
		if($meetingType >"" && $meetingType == "rg_meeting"){
			$sql = "SELECT DISTINCT rg_meetings.id ,rg_meetings.nr_national_review_id ,rg_meeting_start_date,rg_meeting_end_date,rgc_access_start_date, rgc_access_end_date, rg_meetings.rgc_meeting_minutes_doc FROM rg_meetings
					LEFT JOIN rg_meeting_programmes ON rg_meetings.id = rg_meeting_programmes.rg_meeting_id
					LEFT JOIN nr_programmes ON rg_meeting_programmes.nr_programme_id = nr_programmes.id
					$left_join
					$whereCondition
					$andCondition
					$filterWher
					ORDER BY rg_meeting_start_date";
		
		}else{
			$sql = "SELECT DISTINCT nr_meetings.id ,nr_meetings.nr_national_review_id ,nr_meeting_start_date,nr_meeting_end_date,nrc_access_start_date, nrc_access_end_date, nr_meetings.nrc_meeting_minutes_doc FROM nr_meetings
					LEFT JOIN nr_meeting_programmes ON nr_meetings.id = nr_meeting_programmes.nr_meeting_id
					LEFT JOIN nr_programmes ON nr_meeting_programmes.nr_programme_id = nr_programmes.id
					$left_join
					$whereCondition
					$andCondition
					$filterWher
					ORDER BY nr_meeting_start_date";
		}
		$rs = $this->db->query($sql, $combine_params);
		while($row = $rs->fetch()){
			$id = (isset($row[$meetingID])) ? $row[$meetingID] : $row["id"];
			if($meetingType >"" && $meetingType == "rg_meeting"){
				$link = $this->scriptGetForm ('rg_meetings', $id, '_label_admin_addRGMeetings');
			}else{
				$link = $this->scriptGetForm ('nr_meetings', $id, '_label_admin_addMeetings');
			}
			$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";
			$row['nrMeetingsMem'] = $this->getMeetingMemberNames($id, $meetingType);
			$row['nrMeetingsProg'] = $this->getMeetingProgrammesDetails($id, $meetingType, $restrictions);
			$row['editLink'] = $edit;
			array_push($detailArr , $row);
		}
		return $detailArr;
	}
	
	function getMeetingMemberNames($meeting_id, $meetingType =""){
		$return = '';
		$memberIdArr = array();
		if($meetingType >"" && $meetingType == "rg_meeting"){
			$sql = "SELECT * FROM rg_meeting_members WHERE rg_meeting_id = :meeting_id";
		}else{
			$sql = "SELECT * FROM nr_meeting_members WHERE nr_meeting_id = :meeting_id";
		}
		$rs = $this->db->query($sql, compact('meeting_id'));
		while($row = $rs->fetch()){
			array_push($memberIdArr, $row['user_id']);
		}
		
		if(!empty($memberIdArr)){
			foreach($memberIdArr as $memberId){
				$sqlIns = "SELECT name, surname, email FROM users WHERE user_id = :memberId";
				$rsIns = $this->db->query($sqlIns, compact('memberId'));
				while($rowIns = $rsIns->fetch()){
					$return .= '<ul>';
					$return .= "<li>" .$rowIns['name'] . " " . $rowIns['surname'] . " (" .$rowIns['email'] .")". "</li>" ;
					$return .= '</ul>';
				}
			}
		}
		return $return;
	}
	
	function getMeetingProgrammesDetails($meeting_id, $meetingType ="", $restrictions = array()){
		$return = array();
		$progIdArr = array();
		if($meetingType >"" && $meetingType == "rg_meeting"){
			$sql = "SELECT * FROM rg_meeting_programmes WHERE rg_meeting_id = :meeting_id";
		}else{
			$sql = "SELECT * FROM nr_meeting_programmes WHERE nr_meeting_id = :meeting_id";
		}
		$rs = $this->db->query($sql, compact('meeting_id'));
		while($row = $rs->fetch()){
			array_push($progIdArr, $row['nr_programme_id']);
		}
		if(!empty($restrictions)){
			$progRestriction = implode(",", $restrictions);			
		}
		$andCondition = (!empty($restrictions)) ? " AND id NOT IN ($progRestriction)" : "";
		
		if(!empty($progIdArr)){
			foreach($progIdArr as $progId){
				$sqlIns = "SELECT * FROM nr_programmes WHERE id = :progId $andCondition";
				$rsIns = $this->db->query($sqlIns, compact('progId'));
				while($rowIns = $rsIns->fetch()){			
					$rowIns['prog_name'] = $rowIns['hei_name'] . " - " . $rowIns['nr_national_review_id'];
					$rowDetails = $this->nrProgDocDetails($rowIns['id'], $rowIns['ser_doc'], $rowIns['signoff_doc'], $rowIns['analyst_report_doc'], $rowIns['chair_report_doc'], $rowIns['recommendation_report_doc'], $rowIns['heqc_recommendation_report_doc'], "popup", $rowIns['heqc_nrc_report_doc'], $rowIns['improvement_doc']);
					$rowIns['docsRelated'] = $rowDetails;
					array_push($return , $rowIns);
				}
			}
		}
		return $return;
	}
	
	function manageSecGroupSelect($grp, $title, $selection, $meetingType =""){	
		$id = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;
		// $this->pr($grp);
		// $this->pr($id);
		$memSelectArr = array();
		$memAvailArr = array();
		$memIdArr = array();
		if ($meetingType > "" && $meetingType == "rg_meeting"){
			$SQLSelected = "SELECT users.user_id, name, surname, email FROM users
						LEFT JOIN rg_meeting_members ON users.user_id = rg_meeting_members.user_id
						LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
						WHERE rg_meeting_members.rg_meeting_id = :id
						AND sec_group_ref = :grp
						ORDER BY name ";
		}else{
			$SQLSelected = "SELECT users.user_id, name, surname, email FROM users
						LEFT JOIN nr_meeting_members ON users.user_id = nr_meeting_members.user_id
						LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
						WHERE nr_meeting_members.nr_meeting_id = :id
						AND sec_group_ref = :grp
						ORDER BY name ";
		}
		// echo $SQLSelected;
		$RsSelected = $this->db->query($SQLSelected,compact('id', 'grp'));
		
		while ($rowSelected = $RsSelected->fetch()) {
			array_push($memSelectArr, $rowSelected);
			array_push($memIdArr, $rowSelected['user_id']);
		}
	
		$placeHolders = (!empty($memIdArr)) ? implode(', ', array_fill(0, count($memIdArr), '?')) : 0;
		$and_conditions = (($id != "NEW")  ? " AND user_id NOT IN ($placeHolders)" : '');
		$params = ($id != "NEW") ? $memIdArr : array();
		$SQLAvailable = "SELECT DISTINCT users.user_id, name, surname, email FROM users
					LEFT JOIN sec_UserGroups ON sec_UserGroups.sec_user_ref = users.user_id
					WHERE sec_group_ref = $grp
					$and_conditions 					 
					ORDER BY name";

		// echo $SQLAvailable;
		$RSAvailable = $this->db->query($SQLAvailable, $params);
		
		while ($row = $RSAvailable->fetch()) {
			array_push($memAvailArr, $row);
		}
		

				
		$selectName = ($selection == "selectedMember") ? "memSelected[]" : "memAvailable[]";
		$selectionArr = ($selection == "selectedMember") ? $memSelectArr : $memAvailArr;
		
		if(!empty($selectionArr) || $selectionArr == $memAvailArr || $selectionArr == $memSelectArr){
			echo '<div class = "select-container span5">';
			echo "<h3>" . $title . "</h3>";
			echo '<div class = "' . $selection .'">';
			echo '<select id = "' . $selection . '" name= "' . $selectName . '" class= "' . $selection . '" multiple = "multiple" size = "10">';
			foreach($selectionArr as $key => $mem){							
				echo "<option value = " .$mem['user_id']. ">". $mem['name'] . " " . $mem['surname'] ." (".$mem['email'].")". "</option>";
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';
		}
	}
	
	function saveMeetingMembers($selectedMemberArr, $meeting_id, $meetingType= ""){
		if($meetingType > "" && $meetingType == "rg_meeting"){
			$table = "rg_meeting_members";
			$fields = "(rg_meeting_id, user_id  )";
			$whereField = "rg_meeting_id";
		}else{
			$table = "nr_meeting_members";
			$fields = "(nr_meeting_id, user_id  )";
			$whereField = "nr_meeting_id";
		}
	
		$sql = "DELETE FROM $table WHERE $whereField = :meeting_id";
		$rs = $this->db->query($sql, compact('meeting_id'));
		
		if(!empty($selectedMemberArr)){
			foreach($selectedMemberArr as $selectedMemId){
				$sqlInsert = "INSERT INTO $table $fields VALUES (:meeting_id, :selectedMemId)";
				$rsInsert = $this->db->query($sqlInsert, compact('selectedMemId', 'meeting_id'));
			}
		}
	}

	function saveMeetingProgAssignment($progAssignedArr, $user_id, $tableName = "" ){
		$table = $tableName > '' ? $tableName : 'rg_meeting_programmes_assignment';
		$sql = "DELETE FROM $table WHERE user_id = :user_id";
		$rs = $this->db->query($sql, compact('user_id'));
		
		if(!empty($progAssignedArr)){
			foreach($progAssignedArr as $selectedProgId){
				$sqlInsert = "INSERT INTO $table (user_id, nr_programme_id) VALUES (:user_id, :selectedProgId)";
				$rsInsert = $this->db->query($sqlInsert, compact('user_id', 'selectedProgId'));
			}
		}		
	}
	
	function saveMeetingRestriction($progSelectedArr, $user_id, $tableName = ""){
		$table = $tableName > '' ? $tableName : 'rg_meeting_programmes_restrictions';
		$sql = "DELETE FROM $table WHERE user_id = :user_id";
		$rs = $this->db->query($sql, compact('user_id'));
		
		if(!empty($progSelectedArr)){
			foreach($progSelectedArr as $selectedProgId){
				$sqlInsert = "INSERT INTO $table (user_id, nr_programme_id) VALUES (:user_id, :selectedProgId)";
				$rsInsert = $this->db->query($sqlInsert, compact('user_id', 'selectedProgId'));
			}
		}		
	}
	function getMeetingProgAssigned($userId = "", $tableName = ""){
		$assigmentArr = array();
		$table = ($tableName > "") ? $tableName : 'rg_meeting_programmes_assignment';
		// $id = ($userId > "") ? $userId : 'rg_meeting_programmes_assignment.user_id';
		$id = ($userId > "") ? $userId : (($tableName > "") ? $tableName .'.user_id' : 'rg_meeting_programmes_assignment.user_id');
		$sql = "SELECT users.user_id, CONCAT(name,' ', surname, '(',email, ' )') AS userDetails, CONCAT(hei_name,' - ', nr_national_review_id) AS progName, nr_programmes.id AS progID
				FROM users
				LEFT JOIN $table ON users.user_id = $table.user_id
				LEFT JOIN nr_programmes ON $table.nr_programme_id = nr_programmes.id
				WHERE users.user_id = " . $id . "";
		$rs = $this->db->query($sql);
		while($row = $rs->fetch()){
			$link = $this->scriptGetForm ('users', $row["user_id"], '_admin_manageUsersEdit');
			$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";
			$row['edit'] = $edit;
			$assigmentArr[$row['user_id']]['userDetail'] = $row['userDetails']; 
			$assigmentArr[$row['user_id']]['progName'][] = $row['progName'];
			$assigmentArr[$row['user_id']]['edit'] = $row['edit'];
			$assigmentArr[$row['user_id']]['progIdArr'][] = $row['progID'];
		}
		
		return $assigmentArr;		
	}
	function getMeetingRestriction($userId = "", $tableName = ""){
		$restrictionArr = array();
		$table = ($tableName > "") ? $tableName : 'rg_meeting_programmes_restrictions';
		$id = ($userId > "") ? $userId : (($tableName > "") ? $tableName .'.user_id' : 'rg_meeting_programmes_restrictions.user_id');
		$sql = "SELECT users.user_id, CONCAT(name,' ', surname, '(',email, ' )') AS userDetails, CONCAT(hei_name,' - ', nr_national_review_id) AS progName, nr_programmes.id AS progID
				FROM users
				LEFT JOIN $table ON users.user_id = $table.user_id
				LEFT JOIN nr_programmes ON $table.nr_programme_id = nr_programmes.id
				WHERE users.user_id = " . $id . "";

		$rs = $this->db->query($sql);
		while($row = $rs->fetch()){
			$link = $this->scriptGetForm ('users', $row["user_id"], '_admin_manageUsersEdit');
			$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";
			$row['edit'] = $edit;
			$restrictionArr[$row['user_id']]['userDetail'] = $row['userDetails']; 
			$restrictionArr[$row['user_id']]['progName'][] = $row['progName'];
			$restrictionArr[$row['user_id']]['edit'] = $row['edit'];
			$restrictionArr[$row['user_id']]['progIdArr'][] = $row['progID'];
		}
		
		return $restrictionArr;
	}
	
	function listActiveNR_ids($name=""){
		$listArr = array();
		$return = '';
		$SQL = "SELECT *
			FROM nr_national_reviews
			WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() 
			ORDER BY id";
		$RS = $this->db->query($SQL, array()) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
			array_push($listArr, $row);
		}
		if(!empty($listArr)){
			$count = count($listArr) - 1;
			foreach ($listArr as $index => $list){
				if($name >""){
					$return .= $list['programme_to_review'];
				}else{
					$return .= $list['id'];
				}
				
				$return .= ($index < $count) ? ', ' : '';
			}
		}
		
		return $return;
	}
	
	
	function instNRProgressDetails($progId, $panelStyle = ""){
		$details = array();
		
		$SQL = "SELECT nr_programmes.id, nr_programmes.hei_code, nr_programmes.hei_name, nr_programmes.nr_programme_abbr, nr_programmes.nr_programme_name, nr_programmes.heqsf_reference_no, nr_programmes.date_submitted, nr_programmes.ser_doc, nr_programmes.signoff_doc, nr_programmes.analyst_report_doc, nr_programmes.chair_report_doc, nr_programmes.recommendation_report_doc, nr_programmes.heqc_recommendation_report_doc
		FROM
			nr_programmes
		WHERE
			nr_programmes.id = :progId";
		
		$RS = $this->db->query($SQL, compact('progId')) or die($this->db->lastError[2]);
		
		while($row = $RS->fetch()){
			$url = "javascript:showSERreadOnly(" . $row['id'] . ");";	
			$onlineTable = '<a href="' . $url . '">Profile of the programme</a>';
			$comparisonLink = $this->scriptGetForm('nr_programmes', $row['id'], '_label_ser_recommWriter_Criteria');
			// $urlRecommCriteria = "javascript:recommCriteriaComparison(" . $row['id'] . ");";
			
			$curentUserEdit_id = Settings::get('currentUserID');
			$rg_groupId = $this->getGroupId('RC member');
			$nrc_groupId = $this->getGroupId('NRC member');

			$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	
			$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);

			$recommLinkTitle = ($isNRC_member || $isRgMember) ? 'SER, Review panel and Recommendation criteria compared' : 'SER and Review panel criteria compared';			
			$urlRecommCriteria = "javascript:recommCriteriaComparison(\"" . $row['id'] . "\", \"" . $recommLinkTitle . "\");";	
			
			$row['active_process_person'] = $this->getSERProcessDetails($row['id'], 'id', 'nr_programmes');
			$row['screening'] = $this->getScreeningDetails('programme_ref', $row['id'], 'form');
			$row['prelimAnalysis'] = $this->getPrelimAnalysisDetails('id', $row['id']);
			$row['link_report'] = $this->createDocLink($row['ser_doc'], 'SER');
			
			$row['serSubmissionArr']['ser'] = $this->createDocLink($row['ser_doc'], 'SER');
			$row['serSubmissionArr']['sign_off'] = $this->createDocLink($row['signoff_doc'], 'Sign-off');
			$row['serSubmissionArr']['data_table'] = $onlineTable;
			$row['serSubmissionArr']['date_submitted'] = $row['date_submitted'];
			$row['onlineTable'] = $onlineTable;			
			$row['siteVisitDetails'] = $this->getPanelDetails('id', $row['id'],$panelStyle);
			$row['recommDetails'] = $this->getRecommDetails('id', $row['id']);
			
			$row['panelFinding']['additionalDocArr'] = $this->getPrelimAdditionalInfo($row['id']);
			$row['panelFinding']['comparisonLink'] = "<a href='" . $urlRecommCriteria . "'>" . $recommLinkTitle . "</a>";
			$row['panelFinding']['panel_report'] = $this->createDocLink($row['chair_report_doc'], 'Review panel report');
			$row['rgMeetingDetails'] = $this->getInstRGProgMeetings($row['id']);
			$row['nrMeetingDetails'] = $this->getInstNRCProgMeetings($row['id']);
			array_push($details, $row);
		}
		
		return $details;
	}	
	function getInstRGProgMeetings($progId){
		$detailsArr = array();
		$sql ="SELECT DISTINCT rg_meetings.id,nr_programmes.id AS progId ,rg_meeting_start_date,rg_meeting_end_date,rgc_access_start_date, rgc_access_end_date,  nr_programmes.heqc_recommendation_report_doc FROM rg_meetings
					LEFT JOIN rg_meeting_programmes ON rg_meetings.id = rg_meeting_programmes.rg_meeting_id
					LEFT JOIN nr_programmes ON rg_meeting_programmes.nr_programme_id = nr_programmes.id
					WHERE nr_programmes.id = :progId
					ORDER BY rg_meeting_start_date";

		$RS = $this->db->query($sql, compact('progId')) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
			$row['assigned_memberArr'] = $this->getAssignedUserByProg($progId);
			array_push($detailsArr, $row);
		}
		return $detailsArr;	
	}
	
	function getInstNRCProgMeetings($progId){
		$detailsArr = array();
		$sql ="SELECT DISTINCT nr_meetings.id,nr_programmes.id AS progId ,nr_meeting_start_date,nr_meeting_end_date,nrc_access_start_date, nrc_access_end_date, nrc_meeting_minutes_doc, nr_programmes.heqc_nrc_report_doc FROM nr_meetings
					LEFT JOIN nr_meeting_programmes ON nr_meetings.id = nr_meeting_programmes.nr_meeting_id
					LEFT JOIN nr_programmes ON nr_meeting_programmes.nr_programme_id = nr_programmes.id
					WHERE nr_programmes.id = :progId
					ORDER BY nr_meeting_start_date";

		$RS = $this->db->query($sql, compact('progId')) or die($this->db->lastError[2]);
		while($row = $RS->fetch()){
			$row['members'] = $this->getMeetingMemberNames($row['id']);
			$row['assigned_memberArr'] = $this->getAssignedUserByProg($progId, 'nr_meeting_programmes_assignment');
			array_push($detailsArr, $row);
		}
		return $detailsArr;	
	}	
	
	function getAssignedUserByProg($progId, $tableName = ""){
/*		$table = $tableName > '' ? $tableName : 'rg_meeting_programmes_assignment';
		$sql = "DELETE FROM $table WHERE user_id = :user_id";
		$rs = $this->db->query($sql, compact('user_id'));
		
		if(!empty($progAssignedArr)){
			foreach($progAssignedArr as $selectedProgId){
				$sqlInsert = "INSERT INTO $table (user_id, nr_programme_id) VALUES (:user_id, :selectedProgId)";
				$rsInsert = $this->db->query($sqlInsert, compact('user_id', 'selectedProgId'));
			}
		}*/	
		$table = $tableName > '' ? $tableName : 'rg_meeting_programmes_assignment';
		$usertArr = array();
		$sql = "SELECT CONCAT(users.name,' ', users.surname, '(',users.email, ' )') AS userDetails
				FROM $table
				LEFT JOIN users ON users.user_id = $table.user_id
				WHERE nr_programme_id = :progId";
		$rs = $this->db->query($sql, compact('progId'));
		while($row = $rs->fetch()){
			array_push($usertArr, $row);
		}
		
		return $usertArr;		
	}
	function getUserActiveProcess($userId){
		$processArr = array();
		$sql = "SELECT processes_ref FROM  active_processes WHERE status = 0  AND processes_ref <> 100 AND user_ref = :userId";
		$rs = $this->db->query($sql, compact('userId'));
		
		while($row = $rs->fetch()){			
				array_push($processArr, $row);			
		}
		return $processArr;	
	}
	
	// function setAssignedSERCompletionStatus($fieldTemplate, $fieldMatch, $actionMayShow){
		// switch($fieldTemplate){
			// case 'ser_recommWriterCriteriaEvaluation':
				// if($actionMayShow){
					// $this->db->setValueInTable('nr_programmes','id',$fieldMatch,'recommendation_completed','1');
				// }else if(!$actionMayShow){
					// $this->db->setValueInTable('nr_programmes','id',$fieldMatch,'recommendation_completed','0');
				// }				
			// break;
			
			// case 'ser_panelCriteriaEvaluation':
				// if($actionMayShow){
					// $this->db->setValueInTable('nr_programmes','id',$fieldMatch,'siteVisit_completed','1');
				// }else if(!$actionMayShow){
					// $this->db->setValueInTable('nr_programmes','id',$fieldMatch,'siteVisit_completed','0');
				// }			
			// break;
		// }
	// }
	
	function displayProgressReportOfNR($prog_id = "", $topdf ="", $pdffileName =""){
		$prog_id = ($prog_id > "") ? $prog_id : $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
		$details = $this->instNRProgressDetails($prog_id, "role");
		
		$recommendationCompleted = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendation_completed");
		$siteVisit_completed = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"siteVisit_completed");
		
		$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendationSubmittedByAdmin_ind");
		$siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"siteVisitSubmittedByAdmin_ind");
		
		echo $this->element('progressReportOfNR', compact('details', 'recommendationCompleted', 'siteVisit_completed', 'recommendationSubmittedByAdmin_ind', 'siteVisitSubmittedByAdmin_ind'));
		
	}	
	// END of Class
}
