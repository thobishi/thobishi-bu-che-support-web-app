<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();

	//$message = $this->getTextContent ("processOutcomeAccredited", "Accredited");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		The outcome of this application is:
	</td>
</tr>
<tr>
	<td>
		<?php $this->displayOutcome($app_proc_id); ?>
	</td>
</tr>
<!--
<tr>
	<td>
		<b>The procedure for <b>accreditation</b> is as follows:</b>
		<ol>
			<li>The email below will be sent to the institution to notify them that their application has been accredited.</li>
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
