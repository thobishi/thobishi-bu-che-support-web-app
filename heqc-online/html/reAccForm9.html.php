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
     <td colspan="2" class="loud"><b>2.7</b> Programme design<hr></td>
</tr>
	<tr>
		<td colspan="2"><br/><b>2.7.1</b> Programme design details</td>
	</tr>
	<tr>
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

			array_push($dFields, "type__text|size__10|name__course_title");
			array_push($dFields, "type__text|size__5|name__core_or_elective");
			array_push($dFields, "type__select|name__NQF_level_ref|description_fld__NQF_level|fld_key__NQF_id|lkp_table__NQF_level|lkp_condition__1|order_by__NQF_id");
			array_push($dFields, "type__text|size__5|name__SAQA_credits");
			array_push($dFields, "type__text|size__5|name__contact_hours");
			array_push($dFields, "type__text|size__5|name__study_hours");
//			array_push($dFields, "type__textarea|size__25|name__course_outcomes");
//			array_push($dFields, "type__textarea|size__25|name__assessment_methods");

			$hFields = array();
			array_push($hFields,"Title of module");
			array_push($hFields,"Core (C) / Elective (E) module");
			array_push($hFields,"NQF level");
			array_push($hFields,"Credits");
			array_push($hFields,"No. of contact hours");
			array_push($hFields,"% of contact learning in programme");
//			array_push($hFields,"Course/unit outcomes");
//			array_push($hFields,"Course/unit assessment methods");

			$this->gridShowRowByRow("reaccred_programme_design", "reaccred_programme_design_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 40, 5, "true", "true", 1);
		?>
		</table>
     </td>
</tr>
<tr>
     <td colspan="2">
     <br/> Please upload module outline which should include:
	 <ul>
		<li>Programme outcomes</li>
		<li>Module outcomes</li>
		<li>Assessment methods per module</li>
		<li>Mode of delivery of module</li>
</li>
	</ul>
	</td>
</tr>
<tr>
     <td><?php $this->makeLink("course_outline_doc");?><br><br></td>
</tr>
<tr>
     <td colspan="2">
     <br/><b>
     2.7.2</b> How is the programme design aligned with the prescribed level and purpose of the qualification?</td>
</tr>
<tr>
     <td><?php $this->showField("programme_design");?><br><br></td>
</tr>
<tr>
     <td colspan="2"><b>2.7.3</b> In the case of professional programmes, how does the programme design articulate with the professional/occupational purpose of the qualification? (In the case of a professional qualification include as an Annexure a letter from the professional body regarding the approval of the programme.)</td>
</tr>
<tr>
     <td><?php $this->showField("professional_programme_design");?><br><br></td>
</tr>
<tr>
     <td colspan="2"><b>2.7.4</b> In the case of programmes that include elective modules, describe any rules of combination that govern students' choices of elective modules. Include details of how the rules of combination are communicated to students.</td>
</tr>
<tr>
     <td><?php $this->showField("elective_units");?><br><br></td>
</tr>
<tr>
   <td colspan="2"><b>2.7.5</b> How does the programme make provision for learner support and for the learning needs of the target student intake?</td>
</tr>
<tr>
     <td><?php $this->showField("provision_learner_support");?><br><br></td>
</tr>
</table>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
