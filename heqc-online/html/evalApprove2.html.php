<?php
$this->showInstitutionTableTop ();
$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
if ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "secretariat_doc") != 0)
{
	$this->createAction ("next", "End application workflow", "submit", "", "ico_next.gif");
}
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		If you, as manager, have approved this application then please mark it as ready for an AC meeting. This application can
		then be assigned to an AC Meeting and AC Members will have access to it via the AC Member Portal.  Please note that you
		may mark it as ready for an AC Meeting before you have uploaded the directorate recommendation.
		<br>
		<br>
	</td>
</tr>
<tr>
	<td>
	<?php $this->showField('application_status');?>
	<span class="visi">
	If this application is approved, please check this box to indicate that it may be assigned to an AC Meeting.
	<?php $this->showField("readyForACMeeting");?>
	</span>
	<br>
	<br>
	</td>
</tr>
<tr>
<td>
	Please upload the directorate recommendation for this application below. Please upload this as soon as possible, as once this
	document has been uploaded, AC members will have immediate access to it online.
	<br><br>
	Once you have uploaded this document, please click "End application workflow" to close off this process and remove it from
	your list of active processes.
	<br>
	<br>
<?php 
	$this->makeLink("secretariat_doc");
?>
	<br>
</td>
</tr>
</table>

