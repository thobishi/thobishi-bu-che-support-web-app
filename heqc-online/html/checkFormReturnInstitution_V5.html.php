<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$finalchecklistreport  = $this->getValueFromTable("ia_proceedings", "application_ref", $app_id, "checklist_final_doc");


?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php
		$this->showInstitutionTableTop ();
	?>
	</td>
</tr>

<tr>
	<td>

		<?php
		// Display evaluators that confirmed "accepted" to evaluate the application
		$criteria = array("evalReport_status_confirm = 1");
		// Display evaluators for this proceeding only
		//$evals = $this->getSelectedEvaluatorsForApplication($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $criteria);
		$evals = $this->getSelectedEvaluatorsForApplication($app_proc_id, $criteria, "Proceedings");

		// Process cannot continue without evaluators having confirmed.

		$html = '<table width="95%" align="left">';

		$html .= <<<html
			<tr class='oncolourb'>
				
				<td><b>Final Checklist Report</b></td>
			</tr>
html;

		$n_confirm = count($evals);

		// Only allow workflow to continue (display Next button) if at least one evaluator has confirmed.
	//	if ($n_confirm == 0){
			//$this->formActions["next"]->actionMayShow = false;
			$html .= <<<html
				<tr>
				
				</tr>
html;
	//	}

		// Evaluators who have confirmed must be notified of the portal via email.
		
				$contractDoc = new octoDoc($finalchecklistreport);
				if ($contractDoc->isDoc()){
					$contractLink = "<a href='".$contractDoc->url()."' target='_blank'>".$contractDoc->getFilename()."</a>";
				}
				

				$html .= <<<html
			
					
					<td>$contractLink</td>




				
html;
			

		$html .= "</table>";

		echo $html;


		?>

	</td>


	
</tr>
<tr>
			<td>
			Resubmission Due Date:  <?php		
			$this->showField('resubmission_due_date');
			?>
			</td>
			
			<td>
			
			</td>
			<td>
			
			</td>
</tr>
<tr>
	<td valign="top">
		<span class="loud">Letter on Return the application to the institution :</span>
	</td>
</tr>
<tr>
	<td align="center">
		<?php
		$this->formFields['portal_email']->fieldValue = $this->getTextContent("checkFormReturnInstitution_V5", "returntoinstitution");
		$this->showField('portal_email');
		?>
	</td>
</tr>
</table>
