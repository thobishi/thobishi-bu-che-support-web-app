<?php 
	$priv_publ = (isset($_POST["FLD_priv_publ"]))?($_POST["FLD_priv_publ"]):(false);
	$code = "";
	if ($priv_publ) {
		switch ($priv_publ) {
			case "2":
				$code = "PU";
				break;
			case "1":
				$code = "PR";
				break;
		}
		$code = $this->createInstitution_reference($code);
		$this->formFields["HEI_code"]->fieldValue = $code;
		$this->showField("HEI_code");
	}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>INSTITUTION INFORMATION:</b>
<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td align="right" width="30%"><b>Multi/Single:</b></td>
	<td class="oncolour" width="70%"><?php $this->showField("Multi_Single")?></td>
</tr>
<?php 
	if ($this->getFieldValue("priv_publ") == 2) {
?>
<tr>
	<td align="right"><b>University/Technikon:</b></td>
	<td class="oncolour"><?php $this->showField("Uni_tech") ?></td>
</tr><tr>
	<td align="right"><b>Historically Black:</b></td>
	<td class="oncolour"><?php $this->showField("Hist_black") ?></td>
</tr><tr>
	<td align="right"><b>Comprehensive:</b></td>
	<td class="oncolour"><?php $this->showField("Compre_h") ?></td>
</tr>
<?php 
	}
	
	if ($this->getFieldValue("priv_publ") == 1) {
?>
<tr>
	<td align="right"><b>National/International:</b></td>
	<td class="oncolour"><?php $this->showField("Nat_international") ?></td>
</tr><tr>
	<td align="right"><b>Type of Juristic Person:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("Type_of_juristic") ?></td>
</tr><tr>
	<td align="right"><b>DoE Registration number:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("doe_registration_number") ?></td>
</tr><tr>
	<td align="right"><b>Legal Name:</b></td>
	<td class="oncolour">&nbsp;<?php $this->showField("legal_name") ?></td>
</tr><tr>
	<td align="right"><b>Trading Name:</b> (if any)</td>
	<td class="oncolour">&nbsp;<?php $this->showField("trading_name") ?></td>
</tr>
<?php 
	}
?>
</table>
<br><br>
</td></tr></table>
