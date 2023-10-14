<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>INSTITUTIONAL INFORMATION:</b>
<br><br>
Please enter your institutional information for the following institution for re-accreditation purposes
<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td width="30%" align="right"><b>Institution Name:</b></td>
	<td class="oncolour">&nbsp;
	<?php
		$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $this->formFields["institution_ref"]->fieldValue, "HEI_name"); 
		if ($this->formFields["institution_name"]->fieldValue == "") $this->formFields["institution_name"]->fieldValue = $inst_name;
		$this->showField("institution_name");
	?>
	</td>
</tr>
</table>
<?php $this->showField("institution_ref"); ?>
<br><br>
</td></tr></table>
