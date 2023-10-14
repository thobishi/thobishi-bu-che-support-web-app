<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	$this->showField('lkp_proceedings_ref');
	$manage_override = 0;
	if (isset($_POST["manage_override"])){
		$manage_override = 2;
		$this->formFields["manage_override"]->fieldValue = 2;
		$this->formFields["manage_override"]->fieldType = 'HIDDEN';
		$this->showField("manage_override");
	}
	//$message = $this->getTextContent ("processOutcomeNotAccredited", "Not accredited");

	$proc_type = $this->formFields["lkp_proceedings_ref"]->fieldValue;

	// Indicate that next proceedings will be started because a document is uploaded.
	if (($proc_type != 3 || ($manage_override == 2)) && $this->formFields['representation_doc']->fieldValue > 0){
		$this->formActions["next"]->actionDesc = 'End this proceedings. Continue to next user to start representation proceedings';
	}
	
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	The outcome of the proceedings for this programme is: 
	<hr>
			<?php $this->displayOutcome($app_proc_id); ?>
	<hr>
	</td>
</tr>
<?php
	if (  ($proc_type != 3) || ($proc_type == 3 && $manage_override == 2) ){ 
?>
<tr>
	<td>
		In cases where a programme is not recommended for accreditation, the institution may make representations. These representations: 
		<ol>
		<li>should be in writing </li>
		<li>should not repeat the contents of the original application </li>
		<li>should be confined to the information provided by the institution during the evaluation process: that is, information that was part of the institution's application and that was made available to the panel of evaluators </li>
		<li>should address the report and recommendations of the Accreditation Committee focusing on any errors and omissions that may have occurred in the evaluation process </li>
		<li>should reach the HEQC within 21 working days of the date of the letter </li>
		</ol>
		Should the decision not to accredit a programme be upheld, the institution may not request another representation and may not submit the same programme for accreditation for a period of two years.	
	</td>
</tr>
		<tr>
			<td>
				<hr>
				Is there a representation? <?php $this->showField('representation_ind'); ?>	
			</td>
		</tr>
		<tr>
			<td>
				<b>If yes: </b>
			</td>
		</tr>
		<tr>
			<td>
				Upload the representation: <?php $this->makeLink('representation_doc'); ?>
			</td>
		</tr>
		<tr>
			<td>
				Date that the representation was submitted: <?php $this->showField('representation_submission_date'); ?>
				<hr>
			</td>
		</tr>
<?php		
	} else {
?>
		<tr>
			<td>
				<b>This is the representation for an application.  No further processing may take place.  The outcome has been upheld.</b>
				<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please check the box and click on Approve if management has approved that another representation may be made <?php $this->showField("manage_override"); ?>
<?php 
				$instr = <<<INSTR
					&nbsp;<input class="btn" type="button" value="Approve" onClick="checkManageOverride(document.defaultFrm.manage_override);">
INSTR;
				echo $instr;
?>
			</td>
		</tr>
<?php
	}
?>
<tr>
	<td>
		<b>Click on <i>Close this process and end this proceedings</i> to close the proceedings </b>
	</td>
</tr>
<!--
<tr>
	<td>
	<span class="specialb">Email to be sent to the institutional administrator.</span><i> You may edit the text to include additional information.</i>
<?php
		//$this->formFields['email_content']->fieldValue = $message;
		//$this->showfield('email_content');
?>
	</td>
</tr>
-->
</table>
<br>
