<a name="application_form_question13"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>13. LECTURE ROOMS</b>
<br><br>
<i>Please indicate the number of lecture rooms for your institution and their total capacity  (e.g. 5 lecture rooms of capacity 100, 2 lecture rooms of capacity 250, etc.).</i>
<br><br>
<a name="institutional_profile_lecture_rooms"></a>
<?php 
	// Lecture Rooms
	//  institutional_profile_lecture_rooms
/*
	$headArr = array();
	$headArr["Capacity <br>(e.g. 100 students)"] = "1";
	$headArr["Number of Lecture Halls/Rooms <br>(e.g. 5)"] = "1";
	$headArr["Location"] = "2";

	$fieldsArr = array();
	$fieldsArr["room_capacity"] = "";
	$fieldsArr["room_nr"] = "";
	$fieldsArr["location_main_campus_selectFLD"] = "Main Campus";
	$fieldsArr["location_site_name"] = array("Site (name)");

	$select_arr["location_main_campus_selectFLD"]["description_fld"] = "site_name";
	$select_arr["location_main_campus_selectFLD"]["fld_key"] = "institutional_profile_sites_id";
	$select_arr["location_main_campus_selectFLD"]["lkp_table"] = "institutional_profile_sites";
	$select_arr["location_main_campus_selectFLD"]["lkp_condition"] = "1 AND institution_ref=".$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$select_arr["location_main_campus_selectFLD"]["order_by"] = "site_name";

	$addRowText = "lecture room";
	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_lecture_rooms", "institutional_profile_lecture_rooms_id", "institution_ref",$fieldsArr, 5, 0, $headArr, "", $select_arr, "", $addRowText, 1);
*/
	$headArr = array();
	array_push($headArr, "Total capacity <br>(e.g. 100 students)");
	array_push($headArr, "Number of Lecture Halls/Rooms <br>(e.g. 5)");
	array_push($headArr, "Location: Main Campus");
	array_push($headArr, "Location: Site (name)");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__room_capacity|size__10");
	array_push($fieldArr, "type__text|name__room_nr|size__10");
	array_push($fieldArr, "type__select|name__location_main_campus_selectFLD|description_fld__site_name|fld_key__institutional_profile_sites_id|lkp_table__institutional_profile_sites|lkp_condition__".'1 AND institution_ref='.$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref")."|order_by__site_name");
	array_push($fieldArr, "type__text|name__location_site_name|size__50");

	$addRowText = "lecture room";
	$this->gridShowTableByRow("institutional_profile_lecture_rooms", "institutional_profile_lecture_rooms_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true, $addRowText);
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
</td></tr></table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
