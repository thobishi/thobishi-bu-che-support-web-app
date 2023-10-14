	<br>
<?php 
        $app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($app_id); }

	$prov_type = HEQConline::checkAppPrivPubl($app_id);
	$this->formFields["institution_id"]->fieldValue = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$this->showField("institution_id");

	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
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
	$rs = mysqli_query($conn, $sql);
	$this->formFields["CESM_level3_ref"]->fieldValuesArray = array();
	while ($row = mysqli_fetch_array($rs)) {
		$this->formFields["CESM_level3_ref"]->fieldValuesArray[$row['SpecialisationCESM_qualifiers_id']] = $row['Description'];
	}

?>
<!-- 2009-12-17 Robin: HEQF alignment requirements -->
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<table width="95%" border=0 align="right" cellpadding="2" cellspacing="2">
	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Programme Type:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("prog_type") ?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Qualification Type:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("qualification_type_ref") ?></td>
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
					<td valign="top" class="oncolour"><?php $this->showField("qualification_designator_ref") ?></td>
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
						<td valign="top" class="oncolour"><?php $this->showField("designation");?></td>
					</tr>
					<tr>
						<td valign="top"><b>Motivation for use of designator alternative:</b></td>
						<td valign="top" class="oncolour"><?php $this->showField("motivation_alt_designator");?></td>
					</tr>
				</table>
		
			</div>
	
		</td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>CESM Classification (e.g. Education):</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("CESM_code1") ?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%">	
			<b>First Qualifier (e.g. 0703 - Education Management and Leadership):</b>
		</td>
		<td valign="top" class="oncolour"><?php $this->showField("CESM_level2_ref") ?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Second Qualifier (e.g. 070305 Higher Education):</b></td>
		<td valign="top" class="oncolour">
			<?php $this->showField("CESM_level3_ref") ?>
			<br>
			<?php $this->showField("CESM_level3_defn") ?>
		</td>
	</tr>


	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>NQF Level:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("NQF_ref") ?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Minimum number of credits:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("num_credits");?></td>
	</tr>
<!-- 2010-01-07 Robin 
	Commented out until HEQC can resolve it
	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Total Credits at relevant NQF Levels</b></td>
		<td valign="top" class="oncolour">

			<?php
			//$nqf5 = ($qt == 1 || $qt == 2 || $qt == 3 || $qt == 5 || $qt == 6) ? "block" : "none";
			//$nqf6 = ($qt == 1 || $qt == 2 || $qt == 3 || $qt == 4 || $qt == 5 || $qt == 6) ? "block" : "none";
			//$nqf7 = ($qt == 2 || $qt == 3 || $qt == 4 || $qt == 5 || $qt == 6 || $qt == 7 || $qt == 8) ? "block" : "none";
			//$nqf8 = ($qt == 4 || $qt == 6 || $qt == 7 || $qt == 8 || $qt == 9) ? "block" : "none";
			//$nqf9 = ($qt == 7 || $qt == 8 || $qt == 9 || $qt == 10) ? "block" : "none";
			//$nqf10 = ($qt == 9 || $qt == 10) ? "block" : "none";
			?>


			<div id="nqf5" style="display:<?php //echo $nqf5;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?></td></tr>
			</table>
			</div>			
			<div id="nqf6" style="display:<?php //echo $nqf6;?>">
			<table>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
			</table>
			</div>
			<div id="nqf7" style="display:<?php //echo $nqf7;?>">
			<table>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?></td></tr>
			</table>
			</div>			
			<div id="nqf8" style="display:<?php //echo $nqf8;?>">
			<table>
				<tr><td><b>Level 8</b></td><td><?php //$this->showField('nqf_level_8_credits');?></td></tr>
			</table>
			</div>			
			<div id="nqf9" style="display:<?php //echo $nqf9;?>">
			<table>
				<tr><td><b>Level 9</b></td><td><?php //$this->showField('nqf_level_9_credits');?></td></tr>
			</table>
			</div>			
			<div id="nqf10" style="display:<?php //echo $nqf10;?>">
			<table>
				<tr><td><b>Level 10</b></td><td><?php //$this->showField('nqf_level_10_credits');?></td></tr>
			</table>
			</div>		
-->	
<!--
			<?php
			//$qt1 = ($qt == 1) ? "block" : "none";
			//$qt2 = ($qt == 2) ? "block" : "none";
			//$qt3 = ($qt == 3) ? "block" : "none";
			//$qt4 = ($qt == 4) ? "block" : "none";
			//$qt5 = ($qt == 5) ? "block" : "none";
			//$qt6 = ($qt == 6) ? "block" : "none";
			//$qt78 = ($qt == 7 || $qt == 8) ? "block" : "none";
			//$qt9 = ($qt == 9) ? "block" : "none";
			//$qt10 = ($qt == 10) ? "block" : "none";
			?>
			// showField cannot be repeated for a field.  Values are overwritten by the additional showField values.
			<div id="qt1_nqf" style="display:<?php //echo $qt1;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
			</table>
			</div>
			<div id="qt2_nqf" style="display:<?php //echo $qt2;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?></td></tr>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?></td></tr>
			</table>
			</div>
			<div id="qt3_nqf" style="display:<?php //echo $qt3;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?> (maximum 120)</td></tr>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?> (minimum 60)</td></tr>
			</table>
			</div>
			<div id="qt4_nqf" style="display:<?php //echo $qt4;?>">
			<table>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 8</b></td><td><?php //$this->showField('nqf_level_8_credits');?></td></tr>
			</table>
			</div>
			<div id="qt5_nqf" style="display:<?php //echo $qt5;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?> (maximum 96)</td></tr>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?> (minimum 120)</td></tr>
			</table>
			</div>
			<div id="qt6_nqf" style="display:<?php //echo $qt6;?>">
			<table>
				<tr><td><b>Level 5</b></td><td><?php //$this->showField('nqf_level_5_credits');?> (maximum 96)</td></tr>
				<tr><td><b>Level 6</b></td><td><?php //$this->showField('nqf_level_6_credits');?></td></tr>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 8</b></td><td><?php //$this->showField('nqf_level_8_credits');?> (minimum 96)</td></tr>
			</table>
			</div>
			<div id="qt78_nqf" style="display:<?php //echo $qt78;?>">
			<table>
				<tr><td><b>Level 7</b></td><td><?php //$this->showField('nqf_level_7_credits');?></td></tr>
				<tr><td><b>Level 8</b></td><td><?php //$this->showField('nqf_level_8_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 9</b></td><td><?php //$this->showField('nqf_level_9_credits');?></td></tr>
			</table>
			</div>
			<div id="qt9_nqf" style="display:<?php //echo $qt9;?>">
			<table>
				<tr><td><b>Level 8</b></td><td><?php //$this->showField('nqf_level_8_credits');?></td></tr>
				<tr><td><b>Level 9</b></td><td><?php //$this->showField('nqf_level_9_credits');?> (minimum 120)</td></tr>
				<tr><td><b>Level 10</b></td><td><?php //$this->showField('nqf_level_10_credits');?></td></tr>
			</table>
			</div>
			<div id="qt10_nqf" style="display:<?php //echo $qt10;?>">
			<table>
				<tr><td><b>Level 9</b></td><td><?php //$this->showField('nqf_level_9_credits');?></td></tr>
				<tr><td><b>Level 10</b></td><td><?php //$this->showField('nqf_level_10_credits');?> (minimum 360)</td></tr>
			</table>
			</div>
-->
		</td>
	</tr>
	
	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Minimum duration (years) for completion - Full Time:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("full_time") ?><span class="specials">(Enter only numeric values)</span></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Minimum duration (years) for completion - Part Time:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("part_time") ?><span class="specials">(Enter only numeric values)</span></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%">
			<b>Has the programme been approved by the relevant governance structure within the institution?</b>
		</td>
		<td valign="top" class="oncolour"><?php $this->showField("senate_approved") ?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Date of approval:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("senate_approved_date") ?></td>
	</tr>
<?php 
	//if provider is public, display following questions
	if ($prov_type == 2) {
?>
	<tr><td colspan="2"><hr></td></tr>

	<!--PQM fields-->

	<tr>
	<td ALIGN=RIGHT valign="top"><b>Does the programme form part of your approved PQM?</b></td>
	<td class="oncolour"><?php $this->showField("is_part_pqm") ?></td>
	</tr>

	<?php 	$displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'is_part_pqm', '2') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'is_part_pqm', '2') : "none"; ?>
	<tr>
		<td colspan="2">
			<div id="is_part_pqm" style="display:<?php echo $displayStyle?>">
			<table cellpadding="2" cellspacing="2" align="right" width="100%">
				<tr>
					<td ALIGN=RIGHT valign="top" width="50%"><b>Upload the DHET PQM approval: </b></td>
					<td class="oncolour"><?php $this->makeLink("doe_pqm_doc") ?></td>
				</tr>
				<tr>
					<td ALIGN=RIGHT valign="top"><b>Enter the date when the PQM application was made to DHET: </b></td>
					<td class="oncolour"><?php $this->showField("doe_pqm_date") ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>

	<?php 
		$displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'is_part_pqm', '1') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'is_part_pqm', '1') : "none";
	?>
	<tr>
		<td colspan="2">
			<div id="isNOT_part_pqm" style="display:<?php echo $displayStyle?>">
			<table cellpadding="2" cellspacing="2" align="right" width="100%" border="0">
				<tr>
					<td ALIGN=RIGHT valign="top" width="50%"><b>Have you applied for PQM approval for this programme with the DHET?</b></td>
					<td class="oncolour"><?php $this->showField("doe_pqm_lkp") ?></td>
				</tr>
			</table>

				<?php 
					$displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'doe_pqm_lkp', '2') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'doe_pqm_lkp', '2') : "none";
				?>
				<div id="doe_pqm_lkp_div" style="display:<?php echo $displayStyle?>">
				<br><br>
				</div>
			</div>
		</td>
	</tr>
<?php 
	} // end display if public
?>
	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>Date by which you plan to start offering the programme:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("prog_start_date") ?></td>
	</tr>

</table>
<br><br>
</td></tr></table>
<hr>

