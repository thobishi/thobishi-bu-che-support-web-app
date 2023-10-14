<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
<td colspan="2" class="loud"><b>2.16</b> Fulfillment of conditions<br><hr></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
	<td valign="top"><i>Please give details of all conditions (accreditation and re-accreditation) set by HEQC per programme and evidence that these have been fulfilled.</i> <br><br></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td valign="top"><b>Evidence of fulfillment of institutional and programme-specific conditions.</b><br></td>
</tr>
<tr>
 	<td colspan="2" valign="top">

	<table border="1" width="95%">
	<tr><td>Conditions</td><td>Evidence of fulfilment</td></tr>
	<tr><td>Institutional</td><td><?php $this->showField("AC_conditions_fulfilled_inst"); ?></td></tr>
	<tr><td>Programme-specific</td><td><?php $this->showField("AC_conditions_fulfilled_program"); ?></td></tr>
	</table>

	</td>
</tr>
</table>
<fieldset >
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
		<br>

		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td>
			<ul>
				<li><b>Upload any documentation which will indicate evidence of fulfilling conditions.</b>
				<br>
				<?php $this->makeLink("AC_conditions_fulfilled_doc") ?></li>
			</ul>
			</td>
		</tr>
		</table>

		<br>
</fieldset>