<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	// 2012-06-07 Robin: Moved to checkForm1
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="2">
				Please confirm that the displayed proceedings is correct for this application.  The proceedings relates to the type of processing 
				taking place for the application e.g. application for accreditation or deferral.  You may go to menu option: <i>Accreditation</i> in
				order to view the processing that the application has already been through.
				<br><br>
			</td>
		</tr>
		<tr>
		<td class="visi" colspan="2"><span class="">Please note that the proceedings will form part of the AC Meeting documentation for this application.</span></td>
		</tr>
		<tr>
			<td class="oncolour">Record of proceedings relating to:</td>
			<td>
			<?php 
				$this->showField('lkp_proceedings_ref');
			?>				
			</td>
		</tr>
		<?php
		if ($this->formFields['lkp_proceedings_ref']->fieldValue == 4){  // conditional proceedings  ?>
			<?php if ($app_proc_id > 0){ ?>
			<tr>
				<td colspan="2">
					<br>
					<b>The following conditions will be processed during this proceedings:</b><br>
					<?php echo $this->displayConditions($app_proc_id, "proceeding"); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2">
					<br>
					<hr>
				</td>
			</tr>
			<tr>
			<td colspan="2">
				<b>List of all conditions for this application:</b><br>
				<?php echo $this->displayConditions($app_id, "application"); ?>
			</td>
		</tr>
		<?php } ?>
		</table>
	</td>
</tr>
</table>
