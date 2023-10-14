<?php 

	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id,"","Reaccred");
	if (count($evals) > 0){
		$this->createAction ("next", "Next", "submit", "", "ico_next.gif");
	}

?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">

		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id)?>
		<br>

		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			Please select the evaluators that will evaluate this re-accreditation application.<br><br>
			<span class="visi">Please note that the Next button
			will appear in the Actions menu when at least one evaluator has been selected.</span>.
			</td>
		</tr>
		<tr>
			<td><span class="loud">Search for Evaluators:</span></td>
		</tr>
		<tr>
		<td>On the selection of appropriate evaluators rests the strength of the outcome of the Re-accreditation Phase of the accreditation process.  The Evaluators database will help you choose evaluators for this programme. In making your selection:
		<ul>
		<li>You need to choose 3 subject specialists one of whom will become the chair of the evaluation process and will be responsible for submitting a final evaluation report to the HEQC.</li>
		<li>In choosing evaluators you need to take into account how many programmes a particular evaluator is currently busy with.</li>
		<li>Choose evaluators who have undergone training and are familiar with HEQC procedures.</li>
		<li>In the case of professional programmes involving ETQAs and professional associations with which the HEQC has
		cooperation agreements, make sure that you choose evaluators recognized by the ETQA/professional association.</li>
		</ul>
		<hr>
		</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="50%" valign="top">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right"><b>Search:</b> (by name, surname, initials or idnumber)</td>
			<td class="oncolour" colspan="3"><?php $this->showField("searchText") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Search:</b> (by Job Title)</td>
			<td class="oncolour" colspan="3"><?php $this->showField("searchText1") ?></td>
		</tr>
<!--
		<tr>
			<td align="right"><b>Available:</b>&nbsp;</td>
			<td class="oncolour"><?php//$this->showField("active") ?></td>
			<td align="right"><b>A-Rated:</b>&nbsp;</td>
			<td class="oncolour"><?php//$this->showField("A_rated") ?></td>
		</tr>
-->
		<tr>
			<td align="right"><b>Full/Part Time:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("Full_part") ?></td>
			<td align="right"><b>Race:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("Race") ?></td>
		</tr>
		<tr>
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
		</tr>
		<tr>
			<td align="right"><b>Organisation Type:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("Organisation_type_ref") ?></td>
			<td align="right"><b>Highest Qualification:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("qualifications_ref") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Teaching Experience:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
			<td align="right"><b>Research Experience:</b>&nbsp;</td>
			<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
		</tr>

		<tr>
			<td align="right"><b>Institution:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("employer_ref") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Institution Type:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("Employer_type_ref") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Historical Status:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("historical_status_ref") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Merge Status:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("merged_status_ref") ?></td>
		</tr>
		<tr>
		<td align="right"><b>ETQA:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("ETQA_ref") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Main CESM classification:</b>&nbsp;</td>
			<td class="oncolour" colspan="3">
			<?php	$this->showField("CESM_code1") ?>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Sub CESM classification:</b>&nbsp;</td>
			<td class="oncolour" colspan="3">
			<?php	$this->showField("CESM_code2") ?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right" colspan="3">
				<input class="btn" type="button" value="Search" onClick="doSearch();">
			</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		</table>
	</td>
	<td valign="top">
</FORM>
<?php 
$this->formAction = "?";
$this->formTarget = "";
$this->formName = "defaultFrm";

$this->createForm();
?>
		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
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
					each Evaluator will launch a new window with more information on the specific Evaluator.
					From within the new window, you will be able to add the Evaluator to the Accreditation Process.
					</span>
					</td>
				</tr>
			</table>
			</fieldset>
			<br>

			</td>
		</tr>

		<tr>
			<td class="oncolour" width="95%" valign="top">

			<table width="95%" align="center" cellpadding="2" cellspacing="2" border="0">
			<tr>
				<td>
					i) <b>Search Results:</b>
					<br>
					<IFRAME width="100%" id="resultsFrame" name="resultsFrame" src="" style="height:200;width:100%"></IFRAME>
				</td>
			</tr>
			</table>

			</td>
		</tr>
		<tr>
			<td>
			<hr>
			<br>
			The following evaluators have been selected to evaluate this re-accreditation application.
			</td>
		</tr>
		<tr>
			<td>
			<table width="100%" align="left" cellpadding="2" cellspacing="2" border="0">
			<?php

				$dFields = array();
				// 2011-09-12 Robin: If active=2 then early evaluators who may no longer active are not displayed.  Therefore removing. Active=2 must be included in the search but not in the display.
				//array_push($dFields, "type__select|name__Persnr_ref|status__3|description_fld__CONCAT(Surname,',',Names)|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__Surname>'' AND Evaluator=1 AND active=2|order_by__Surname");
				array_push($dFields, "type__select|name__Persnr_ref|status__3|description_fld__CONCAT(Surname,',',Names)|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__Surname>'' AND Evaluator=1|order_by__Surname");

				array_push($dFields, "type__radio|name__do_summary|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");

				$hFields = array("Evaluator", "Chairman");

				//2011-09-12 Robin: Remove Del options as it will remove all evaluator report information without prompting 
				//$this->gridShowRowByRow("evalReport","evalReport_id","reaccreditation_application_ref__".$reaccred_id,$dFields,$hFields, 40, 5, "", "true",0);
				$this->gridShowRowByRow("evalReport","evalReport_id","reaccreditation_application_ref__".$reaccred_id,$dFields,$hFields, 40, 5, "", "",0);
			?>
			</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2">




<br>



</td>
</tr>
</table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
	function addGridEntries($keyval){
		document.defaultFrm.cmd.value = "new|evalReport|reaccreditation_application_ref|<?php echo $reaccred_id ;?>|Persnr_ref|" + $keyval;
		moveto('stay');
	}
</script>
<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>