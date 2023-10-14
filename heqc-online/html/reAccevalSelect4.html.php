<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>


	<br>
	<?php echo $this->displayReaccredHeader ($reaccred_id);?>
	<br>


	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td align="center">
		The following evaluators have confirmed that they will evaluate this application:
		</td>
	</tr>
	
	<tr>
		<td>
	
		<?php
		// Display evaluators that confirmed "accepted" to evaluate the application
		$criteria = array("evalReport_status_confirm = 1");
		$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id, $criteria, "Reaccred");

		// Process cannot continue without evaluators having confirmed.
	
		$html = '<table width="80%" align="center">';
	
		$html .= <<<html
			<tr class='oncolourb'>
				<td><b>Evaluator name</b></td>
				<td><b>Email address</b></td>
				<td><b>Work Telephone</b></td>
				<td><b>Chair Person</b></td>
				<td><b>Date Email Sent</b></td>
			</tr>
html;
	
		$n_confirm = count($evals);
	
		// Only allow workflow to continue (display Next button) if at least one evaluator has confirmed.
		if ($n_confirm == 0){
			$this->formActions["next"]->actionMayShow = false;
			$html .= <<<html
				<tr>
					<td colspan="4">No evaluators have confirmed.  This process cannot continue.
					Please click Previous and confirm which evaluators have accepted.
					</td>
				</tr>
html;
		}
	
		// Evaluators who have confirmed must be notified of the portal via email.
		if ($n_confirm > 0){
			foreach($evals as $e){
				$chair = $this->getValueFromTable("lkp_yes_no","lkp_yn_id",$e["do_summary"],"lkp_yn_desc");
	
				$html .= <<<html
				<tr>
					<td>$e[Name]</td>
					<td>$e[E_mail]</td>
					<td>$e[Work_Number]</td>
					<td>$chair</td>
					<td>$e[evalReport_date_sent]</td>
				</tr>
html;
			}
		}
	
		$html .= "</table>";
	
		echo $html;
		?>
	
		</td>
	</tr>
	
	<tr>
		<td>
			<br>
			<b>All the above evaluators will receive the following email (if it hasn't been sent to them before - see <i>Date Email Sent</i>) to inform them on how to access the evaluator portal.</b><br>
		</td>
	</tr>
	
	<tr>
		<td valign="top"> <span class="loud">Letter on Evaluator Portal Access:</span></td>
	</tr>
	<tr>
		<td align="center">
		<?php
		$this->formFields['evaluator_portal_email']->fieldValue = $this->getTextContent("reAccevalSelect4", "Letter on Evaluator Portal Access");
		$this->showField('evaluator_portal_email');
		?>
		</td>
	</tr>
	</table>


</td>
</tr>
</table>
