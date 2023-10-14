<br>
<table width='95%' border='0' align='center'>
<tr>
<td>
Please note that the new user must use their email address to login to the system.  If they are logging in for the first time
then they need to click on Forgot Password and the system will immediately notify them of their password via email.
<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td>Title:</td>
		<td><?php echo $this->showField("title_ref")?></td>
	</tr>
	<tr>
		<td>Name:</td>
		<td><?php echo $this->showField("name")?></td>
	</tr>
	<tr>
		<td>Surname:</td>
		<td><?php echo $this->showField("surname")?></td>
	</tr>
	<tr>
		<td>E-mail:</td>
		<td><?php echo $this->showField("email")?></td>
	</tr>
	<tr>
		<td>Institution:</td>
		<td><?
			if ($this->formFields["institution_name"]->fieldValue == "") $this->formFields["institution_name"]->fieldValue = "Council of Higher Education";
			echo $this->showField("institution_name");
			?>
		</td>
	</tr>
	<tr>
		<td>Programme:</td>
		<td><?echo $this->showField("institution_ref"); ?></td>
	</tr>
	<tr>
		<td>Contact Number:</td>
		<td><?php echo $this->showField("contact_nr")?></td>
	</tr>
	<tr>
		<td>Status:</td>
		<td><?php echo $this->showField("active")?></td>
	</tr>
	</table>
</td></tr></table>
</td></tr>
</table>
<br>