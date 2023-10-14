<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>Please complete the following information in relation to your institution's infrastructure</b>
<br><br>
<b>MAIN CAMPUS INFORMATION</b>
<a name="institutional_profile_main_campus_info"></a>
<?php 
	// Number and type of IT infrastructure
	//  institutional_profile_main_campus_info
	$headArr = array();
	$headArr["Site Name"] = "1";
	$headArr["Location"] = "1";
	$headArr["Headcount Enrolments 2003"] = "2";
	
	$fieldsArr = array();
	$fieldsArr["campus_name"] = "";
	$fieldsArr["location"] = "";
	// BUG: 2003 should be 2004
	$fieldsArr["enrollments_2003_undergrad"] = array("Undergraduate");
	$fieldsArr["enrollments_2003_postgrad"] = array("Postgraduate");

	//echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_main_campus_info", "institutional_profile_main_campus_info_id", "institution_ref",$fieldsArr, 5, 0, $headArr);
?>
<table width='95%' align='center' class='oncoloursoft' cellpadding='2' cellspacing='2' border='1'><tr><td>
	<table cellpadding='2' class='oncolourswitchb' width='100%'>
		<tr>
			<td width='33%' valign='top' rowspan='1' align='right'><b>Name</b></td>
			<td valign='top' align='left'><?php $this->showField("site_name")?></td>
		</tr>
		<tr>
			<td width='33%' valign='top' rowspan='1' align='right'><b>Location</b></td>
			<td valign='top' align='left'><?php $this->showField("location")?></td>
		</tr>
		<tr>
			<td width='33%' valign='top' rowspan='2' align='right'><b>Headcount Enrolments 2004</b></td>
			<td valign='top' align='left'>Undergraduate<br><?php $this->showField("enrol_under_contact")?></td>
		</tr>
		<tr>
			<td valign='top' align='left'>Postgraduate<br><?php $this->showField("enrol_post_contact")?></td>
		</tr></table>
	</td></tr></table>


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
