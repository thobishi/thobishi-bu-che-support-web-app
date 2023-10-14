<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td colspan="2">
	<b>Contact Information:</b>
	</td>
</tr>
<?php 
for ($i=0; $i < count($site_arr); $i++) {
		$this->formFields["surname"]->fieldValue = $site_arr[$i]["surname"];
		$this->formFields["name"]->fieldValue = $site_arr[$i]["name"];
		$this->formFields["title_ref"]->fieldValue = $site_arr[$i]["title"];
		$this->formFields["email"]->fieldValue = $site_arr[$i]["email"];
		$this->formFields["contact_nr"]->fieldValue = $site_arr[$i]["contact_nr"];
		$this->formFields["contact_fax_nr"]->fieldValue = $site_arr[$i]["contact_fax_nr"];
?>
<tr>
	<td colspan="2"><b>Site: <?php echo $site_arr[$i]["site_name"];?> - <?php echo $site_arr[$i]["location"];?></b></td>
</tr><tr>
	<td width="30%" align="right"><b>Site Address:</b></td>
	<td class="oncolour"><?php echo $site_arr[$i]["address"];?></td>
</tr><tr>
	<td width="30%" align="right"><b>Postal Address:</b></td>
	<td class="oncolour"><?php echo $site_arr[$i]["postal_address"];?></td>
</tr><tr>
	<td width="30%" align="right"><b>Surname:</b></td>
	<td class="oncolour"><?php $this->showField("surname")?></td>
</tr><tr>
	<td align="right"><b>Name:</b> </td>
	<td class="oncolour"><?php $this->showField("name")?></td>
</tr><tr>
	<td align="right"><b>Title:</b> </td>
	<td class="oncolour"><?php echo $this->showField("title_ref")?></td>
</tr><tr>
	<td align="right"><b>User E-mail:</b> </td>
	<td class="oncolour"><?php $this->showField("email")?></td>
</tr><tr>
	<td align="right"><b>Contact No:</b> </td>
	<td class="oncolour"><?php $this->showField("contact_nr")?></td>
</tr><tr>
	<td align="right"><b>Contact Fax No:</b> </td>
	<td class="oncolour"><?php $this->showField("contact_fax_nr")?></td>
</tr>
<?php 
}
?>
</table>
</td></tr></table>
