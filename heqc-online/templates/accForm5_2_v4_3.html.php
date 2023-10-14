<a name="application_form_question3"></a>
<br>
<?php 
	$ia_site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$institutional_site_ref = $this->getValueFromTable("ia_criteria_per_site", "ia_criteria_per_site_id", $ia_site_id, "institutional_profile_sites_ref");
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $ia_site_id);
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>EDIT FACILITIES OR VENUES </b><br>
<br>
<fieldset>
<legend>Minimum standards</legend>
Academic staff responsible for the programme are suitably qualified and have sufficient relevant experience and teaching competence, and their assessment competence and research profile are adequate for the nature and level of the programme. The institution and/or other recognised agencies contracted by the institution provide opportunities for academic staff to enhance their competences and to support their professional growth and development.
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>1.</b></td>
	<td valign="top"><b>Indicate the number of facilities or venues required, available and the maximum capacity of available venues</b></td>
</tr>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td colspan="2">
<?php 
	$headArr = array();
	// array_push($headArr, "Contact:vertical");
	// array_push($headArr, "Distance:vertical");
	//array_push($headArr, "Select:vertical");
	array_push($headArr, "Facilities/Venues required");
	array_push($headArr, "Number required");
	array_push($headArr, "Number available");
	array_push($headArr, "Maximum capacity of available");


	$fieldArr = array();

	// array_push($fieldArr, "type__checkbox|name__contact_checkbox");
	// array_push($fieldArr, "type__checkbox|name__distance_checkbox");
	//array_push($fieldArr, "type__checkbox|name__select_checkbox");
	array_push($fieldArr, "type__text|name__n_required|size_4");
	array_push($fieldArr, "type__text|name__n_available|size__4");
	array_push($fieldArr, "type__text|name__n_maximum_capacity|size__4");


?>
	
	<br><br>
	<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
	<?php 
		$this->gridShow("ias_facilities", "ias_ facilities_id", "ia_criteria_per_site_ref__".
		$current_id, $fieldArr, $headArr, 
		"lkp_site_facilities", "id", "lkp_site_facilities_desc",
		 "lkp_site_facilities_ref", 1);

		 // $this->gridShowRowByRow("ia_modes_of_delivery","ia_mode_of_delivery_id","application_ref__".
		 //$current_id,$dFields,$hFields, 40, 5, "true", "true",1);
	?>
	</td>
	</tr>
	
	</table>

	<br><br>

<tr>
	<td valign="top"><b>2.</b></td>
	<td valign="top"><b>If any other facilities or venues are required specify and provide a motivation: </b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("3_2_progcoord_lkp") ?><br></td>
</tr>


<tr>
	<td valign="top"><b>3.</b></td>
	<td valign="top"><b>Number of teaching staff members per site for this programme / qualification </b></td>
</tr>

<tr>
	<td valign="top"><b>4.</b></td>
	<td valign="top"><b>Name of Programme Coordinator per site for this programme / qualification </b></td>
</tr>

<tr>
	<td valign="top"><b>5.</b></td>
	<td valign="top"><b>Upload Programme Coordinator CV   </b></td>
</tr>

<tr>
	<td valign="top"><b>6.</b></td>
	<td valign="top"><b>Complete the planned headcount enrolments for this programme / qualification per site </b></td>
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