<br>
<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($reaccred_id); ?>
		</td>
	</tr>
	<tr>
	<td colspan="2" class="loud">2.3 Details of the person who will be the primary contact during the accreditation process.<hr><br></td>
	</tr>
	<tr>
	<td width="20%" valign="top"><b>Surname</b></td>
	<td><?php $this->showField("primary_contact_surname");?></td>
	</tr>
	<tr>
	<td width="20%" valign="top"><b>Name</b></td>
	<td><?php $this->showField("name");?></td>
	</tr>
	<tr>
	<td width="20%" valign="top"><b>Title</b></td>
	<td><?php $this->showField("Title");?></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>Designation</b></td>
		<td><?php $this->showField("designation");?></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>Postal address</b></td>
		<td><?php $this->showField("pos_address");?><br></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>Physical address</b></td>
		<td><?php $this->showField("phys_address");?><br></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>Fax no.</b></td>
		<td><?php $this->showField("fax_no");?><br></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>Telephone no.</b></td>
		<td><?php $this->showField("tel_no");?><br></td>
	</tr>
	<tr>
		<td width="20%" valign="top"><b>E-mail address.</b></td>
		<td><?php $this->showField("email_add");?><br></td>
	</tr>
</table>
<br>