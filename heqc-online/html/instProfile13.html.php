<a name="application_form_question9"></a>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>9. NAME, TYPE  AND LOCATION OF LIBRARIES</b>
<br><br>
<i>Provide details on any recent improvements to the provision of library facilities available to staff and to students
(library space, books, journals, access to the internet).  The response should chart the history of progress since the first application for accreditation.</i>
<br><br>
<?php $this->showField("improvements_library_facilities"); ?>
<br><br>
<i>If the institution has more than one library, please press the "Add Library" button to Add information about all your libraries.</i>
<br><br>
<a name="institutional_profile_libraries"></a>
<?php 
	// number, type  and location of libraries
	// institutional_profile_libraries
/*	$fieldsArr = array();
	$fieldsArr["name"] = array("Name", 50);
	$fieldsArr["type"] = array("Type <br>(general; Social Science)", 50);
	$fieldsArr["location_selectFLD"] = "Location <br>(Main Campus/Pretoria site of delivery)"	;
	$fieldsArr["nr_prof_staff"] = array("Number of Professional Staff attached to the library", 50);
	$fieldsArr["working_hours"] = array("Working hours <br>(e.g. Mon-Fri 08-20.00; Sat 09-13.00)", 50);

	$select_arr["location_selectFLD"]["description_fld"] = "site_name";
	$select_arr["location_selectFLD"]["fld_key"] = "institutional_profile_sites_id";
	$select_arr["location_selectFLD"]["lkp_table"] = "institutional_profile_sites";
	$select_arr["location_selectFLD"]["lkp_condition"] = "1 AND institution_ref=".$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$select_arr["location_selectFLD"]["order_by"] = "site_name";

	$addRowText = "library";
*/
	$headArr = array();
	array_push($headArr, "Name");
	array_push($headArr, "Type <br>(general; Social Science)");
	array_push($headArr, "Location <br>(Site of delivery)");
	array_push($headArr, "Number of Professional Staff attached to the library");
	array_push($headArr, "Working hours <br>(e.g. Mon-Fri 08-20.00; Sat 09-13.00)");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__name|size__50");
	array_push($fieldArr, "type__text|name__type|size__50");
	array_push($fieldArr, "type__select|name__location_selectFLD|description_fld__site_name|fld_key__institutional_profile_sites_id|lkp_table__institutional_profile_sites|lkp_condition__".'1 AND institution_ref='.$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref")."|order_by__site_name");
	array_push($fieldArr, "type__text|name__nr_prof_staff|size__50");
	array_push($fieldArr, "type__text|name__working_hours|size__50");

	$addRowText = "library";
	$this->gridShowTableByRow("institutional_profile_libraries", "institutional_profile_libraries_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true, $addRowText);

//	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_libraries", "institutional_profile_libraries_id", "institution_ref",$fieldsArr, 5, 0, "", "", $select_arr, "", $addRowText, 1);
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

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
