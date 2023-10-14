<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="3">
			<span class="loud">Manage Users > Add / Edit User</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			Please note that the new user must use their email address to login to the system.
			If they are logging in for the first time then they need to click on Forgot Password and the system will immediately notify them of their password via email.
			<br><br>
		</td>
	</tr>
	<tr>
		<td>
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
				<td>Registration Date:</td>
				<td><?php echo $this->showField("registration_date")?></td>
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

		</td>
	</tr>
</table>

<br>