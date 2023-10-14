<?php $inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID; ?>
<a name="application_form_question3"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>3. HUMAN RESOURCES POLICIES AND PROCEDURES:</b>
<br><br>
<i>Please complete the following profile of the staff of the institution for the current year</i>
<br><br>
<?php 

	$SheadArr = array();
	array_push($SheadArr, "");
	array_push($SheadArr, "Black Male");
	array_push($SheadArr, "Black Female");
	array_push($SheadArr, "Coloured Male");
	array_push($SheadArr, "Coloured Female");
	array_push($SheadArr, "Indian Male");
	array_push($SheadArr, "Indian Female");
	array_push($SheadArr, "White Male");
	array_push($SheadArr, "White Female");

	$SfieldsArr = array();
	array_push($SfieldsArr, "type__text|name__nr_black_male|size__5");
	array_push($SfieldsArr, "type__text|name__nr_black_female|size__5");
	array_push($SfieldsArr, "type__text|name__nr_coloured_male|size__5");
	array_push($SfieldsArr, "type__text|name__nr_coloured_female|size__5");
	array_push($SfieldsArr, "type__text|name__nr_indian_male|size__5");
	array_push($SfieldsArr, "type__text|name__nr_indian_female|size__5");
	array_push($SfieldsArr, "type__text|name__nr_white_male|size__5");
	array_push($SfieldsArr, "type__text|name__nr_white_female|size__5");
?>
<b>Full-time staff profile</b>
<br>
<br>


<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
//echo $inst;
	$this->gridShow("institutional_profile_pol_staff_ft", "institutional_profile_pol_staff_ft_id", "institution_ref__".$inst, $SfieldsArr, $SheadArr, "lkp_pol_profile_staff", "lkp_pol_profile_staff_id", "lkp_pol_profile_staff_desc", "lkp_pol_profile_staff_ref", 1, 40, 10);
?>
</table>
<br><br><b>Part-time staff profile</b>
<br>
<br>


<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->gridShow("institutional_profile_pol_staff_pt", "institutional_profile_pol_staff_pt_id", "institution_ref__".$inst, $SfieldsArr, $SheadArr, "lkp_pol_profile_staff_parttime", "lkp_pol_profile_staff_id", "lkp_pol_profile_staff_desc", "lkp_pol_profile_staff_ref", 1, 40, 10);
?>
</table
<br><br>
<i><?php echo  $this->getDBsettingsValue("InstProfilePolMsg"); ?></i>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$headArr = array();
	array_push($headArr, "");
	array_push($headArr, "Yes / No");
	array_push($headArr, "Comment");
	array_push($headArr, "Upload File");

	$fieldsArr = array();
	array_push($fieldsArr, "type__radio|name__yes_no|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");
	array_push($fieldsArr, "type__textarea|name__comment_text");

	$this->gridShow("institutional_profile_pol_budgets_hr", "institutional_profile_pol_budgets_hr_id", "institution_ref__".$inst, $fieldsArr, $headArr, "lkp_pol_budgets_hr", "lkp_pol_budgets_hr_id", "lkp_pol_budgets_hr_desc", "lkp_pol_budgets_hr_ref", 1, 40, 10, true, "inst_uploadDoc");

?>
</table>
</td></tr></table>
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
