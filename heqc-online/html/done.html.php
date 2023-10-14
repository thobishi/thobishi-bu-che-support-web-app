<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br><br>
	</td>
</tr><tr>
	<td>
		<br><br><b>Once you click on "Submit Application and Log out", your application will be sent to the HEQC Accreditation Directorate.</b>
		<br><br>Please use the following reference number in all future queries.
		<br><br> REFERENCE NO: <b><?php echo $this->getFieldValue("CHE_reference_code")?></b><br>
		<br><br>Please note that <font color="red">you are required to print your application form before submitting</font> for your institution's records.</b><br><br>
		You can view / print your application form by clicking on the "View / Print Application Form" in the actions menu.
<br><br><br><br></td></tr></table>

<script>
	var printed = '<?php echo $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "application_printed");?>';
</script>
