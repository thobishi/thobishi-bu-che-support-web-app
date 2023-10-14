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
	<td colspan="2" class="loud">2.5 Programme coordination<hr></td>
	</tr>
	<tr>
		<td colspan="2"><br/><b>2.5.1</b> Details of the programme coordinator/manager (if there is more than one site of delivery, provide details for each site).<br></td>
	</tr>
	<tr>
		<td>
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

			array_push($dFields, "type__select|name__site_ref|description_fld__site_name|fld_key__institutional_profile_sites_id|lkp_table__institutional_profile_sites|lkp_condition__".'1 AND institution_ref='.$this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref")."|order_by__site_name");
			array_push($dFields, "type__text|size__10|name__coordinator_name");
			array_push($dFields, "type__select|name__coordinator_title_ref|description_fld__lkp_title_desc|fld_key__lkp_title_id|lkp_table__lkp_title|lkp_condition__1|order_by__lkp_title_desc");
			array_push($dFields, "type__text|size__10|name__coordinator_designation");
			array_push($dFields, "type__text|size__10|name__coordinator_highest_qual");
			array_push($dFields, "type__text|size__5|name__num_years_prog");
			array_push($dFields, "type__text|size__5|name__num_years_coord");

			$hFields = array();
			array_push($hFields,"Site name");
			array_push($hFields,"Name");
			array_push($hFields,"Title");
			array_push($hFields,"Designation");
			array_push($hFields,"Highest qualification");
			array_push($hFields,"No. of years in programme");
			array_push($hFields,"No. of years as programme coordinator");

			$this->gridShowRowByRow("reaccred_prog_coordinator", "reaccred_prog_coordinator_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 40, 5, "true", "true", 1);
		?>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><b>2.5.2</b> Describe the role of the programme coordinator and indicate how it is integrated within the institutional system of academic and administrative management.</td>
		</tr>
		<tr>
		<td><?php $this->showField("role_programme_coordinator");?><br><br></td>
	</tr>
	<tr>
		<td colspan="2"><b>2.5.3</b> Describe the role played by the programme coordinator in providing intellectual leadership of the programme and in ensuring its academic coherence, professional integrity, effective delivery and the quality assurance of delivery of the programme.</td>
		</tr>
		<tr>
		<td><?php $this->showField("quality_assurance");?><br><br></td>
	</tr>
	<tr>
		<td colspan="2"><b>2.5.4</b> What provision is made for lecturer/tutor input and participation in relevant aspects of programme coordination?</td>
		</tr>
		<tr>
		<td><?php $this->showField("lecturer_tutor_input");?><br><br></td>
	</tr>
	<!--<tr>
		<td colspan="2"><b>2.5.5</b> What provision is made for student input and participation in relevant aspects of programme coordination?</td>
		</tr>
		<tr>
		<td><?php //$this->showField("students_input");?><br><br></td>
		</tr>-->
</table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>