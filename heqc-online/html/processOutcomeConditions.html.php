<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	
	$table = $this->dbTableCurrent;
	$keyFLD = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField;
	$keyVal = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;

	if(isset($table))
		$_SESSION["ses_table"] = $table;
	if(isset($table))
		$_SESSION["ses_keyFLD"] = $keyFLD;
	if(isset($table))
		$_SESSION["ses_keyVal"] = $keyVal;
	
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	
	$table = $this->dbTableCurrent;
	$keyFLD = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField;
	$keyVal = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;

	if(isset($table))
		$_SESSION["ses_table"] = $table;
	if(isset($table))
		$_SESSION["ses_keyFLD"] = $keyFLD;
	if(isset($table))
		$_SESSION["ses_keyVal"] = $keyVal;
	
	
	$this->showInstitutionTableTop ();
	//$message = $this->getTextContent ("processOutcomeConditions", "Conditional outcome");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<br>
		The HEQC Board approved the recommendation of the Accreditation Committee that this programme be:
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php $this->displayOutcome($app_proc_id); ?>
	</td>
</tr>
<tr>
	<td colspan="2">
	<br/>
	The institution has until the due date above to email you the compliance with conditions information. Please upload the compliance with conditions documents received from the institution.
	</td>
</tr>
<tr>
	<td>
		Upload the compliance with conditions documents<br/> received from the institution: 
	</td>
	<td>
		<?php $this->makeLink('condition_doc'); ?>
	</td>
</tr>
<tr>
	<td>
		Date that the conditions document was received: 
	</td>
	<td>
		<?php $this->showField('conditions_submission_date'); ?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<hr>
	</td>
</tr>
<!--
<tr>
	<td>
		<b>The procedure for conditional accreditation is as follows:</b>
		<ol>
			<li>The email below will be sent to the institution to notify them of the provisional accreditation with conditions.</li>
			<li>
				The institution will receive a conditional accreditation process for this application in the HEQC-Online system.  They may
				login, upload any required information and submit it to CHE using this process.
			</li>
			<li>
				This process will be closed when you click on Next.
			</li>
		</ol>
	</td>
</tr>
<tr>
	<td>
	<span class="specialb">Email to be sent to the institutional administrator.</span><i> You may edit the text to include additional information.</i>
<?php
		//$this->formFields['email_content']->fieldValue = $message;
		//$this->showfield('email_content');
?>
	</td>
</tr>
-->
</table>
<br>
