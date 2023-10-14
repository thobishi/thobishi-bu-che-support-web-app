<?php 
//	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
//	$this->showField("application_ref");
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id)?>
		<br>

		<table width="85%" border=0  cellpadding="2" cellspacing="2">
		<tr>
			<td><b>The above re-accreditation application has been paid and may now begin the re-accreditation approval process.</b></td>
		</tr>
		<tr>
			<td><b>Click next to pass this application to the project administrator and notify them that it is ready for processing.</b></td>
		</tr>
		</table>
		<br>
		<br>
	</td>
</tr>
</table>

