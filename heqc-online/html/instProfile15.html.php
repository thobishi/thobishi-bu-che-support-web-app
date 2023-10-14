<a name="application_form_question11"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>11. NAME AND TYPE OF LABORATORIES</b>
<br><br>
<i>Please complete the following information in relation to your institution's infrastructure</i>
<br><br>
<i>Provide details on any recent improvements or additions to the specialised facilities available to staff and to students
(for example, programme-specific facilities such as studios, theatres, cameras, lighting, design rooms etc).</i>
<br><br>
<?php $this->showField("improvements_specialised_facilities"); ?>
<br><br>
<i>If the institution has more than one laboratory, please press the "Add Laboratory" button to Add information about all your laboratories. </i>
<br><br>
<a name="institutional_profile_nr_type_laboratories"></a>
<?php 
	// Number and type of laboratories
	//  institutional_profile_nr_type_laboratories
/*	$fieldsArr = array();
	$fieldsArr["name"] = array("Name", 50);
	$fieldsArr["type"] = array("Type (Physics,Bio-chemistry)", 50);
	$fieldsArr["location_selectFLD"] = "Location";
	$fieldsArr["nr_tech_staff"] = array("Number technical staff", 3);
	$fieldsArr["budget"] = array("Budget <br>(Rands)", 8);

	$select_arr["location_selectFLD"]["description_fld"] = "site_name";
	$select_arr["location_selectFLD"]["fld_key"] = "institutional_profile_sites_id";
	$select_arr["location_selectFLD"]["lkp_table"] = "institutional_profile_sites";
	$select_arr["location_selectFLD"]["lkp_condition"] = "1 AND institution_ref=".$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$select_arr["location_selectFLD"]["order_by"] = "site_name";

	$addRowText = "laboratory";
	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_nr_type_laboratories", "institutional_profile_nr_type_laboratories_id", "institution_ref",$fieldsArr, 5, 0, "", "", $select_arr, "", $addRowText, 1);
*/
	$headArr = array();
	array_push($headArr, "Name");
	array_push($headArr, "Type (Physics,Bio-chemistry)");
	array_push($headArr, "Location");
	array_push($headArr, "Number technical staff");
	array_push($headArr, "Budget <br>(Rands)");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__name|size__50");
	array_push($fieldArr, "type__text|name__type|size__50");
	array_push($fieldArr, "type__select|name__location_selectFLD|description_fld__site_name|fld_key__institutional_profile_sites_id|lkp_table__institutional_profile_sites|lkp_condition__".'1 AND institution_ref='.$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref")."|order_by__site_name");
	array_push($fieldArr, "type__text|name__nr_tech_staff|size__5");
	array_push($fieldArr, "type__text|name__budget|size__10");

	$addRowText = "laboratory";
	$this->gridShowTableByRow("institutional_profile_nr_type_laboratories", "institutional_profile_nr_type_laboratories_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true, $addRowText);
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
