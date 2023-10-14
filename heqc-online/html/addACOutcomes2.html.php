<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	You are entering AC outcome information for:
	</td>
</tr>
<tr>
	<td align="center">
<?php 
	$application_id = ($this->dbTableInfoArray['Institutions_application']->dbTableCurrentID);
	$CHE_ref_number = $this->getValueFromTable("Institutions_application", "application_id", $application_id, "CHE_reference_code");
	$this->displayApplicationForOutcomes($application_id);
	$AC_history_link = "<a href='pages/acMeetingHistory.php?app_ref=".base64_encode($application_id)."' target='_blank'><i>(View this application's AC meeting history)</i></a>";
	
	//echo $application_id;

?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<table border=0 cellpadding="2" cellspacing="2">
	<tr class="onblue">
	   	<td width="30%">Please select the AC meeting that this application was tabled at:</td>
		<td><?php echo $this->showField("AC_Meeting_date")?></td>
	</tr>

	<tr class="onblue">
		<td valign="top">Please select the AC outcome of the application from the above meeting:</td>
		<td><?php $this->showField("AC_desision");?></td>
	</tr>

	<tr valign="top" class="onblue">
		<td valign="top">
			Please enter any relevant comments:<br />
			<span class="specialsi">e.g. Indicate when conditions were met or a representation received. 
			Do not overwrite the comment.  Add additional information at the end of the comment.</span>
		</td>
		<td><?php $this->showField("AC_conditions");?></td>
	</tr>
<!--
	<tr class="onblue">
		<td valign="top">Please upload any documents pertaining to this application that went along to the AC meeting:</td>
		<td><?php//$this->makeLink("AC_conditions_doc");?></td>
	</tr>
-->
	<tr class="onblue">
		<td valign="top">Historic outcome documentation uploaded for this application:<br /><span class="specialsi">Replaced by document basket in July 2012</span></td>
		<td>
			<?php
			$outcome_docs = "&nbsp;";
			$doc_no = $this->formFields['AC_conditions_doc']->fieldValue;
			$oDoc = new octoDoc($doc_no);
			if ($oDoc->isDoc()) {
				$outcome_docs = '<a href="'.$oDoc->url().'" target="_blank">'.$oDoc->getFilename().'</a>';
			}
			echo $outcome_docs;
			?>
		</td>
	</tr>

	<!-- 2017-09-13 : Richard Added SAQA Submission section to allow replacements of SAQA submission forms-->
	<tr class="onblue">
		<td class="loud"><br><b>SAQA Submission</b><br>
		<span class="specialsi">Historic (prior to July 2011) documents may be uploaded here in order to keep a record. 
		Currently SAQA submissions may be uploaded and viewed with the proceedings. </span>
		</td>
		<td><?php $this->makeLink("1_saqa_submission_doc"); ?></td>
	</tr>	
	
	<!-- 2017-11-13 : Richard Added SAQA recommendation date and ID -->
	<tr class="onblue">
		<td width="30%">Date recommended to SAQA for registration:</td>
		<td><?php echo $this->showField("date_recommended_to_SAQA")?></td>
	</tr>
	<tr class="onblue">
		<td width="30%">SAQA Id:</td>
		<td><?php echo $this->showField("SAQA_id")?></td>
            </tr> 

          <tr class="onblue">
		<td width="30%">Accreditation withdrawn outcome:</td>
		<td><?php echo $this->showField("withdrawn_decision_ref")?></td>
            </tr>

         <tr class= "onblue">
                     <td width="30%">Date accreditation withdrawn (usually HEQC meeting date of final decision) </td>
                      <td> <?php if($this->formFields['withdrawn_decision_date']->fieldValue =="0000-00-00" OR $this->formFields['withdrawn_decision_date']->fieldValue =="1000-01-01")
{$this->formFields['withdrawn_decision_date']->fieldValue ="";
echo $this->showfield("withdrawn_decision_date"); } else{ echo $this->showfield("withdrawn_decision_date"); } ?> </td>
            </tr>

               </tr>

			<tr class= "onblue">
				<td width="30%">Reason for withdrawal of accreditation </td>
				<td> <?php echo $this->showfield("withdrawal_reason")?> </td>
            </tr> 

	
	<tr class="onblue">
		<td class="loud"><br><b>Deferrals</b><br>
		<span class="specialsi">Historic (prior to July 2011) documents may be uploaded here in order to keep a record. 
		Currently deferrals may be uploaded and viewed with the proceedings. </span>
		</td>
		<td><?php $this->makeLink("deferral_doc"); ?></td>
	</tr>
	<tr class="onblue">
		<td class="loud"><br><b>Compliance with conditions documents received from the institution</b><br>
		<span class="specialsi">Historic (prior to July 2011) documents may be uploaded here in order to keep a record. 
		Currently conditions may be uploaded and viewed with the proceedings. </span>
		<td><?php $this->makeLink("condition_doc"); ?></td>
	</tr>
	<tr class="onblue">
		<td class="loud"><br><b>Representations</b><br>
		<span class="specialsi">Historic (prior to July 2011) documents may be uploaded here in order to keep a record. 
		Currently representations may be uploaded and viewed with the proceedings. </span>
		</td>
		<td><?php $this->makeLink("representation_doc"); ?></td>
	</tr>
	<tr class="onblue">
		<td colspan="2">
			Please upload any other documentation pertaining to this application.  The following documents should be uploaded:
			<ul>
				<li>Evaluators reports</li>
				<li>Directorate recommendation</li>
				<li>Letters and outcome</li>
				<li>Site visit report</li>
			</ul>
		
		<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
			<tr>
				<td align="right" colspan="4">
					<?php if ($this->view != 1){
						$newlink = $this->scriptGetForm ('ia_documents', 'NEW', '_label_applicDocumentBasket');
						$anewlink = "<a href='".$newlink."'>>>> Add new document</a>";
						echo $anewlink;
					}?>
				</td>
			</tr>
			<tr class="onblueb">
				<td>Document Title</td>
				<td>Date added</td>
				<td>Edit/Update</td>
			</tr>
<?php 			
                        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                        if ($conn->connect_errno) {
                            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                            printf("Error: %s\n".$conn->error);
                            exit();
                        }

                        $SQL = "SELECT * FROM ia_documents WHERE application_ref=?";
                        
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("s", $application_id);
                        $sm->execute();
                        $rs = $sm->get_result();
			//$rs = mysqli_query($SQL);
	
			$docHtml = "";
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {
					$title = $row["document_title"];
					$doc_ref = $row["application_doc"];
					$dateUpdated  = $this->getValueFromTable("documents", "document_id", $doc_ref , "last_update_date");
					$document = new octoDoc($doc_ref);
					if ($document->url() > ''){
						$docLink	 = "<a href='".$document->url()."' target='_blank'>".$title."</a>";
					} else {
						$docLink = $title;
					}
					$doc_id = $row["ia_documents_id"];
					$editlink = $this->scriptGetForm ('ia_documents', $doc_id, '_label_applicDocumentBasket');
					$aeditlink = "<a href='".$editlink."'>Edit</a>";

					$docHtml .=<<<TEXT
						<tr>
							<td>$docLink</td>
							<td>$dateUpdated</td>
							<td>$aeditlink</td>
						</tr>
TEXT;
				}
				echo $docHtml;
			} else {
				echo "<tr><td> - No documents have been added -</td></tr>";
			}
?>
			</table>
		</td>
	</tr>
	<tr class="onblue">
		<td class="loud" colspan="2">Other processing history for this application</td>
	</tr>
	<tr class="onblue">
		<td width="30%">AC Meeting History</td>
		<td><?php echo $AC_history_link?></td>
	</tr>
	<tr class="onblue">
		<td width="30%">Re-accreditation</td>
		<td>
		<?php
			$html = "No re-accreditation information available";
			$sql = <<<REACCRED
				SELECT Institutions_application_reaccreditation_id, institution_ref, referenceNumber, programme_name, 
				reacc_submission_date, reacc_acmeeting_date, reacc_decision_ref, lkp_reacc_decision.lkp_reacc_title
				FROM Institutions_application_reaccreditation
				LEFT JOIN lkp_reacc_decision ON lkp_reacc_decision.lkp_reacc_id = Institutions_application_reaccreditation.reacc_decision_ref
				WHERE Institutions_application_reaccreditation.referenceNumber = ?
REACCRED;

                        $sm = $conn->prepare($sql);
                        $sm->bind_param("s", $CHE_ref_number);
                        $sm->execute();
                        $rs = $sm->get_result();

			//$rs = mysqli_query($sql);
			if ($rs){
				$num_rows = mysqli_num_rows($rs);
				if ($num_rows > 0){
					$html = <<<HTML
						<table width="95%" border="1">
						<tr class="onblueb">
							<td colspan="2">Programme</td>
							<td>Submission date</td>
							<td>Outcome date</td>
							<td>Outcome</td>
						</tr>
HTML;
					while($row = mysqli_fetch_array($rs)){
						$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["institution_ref"]."&DBINF_institutional_profile___institution_ref=".$row["institution_ref"]."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$row["Institutions_application_reaccreditation_id"];
						$linka = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>';
						$linkp = '<a href="javascript:winPrintReaccProcessInfo(\'Re-accreditation Application Processing Information\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["lkp_reacc_title"].'</a>';

						$html .= <<<HTML
						<tr>
							<td>$linka</td>
							<td>$row[programme_name]</td>
							<td>$row[reacc_submission_date]</td>
							<td>$row[reacc_acmeeting_date]</td>
							<td>$linkp</td>
						</tr>
HTML;
					}
					$html .= "</table>";
				}
			}
			echo $html;
			?>

		</td>
	</tr>
	<tr class="onblue">
		<td width="30%">Proceedings</td>
		<td>
		<?php
			$htmlp = "No proceeding information available";
			$sql = <<<PROCEEDINGS
				SELECT ia_proceedings_id, lkp_proceedings_desc, ac_dec.lkp_title AS ac_decision,
				heqc_dec.lkp_title AS heqc_decision, HEQC_Meeting.heqc_start_date, AC_Meeting.ac_start_date
				FROM ia_proceedings
				LEFT JOIN lkp_desicion AS ac_dec ON ac_dec.lkp_id = ia_proceedings.ac_decision_ref
				LEFT JOIN lkp_desicion AS heqc_dec ON heqc_dec.lkp_id = ia_proceedings.heqc_board_decision_ref
				LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
				LEFT JOIN AC_Meeting ON AC_Meeting.ac_id = ia_proceedings.ac_meeting_ref
				LEFT JOIN HEQC_Meeting ON HEQC_Meeting.heqc_id = ia_proceedings.heqc_meeting_ref
				WHERE application_ref = ?
				ORDER BY prev_ia_proceedings_ref
PROCEEDINGS;

                        $sm = $conn->prepare($sql);
                        $sm->bind_param("s", $application_id);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($sql);
			if ($rs){
				$num_rows = mysqli_num_rows($rs);
				if ($num_rows > 0){
					$htmlp = <<<HTML
						<table border="1" width="95%">
						<tr class="onblueb">
							<td>Proceeding Type</td>
							<td>AC meeting</td>
							<td>AC recommendation</td>
							<td>HEQC meeting</td>
							<td>HEQC Outcome</td>
						</tr>
HTML;
					while($row = mysqli_fetch_array($rs)){
						//$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["institution_ref"]."&DBINF_institutional_profile___institution_ref=".$row["institution_ref"]."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$row["Institutions_application_reaccreditation_id"];
						//$linka = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>';
						//$linkp = '<a href="javascript:winPrintReaccProcessInfo(\'Re-accreditation Application Processing Information\',\''.$row["Institutions_application_reaccreditation_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["lkp_reacc_title"].'</a>';

						$htmlp .= <<<HTML
						<tr>
							<td>$row[lkp_proceedings_desc]</td>
							<td>$row[ac_start_date]</td>
							<td>$row[ac_decision]</td>
							<td>$row[heqc_start_date]</td>
							<td>$row[heqc_decision]</td>
						</tr>
HTML;
					}
					$htmlp .= "</table>";
				}
			}
			echo $htmlp;
			?>

		</td>
	</tr>
	</table>

	</td>
</tr>


</td></tr>
</table>
<br>

