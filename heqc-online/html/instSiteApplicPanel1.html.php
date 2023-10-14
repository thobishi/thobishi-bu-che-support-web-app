<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$this->formActions["next"]->actionMayShow = false;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_app_id,"sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Select Panel members for site visits:
		<br>
	</td>
</tr>
</table>
<?php 

$evals = $this->getSelectedEvaluatorsForSiteVisits($site_app_id, 'applic');
if (count($evals) > 0){
	$this->formActions["next"]->actionMayShow = true;
}

?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			Please select the panel that will conduct the site visits for this institution.<br><br>
			<span class="visi">Please note that the Next button
			will appear in the Actions menu when at least one evaluator has been selected.</span>.
			</td>
		</tr>
		<tr>
			<td><span class="loud">Search for Evaluators:</span></td>
		</tr>
		<tr>
		<td>The Evaluators database will help you choose evaluators for this programme. In making your selection:
		<ul>
		 <li>You need to choose all the evaluators that will be conducting the site visit one of whom will become the chair of the evaluation process and will be responsible for submitting the site visit report to the HEQC.</li>
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
			<td align="right"><b>Search:</b> (by Name, surname or email address)</td>
			<td class="oncolour" colspan="3"><?php $this->showField("searchText") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Search:</b> (by Job Title)</td>
			<td class="oncolour" colspan="3"><?php $this->showField("searchText1") ?></td>
		</tr>
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
			<td class="oncolour" colspan="3"><?php $this->showField("Province") ?></td>
		</tr>
		<tr>
			<td align="right"><b>Highest Qualification:</b>&nbsp;</td>
			<td class="oncolour" colspan="3"><?php $this->showField("qualifications_ref") ?></td>
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
					From within the new window, you will be able to add the Evaluator.
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
		</table>
	</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
			<br>
			The following evaluators have been selected to conduct site visits for this institution.
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%" align="left" cellpadding="2" cellspacing="2" border="0">
			<?php

				$dFields = array();
				array_push($dFields, "type__select|name__evaluator_persnr|status__3|description_fld__CONCAT(Surname,', ',Names)|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__1|order_by__Surname");

				//array_push($dFields, "type__radio|name__chairman_yn_ref|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");

				//$hFields = array("Evaluator", "Chairman");
				$hFields = array("Evaluator");

				// No delete option - too risky
				$this->gridShowRowByRow("inst_site_app_proceedings_eval","inst_site_app_proc_eval_id","inst_site_app_proc_ref__".$site_app_id,$dFields,$hFields, 40, 5, "", "",0);
			?>
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
	function addGridEntries($keyval){
		document.defaultFrm.cmd.value = "new|inst_site_app_proceedings_eval|inst_site_app_proc_ref|<?php echo $site_app_id ;?>|evaluator_persnr|" + $keyval;
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
