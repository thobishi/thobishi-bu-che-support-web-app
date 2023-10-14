<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
   <td colspan="2" class="loud"><b>2.13</b> Student retention, throughput and completion rates<br><hr></td>
</tr>
<tr>
   <td colspan="2"><br/><b>2.13.1</b> Details of student completion rates<br></td>
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

			array_push($dFields, "type__text|size__10|name__students_enrolled");
			array_push($dFields, "type__text|size__10|name__completed_within_time");
			array_push($dFields, "type__text|size__10|name__completed_beyond_time");
			array_push($dFields, "type__text|size__10|name__completed_qualification");

			$hFields = array();
			array_push($hFields,"Year");
			array_push($hFields,"No. of students enrolled");
			array_push($hFields,"No. of students who completed the programme within minimum time");
			array_push($hFields,"No. of students who completed the programme beyond minimum time");
			array_push($hFields,"Total no. of students who completed the qualification");

			$this->gridShow("reaccred_student_rates", "reaccred_student_rates_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, "lkp_year", "lkp_year_desc", "lkp_year_desc", "student_year",1,40,5,FALSE,""," lkp_year_desc BETWEEN 2012 AND 2016");

		?>
		</table>
     </td>
</tr>
<tr>
  <td colspan="2"><br/><b>2.13.2</b> Based on an analysis of the student completion and success rates in the programme, comment on significant successes, areas of concern and planned improvements to address throughput rates, graduation rates and dropout rates. Provide examples and evidence to support the conclusions drawn.<br></td>
</tr>
<tr>
  <td><?php $this->showField("demographic_diversity_students");?><br><br></td>
</tr>
<tr>
  <td colspan="2"><b>2.13.3</b> Provide examples of any tracer studies conducted during the last three years to track the employability of graduates. Explain and assess how the results of these studies are incorporated into the institutional and programmatic strategic, academic and resource planning in order to improve the quality of programme provision?<br></td>
</tr>
<tr>
  <td><?php $this->showField("tracer_studies_conducted");?><br><br></td>
</tr>
</table>