<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	// The process may only proceed if the total invoice amount has been paid and the process is with the payment user.
	if ($this->formFields["received_confirmation"]->fieldValue == 0 || $this->currentUserID <> $this->getDBsettingsValue("usr_registry_payment")) {
		$this->formActions["next"]->actionMayShow = false;
	}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">
<?php
			if ($this->formFields["received_confirmation"]->fieldValue == 0){
				echo "This application has not been paid in full.  The process may only continue when paid.";
			}
			if ($this->formFields["received_confirmation"]->fieldValue == 1){
				echo "This application has been paid. Click next to proceed.";
			}
?>			
		</span>
		<br>
	</td>
</tr>
<tr>
	<td>
	</td>
</tr>
</table>

