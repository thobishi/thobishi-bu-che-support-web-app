<?php $this->showInstitutionTableTop ();?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Search for Evaluators:</span></td>
</tr><tr>
	<td colspan="6">On the selection of appropriate evaluators rests the strength of the outcome of the Accreditation Phase of the accreditation process.  The Evaluators database will help you choose evaluators for this programme. In making your selection:
<ul>
<li>Always choose more evaluators than you need, so you do not have to repeat the process.</li>
<li>You need to choose 3 subject specialists and 2 QA managers.</li>
<li>Choose evaluators who have undergone training and are familiar with HEQC procedures.</li>
<li>In the case of professional programmes involving ETQAs and professional associations with which the HEQC has
cooperation agreements, make sure that you choose evaluators recognized by the ETQA/professional association.</li>
</ul>
</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td align="right"><b>Race:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("race") ?></td>
	<td align="right"><b>Gender:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("gender") ?></td>
	<td align="right"><b>Disability:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("disibility") ?></td>
</tr><tr>
	<td align="right"><b>Province:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("province") ?></td>
	<td align="right"><b>Sector:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("sector") ?></td>
	<td align="right"><b>Full/Part Time:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("full_part") ?></td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td align="right"><b>Teaching Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
	<td align="right"><b>Research Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Research_experience") ?></td>
	<td align="right"><b>Audit Evaluation Experience:</b>&nbsp;</td><td class="oncolour"><?php $this->showField("Audit_Eval_Experience") ?></td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="right"><b>ETQA:</b>&nbsp;</td><td class="oncolour" colspan="4"><?php $this->showField("ETQA_ref") ?></td>
</tr><tr>
	<td colspan="2" align="right"><b>CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->formFields["CESM_code"]->fieldValue = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "CESM_code1");
		$this->showField("CESM_code") ?>
	</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr><tr>
	<td colspan="2" align="right"><b>Search:</b> (by Name)</td><td class="oncolour" colspan="4"><?php $this->showField("searchText") ?></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4">
	<input type="Hidden" name="app_ref" value="<?php echo $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID?>">
	<input class="btn" type="button" value="Search" onClick="doSearch();">
	</td>
</tr><tr>
	<td colspan="6">&nbsp;</td>
</tr></table>
</FORM>
<?php 
$this->formAction = "?";
$this->formTarget = "";
$this->formName = "defaultFrm";

$this->createForm();
?>
<table width="550" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="center">
	<fieldset class="go">
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<span class="msgn">
			Your search results will be displayed in box (i) below.
			<br><br>
			The <img src="images/info_off.png" width="16" height="15" alt="Information button"> button  next to
			each evaluator will launch a new window with more information on the specific Evaluator.
			From within the new window, you will be able to add the Evaluator to the Accreditation Process.
			</span>
			</td>
		</tr>
	</table>
	</fieldset>
	<br>
	</td>
	<td>&nbsp;</td>
	<td align="center">
	<fieldset class="go">
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<span class="msgn">
			The evaluators that you select to be part of the Accreditation Process will be displayed in box (ii) below.
			<br><br>
			To remove an evaluator from the list,
			click the remove button. <br>
			Example remove button: &nbsp;<img src="images/btn_remove_off.gif" width="33" height="22" alt="Remove">
			</span>
			</td>
		</tr>
	</table>
	</fieldset>
	<br>
	</td>
</tr>
<tr>
<td class="oncolour" width="55%" valign="top">
	<table width="300" align="center" cellpadding="2" cellspacing="2" border="0"><tr>
		<td>i) <b>Search Results:</b>
		<br>
		<IFRAME id="resultsFrame" name="resultsFrame" src="" style="height:150"></IFRAME></td>
	</tr></table>
</td>
<td width="30" align="center">
</td>
<td class="oncolour" valign="top" width="40%">
	<table width="220" align="center" cellpadding="2" cellspacing="2" border="0"><tr>
		<td>ii) <b>Selected evaluator:</b><br><input type="hidden" name="eval_id"><input type="text" readonly name="eval_display"></td>
	</tr></table>
</td>
</tr></table>
</td></tr></table>
