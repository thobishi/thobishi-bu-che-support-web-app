<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	// 2012-06-07 Robin: Moved to checkForm1
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
    $this->formFields["submission_date"]->fieldValue = $this->getValueFromTable("Institutions_application","application_id",$app_id,"submission_date");
    $this->showField("submission_date");
   	$is_at_manager = $this->getValueFromTable("screening", "application_ref", $app_id, "proc_to_manager");
	$grp = 7;  //Checklisting group
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="2">
<?php
			if ($is_at_manager == 0){
				$this->formActions["changeUser"]->actionMayShow = false;
				$instr = <<<TEXT
					Please complete the background for this application proceedings.  The proceedings relates to the type of processing taking place for 
					the application e.g. application for accreditation or deferral.
TEXT;
				echo $instr;
			} else {
				$instr = <<<TEXT
					Please check the background for this application proceedings.  If it is incomplete or erroneous please return it to your colleague.
TEXT;
				echo $instr;
			}
?>
			<br><br>
			</td>
		</tr>
		<tr>
		<td class="visi" colspan="2"><span class="">Please note that the background will form part of the AC Meeting documentation for this application.</span></td>
		</tr>
		<tr>
			<td class="oncolour">Record of proceedings relating to:</td>
			<td>
			<?php 
				// Set proceedings to apply for accreditation
				if ($this->formFields["lkp_proceedings_ref"]->fieldValue == 0){
					$this->formFields["lkp_proceedings_ref"]->fieldValue = 1;
				}
				$this->showField('lkp_proceedings_ref');
			?>				
			</td>
		</tr>
		<tr>
			<td valign="top" class="oncolour">Background</td>
			<td>
			<?php 
				if ($this->formFields['applic_background']->fieldValue == ""):
					$this->formFields["applic_background"]->fieldValue = $this->getApplicationBackground($app_id);
				endif;
				$this->showField('applic_background'); 
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				if ($is_at_manager == 1){
					$dd = $this->makeDropdownOfGroupUsers($grp);
			?>
					<hr>
					If returning the background:
					<br><br>
					Select the colleague to return it to: <?php echo $dd; ?>
					<br><br>
					Enter the instruction to email to your colleague with the background. Click on <span class="specialb">Return background to colleague</span> in the Actions menu.
					<?php $this->showField("request"); ?>
			<?php
				}
			?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
