<a name="application_form_question8"></a>
<br>
<?php 
	$site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<b>8. PROGRAM ADMINISTRATIVE SERVICES: (Criterion 8)</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
The programme has effective administrative services for providing information; managing the programme information system; dealing with a diverse student population; and ensuring the integrity of processes leading to certification of the qualification obtained through the programme.
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>8.1</b></td><td valign="top"><b>Outline the administrative services that the programme has in order to provide information, manage the programme information system and deal with the needs of the students and academics.</b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_1_administrativeservices_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>8.2</b></td><td valign="top"><b>Describe the administrative process to ensure the integrity of assessment practices and certification.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_2_administrativeprocesses_text") ?><br><br></td>
</tr>

</table>

</td></tr></table>
