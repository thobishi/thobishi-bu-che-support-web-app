<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Search for Evaluators:</span></td>
</tr>
<tr><td>
On the selection of appropriate evaluators rests the strength of the outcome of the Accreditation Phase of the accreditation process.  The Evaluators database will help you choose evaluators for this programme. In making your selection:
<ul>
<li>You need to choose 3 subject specialists one of whom will become the chair of the evaluation process and will be responsible for submitting a final evaluation report to the HEQC.</li>
<li>In choosing evaluators you need to take into account how many programmes a particular evaluator is currently busy with.</li>
<li>Choose evaluators who have undergone training and are familiar with HEQC procedures.</li>
<li>In the case of professional programmes involving ETQAs and professional associations with which the HEQC has
cooperation agreements, make sure that you choose evaluators recognized by the ETQA/professional association.</li>
</ul>
</td></tr>
<br>

<tr><td>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="center" colspan="3">
	<fieldset class="go">
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td>
				<span class="msgn">
				<ul>
				<li>Your search results will be displayed in the Search Results box (i) below.</li>
				<li>The <img src="images/info_off.png" width="16" height="15" alt="Information button"> button next to each Evaluator will launch a new window with more information on the specific Evaluator. From within the new window, you will be able to add the Evaluator to the Accreditation Process.</li>
				<li>The Evaluators that you select to be part of the Accreditation Process will be displayed in the Selected Evaluators box (ii) below.</li>
				<li>To remove an Evaluator from the list, click the <img src="images/btn_remove_off.gif" width="33" height="22" alt="Remove"> button.</li>
				</ul>
				</span>
				</td>
			</tr>
		</table>
	</fieldset>
</tr>

<tr>
	<td class="oncolour" width="55%" valign="top">

	<table width="100%" cellpadding="2" cellspacing="2" border="0">
	<tr align="center">
		<td width="70%">
			i) <b>Search Results:</b>
			<br>
			<IFRAME width="100%" id="resultsFrame" name="resultsFrame" src="" style="height:150"></IFRAME>
		</td>



	<td width="5%" align="center">
		<img src="images/insert.gif" width="25" height="16" alt="">
		<br>
		<a href="javascript:removeSelectEntries(document.defaultFrm.elements['FLDS_resultsSelect[]']);"><img src="images/btn_remove.gif" width="33" height="22" alt="Remove" border="0"></a>
	</td>

	<td class="oncolour" valign="top" width="40%" align="left">
		ii) <b>Selected Evaluators:</b><br><?php $this->showField("resultsSelect") ?>
	</td>

	</tr>
	</table>

	</td>
</tr>

<tr><td colspan="3">
<br><br>
</td></tr>

</table>


<tr><td>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr><td colspan="3">
<span class="loud">Basic criteria:</span>
<hr>
</td></tr>

<tr>
	<td align="right" colspan="2"><b>Search for:</b></td>
	<td align="left" class="oncolour" colspan="4">
		<?php $this->showField("National_Review_Evaluator") ?>&nbsp;<b>National Review Evaluator</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Auditor") ?>&nbsp;<b>Auditor</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Evaluator") ?>&nbsp;<b>Evaluator</b>&nbsp;
	</td>
</tr>
<tr>
	<td align="right" colspan="2"><b>Search:</b> (by Name)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText") ?></td>
</tr>

<tr>
	<td align="right" colspan="2"><b>Institution:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("employer_ref") ?></td>
</tr>

<tr class="oncolour">
	<td align="right" align="17%"><b>Available:</b>&nbsp;</td>
	<td  align="16%"><?php $this->showField("active") ?></td>
	<td align="right" align="17%"><b>Race:</b>&nbsp;</td>
	<td class="oncolour" align="17%"><?php $this->showField("Race") ?></td>
	<td align="right" align="17%"><b>Gender:</b>&nbsp;</td>
	<td class="oncolour" align="17%"><?php $this->showField("Gender") ?></td>
</tr>

<tr class="oncolour">
	<td align="right" align="16%"><b>Disability:</b>&nbsp;</td>
	<td class="oncolour" align="16%"><?php $this->showField("Disability") ?></td>
	<td align="right" align="16%"><b>Province:</b>&nbsp;</td>
	<td class="oncolour" align="16%"><?php $this->showField("Province") ?></td>
	<td align="right" align="16%"><b>Highest Qualification:</b>&nbsp;</td>
	<td class="oncolour" align="16%"><?php $this->showField("qualifications_ref") ?></td>
</tr>

</table>

<br><br>


<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td colspan="10">
	<span class="loud">Advanced criteria:</span>
	<hr>
</td></tr>

<tr>
	<td align="right" colspan="2"><b>Search:</b> (by Job Title)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText1") ?></td>
</tr>

<tr>
	<td align="right" colspan="2"><b>Institution Type:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("Employer_type_ref") ?></td>
</tr>
<tr>
	<td align="right" colspan="2"><b>Historical Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("historical_status_ref") ?></td>
</tr>
<tr>
	<td align="right" colspan="2"><b>Merge Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("merged_status_ref") ?></td>
</tr>

<tr>
	<td align="right" colspan="2"><b>Main CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code1") ?>
	</td>
</tr>
<tr>
	<td align="right" colspan="2"><b>Sub CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code2") ?>
	</td>
</tr>

<tr>
<td align="right" colspan="2"><b>ETQA:</b>&nbsp;</td>
	<td class="oncolour"  colspan="4"><?php $this->showField("ETQA_ref") ?></td>
</tr>

<tr class="oncolour">
	<td align="right" align="17%"><b>A-Rated:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("A_rated") ?></td>
	<td align="right"><b>Full/Part Time:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Full_part") ?></td>
	<td align="right"><b>Sector:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Eval_sector_ref") ?></td>
</tr>

<tr class="oncolour">
	<td align="right"><b>Organisation Type:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Organisation_type_ref") ?></td>
	<td align="right"><b>Teaching Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
	<td align="right"><b>Research Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4" align="right">
	<input class="btn" type="button" value="Search" onClick="doSearch();">
	</td>
</tr>

</table>
</FORM>
<?php 
$this->formAction = "?";
$this->formTarget = "";
$this->formName = "defaultFrm";

$this->createForm();
?>

</td>
</tr>
</table>
