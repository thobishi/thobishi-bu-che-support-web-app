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
		Emails of final site visit arrangements and documents to panel members:
		<br>
	</td>
</tr>
<tr>
	<td>
		Each panel member below will receive the site visit emails containing all details for the site visists they are conducting.  
		In order to email the letter to an evaluator ensure the box under the <b>Send Email?</b> heading next to the relevant evaluator is checked.
		By default the box is checked if the letter has not been previously emailed.
		<br>
		<br>
		The following evaluators have been appointed to conduct site visits.
		<br>
		</td>
</tr>
<tr>
	<td>
		<?php
		
			$html = '<table width="95%">';
			$html .= <<<html
				<tr>
					<td><b>Evaluator name</b></td>
					<td><b>Email address</b></td>
					<td><b>Telephone number</b></td>
					<td><b>Site name</b></td>
					<td><b>Upload final contract</b></td>
					<td><b>Date email sent</b></td>
					<td><b>Send Email?</b></td>
				</tr>
html;
			$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_app_id, 'allvisits');
			$prev_E = "";

			foreach ($eval_arr AS $eval){  // foreach evaluator
	
				foreach($eval AS $e){     // for each site visit evaluator is conducting
					$tel = "&nbsp;";
					if ($e['Work_Number'] > '' && $e['Mobile_Number'] > ''){
						$tel = $e['Work_Number'] . ", " . $e['Mobile_Number'];
					} elseif ($e['Work_Number'] > ''){
						$tel = $e['Work_Number'];
					} elseif ($e['Mobile_Number'] > ''){
						$tel = $e['Mobile_Number'];
					}
					$ename = $e["Name"];
					$email = $e["E_mail"];
					$contract = "Upload contract";
					$contractDoc = new octoDoc($e['eval_contract_doc']);
					if ($contractDoc->isDoc()){
							$contract = "<a href='".$contractDoc->url()."' target='_blank'>".$contractDoc->getFilename()."</a>";
					}
					$clink = $this->scriptGetForm ('inst_site_app_proceedings_eval', $e["inst_site_app_proc_eval_id"], '_siteLoadFinalEvalContract');
					$contractLink = "<a href='".$clink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> $contract";
					if ($e["Persnr"] == $prev_E){
						$tel = "&nbsp;";						
						$ename = "&nbsp;";						
						$email = "&nbsp;";						
						$contractLink = "&nbsp;";		
					}
					
					$checked = "";
					$date_sent = "&nbsp;";
					if ($e["panel_letter_sent_date"] > '1970-01-01'){
						$date_sent = $e["panel_letter_sent_date"];
					} else {
						$checked = "CHECKED";  // Email has been sent previously
					}

					$html .= <<<html
						<tr>
							<td>$ename</td>
							<td>$email</td>
							<td>$tel</td>
							<td>$contractLink</td>
							<td>$e[site_name]</td>
							<td>$date_sent</td>
							<td><input name="chkEvalSite$e[inst_site_visit_eval_id]" type="Checkbox" $checked></td>
						</tr>
html;
					$prev_E = $e["Persnr"];
				}
			}
			$html .= "</table>";

			echo $html;
		?>
	</td>
</tr>
<tr>
	<td align="center">
	<br>
	Evaluators that have accepted to take part in the programme evaluation will have access to the site visits information until: <?php $this->showfield('evaluator_access_end_date'); ?>
	<br><br>
	</td>
</tr>
<tr>
	<td>
		The following emails will be sent to the relevant evaluator if you checked the <b>Send Email?</b> box for the evaluator above.
		<br />
		<br />
		<span class="visi">The content below may be edited. This edited content will be emailed when you click on Continue.</span>
	</td>
</tr>
<tr>
	<td>
		<?php
			$sql = <<<SQL
			SELECT inst_site_visit_id
			FROM inst_site_visit
			WHERE inst_site_app_proc_ref = $site_app_id;
SQL;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		//$sm = $conn->prepare($sql);
		//$sm->bind_param("s", $site_app_id);
		//$sm->execute();
		//$rs = $sm->get_result();


		$rs = mysqli_query($conn, $sql);
		if ($rs){
			while ($s = mysqli_fetch_array($rs)){
?>
				<br />
				<br />
<?php
				// This hidden field is set in order to pass the evaluator persnr to the template_text text_programming field
				$id = $s['inst_site_visit_id'];
				$this->formFields['site_visit_id']->fieldValue = $id;
				$this->showField('site_visit_id');
				
				// Need to create these fields dynamically in order to display each email per site visit, allow them to be edited and 
				// then emailed to the respective evaluator.
				$this->createInput ("email_sitevisit_".$id, "TEXTAREA");
				$this->formFields["email_sitevisit_".$id]->fieldCols = 100;
				$this->formFields["email_sitevisit_".$id]->fieldRows = 30;
				$this->formFields["email_sitevisit_".$id]->fieldValue = $this->getTextContent("instSiteApplicPanel8", "Site visit letter to Panel members");
				$this->showField("email_sitevisit_".$id);
				
				$docs_arr = $this->getSiteVisitAttachments($id);
				$docs = "";
				foreach ($docs_arr AS $doc => $title){
					$att = new octoDoc($doc);
					if ($att->isDoc()) {
						$attachment = '<a href="'.$att->url().'" target="_blank">'.$title.'</a>';
					}
					$docs .= <<<HTML
						$attachment<br />
HTML;
				}
?>
				<span class="visi"><br /><br />Attachments that will be sent with the email above:<br /></span>
<?php
				echo $docs;
			}
		}
?>

	</td>
</tr>
</table>
