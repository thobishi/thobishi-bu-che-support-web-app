<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();

	$message = $this->getTextContent ("acMeetingOutcome3", "Deferred outcome");

?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		The HEQC Board approved the recommendation of the Accreditation Committee that this programme be:
	</td>
</tr>
<tr>
	<td>
		<span class="visi">Deferred pending - get reason for deferral - get due date of deferral</span>
	</td>
</tr>
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
	<span class="specialb">Email to be sent to the instituional administrator.</span><i> You may edit the text to include additional information.</i>
<?php
		$this->formFields['email_content']->fieldValue = $message;
		$this->showfield('email_content');
?>
	</td>
</tr>
</table>
<br>
