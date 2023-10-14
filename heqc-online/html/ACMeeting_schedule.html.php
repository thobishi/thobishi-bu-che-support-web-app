<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Search for Evaluators:</span></td>
</tr><tr>
	<td colspan="6">On the selection of appropriate evaluators rests the strength of the outcome of the Accreditation Phase of the accreditation process.  The Evaluators database will help you choose evaluators for this programme. In making your selection:
<ul>
<li>You need to choose 3 subject specialists one of whom will become the chair of the evaluation process and will be responsible for submitting a final evaluation report to the HEQC.</li>
<li>In choosing evaluators you need to take into account how many programmes a particular evaluator is currently busy with.</li>
<li>Choose evaluators who have undergone training and are familiar with HEQC procedures.</li>
<li>In the case of professional programmes involving ETQAs and professional associations with which the HEQC has
cooperation agreements, make sure that you choose evaluators recognized by the ETQA/professional association.</li>
</ul>
</td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td align="right"><b>Available:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("active") ?></td>
	<td align="right"><b>A-Rated:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("A_rated") ?></td>
	<td align="right"><b>Full/Part Time:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Full_part") ?></td>
</tr>
<tr>
	<td align="right"><b>Race:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Race") ?></td>
	<td align="right"><b>Gender:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Gender") ?></td>
	<td align="right"><b>Disability:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Disability") ?></td>
</tr>
<tr>
	<td align="right"><b>Province:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Province") ?></td>
	<td align="right"><b>Sector:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Eval_sector_ref") ?></td>
	<td align="right"><b>Organisation Type:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Organisation_type_ref") ?></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td align="right"><b>Highest Qualification:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("qualifications_ref") ?></td>
	<td align="right"><b>Teaching Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
	<td align="right"><b>Research Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search for:</b></td>
	<td colspan="4" align="left" class="oncolour">
		<?php $this->showField("National_Review_Evaluator") ?>&nbsp;<b>National Review Evaluator</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Auditor") ?>&nbsp;<b>Auditor</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Evaluator") ?>&nbsp;<b>Evaluator</b>&nbsp;
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Institution:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("employer_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Institution Type:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("Employer_type_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Historical Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("historical_status_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Merge Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("merged_status_ref") ?></td>
</tr>
<tr>
<td colspan="2" align="right"><b>ETQA:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("ETQA_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Main CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code1") ?>
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Sub CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code2") ?>
	</td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search:</b> (by Name)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search:</b> (by Job Title)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText1") ?></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4">
	<input class="btn" type="button" value="Search" onClick="doSearch();">
	</td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
</table>
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
			each evaluator will launch a new window with more information on the specific evaluator.
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
	<table width="400" align="center" cellpadding="2" cellspacing="2" border="0">
	<tr>
		<td>
			i) <b>Search Results:</b>
			<br>
			<IFRAME width="100%" id="resultsFrame" name="resultsFrame" src="" style="height:150"></IFRAME>
		</td>
	</tr>
	</table>
</td>
<td width="30" align="center">
<img src="images/insert.gif" width="25" height="16" alt="">
<br>
<a href="javascript:removeSelectEntries(document.defaultFrm.elements['FLDS_resultsSelect[]']);"><img src="images/btn_remove.gif" width="33" height="22" alt="Remove" border="0"></a>
</td>
<td class="oncolour" valign="top" width="40%">
	<table width="200" align="center" cellpadding="2" cellspacing="2" border="0"><tr>
		<td>ii) <b>Selected evaluators:</b><br><?php $this->showField("resultsSelect") ?></td>
	</tr></table>
</td>
</tr></table>
</td></tr></table>
