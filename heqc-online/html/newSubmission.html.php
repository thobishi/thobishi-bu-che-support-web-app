
<?php 



			$this->formFields["submission_date"]->fieldValue = date("Y/m/d");
	$this->showField("submission_date");

	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->showField("application_ref");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="85%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td><b>An application has been submitted for Accreditation.</b></td>
</tr>
<tr>
	<td>
	<br>
	<b>PRIVATE INSTITUTION:</b>
	<ul>
		<li>The system will check whether the institution has already paid for this programme to be accredited.</li>
		<li>If the Institution has paid then this application will go to the Checklisting process.
		<li>If the Institution has not paid then this application will go to the Payments process.
	</ul>
	<b>PUBLIC INSTITUTION</b>:
	<ul>
		<li>The application will go directly to the Checklisting process.</li>
	</ul>	
	<br>
	To continue with the accreditation process for this application click on the "Next" link on the Actions menu.
	</td>
</tr></table>
<br><br>
</td></tr></table>

