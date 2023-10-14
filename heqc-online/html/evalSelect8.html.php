<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

<?php
$this->showInstitutionTableTop ();
$this->formFields['request_status']->fieldValue = 2; // Indicates that request has been sent. Will no longer be allowed to edit.
$this->showField('request_status');
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
	<td>
	The following email will be sent to the Institutional Administrator when you click on <b>Email request and return to requests</b>:
	</td>
</tr>

<tr>
	<td valign="top"> <br><span class="loud">Request to Institutional Administrator:</span></td>
</tr>

<tr>
	<td align="center">
	<?php
 	$this->formFields["request_email"]->fieldValue = $this->getTextContent("evalSelect8", "Request for additional information");
	$this->showField('request_email');
	?>
	</td>
</tr>

</table>


</td>
</tr>
</table>
