<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
	<b>A) INSTITUTION INFORMATION</b>
	<br><br>
	<b>Section A</b> of the application form requires you to complete an institutional profile. You will need to do this the first time you enter the system. After this, the profile should be updated once a year.

<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td width="30%" align="right"><b>Institution Name:</b></td>
	<td width="70%" class="oncolour">&nbsp;<?php $this->showField("HEI_name") ?></td>
</tr>

<?php /*
	BUG:
<tr>
	<td align="right"><b>Private/Public:</b></td>
	<td class="oncolour"><?php $this->showField("priv_publ") ?></td>
</tr>
*/ ?>
<tr>
	<td colspan="2">
	<b>Prerequisites for</b>
	<div id="public_priv" style="display:none">
	<b>Public providers:</b><br>
		<ul>
			<li>Programme is part of the <i>Programme and Qualification Mix</i> accepted by DHET</li>
			<li>Qualification is registered with SAQA</li>
		</ul>
	</div>
	<div id="private_priv" style="display:none">
	<b>Private providers:</b><br>
		<ul>
			<li>Provider must be registered with the DHET</li>
			<li>Qualification must be registered with SAQA on the NQF</li>
		</ul>
	</div>
	</td>
</tr></table>
<br><br>
<script>
<?php 
	if ($filled_in == 1) {
//		echo 'showHide(document.all.private_priv);';
//		2010-01-05 Robin: standards compliant
		echo 'showHide(document.getElementById("private_priv"));';
	}
	if ($filled_in == 2) {
//		echo 'showHide(document.all.public_priv);';
		echo 'showHide(document.getElementById("public_priv"));';
	}
?>
</script>
</td></tr></table>
