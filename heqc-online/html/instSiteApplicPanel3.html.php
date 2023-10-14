<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_app_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Email letter of appointment to panel members:
		<br>
	</td>
</tr>
<tr>
	<td>
		The letters of appointment displayed per evaluator may be edited and sent to the evaluator. It is optional to email these letters.  
		In order to email the letter to an evaluator ensure the box under the <b>Send Email?</b> heading next to the relevant evaluator is checked.
		By default the box is checked if the letter has not been previously emailed.
		<br>
		<br>
		The following evaluators have been selected to conduct site visits.
		<br>
		</td>
</tr>
<tr>
	<td>
		<?php
		
			$html = '<table width="80%">';
			$html .= <<<html
				<tr>
					<td><b>Evaluator name</b></td>
					<td><b>Email address</b></td>
					<td><b>Telephone number</b></td>
					<td><b>Contract to email</b></td>
					<td><b>Date email sent</b></td>
					<td><b>Send Email?</b></td>
				</tr>
html;
			$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_app_id, 'applic');
			foreach ($eval_arr AS $e){
				$date_sent = $e["appoint_email_sent_date"];
				$tel = "&nbsp;";
				if ($e['Work_Number'] > '' && $e['Mobile_Number'] > ''){
					$tel = $e['Work_Number'] . ", " . $e['Mobile_Number'];
				} elseif ($e['Work_Number'] > ''){
					$tel = $e['Work_Number'];
				} elseif ($e['Mobile_Number'] > ''){
					$tel = $e['Mobile_Number'];
				}
				$checked = "";
				$date_sent = "&nbsp;";
				if ($e["appoint_email_sent_date"] > '1970-01-01'){
					$date_sent = $e["appoint_email_sent_date"];
				} else {
					$checked = "CHECKED";  // Email has been sent previously
				}

	                        $contract = "Upload contract";
        	                $contractDoc = new octoDoc($e['eval_contract_doc']);
                	        if ($contractDoc->isDoc()){
                        	        $contract = "<a href='".$contractDoc->url()."' target='_blank'>".$contractDoc->getFilename()."</a>";
                        	}
                        	$clink = $this->scriptGetForm ('inst_site_app_proceedings_eval', $e["inst_site_app_proc_eval_id"], '_siteLoadEvalContract');
                        	$contractLink = "<a href='".$clink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> $contract";

				$html .= <<<html
					<tr>
						<td>$e[Name]</td>
						<td>$e[E_mail]</td>
						<td>$tel</td>
						<td>$contractLink</td>
						<td>$date_sent</td>
						<td><input name="chkEval$e[Persnr]" type="Checkbox" $checked></td>
					</tr>
html;

			}
			$html .= "</table>";

			echo $html;
		?>
	</td>
</tr>
<tr>
	<td>
		The following emails will be sent to the relevant evaluator if you checked the <b>Send Email?</b> box for the evaluator above.
		<br>
		<span class="visi">The content below may be edited. This edited content will be emailed when you click on Continue.</span>
	</td>
</tr>
<tr>
	<td>
		<?php
		foreach ($eval_arr as $e){
		?>
			<br />
		<?php
			// This hidden field is set in order to pass the evaluator persnr to the template_text text_programming field
			$this->formFields['e_persnr']->fieldValue = $e['Persnr'];
			$this->showField('e_persnr');
			
			// Need to create these fields dynamically in order to display each email per evaluator, allow them to be edited and 
			// then emailed to the respective evaluator.
			$this->createInput ("email_persnr_".$e['Persnr'], "TEXTAREA");
			$this->formFields["email_persnr_".$e['Persnr']]->fieldCols = 100;
			$this->formFields["email_persnr_".$e['Persnr']]->fieldRows = 20;
			$this->formFields["email_persnr_".$e['Persnr']]->fieldValue = $this->getTextContent("instSiteApplicPanel3", "Letter of appointment for site visit");
			$this->showField("email_persnr_".$e['Persnr']);
		}
		?>
	</td>
</tr>
</table>
