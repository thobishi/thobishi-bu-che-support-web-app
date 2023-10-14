<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">

		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id)?>
		<br>

	</td>
	</tr>
	<tr>
	<td width="50%">
		Please select which type of evaluation will take place on this application, so that the correct email template text may be added to the evaluator emails:
	</td>
	<td valign="top">
		<?php $this->showField("evaluationType");?>
	</td>
</tr>
</table>
