<a name="application_form_question3"></a>
<br>
<?php 
	$ia_site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$institutional_site_ref = $this->getValueFromTable("ia_criteria_per_site", "ia_criteria_per_site_id", $ia_site_id, "institutional_profile_sites_ref");
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $ia_site_id);
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<!--<b>EDIT FACILITIES OR VENUES </b><br>
<br>
<fieldset>
<legend>Minimum standards</legend>
Academic staff responsible for the programme are suitably qualified and have sufficient relevant experience and teaching competence, and their assessment competence and research profile are adequate for the nature and level of the programme. The institution and/or other recognised agencies contracted by the institution provide opportunities for academic staff to enhance their competences and to support their professional growth and development.
</fieldset>-->
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
	array_push($headArr, "Facilities/Venues required");
	array_push($headArr, "Number required");
	array_push($headArr, "Number available");
	array_push($headArr, "Maximum capacity of available");


	$fieldArr = array();
	array_push($fieldArr, "type__text|name__n_required|size_4");
	array_push($fieldArr, "type__text|name__n_available|size__4");
	array_push($fieldArr, "type__text|name__n_maximum_capacity|size__4");


?>
	
	<br><br>
	<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
	<?php 
		$this->gridShow("ias_facilities", "ias_facilities_id", "ia_criteria_per_site_ref__".
		$institutional_site_ref, $fieldArr, $headArr, 
		"lkp_site_facilities", "id", "lkp_site_facilities_desc",
		 "lkp_site_facilities_ref", 1);
	?>
	</td>
	</tr>
	
	</table>


<tr>
	<td valign="top"><b>2.</b></td>
	<td valign="top"><b>If any other facilities or venues are required specify and provide a motivation: </b>
		<?php echo $this->makeLink("other_facilities_motivation_doc") ?>
	 </td>

</tr>


<tr>
	<td valign="top"><b>3.</b></td>
	<td valign="top"><b>Number of teaching staff members per site for this programme / qualification </b>

		<table border="1" style="width:100%" >	
		<tr>
			<th rowspan="2">Full-time</th>
			<td>Current</td>
			<td><?php $this->showField("n_ft_teach_staff_current") ?>	</td>
		</tr>	
		<tr>
			<td>Planned</td>
			<td><?php $this->showField("n_ft_teach_staff_planned") ?>	</td>
		</tr>

		<tr>
			<th rowspan="2">Part-time</th>
			<td>Current</td>
			<td><?php $this->showField("n_pt_teach_staff_current") ?>	</td>
		</tr>	
		<tr>
			<td>Planned</td>
			<td><?php $this->showField("n_pt_teach_staff_planned") ?>	</td>
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td valign="top"><b>4.</b></td>
	<td valign="top"><b>Name of Programme Coordinator per site for this programme / qualification </b>
		<?php $this->showField("3_3_1_progcoordname_char") ?>	
	 </td>
	
</tr>

<tr>
	<td valign="top"><b>5.</b></td>
	<td valign="top"><b>Upload Programme Coordinator CV   </b>
		<?php echo $this->makeLink("3_3_3_progcoordcv_doc") ?>
	</td>
</tr>

<tr>
	<td valign="top"><b>6.</b></td>
	<td valign="top"><b>Complete the planned headcount enrolments for this programme / qualification per site </b>
	
	<table border="1" style="width:100%" >	
		<tr>
			<th>Planned Headcount enrolment for the first enrolment</th>
			<td><?php $this->showField("n_headcount_enrol_year1_planned") ?>	</td>
		</tr>	

		<tr>
			<th>Planned Headcount enrolment for Year 2</th>
			<td><?php $this->showField("n_headcount_enrol_year2_planned") ?>	</td>
		</tr>	

		<tr>
			<th>Planned Headcount enrolment for Year 3</th>
			<td><?php $this->showField("n_headcount_enrol_year3_planned") ?>	</td>
		</tr>	

		<tr>
			<th>Planned Headcount enrolment for Year 4</th>
			<td><?php $this->showField("n_headcount_enrol_year4_planned") ?>	</td>
		</tr>	
		
	</table>
	
	</td>
</tr>


<tr><td colspan="1"><br></td></tr>
</table>


<br><br>

<fieldset >
<legend><b>The following documentation to be uploaded as it pertains to this programme and site of delivery</b></legend>
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
					<td class="oncolour"><b>ACADEMIC STAFF MEMBERS for this programme / qualification - CVs (per site of delivery)</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("4_academicstaffcvs_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Workload allocation model (per site of delivery) </b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("4_workloadallocationmodel_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Staff Recruitment Plan (per site of delivery)</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("4_staffrecruitmentpolicy_doc") ?><br></td>
				</tr>
			</table>
		</li>

	<!--	<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Library holdings/budget specific to programme</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("7_librarybudget_doc") ?><br></td>
				</tr>
			</table>
		</li>

		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>For postgraduate programme / qualification: ethical clearance process</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("9_codeethics_doc") ?><br></td>
				</tr>
			</table>
		</li>
-->

	</ul>
</div>
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