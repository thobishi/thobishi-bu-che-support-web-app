<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
	$this->showInstitutionTableTop ();
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<br>
		Please indicate the type of the conditions that this document addresses.  This information will be used to indicate 
		to evaluators which set of conditions they must evaluate using this conditions document.
		<ul>
		<li>A prior to commencement document must address ALL the prior to commencement conditions.</li>
		<li>A short term condition document must address ALL the short term conditions.</li>
		<li>A long term condition document must address ALL the long term conditions.</li>
		</ul>
		<span class="visi">Note: The institution must address all conditions for a particular type or types in the document.  It is your responsibility to check the above and if any are omitted then contact the institution and request 
		them to address the missing conditions, update and resend the document.</span>
	</td>
</tr>
<tr>
	<td>
		Select condition type or types that the document addresses: 
	</td>
	<td>
		<?php //$this->showField('condition_doc_term_ref');
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
		// Build list of unmet conditions
		if ($proc_type == 1 || $proc_type == 2 || $proc_type == 3 || $proc_type == 5 ){
			$sql1 = <<<TERM
				SELECT DISTINCT condition_term_ref, lkp_condition_term_desc
				FROM ia_proceedings_heqc_decision, lkp_condition_term
				WHERE ia_proceedings_heqc_decision.condition_term_ref = lkp_condition_term.lkp_condition_term_id
				AND ia_proceedings_heqc_decision.ia_proceedings_ref = $app_proc_id
TERM;
		}
		if ($proc_type == 4 || $proc_type == 6 ){
			$sql1 = <<<TERM
				SELECT DISTINCT condition_term_ref, lkp_condition_term_desc
				FROM ia_conditions, lkp_condition_term
				WHERE ia_conditions.condition_term_ref = lkp_condition_term.lkp_condition_term_id
				AND ia_conditions.application_ref = $app_id
				AND ia_conditions.condition_met_yn_ref != 2
TERM;
		}
		$rs1 = mysqli_query($conn, $sql1);
		$input = "";
		while ($row1 = mysqli_fetch_array($rs1)){
			$input .= <<<INPUT
				<input type="checkbox" name="cond_term[]" value="{$row1["condition_term_ref"]}">{$row1["lkp_condition_term_desc"]}</input>
INPUT;
		}
		echo $input;
		?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php $this->displayOutcome($app_proc_id); ?>
	</td>
<tr>
	<td>
		Final compliance with conditions document: 
	</td>
	<td>
		<?php $this->makeLink('condition_doc'); ?>
	</td>
</tr>
</table>
<br>
