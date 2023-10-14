<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	The displayed letter of appointment may be sent to the recommendation user.
	<b>To send the letter of appointment email to the user click on the checkbox under the heading Send Email.</b>
	<br>
	<br>
	The following user has been selected to do the Directorate Recommendation for this application.
	<br>
	<br>
	<?php
	$html = '<table width="80%">';
	$html .= <<<html
		<tr class="oncolour">
			<td><b>User name</b></td>
			<td><b>Email address</b></td>
			<td><b>Phone numbers</b></td>
			<td><b>Email Sent?</b></td>
			<td><b>Date sent</b></td>
			<td><b>Send Email?</b></td>
		</tr>
html;
	$ru = $this->getSelectedRecommUserForAppProceeding($app_proc_id);

	$sent = ($ru["lop_isSent"] == 1) ? "Yes" : "&nbsp";
	$date_sent = $ru["lop_isSent_date"];

	$html .= <<<html
	<tr>
		<td>$ru[user_name]</td>
		<td>$ru[email]</td>
		<td>$ru[contact_nr] $ru[contact_cell_nr]</td>
		<td>$sent</td>
		<td>$date_sent</td>
		<td><input name="chkRecomm$ru[user_id]" type="Checkbox"></td>
	</tr>
html;
	$html .= "</table>";

	echo $html;
	?>
	</td>
</tr>
	
<tr>
	<td>&nbsp;</td>
</tr>

<tr>
	<td><b>The following letter of appointment will be sent to recommendation user, if checked:</b>
	</td>
</tr>

<tr>
	<td>
	<?php 
	$this->formFields['recommendation_appointment_email']->fieldValue = $this->getTextContent("recommSelect2", "Letter of appointment for recommendation");
	$this->showfield('recommendation_appointment_email');
	?>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>

</table>
