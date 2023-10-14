	<br>
<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($app_id); }

	$prov_type = HEQConline::checkAppPrivPubl($app_id);

	$this->formFields["institution_id"]->fieldValue = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$this->showField("institution_id");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<table width="95%" border=0 align="right" cellpadding="2" cellspacing="2">
<tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Programme Type:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("prog_type") ?></td>
</tr>
<?php 
	$senate_msg = ($prov_type == 1) ? "Has the programme been approved by the relevant governance structure within the institution?" : "Has the programme been approved by Senate?";
?>
<tr>
<td ALIGN=RIGHT valign="top" width="50%"><b><?php echo $senate_msg?></b></td>
<td valign="top" class="oncolour"><?php $this->showField("senate_approved") ?></td>
</tr>

<tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Date of approval:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("senate_approved_date") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Qualification Designation (e.g. BSc or Diploma) :</b></td>
<td valign="top" class="oncolour"><?php $this->showField("designation") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>First Qualifier (e.g. Chemistry or Web Design) :</b></td>
<td valign="top" class="oncolour"><?php $this->showField("1st_qualifier") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Second Qualifier (e.g. Organic Chemistry or 3D) :</b></td>
<td valign="top" class="oncolour"><?php $this->showField("2nd_qualifier") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>CESM Classification (e.g. Education):</b></td>
<td valign="top" class="oncolour"><?php $this->showField("CESM_code1") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>NQF Level:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("NQF_ref") ?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Number of Credits:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("num_credits");?></td>
</tr><tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Minimum duration (years) for completion - Full Time:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("full_time") ?></td>
</tr>
<tr>
<td ALIGN=RIGHT valign="top" width="50%"><b>Minimum duration (years) for completion - Part Time:</b></td>
<td valign="top" class="oncolour"><?php $this->showField("part_time") ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr><tr>
<td ALIGN=LEFT valign="top"><b><span class="speciale">Status:</span></b></td>
<td>&nbsp;</td>
</tr>

<?php 
	if ($prov_type == 1) {  //display if private
?>

	<!--DOE fields-->
	<tr><td colspan="2"><hr></td></tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="50%"><b>Have you applied for registration with the DoE for this programme?</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("is_reg_doe") ?></td>
	</tr>

	<tr>
		<td colspan="2" align="right">
				<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_doe', '2') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_doe', '2') : "none"; ?>
				<div id="is_reg_doe" style="display:<?php echo $displayStyle?>">
				<table cellpadding="2" cellspacing="2" align="right" width="100%">
				<tr>
					<td ALIGN=RIGHT valign="top" width="50%"><b>Existing providers: DoE Registration Number:</b></td>
					<td valign="top" class="oncolour"><?php $this->showField("doe_reg_nr") ?></td>
				</tr>
				<tr>
					<td ALIGN=RIGHT valign="top"><b>Please upload the DoE Registration Certificate:</b></td>
					<td valign="top" class="oncolour"><?php $this->makeLink("doe_registration_certificate_doc");?></td>
				</tr>
				</table>
				</div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="right">
			<?php $displayStyle = ( $this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_doe', '3') != "") ?  $this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_doe', '3') : "none"; ?>
			<div id="is_reg_doe2" style="display:<?php echo $displayStyle?>">
					<table cellpadding="2" cellspacing="2" align="right" width="100%">
						<tr>
							<td ALIGN=RIGHT valign="top" width="50%"><b>Please enter the date when application was made to DoE:</b></div></td>
							<td valign="top" class="oncolour"><?php $this->showField("doe_appl_date") ?></td>
						</tr>
					</table>
			</div>
		</td>
	</tr>
<?php 
	}	//end display if private
?>

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
					<td ALIGN=RIGHT valign="top" width="50%"><b>Upload the DoE PQM approval: </b></td>
					<td class="oncolour"><?php $this->makeLink("doe_pqm_doc") ?></td>
				</tr>
				<tr>
					<td ALIGN=RIGHT valign="top"><b>Enter the date when the PQM application was made to DoE: </b></td>
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
					<td ALIGN=RIGHT valign="top" width="50%"><b>Have you applied for PQM approval for this programme with the DoE?</b></td>
					<td class="oncolour"><?php $this->showField("doe_pqm_lkp") ?></td>
				</tr>
			</table>

				<?php 
					$displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'doe_pqm_lkp', '2') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'doe_pqm_lkp', '2') : "none";
				?>
				<div id="doe_pqm_lkp_div" style="display:<?php echo $displayStyle?>">
				<br><br>

<?php 
	} // end display if public
	if ($prov_type == 1) {
		echo "<tr><td colspan='2'>";
	}
?>
					<!--SAQA fields-->
					<table cellpadding="2" cellspacing="2" align="right" width="100%" border="0">

					<tr><td colspan="2"><hr></td></tr>

					<tr>
					<td ALIGN=RIGHT valign="top" width="50%"><b>Is the qualification registered by SAQA on the NQF?</b></td>
					<td valign="top" class="oncolour"><?php $this->showField("is_reg_saqa_nqf") ?></td>
					</tr>

					<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_saqa_nqf', '2') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_saqa_nqf', '2') : "none"; ?>
					<tr>
						<td colspan="2" align="right">
							<div id="is_reg_saqa_nqf" style="display:<?php echo $displayStyle?>">
								<table cellpadding="2" cellspacing="2" align="right" width="100%">
									<tr>
										<td ALIGN=RIGHT valign="top" width="50%"><b>SAQA Registration Number:</b></td>
										<td valign="top" class="oncolour"><?php $this->showField("saqa_reg_nr") ?></td>
									</tr>
									<tr>
										<td ALIGN=RIGHT valign="top"><b>Please upload the SAQA Registration Certificate</b><br><i>(optional)</i></td>
										<td valign="top" class="oncolour"><?php $this->makeLink("saqa_registration_certificate_doc");?></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2" align="right">
							<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_saqa_nqf', '3') != "") ? $this->displayifConditionMetInstitutions_applications($app_id, 'is_reg_saqa_nqf', '3') : "none"; ?>
							<div id="is_reg_saqa_nqf2" style="display:<?php echo $displayStyle?>">
									<table cellpadding="2" cellspacing="2" align="right" width="100%">
										<tr>
											<td ALIGN=RIGHT valign="top" width="50%"><b>Please enter the date when application was made to SAQA:</b></td>
											<td valign="top" class="oncolour"><?php $this->showField("saqa_appl_date") ?></td>
										</tr>
									</table>
							</div>
						</td>
					</tr>

				</table>
<?php 
	//if provider is public, display the following
	if ($prov_type == 2) {
?>
				</div>
			</div>
<?php 
	} // end display if public
	//display following for both
?>
		</td>
	</tr>

	<tr><td colspan="2"><hr></td></tr>
	<tr>
		<td ALIGN=RIGHT valign="top" width="50%"><b>Date by which you plan to start offering the programme:</b></td>
		<td valign="top" class="oncolour"><?php $this->showField("prog_start_date") ?></td>
	</tr>

</table>
<br><br>
</td></tr></table>
<hr>
