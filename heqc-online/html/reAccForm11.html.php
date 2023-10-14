<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
   <td colspan="2" class="loud"><b>2.9</b> Staffing<br><hr></td>
</tr>
<tr>
   <td colspan="2"><br/><b>2.9.1</b> Academic staff teaching the programme<br></td>
</tr>
<tr>
	<td colspan="2" valign="top">
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

		array_push($dFields, "type__text|size__30|name__staff_name");
		array_push($dFields, "type__text|size__30|name__designation");
		array_push($dFields, "type__select|name__full_part_time_ref|description_fld__lkp_full_part_desc|fld_key__lkp_full_part_id|lkp_table__lkp_full_part|lkp_condition__1|order_by__lkp_full_part_desc");
		array_push($dFields, "type__date|size__10|name__date_of_appointment");
		array_push($dFields, "type__textarea|name__qualifications");
		array_push($dFields, "type__text|size__5|name__number_years_teaching");
		array_push($dFields, "type__textarea|name__courses_units");

		$hFields = array();
		array_push($hFields,"Name");
		array_push($hFields,"Designation");
		array_push($hFields,"Full-time (F)/part-time(P)");
		array_push($hFields,"Date of first appointment at the institution");
		array_push($hFields,"Qualifications");
		array_push($hFields,"Number of years of teaching the programme");
		array_push($hFields,"Modules taught");

		$this->gridShowTableByRow("reaccred_academic_staff", "reaccred_academic_staff_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 70, 5, "true", "Academic staff member");
	?>
 	</td>
</tr>
<tr>
   <td colspan="2"><br/><b>2.9.2</b> What procedures are in place to ensure that academic staff, both full-time and part-time, are provided with sufficient time and opportunity for the development of curriculum, module design, learning materials, assessment, and the necessary learner support?</td>
</tr>
<tr>
     <td><?php $this->showField("staff_workload_allocations");?><br><br></td>
</tr>
<tr>
   <td colspan="2"><br/><b>2.9.3</b> Provide details of academic staff workload allocations, together with details of staff development activities conducted during the last three years.</td>
</tr>
<tr>
     <td><?php $this->showField("staff_academic_workload");?><br><br></td>
</tr>
<!--<tr>
   <td colspan="2"><b>2.9.4</b> Administrative and support staff involved in the programme</td>
</tr>
<tr>
	<td valign="top">
	<?php

		// $dFields = array();
		// array_push($dFields, "type__text|size__30|name__administrative_name");
		// array_push($dFields, "type__text|size__30|name__administrative_designation");
		// array_push($dFields, "type__select|name__full_part_time_ref|description_fld__lkp_full_part_desc|fld_key__lkp_full_part_id|lkp_table__lkp_full_part|lkp_condition__1|order_by__lkp_full_part_desc");
		// array_push($dFields, "type__textarea|name__qualifications");
		// array_push($dFields, "type__text|size__5|name__number_years_programme");
		// array_push($dFields, "type__textarea|name__administrative_functions");

		// $hFields = array();
		// array_push($hFields,"Name");
		// array_push($hFields,"Designation");
		// array_push($hFields,"Full-time (F)/part-time(P)");
		// array_push($hFields,"Qualifications");
		// array_push($hFields,"Number of years involved in the programme");
		// array_push($hFields,"Function(s)");

		// $this->gridShowTableByRow("reaccred_admin_supportstaff", "reaccred_admin_supportstaff_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 70, 5, "true", "Administrative / support staff member");

	?>
   </td>
</tr>-->
<tr>
   <td colspan="2"><br/><b>2.9.4</b> What procedures are in place to ensure that administrative and support staff, both full-time and part-time, are provided with sufficient time and opportunity for the development of skills necessary for the effective support of the programme?</td>
</tr>
<tr>
     <td><?php $this->showField("staff_development_activities");?><br><br></td>
</tr>
<tr>
   <td colspan="2"><br/><b>2.9.5</b> Provide details of the administrative/support staff workload allocations, together with details of staff development activities conducted during the last three years.</td>
</tr>
<tr>
     <td><?php $this->showField("staff_admin_workload");?><br><br></td>
</tr>
</table>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
