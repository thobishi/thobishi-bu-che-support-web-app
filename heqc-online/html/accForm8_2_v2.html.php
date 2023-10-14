<a name="application_form_question3"></a>
<br>
<?php 
	$ia_site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$institutional_site_ref = $this->getValueFromTable("ia_criteria_per_site", "ia_criteria_per_site_id", $ia_site_id, "institutional_profile_sites_ref");
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $ia_site_id);
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>3. STAFF  QUALIFICATIONS: (Criterion 3)</b><br>
<br>
<fieldset>
<legend>Minimum standards</legend>
Academic staff responsible for the programme are suitably qualified and have sufficient relevant experience and teaching competence, and their assessment competence and research profile are adequate for the nature and level of the programme. The institution and/or other recognised agencies contracted by the institution provide opportunities for academic staff to enhance their competences and to support their professional growth and development.
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>3.1</b></td>
	<td valign="top"><b>For each staff member who contributes to the programme, provide:</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top">
	<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
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

		$dFields = array();
		array_push($dFields, "type__text|size__10|name__staff_member_name");
		array_push($dFields, "type__select|name__lkp_full_part_ref|description_fld__lkp_full_part_desc|fld_key__lkp_full_part_id|lkp_table__lkp_full_part|lkp_condition__1|order_by__lkp_full_part_desc");


		array_push($dFields, "type__text|size__5|name__staff_member_qualifications");
		array_push($dFields, "type__text|size__5|name__staff_member_field_of_study");
		array_push($dFields, "type__text|size__5|name__staff_member_years_teaching");
		array_push($dFields, "type__text|size__5|name__staff_member_supervision_exp");
		array_push($dFields, "type__text|size__10|name__staff_member_research_output");
		array_push($dFields, "type__textarea|size__20|name__allocated_module");

		$hFields = array();
		array_push($hFields,"Name");
		array_push($hFields,"Full/part-time");
		array_push($hFields,"Qualifications or highest relevant qualification and awarding institution");
		array_push($hFields,"Field in which qualification was obtained e.g. organic chemistry");
		array_push($hFields,"No. of years of tertiary teaching experience");
		array_push($hFields,"PG supervision experience e.g. two MSc, one PhD");
		array_push($hFields,"Research output e.g. 10 DoE-accredited journal publications");
		array_push($hFields,"Allocated module/s");

		$this->gridShowRowByRow("ias_staff_details", "ias_staff_details_id", "ia_criteria_per_site_ref__".$ia_site_id, $dFields, $hFields, 40, 5, "true", "true", 1);
	?>
	</table>
	</td>
</tr>
<tr>
	<td valign="top"><b>3.2</b></td>
	<td valign="top"><b>Do you have a programme coordinator?</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("3_2_progcoord_lkp") ?><br></td>
</tr>
<tr>
	<td colspan="2">

<?php 
	if ($this->formFields["3_2_progcoord_lkp"]->fieldValue != "2")
	{ $displayDivProgCoord = "none"; }
	else
	{ $displayDivProgCoord = "block"; }

?>

<div id="progcoord_div" style="display:<?php echo $displayDivProgCoord?>">
	<table>
		<tr>
			<td valign="top"><b>3.3</b></td>
			<td valign="top"><b>Name of the programme coordinator:</b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td valign="top"><?php $this->showField("3_3_1_progcoordname_char") ?></td>
		</tr>
		<tr>
			<td valign="top"><b>&nbsp;</b></td>
			<td valign="top"><b>Describe the roles and responsbilities of the programme coordinator:</b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td valign="top"><?php $this->showField("3_3_2_progcoordroles_text") ?></td>
		</tr>
		<tr>
			<td valign="top"><b>&nbsp;</b></td>
			<td valign="top"><b>Upload the CV of the programme coordinator:</b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td valign="top"><?php $this->makeLink("3_3_3_progcoordcv_doc") ?></td>
		</tr>
	</table>
</div>
	<td>
</tr>

<tr><td colspan="1"><br></td></tr>

<tr>
	<td valign="top"><b>3.4</b></td><td valign="top"><b>What opportunities does the institution provide for academic staff to enhance their competences and to support their professional growth and development?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_4_accstaffopp_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>3.5</b></td><td valign="top"><b>Provide a detailed description of your workload allocation model taking into account the number of academic staff attached to the programme and envisaged student enrolments.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_5_workalloc_text") ?></td>
</tr></table>
<br><br>

<fieldset >
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<?php /*
<!-- The following is for PRIVATE providers  -->
*/
//hardcoded - take out
$display1 = "block";
?>
<div style="display:<?php echo $display1?>">

	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Staff development policy:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("3_staffdev_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Workplace Skills Plan (WSP):</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("3_workskillsplan_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>If you are still to comply with some of the minimum standards for this criterion, please attach your plan to achieve compliance:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("3_complianceplan_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
</div>
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Any other documentation which will indicate your compliance with this criterion.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("3_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
		<br>
</fieldset>

<br><br>
</td></tr></table>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}

	function checkPlacement (obj, obj2) {
		if (obj.value == 1) {
			alert("Please fill in the block below");
		}
	}
</script>


<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>

<hr>