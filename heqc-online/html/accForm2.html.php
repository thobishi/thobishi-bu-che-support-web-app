<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>INSTITUTION INFORMATION:</b>
<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr><td><span class="specialb">Prerequisites:</span></td></tr>
<tr><td>&nbsp;</td></tr></table>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<?php 
	$provider_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID, "priv_publ");
	switch($provider_type) {
		case "2":
?>
<b>Public providers:</b>
<br><br>
<ul>
<li>The programme is included in the Programme and Qualification Mix of the institution accepted by the DOE</li>
<li>The Qualification is registered at SAQA</li>
<li>Qualifications descriptors must comply with the New Academic Policy</li>
</ul>
<?php 
			break;
		case "1":
?>
<b>Private providers:</b>
<br><br>
<ul>
<li>Provider must be registered with the DoE</li>
<li>Qualification registered in SAQA</li>
<li>Qualifications descriptors must comply with the New Academic Policy</li>
</ul>
<?php 
			break;
	}
?>
</td>
</tr><tr>
	<td align="center"><?php $this->showField("prerequisites") ?></td>
</tr><tr>
	<td>
	<br><br><br>
	<span class="err">
	Unless you have completed the above you cannot proceed to the next step
	</span>
	<br><br><br>
	</td>
</tr></table>
</td></tr></table>
