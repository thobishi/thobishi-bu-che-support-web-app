<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>The paper evaluation of the above programme is finished. Using the table below decide whether this accreditation process must include a site visit.</b></td>
</tr><tr>
	<td><b>Decision criteria for a site visit in the Accreditation Accreditation Phase</b></td>
</tr></table>
<table align="center" border="1"><tr>
	<td class="oncolourb" valign="top" align="center">INSTITUTION</td>
	<td class="oncolourb" valign="top" colspan="4" align="center">PROGRAMME</td>
	<td class="oncolourb" valign="top" colspan="3" align="center">AUDIT RESULTS</td>
	<td class="oncolourb" valign="top" colspan="2" align="center">SITE VISIT</td>
</tr><tr>
	<td>&nbsp;</td>
	<td valign="top">New Site of Delivery</td>
	<td valign="top">New Mode of Delivery</td>
	<td valign="top">New Area of Teaching</td>
	<td valign="top">Higher Qualification in the same area</td>
	<td valign="top">Adequate QA systems</td>
	<td colspan="2" valign="top">Inadequate QA systems</td>
	<td valign="top">Evaluator's recommendation</td>
	<td valign="top">Yes/No</td>
</tr><tr>
	<td nowrap><?php $this->showField("institution_type")?></td>
	<td valign="top"><?php $this->showField("new_site_delivery")?></td>
	<td valign="top"><?php $this->showField("new_mode_delivery")?></td>
	<td valign="top"><?php $this->showField("new_area_teaching")?></td>
	<td valign="top"><?php $this->showField("higher_qualification")?></td>
	<td colspan="3" valign="top"><?php $this->showField("adequate_QA")?></td>
	<td valign="top"><?php echo $this->showEvalDecisionResult()?></td>
	<td valign="top"><?php $this->showField("site_visit")?></td>
</tr></table>
<br><br>
<table align="center" width="60%"><tr>
<td>
		<fieldset>
		<legend><span class="specialb">Site Visit History: <?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></span></legend>
			<table width="100%" cellpadding="2" cellspacing="2"><tr>
				<td align="center"><a href="javascript:doViewHistory(1);moveto('next');">View site visit history</a></td>
			</tr></table>
		</fieldset>
</td>
</tr></table>
<br><br>
<table align="center" width="60%"><tr>
<td>
		<fieldset>
		<legend><span class="specialb">Choose Evaluators</span></legend>
		<table width="100%" cellpadding="2" cellspacing="2"><tr>
			<td><b>Please choose from the list of evaluators below, who you would like to participate in the sitevisit:</b></td>
		</tr><tr>
		<td>
		<?php 
			$headingArray = array();
			array_push($headingArray,"Evaluator");
			array_push($headingArray,"Accept");
		
			$refDispArray = array();
			array_push($refDispArray,"Names");
			array_push($refDispArray,"Surname");
		
			$dispFields = array();
			array_push($dispFields,"do_sitevisit_checkbox");
		
			$this->makeGRID("Eval_Auditors, evalReport",$refDispArray,"Persnr"," application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr=Persnr_ref AND evalReport_status_confirm=1 AND evalReport_completed=1","evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray);
			?>
			</td>
		</tr><tr>
			<td><input type="hidden" name="change_eval" value="0">
				<a href="javascript:changeEvalID('new');moveto('next');">Add new evaluator</a></td>
		</tr></table>
		</fieldset>
</td>
</tr></table>

<br><br>
<?php $this->showField("view_history")?>
<br><br>
<table align="center" border="0"><tr>
	<td class="oncolourb" valign="top"><b>Please comment on your decision:</b></td>
</tr><tr>
	<td><?php $this->showField("site_visit_decision_comment")?></td>
</tr><tr>
	<td valign="top"><b>Override site visit decision:</b> &nbsp;<?php $this->showField("override_sitevisit_decision")?></td>
</tr><tr>
	<td><div id="override_div" style="display:none">Please comment on why you overide the desicion:<br><?php $this->showField("override_sitevisit_decision_comment")?></div></td>
</tr></table>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>Click on each cell according to the information you have and follow the instructions.</td>
</tr><tr>
	<td> 
	The site visit happens if :-
	<ul>
		<li>Registration is pending.</li>
		<li>the programme is offered by a new institution.</li>
		<li>the programme offered by a recently established institution changes its mode of delivery</li>
		<li>the programme offered by a recently established institution is for a higher qualification in an area already offered.</li>
		<li>the HEI QA systems are not adequate.</li>
		<li>the programme is offered by a established institution that is opened a new site of delivery.</li>
		<li>the programme is offered by a established institution that is introducing a new mode of delivery.</li>
		<li>the programme is a totally new area of teaching at an established institution</li>
		<li>the HEIs QA systems are not adequate</li>
	</ul></td>
</tr><tr>
	<td>The results of the assessment indicate that the institution need to be visited. To continue with the process click <a href="javascript:moveto('next')">here</a></td>
</tr></table>
</td></tr></table>
<script>
	checkSiteVisit(document.defaultFrm.FLD_site_visit);
	function checkSiteVisit (destObj) {
		obj = document.defaultFrm;
		if (document.defaultFrm.FLD_override_sitevisit_decision.checked) {
			document.defaultFrm.FLD_override_sitevisit_decision.checked = false;
			document.all.override_div.style.display = 'none';
		}
		for (i=0; i<obj.elements.length; i++) {
			if ((obj.elements[i].type == "checkbox") || ((obj.elements[i].type == "radio") && ((obj.elements[i].value == "1")||(obj.elements[i].value == "2")))) {
				if ((obj.elements[i].checked == true)) {
					destObj.value = "Yes";
					break;
				}else {
					destObj.value = "No";
				}
			}
		}
	}
	
	function enableSiteVisit () {
		document.defaultFrm.FLD_site_visit.disabled = false;
		if ((document.defaultFrm.view_history.value != 1) && (document.defaultFrm.MOVETO.value == 'next')) {
			if ((document.defaultFrm.FLD_site_visit_decision_comment.value == '') && (document.defaultFrm.change_eval.value == 0)) {
				alert('Please enter a comment on your decision');
				document.defaultFrm.FLD_site_visit_decision_comment.focus();
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_override_sitevisit_decision.checked) && (document.defaultFrm.FLD_override_sitevisit_decision_comment.value == '')) {
				alert('Please enter a comment on your decision');
				document.defaultFrm.FLD_override_sitevisit_decision_comment.focus();
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}

	function overrideSiteVisit (obj) {
		if (document.defaultFrm.FLD_site_visit.value == "Yes") {
			document.defaultFrm.FLD_site_visit.value = "No";
		}else {
			document.defaultFrm.FLD_site_visit.value = "Yes";
		}
		if (obj.checked) {
			document.all.override_div.style.display = 'Block';
			document.defaultFrm.FLD_override_sitevisit_decision_comment.focus();
		}else {
			document.all.override_div.style.display = 'none';
		}
	}
	
	function doViewHistory (val) {
		document.defaultFrm.view_history.value = val;
	}
	
	function changeEvalID (id) {
		document.defaultFrm.change_eval.value=id;
	}
</script>
