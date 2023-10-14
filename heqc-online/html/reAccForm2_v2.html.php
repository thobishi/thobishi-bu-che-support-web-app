<?php
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$CHE_no = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "referenceNumber");

	$ins_id = 0;
	if ($CHE_no > ''){
		$ins_id = $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_no, "institution_id");;
	}

	if (!$this->formFields["programme_name"]->fieldValue > '') {
		$programmeName = $this->getValueFromTable("Institutions_application", "CHE_reference_code", $CHE_no, "program_name");
		$this->formFields["programme_name"]->fieldValue = $programmeName;
	}
	$cesm_generation = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';

	
	
	// Populate fieldValues Array for CESM_code1 based on generation
	$sql = <<<CESMs
		SELECT CESM_code1, Description
		FROM SpecialisationCESM_code1
		WHERE {$cesm_generation}
		ORDER BY DOE_CESM_code
CESMs;

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$rs = mysqli_query($conn, $sql);
	$this->formFields["CESM_code1"]->fieldValuesArray = array();
	while ($row = mysqli_fetch_array($rs)) {
		$this->formFields["CESM_code1"]->fieldValuesArray[$row['CESM_code1']] = $row['Description'];
	}

	// Populate fieldValues Array for CESM_level2_ref based on generation
	$sql = <<<CESMs
		SELECT SpecialisationCESM_qualifiers_id, Description
		FROM SpecialisationCESM_qualifiers
		WHERE {$cesm_generation}
		AND level = '2'
		ORDER BY DOE_CESM_code
CESMs;

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$rs = mysqli_query($conn, $sql);
	$this->formFields["CESM_level2_ref"]->fieldValuesArray = array();
	while ($row = mysqli_fetch_array($rs)) {
		$this->formFields["CESM_level2_ref"]->fieldValuesArray[$row['SpecialisationCESM_qualifiers_id']] = $row['Description'];
	}

	// Populate fieldValues Array for CESM_level3_ref based on generation
	$sql = <<<CESMs
		SELECT SpecialisationCESM_qualifiers_id, Description
		FROM SpecialisationCESM_qualifiers
		WHERE {$cesm_generation}
		AND level = '3'
		ORDER BY DOE_CESM_code
CESMs;

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$rs = mysqli_query($conn, $sql);
	$this->formFields["CESM_level3_ref"]->fieldValuesArray = array();
	while ($row = mysqli_fetch_array($rs)) {
		$this->formFields["CESM_level3_ref"]->fieldValuesArray[$row['SpecialisationCESM_qualifiers_id']] = $row['Description'];
	}
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
    <td colspan="2" class="loud">2.1 Programme name, level, SAQA credits and registration<hr><br></td>
</tr>
<tr>
	<td width="50%"><b>CHE Reference Number:</b></td>
	<td><?php echo $this->showField("referenceNumber");?></td>
</tr>
<tr>
	<td width="50%"><b>Programme name:</b></td>
	<td><?php echo $this->showField("programme_name");?></td>
</tr>
<tr>
	<td width="50%"><b>NQF level:</b></td>
	<td><?php echo $this->showField("NQF_level");?></td>
</tr>
<tr>
	<td width="50%"><b>Number of Credits:<b></td>
	<td><?php echo $this->showField("saqa_credits");?></td>
</tr>
<tr>
	<td width="50%"><b>Minimum duration (years) for completion - Full Time:</b></td>
	<td><?php echo $this->showField("full_time_duration") ?></td>
</tr>
<tr>
	<td width="50%"><b>Minimum duration (years) for completion - Part Time:</b></td>
	<td><?php echo $this->showField("part_time_duration") ?></td>
</tr>
<tr>
	<td width="50%"><b>Mode of Delivery:</b></td>
	<td><?php echo $this->showField("mode_delivery") ?></td>
</tr>
<tr>
	<td width="50%"><b>Qualification Type:</b></td>
	<td><?php echo $this->showField("qualification_type_ref") ?></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td valign="top" class="oncolour">
	<?php
		$qt = $this->formFields["qualification_type_ref"]->fieldValue;
	
		if ($qt == 5 or $qt == 6 or $qt == 8 or $qt == 9 or $qt == 10){
			$displayDesignator = "block"; 
		} else	{ 
			$displayDesignator = "none"; 
		}
	
	?>
		<div id="is_degree" style="display:<?php echo $displayDesignator?>">
	
			<table cellpadding="2" cellspacing="2" align="left" width="100%">
			<tr>
				<td valign="top" width="35%"><b>Qualification designator:</b></td>
				<td valign="top" class="oncolour"><?php echo $this->showField("qualification_designator_ref") ?></td>
			</tr>
			</table>
			<br>
			<br>
		</div>
			
	<?php
		if ($this->formFields["qualification_designator_ref"]->fieldValue == 'Oth'){
			$displayOther = "block"; 
		} else	{ 
			$displayOther = "none"; 
		}		
	?>
		<div id="is_other" style="display:<?php echo $displayOther?>">
	
			<table cellpadding="2" cellspacing="2" align="left" width="100%">
				<tr>
					<td align="top"><b>Alternative designator:</b></td>
					<td valign="top" class="oncolour"><?php echo $this->showField("designation");?></td>
				</tr>
				<tr>
					<td valign="top"><b>Motivation for use of designator alternative:</b></td>
					<td valign="top" class="oncolour"><?php  echo $this->showField("motivation_alt_designator");?></td>
				</tr>
			</table>
	
		</div>

	</td>
</tr>

<tr>
	<td width="50%"><b>CESM test Classification (e.g. Education):</b></td>
	<td><?php echo $this->showField("CESM_code1") ?></td>
</tr>

<tr>
	<td width="50%"><b>First Qualifier (e.g. 0703 - Education Management and Leadership):</b></td>
	<td><?php echo $this->showField("CESM_level2_ref") ?></td>
</tr>

<tr>
	<td width="50%"><b>Second Qualifier (e.g. 070305 Higher Education):</b></td>
	<td><?php echo $this->showField("CESM_level3_ref") ?></td>
</tr>
<tr>
	<td colspan="2">
		<!--SAQA fields-->
		<table cellpadding="2" cellspacing="2" align="right" width="100%" border="0">

		<tr><td colspan="2"><hr></td></tr>

		<tr>
		<td width="50%"><b>Is the qualification registered by SAQA on the NQF?</b></td>
		<td valign="top"><?php echo $this->showField("is_reg_saqa_nqf") ?></td>
		</tr>

		<?php $displayStyle = $this->div_reacc($reaccred_id, 'is_reg_saqa_nqf', '2'); ?>
		<tr>
			<td colspan="2" align="right">
				<div id="is_reg_saqa_nqf" style="display:<?php echo $displayStyle?>">
					<table cellpadding="2" cellspacing="2" align="right" width="100%">
						<tr>
							<td width="50%"><b>SAQA Registration Number:</b></td>
							<td><?php echo $this->showField("saqa_reg_nr") ?></td>
						</tr>
						<tr>
							<td width="50%"><b>Please enter the date of registration with SAQA:</b></td>
							<td><?php echo $this->showField("saqa_reg_date") ?></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="right">
				<?php $displayStyle = $this->div_reacc($reaccred_id, 'is_reg_saqa_nqf', '3'); ?>
				<div id="is_reg_saqa_nqf2" style="display:<?php echo $displayStyle?>">
						<table cellpadding="2" cellspacing="2" align="right" width="100%">
							<tr>
								<td width="50%"><b>Please enter the date when application was made to SAQA:</b></td>
								<td><?php echo $this->showField("saqa_appl_date") ?></td>
							</tr>
						</table>
				</div>
			</td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td colspan="2">
		<hr>
		Please indicate all delivery sites for the proposed programme. (Learning Support Centres to be used for
		Distance Education should not be listed in this form.)
	</td>
</tr>
<tr>
	<td colspan="2">
		<table width="550" cellpadding="2" cellspacing="2" border="0" align="center">
		<tr>
				<td valign="top" class="oncolour" width="300">
				<b>Sites for your Institution</b>
				<br>
				<SELECT name="sites_select" style="width:295;height:200;" MULTIPLE>
<?php
				$SQL = <<<sites
					SELECT *
					FROM institutional_profile_sites
					WHERE institution_ref=$ins_id
sites;

  $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
          
              $sm = $conn->prepare($SQL);
                                $sm->bind_param("s", $ins_id);
                                $sm->execute();
                                
                                $rs = $sm->get_result();

			$rs = mysqli_query($conn, $SQL);

				while ($row = mysqli_fetch_array($rs)) {
					$site = $row["site_name"];
					$location = $row["location"];
					echo '<OPTION value="'.$row["institutional_profile_sites_id"].'">'.$site.'</OPTION>';
				}
?>
				</SELECT>

				</td>

				<td width="30" align="center">
<?php
// echo $SQL;
?>
				<a href="javascript:addSites();">
					<img src="images/btn_insert.gif" width="33" height="22" border="0" alt="Insert">
				</a>
				<br><br>
				<a href="javascript:removeSites();">
					<img src="images/btn_remove.gif" width="33" height="22" border="0" alt="Remove">
				</a>

				</td>

				<td valign="top" class="oncolour" width="220">
				<b>This programme is offered at these sites</b>
				<?php echo $this->showField("resultsSelect") ?>
				</td>
			</tr>

		</table>
	</td>
</tr>
</table>
<br>
