<?php 
	$inst_id = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<i>Please provide headcount enrolments for undergraduate and postgraduate programmes for each site of delivery.</i>
<br><br>
<b>UNDERGRADUATE</b>
<a name="institutional_profile_distance_inst"></a>
<?php 	$headArr = array();
	array_push($headArr, "Site of Delivery");
	array_push($headArr, "Male");
	array_push($headArr, "Female");
	array_push($headArr, "Male");
	array_push($headArr, "Female");
	array_push($headArr, "Male");
	array_push($headArr, "Female");
	array_push($headArr, "Male");
	array_push($headArr, "Female");
	
	$fieldArr = array();
	array_push($fieldArr, "type__text|name__nr_black_male|size__3");
	array_push($fieldArr, "type__text|name__nr_black_female|size__3");
	array_push($fieldArr, "type__text|name__nr_coloured_male|size__3");
	array_push($fieldArr, "type__text|name__nr_coloured_female|size__3");
	array_push($fieldArr, "type__text|name__nr_indian_male|size__3");
	array_push($fieldArr, "type__text|name__nr_indian_female|size__3");
	array_push($fieldArr, "type__text|name__nr_white_male|size__3");
	array_push($fieldArr, "type__text|name__nr_white_female|size__3");
?>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<tr align="center" class='oncolourb'><td width="30%">&nbsp;</td><td colspan="2">Black</td><td colspan="2">Coloured</td><td colspan="2">Indian</td><td colspan="2">White</td></tr>
<?php 
	$this->gridShow("institutional_profile_sites_ug_enrol", "institutional_profile_sites_ug_enrol_id", "s_institution_ref__".$inst_id, $fieldArr, $headArr, "institutional_profile_sites", "institutional_profile_sites_id", "site_name", "institutional_profile_sites_ref",1, 40, 10,false,"","institution_ref=$inst_id");
?>
</table>
<br><br>

<b>POSTGRADUATE</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<tr align="center" class='oncolourb'><td width="30%">&nbsp;</td><td colspan="2">Black</td><td colspan="2">Coloured</td><td colspan="2">Indian</td><td colspan="2">White</td></tr>
<?php 
	$this->gridShow("institutional_profile_sites_pg_enrol", "institutional_profile_sites_pg_enrol_id", "s_institution_ref__".$inst_id, $fieldArr, $headArr, "institutional_profile_sites", "institutional_profile_sites_id", "site_name", "institutional_profile_sites_ref",1, 40, 10,false,"","institution_ref=$inst_id");
?>
</table>
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
