<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop (); ?>
<?php $this->showField("application_ref"); ?>
<?php $this->showField("invoice_total"); ?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br><br>
	<b>Private providers are charged for the accreditation of their programmes.
	Providers have to pay the total costs of the accreditation process as invoiced to them for the results of
	the evaluation to be released to them. You need to calculate the accreditation fee for the above provider,
	should a site visit be necessary, the provider will be invoiced with the direct costs of travel,
	accommodation and subsistence of the evaluators once the site visit has taken place. At this stage
	the provider is expected to pay only the fee for programme accreditation and the correspondent fee
	for each extra site of delivery at which the programme will be offered.
	</b>
	</td>
</tr><tr>
	<td align="center"><b>Calculate Payment:</b></td>
</tr></table>
<br>
<table align="center" width="70%">
<tr>
	<td align="right">Programme accreditation: </td>
	<td colspan="2"><?php $this->showField("programme_fee")?></td>
	<td>&nbsp;</td>
</tr>
<?php 
// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
// RTN 24/10/2007 - Re-instate payment per additional site that the programme is offered at.
?>
<tr>
	<td align="right">Programme fee for additional site of delivery:</td>
	<td colspan="2"><?php $this->showField("prog_fee_additional_sites"); ?></td>
</tr>
<tr>
	<td align="right" valign="top">This fee includes the following sites:</td>
	<td valign="top" colspan="2"><?php echo (($sites > "")?($sites):("-- No additional sites --"))?></td>
</tr>
<tr>
	<td align="right">
	<b>New providers only:</b>
	</td>
	<td colspan="2">
	<b>&nbsp;</b>
	</td>
</tr>
<tr>
	<td align="right">Fee for accreditation providers:</td>
	<td><?php $this->showField("new_inst_fee"); ?></td>
	<td>&nbsp;</td>
</tr>

</table>
</td></tr></table>


