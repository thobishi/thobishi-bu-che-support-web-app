<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	//$message = $this->getTextContent ("processOutcome1", "Deferred outcome");

	// Indicate that next proceedings will be started because a document is uploaded.
	// 2016-02-12 Remove this restriction because deferred proceedings should not be closed - They aren't finished being processed.
	// 	- Sometimes an application is deferred for a second evaluation and no documents are required from the institution.
	//if ($this->formFields['deferral_doc']->fieldValue > 0){
		$this->formActions["next"]->actionDesc = 'End this proceedings. Continue to next user to start deferral proceedings';
	//}
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		The outcome of the proceedings for this programme is:
	</td>
</tr>
<tr>
	<td>
		<?php $this->displayOutcome($app_proc_id); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
		Are deferral documents expected from the institution? <?php $this->showField('deferral_ind'); ?>	
	</td>
</tr>
<tr>
	<td>
		<br />
		The institution has until the due date above to email you the deferral information.
		Please upload the deferral documents received from the institution.
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td><b>Deferral documents received<br /> from the institution</b></td><td><?php $this->makeLink('deferral_doc'); ?></td>
		</tr>	
		</table>
	</td>
</tr>
<!--
<tr>
	<td>
		<b>The Deferral procedure is as follows:</b>
		<ol>
			<li>The email below will be sent to the institution to notify them of the deferred outcome.</li>
			<li>
				The institution will receive a deferral process for this application in the HEQC-Online system.  They may
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
