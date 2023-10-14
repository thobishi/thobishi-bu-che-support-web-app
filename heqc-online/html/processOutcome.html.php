<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	$this->showField('heqc_board_decision_ref');
	$this->showField('lkp_proceedings_ref');
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="loud">
			Approve outcome, upload the HEQC decision letter for institution and enter the due date for deferrals, conditions or representations.
		</span>
		<br>
	</td>
</tr>
<tr>
	<td>
	The outcome of the proceedings for this programme is: 
	<hr>
			<?php $this->displayOutcome($app_proc_id); ?>
	<hr>
	</td>
</tr>
<tr>
	<td>
		Please approve that the outcome displayed above matches the outcome in the decision letter by checking this box <?php $this->showField('decision_approved_ind'); ?>
	</td>
</tr>
<?php 
	if ( ($this->formFields["heqc_board_decision_ref"]->fieldValue == 2)||($this->formFields["heqc_board_decision_ref"]->fieldValue == 6) ) {  //Provisional accreditation with conditions - 2017-06-07 Richard: added conditional re-accreditation
?>
		<tr>
			<td>
				<br/><b>Please enter the due dates for conditions:</b>
				<table>
				
				<?php
                                
				//if ($this->formFields['lkp_proceedings_ref']->fieldValue == 4){
				//2017-10-20 Richard: Include conditional re-accred
				if (($this->formFields['lkp_proceedings_ref']->fieldValue == 4) || ($this->formFields['lkp_proceedings_ref']->fieldValue == 6)){
					// get unmet conditions for the application.  Note ia_conditions only has records for conditional proceedings.
					$sql = <<<SQL
						SELECT distinct condition_term_ref, lkp_condition_term_desc
						FROM ia_conditions
						LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
						WHERE application_ref = $app_id
						AND condition_term_ref IN ('p','s','l')
						AND condition_met_yn_ref < 2
SQL;
				} else { // For accreditation, deferral and representation no conditions would have been met yet - so just get outcome list of conditions.
					$sql = <<<SQL
						SELECT distinct condition_term_ref, lkp_condition_term_desc
						FROM ia_proceedings_heqc_decision
						LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
						WHERE ia_proceedings_ref = ?
						AND condition_term_ref IN ('p','s','l')
SQL;
				}
				$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			    if ($conn->connect_errno) {
			        $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			        printf("Error: %s\n".$conn->error);
			        exit();
			    }

			    $sm = $conn->prepare($sql);
			    $sm->bind_param("s", $app_proc_id);
			    $sm->execute();
			    $rs = $sm->get_result();

				//$rs = mysqli_query($conn, $sql);
				if ($rs){
					$n = mysqli_num_rows($rs);
					if ($n > 0){
						$cond_field = array('p'=>'condition_prior_due_date', 's'=>'condition_short_due_date', 'l'=>'condition_long_due_date');
						while ($row = mysqli_fetch_array($rs)){
							$condition_field = $cond_field[$row['condition_term_ref']];
							echo "<tr><td>Due date for all " . $row['lkp_condition_term_desc'] . " conditions</td><td>";
							$this->showField($condition_field);
							echo "</td></tr>";
						}
					}
				} else {
					echo "<tr><td>Condition terms could not be determined. Please contact support.</td></tr>";
				}
?>
			</table>
		</td>
	</tr>
<?php
	}
/*
	if ($this->formFields['lkp_proceedings_ref']->fieldValue == 4){
?>
		<tr>
			<td>
				<table>

<?php

		// identify conditions that have not been met.
			$sql = <<<SQL
			SELECT distinct condition_term_ref, lkp_condition_term_desc
			FROM ia_proceedings_heqc_decision
			LEFT JOIN lkp_condition_term ON lkp_condition_term_id = condition_term_ref
			WHERE ia_proceedings_ref = $app_proc_id
			AND recomm_condition_met_yn_ref != 2
SQL;
			$rs = mysqli_query($sql);
			if ($rs){
				$n = mysqli_num_rows($rs);
				if ($n == 0){
					echo "<tr><td>All conditions have been met.  The outcome of the application should be Provisionally Accredited</td></tr>";
				}
				if ($n > 0){
					echo "<tr><td colspan='2'>Some conditions are still outstanding.  The outcome should be Provisionally Accredited with conditions</td></tr>";
				}
			} else {
				echo "<tr><td>Outstanding conditions could not be determined. Please contact support.</td></tr>";
			}
		?>
				</table>
			</td>
		</tr>
<?php } 
*/
?>
<tr>
	<td>
		<br>
		<br>
		<table width="95%" border=0>
		<tr>
			<td valign="top">Upload the HEQC decision letter that was sent to the institution</td>
			<td><?php $this->makeLink("decision_doc"); ?></td>
		</tr>
		<?php 
		// Due date is not required if application is provisionally accredited or decison of not accredited is the outcome of a representation.
		if (  ($this->formFields["heqc_board_decision_ref"]->fieldValue == 3 && $this->formFields["lkp_proceedings_ref"]->fieldValue != 3) || 
				($this->formFields["heqc_board_decision_ref"]->fieldValue == 4) ){ ?>
		<tr>
			<td>Enter the due date (for deferrals or representations):</td>
			<td><?php $this->showField("heqc_decision_due_date"); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td valign="top">Upload the outcome acceptance letter from the institution</td>
			<td><?php $this->makeLink("inst_outcome_accept_doc"); ?></td>
		</tr>
		</table>
	</td>
</tr>
<!--
<tr>
	<td>
		<br>
		<b>The following processing takes place per outcome:</b>
		<ul>
			<li>
				<b>Provisionally accredited:</b> The institution administrator is notified of the outcome. This proceedings is completed.
			</li>
			<li>
				<b>Provisionally accredited (with conditions):</b> The institution administrator is notified of the outcome. 
				This proceedings is completed.  A new <i>conditional accreditation</i> proceedings for this application is started 
				and made accessible to the institution.
			</li>
			<li>
				<b>Deferred application:</b> The institution administrator is notified of the outcome. This proceedings is completed.
				A new <i>deferred application</i> proceedings for this application is started and made accessible to the institution.
			</li>
			<li>
				<b>Not accredited:</b> The institution administrator is notified of the outcome. If a representation has not been done 
				then the user may request a representation.  Indicate whether or not there will be a representation. If no then this proceedings 
				will be completed.  If yes, then the representation must be uploaded.
			</li>
		</ul>
	</td>
</tr>
-->
</table>
<br>
