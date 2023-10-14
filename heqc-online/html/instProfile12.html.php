<a name="application_form_question8"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>8. MANAGEMENT INFORMATION SYSTEM</b>
<br><br>
<i>The following table requires you to indicate the availability of management information systems to support the management of teaching and learning at your institution. Please, describe the characteristics of the system in relation to the different categories indicated in the space below.</i>
<br><br>
<a name="institutional_profile_management_info_system"></a>
<?php 
	// Management Information System
	// institutional_profile_management_info_system
	//$fieldsArr = array();
	/*$fieldsArr["technical_desc_textFLD"] = array("Technical description <br>(type of database, e.g. SQL Server)");
	$fieldsArr["flds_of_information_textFLD"] = "Fields of information <br>(e.g. biographical information, performance, etc.)";
	$fieldsArr["periodicity_reports_textFLD"] = "Periodicity of reports <br>(annual, quarterly)";
	$fieldsArr["report_purpose_textFLD"] = "Purpose of the report <br>(e.g. Faculty academic committee meeting)";
	$fieldsArr["security_features_textFLD"] = "Security features implemented <br>(describe)";
	$fieldsArr["online_access_staff_selectFLD"] = "Online access for academic staff <br>(yes/no)";
	$fieldsArr["online_access_students_selectFLD"] = "Online access for students <br>(yes/no)";

	$select_arr["online_access_students_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["online_access_students_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["online_access_students_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["online_access_students_selectFLD"]["lkp_condition"] = "1";
	$select_arr["online_access_students_selectFLD"]["order_by"] = "lkp_yn_desc";

	$select_arr["online_access_staff_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["online_access_staff_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["online_access_staff_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["online_access_staff_selectFLD"]["lkp_condition"] = "1";
	$select_arr["online_access_staff_selectFLD"]["order_by"] = "lkp_yn_desc";
*/
	$headArr = array();
	array_push($headArr, "Technical description <br>(type of database, e.g. SQL Server)");
	array_push($headArr, "Fields of information <br>(e.g. biographical information, performance, etc.)");
	array_push($headArr, "Periodicity of reports <br>(annual, quarterly)");
	array_push($headArr, "Purpose of the report <br>(e.g. Faculty academic committee meeting)");
	array_push($headArr, "Security features implemented <br>(describe)");
	array_push($headArr, "Online access for academic staff <br>(yes/no)");
	array_push($headArr, "Online access for students <br>(yes/no)");

	$fieldArr = array();
	array_push($fieldArr, "type__textarea|name__technical_desc_textFLD");
	array_push($fieldArr, "type__textarea|name__flds_of_information_textFLD");
	array_push($fieldArr, "type__textarea|name__periodicity_reports_textFLD");
	array_push($fieldArr, "type__textarea|name__report_purpose_textFLD");
	array_push($fieldArr, "type__textarea|name__security_features_textFLD");
	array_push($fieldArr, "type__select|name__online_access_staff_selectFLD|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");
	array_push($fieldArr, "type__select|name__online_access_students_selectFLD|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");

//	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_management_info_system", "institutional_profile_management_info_system_id", "institution_ref", $fieldsArr, 5, "", "", "", $select_arr, "", "", 1, false);
	$this->gridShowTableByRow("institutional_profile_management_info_system", "institutional_profile_management_info_system_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10);
?>
<br><br>
<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>
</td></tr>
</table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>