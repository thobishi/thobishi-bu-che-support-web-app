<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
  <td colspan="2" class="loud"><b>2.12</b> Student assessment<br><hr></td>
</tr>
<tr>
  <td colspan="2"><br/><b>2.12.1</b> Outline activities over the last three years that have had a focus on assessment, aimed at ensuring that all academic staff, both full-time and part-time, are familiar with the assessment policy of the institution, and are able to apply the policy appropriately, and in a manner that is consonant with the programme design, outcomes, mode(s) of delivery, assessment criteria, and student profile.<br></td>
</tr>
<tr>
  <td><?php $this->showField("outline_meetings_activities");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.2</b> Describe procedures in place to ensure that assessment (an appropriate mix, balance, weighting and assessment standard) is commensurate with the level of the programme.<br></td>
</tr>
<tr>
  <td><?php $this->showField("formative_summative_assessment");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.3</b> Describe the steps taken to ensure that assessment tasks (assignments, tests, projects) are returned to students in sufficient time to allow them to benefit from assessors' feedback.<br></td>
</tr>
<tr>
  <td><?php $this->showField("assessments_returned_feedback");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.4</b> Describe the procedures in place for the internal moderation of assessment. Explain how these procedures are implemented and what improvements have been made by the institution in relation to internal moderation of assessment.<br></td>
</tr>
<tr>
  <td><?php $this->showField("internal_moderation_assessment");?><br><br></td>
</tr>
<!--<tr>
  <td colspan="2"><b>2.12.5</b> Details of internal moderators over the last three years<br></td>
</tr>
<tr>
		<td valign="top">
		<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
		<?php

		// if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
			// $cmd = explode("|", $_POST["cmd"]);
			// switch ($cmd[0]) {
				// case "new":
					// $this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
					// break;
				// case "del":
					// $this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
					// break;
			// }
			// echo '<script>';
			// echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
			// echo 'document.defaultFrm.MOVETO.value = "stay";';
			// echo 'document.defaultFrm.submit();';
			// echo '</script>';
		// }

			// $dFields = array();

			// array_push($dFields, "type__text|size__15|name__moderator_name");
			// array_push($dFields, "type__text|size__15|name__moderator_position");
			// array_push($dFields, "type__text|size__5|name__years_internal_moderator");
			// array_push($dFields, "type__textarea|name__courses_units_moderated");

			// $hFields = array();
			// array_push($hFields,"Name");
			// array_push($hFields,"Position");
			// array_push($hFields,"No. of years as internal moderator");
			// array_push($hFields,"Courses/units moderated");

			// $this->gridShowRowByRow("reaccred_internal_moderators", "reaccred_internal_moderators_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 70, 5, "true", "true", 1);
		?>
		</table>
     </td>
</tr> -->
<tr>
  <td colspan="2"><br/><b>2.12.5</b> Describe the policy for appointment of external examiners, and the process of external examination. Explain how this policy is implemented and what improvements have been made by the institution in relation to external examination processes.<br></td>
</tr>
<tr>
  <td><?php $this->showField("appointment_external_moderators");?><br><br></td>
</tr>
<!--<tr>
  <td colspan="2"><b>2.12.7</b> Details of external examiners over the last three years.<br></td>
</tr>
<tr>
		<td valign="top">
		<?php

			// $dFields = array();
			// array_push($dFields, "type__text|size__10|name__examiners_name");
			// array_push($dFields, "type__text|size__5|name__institution");
			// array_push($dFields, "type__text|size__5|name__qualifications");
			// array_push($dFields, "type__textarea|size__25|name__relevant_expertise");
			// array_push($dFields, "type__text|size__5|name__years_external_examiner");
			// array_push($dFields, "type__textarea|size__20|name__externally_examined");

			// $hFields = array();
			// array_push($hFields,"Name");
			// array_push($hFields,"Institution");
			// array_push($hFields,"Qualifications");
			// array_push($hFields,"Relevant expertise");
			// array_push($hFields,"No. of years as external examiner");
			// array_push($hFields,"Courses/units/dissertations/theses externally examined");


			// $this->gridShowTableByRow("reaccred_external_examiners", "reaccred_external_examiners_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 70, 5, "true", "External examiner");
		?>
     </td>
</tr>
<tr>-->
  <td colspan="2"><br/><b>2.12.7</b> Describe the systems in place to ensure the accuracy, consistency, reliability and security of assessment results.<br></td>
</tr>
<tr>
  <td><?php $this->showField("security_of_assessments");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.8</b> What mechanisms exist to ensure the integrity of the certification process and the validity of the certificates that are issued?<br></td>
</tr>
<tr>
  <td><?php $this->showField("certification_process_validity");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.9</b> What procedures are in place for the settling of student disputes regarding assessment results?<br></td>
</tr>
<tr>
  <td><?php $this->showField("student_disputes_results");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.12.10</b> What provision is made for the development of staff as competent assessors?<br></td>
</tr>
<tr>
  <td><?php $this->showField("staff_competent_assessors");?><br><br></td>
</tr>
</table>