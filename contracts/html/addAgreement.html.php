<?php 
	$isAdministrator = $this->sec_partOfGroup(1);

	// Managers may only VIEW contracts that are assigned to them.
	$isManager = $this->sec_partOfGroup(3);
	
	// Users with the overview role are authorised to view only not to edit
	$isOverview = $this->sec_partOfGroup(4);
	if ($isManager || $isOverview) {
		$this->view = 1;
		$this->formStatus = FLD_STATUS_TEXT;
		
		$this->formActions["stay"]->actionMayShow = 0;
		$this->formActions["next"]->actionMayShow = 0;
	}
	
	$cons_id = $this->dbTableInfoArray["d_consultants"]->dbTableCurrentID;
//echo "Consultants Id: " . $cons_id . "<br>";
	$this->formFields["consultant_ref"]->fieldValue = $cons_id;
	$this->showField("consultant_ref");
	$agreement_id = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
//echo "Consultants Agreements: " . $agreement_id . "<br>";
	$docHtml = "";
	$consultant_name = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "name")." ".$this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "surname");
	$company = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "company");
	$consultant = ($this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "type") == "2") ? $company : $consultant_name;
	$init_display_len = 100;  // Number of characters to display for a comment

	/*
		put javascript/php on that checks that termination date matches with drop down
	*/
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<?php echo echo $this->displayContractHeader($cons_id,"","Add / Edit Contracts"); ?>
			<hr>
		</td>
	</tr>
	<tr>
		<td width="25%" class="oncolourcolumnheader">Contract Idnumber:</td>
		<td class="oncolourcolumn">
			<?php 
				// 2009-03-05 Robin: Calculate idnumber for new records. Display idnumber for existing records.
				if ($agreement_id == 'NEW') $this->formFields["idnumber"]->fieldValue = $this->calcNextIdnumber();
				$this->showField("idnumber");
				echo $this->formFields["idnumber"]->fieldValue;
			?>
		</td>
	</tr>
	<tr>
		<td width="25%" class="oncolourcolumnheader">Contract Description:</td>
		<td class="oncolourcolumn"><?php echo $this->showField("description"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Manager:<br><i>If a manager does not appear in the list add manager as a user first (Admin/Manage users).</i></td>
		<td class="oncolourcolumn"><?php echo $this->showField("che_supervisor_user_ref"); ?></td>
	</tr>
<!--
	<tr>
		<td class="oncolourcolumnheader">Manager name:</td>
		<td class="oncolourcolumn"><?php echo //$this->showField("che_supervisor") ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Manager email:</td>
		<td class="oncolourcolumn"><?php echo //$this->showField("che_supervisor_email") ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Manager telephone no.:</td>
		<td class="oncolourcolumn"><?php echo //$this->showField("che_supervisor_tel") ?></td>
	</tr>
-->
	<tr>
		<td class="oncolourcolumnheader">Status:</td>
		<td class="oncolourcolumn"><?php echo $this->showField("status"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Start date:</td>
		<td class="oncolourcolumn"><?php echo $this->showField("start_date"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Expiry/termination date:</td>
		<td class="oncolourcolumn"><?php echo $this->showField("end_date"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Service Delivery Agreement:</td>
		<td class="oncolourcolumn"><?php echo $this->showField("service_delivery_ref"); ?></td>
	</tr>
<!-- 2009-05-27: Robin - Removed because cannot get a direct match.  Will relook at it when Pastel Evolution is used.
	<tr>
		<td class="oncolourcolumnheader" colspan="2">
		<?php 
		//$link = '<a href="javascript:void window.open(\'pages/reportPastelAccnumbers.html.php\',\'\',\'width=700; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'."View Pastel Account Numbers".'</a>';
		?>
		<i>The following information is needed to identify and extract the financial information
		for this contract.  The required format is accno:descr where accno is the account number and descr is the required
		description.  Multiple values may be entered by separating them with a semi-colon.
		<ul>
			<li>For Service Providers you only need to list the account number/s because they have unique pastel account numbers assigned to them.
			</li>
			<li>Consultant costs are assigned to projects with the line item code 0215.  To extract data for consultants the pastel account number and the description
			identifying the person is required e.g. 0215201:AB Heyns;0215005:A B Heyns;0215227:AB Heyns.
			</li>
			<li> //echo $link; </li>
		</ul>
		</i>
		</td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Pastel extraction criteria<br>
		</td>
		<td class="oncolourcolumn"> // $this->showField("pastel_accnumber"); </td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader" colspan="2">&nbsp;
		</td>
	</tr>
-->
	<tr>
		<td class="oncolourcolumnheader">Fees from Annexure B:<br><i>e.g. rate per hour or rate per day</i></td>
		<td class="oncolourcolumn"><?php echo $this->showField("payment_rate"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Duration:<br><i>e.g. maximum of 10 days</i></td>
		<td class="oncolourcolumn"><?php echo $this->showField("duration"); ?></td>
	</tr>
	<tr>
		<td class="oncolourcolumnheader">Budget (R):<br><i>Enter a numeric value (no spaces or commas) <br>e.g. 100000</i></td>
		<td class="oncolourcolumn"><?php echo $this->showField("budget"); ?></td>
	</tr>
	<tr>
		<td valign="top" class="oncolourcolumnheader">Documents:</td>
		<td class="oncolourcolumn">
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<?php 
	if ($agreement_id == "NEW") {
?>
			<tr>
				<td class="oncoloursoft" align="right" colspan="4">
					This is a new contract. Please save it in order to add documents (click on Save in the Actions menu).</a>
				</td>
			</tr>
<?php 
	}
?>
<?php 
	if ($agreement_id != "NEW") {

				if(($isOverview && $isManager) || $isAdministrator){
?>				
				<tr>
					<td class="oncoloursoft" align="right" colspan="4">
						<a href="javascript:newAgreementDoc('NEW');moveto('_uploadAgreementDocument');">>> Add new document</a>
					</td>
				</tr>
<?php 				
				}
?>				
				<tr>
					<td class="ongreycolumnheader">Title</td>
					<td class="ongreycolumnheader">Type</td>
					<td class="ongreycolumnheader">Date added</td>
					<td class="ongreycolumnheader">Edit/Update</td>
				</tr>
<?php 
			$SQL = "SELECT * FROM d_agreement_docs WHERE agreement_ref=".$agreement_id;

			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {
					$title = $row["document_title"];
					$dateUpdated  = $this->getValueFromTable("documents", "document_id", $row["agreement_doc"], "last_update_date");
					$document = new octoDoc($row['agreement_doc']);
					$docLink	 = "<a href='".$document->url()."' target='_blank'>".$title."</a>";
					$docType = $this->getValueFromTable("lkp_document_type", "lkp_document_type_id", $row["document_type_ref"], "lkp_document_type_desc");
					$doc_id = $row["agreement_doc_id"];
					$editUploadAgr = ($isOverview && !$isAdministrator && !$isManager) ? " ":'<a href="javascript:newAgreementDoc('.$doc_id.');moveto(\'_uploadAgreementDocument\');">Edit</a>';
					$docHtml .=<<< TEXT
						<tr>
							<td class="ongreycolumn">$docLink</td>
							<td class="ongreycolumn">$docType</td>
							<td class="ongreycolumn">$dateUpdated</td>
							<td class="ongreycolumn">$editUploadAgr</td>
						</tr>
TEXT;
				}
				echo $docHtml;
			} else {
				echo "<tr><td> - No documents have been added -</td></tr>";
			}
	}
?>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" class="oncolourcolumnheader">Comments on contract:</td>
		<td class="oncolourcolumn">
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
			<?php 
			if ($agreement_id == "NEW") {
			?>
				<tr>
					<td class="oncoloursoft" align="right" colspan="3">
						This is a new contract. Please save it in order to add comments (click on Save in the Actions menu).</a>
					</td>
				</tr>
			<?php 
			}

			if ($agreement_id != "NEW") {
			
				if(($isOverview && $isManager) || $isAdministrator){
			?>

				<tr>
					<td class="oncoloursoft" align="right" colspan="4">
						<a href="javascript:newAgreementComment('NEW');moveto('_addAgreementComment');">>> Add new comment</a>
					</td>
				</tr>
			<?php 	
				}
			?>
				<tr>
					<td class="ongreycolumnheader">Comment Excerpt</td>
					<td class="ongreycolumnheader">Date comment added</td>
					<td class="ongreycolumnheader" colspan="2">Edit / Update</td>
				</tr>
				<?php 
				$SQL = "SELECT * FROM d_agreement_comments WHERE agreement_ref=".$agreement_id." ORDER BY comment_date";
				$rs = mysqli_query($SQL);
				if (mysqli_num_rows($rs) > 0) {
					while ($row = mysqli_fetch_array($rs)) {
	
						$comment_id = $row["agreement_comment_id"];
						$date_added = $row["comment_date"];
	
						$comment_len = strlen($row['comment']);
						$comment = $row['comment'];
						if ($comment_len > $init_display_len){  // add link to full comment if comment longer than displayed length
							$comment_excerpt = substr($row['comment'], 0, $init_display_len);
							$comment = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$comment_id.'&table=d_agreement_comments&return_field=comment&id_name=agreement_comment_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a>';
						}

						// Only administrators may delete comments
						$delete_comment = "";
						if ($isAdministrator){
							$delete_comment = '<td class="ongreycolumn">' .
								'<a href="javascript:newAgreementComment('.$comment_id.');moveto(\'_addAgreementComment\');">Delete</a>'.
								'</td>';
						}
							$editComment = ($isOverview && !$isAdministrator && !$isManager) ? " ":'<a href="javascript:newAgreementComment('.$comment_id.');moveto(\'_addAgreementComment\');">Edit</a>';
						$html =<<< TEXT
							<tr>
								<td class="ongreycolumn">$comment</td>
								<td class="ongreycolumn">$date_added</td>
								<td class="ongreycolumn">
								$editComment
								</td>
								$delete_comment
							</tr>
TEXT;
						echo $html;
					}  //end while
	
				} else {
					echo "<tr><td> - No comments have been made -</td></tr>";
				}
			
			} // end agreement != NEW
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" class="oncolourcolumnheader">Performance rating of contract:</td>
		<td class="oncolourcolumn">
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
			<?php 
			if ($agreement_id == "NEW") {
			?>
				<tr>
					<td class="oncoloursoft" align="right" colspan="6">
						This is a new contract. Please save it in order to view performance rating (click on Save in the Actions menu).</a>
					</td>
				</tr>
			<?php 
			}

			if ($agreement_id != "NEW") {
				if(($isOverview && $isManager) || $isAdministrator){
			?>
				<tr>
					<td class="oncoloursoft" align="right" colspan="6">
						<a href="javascript:newAgreementPerformance('NEW');moveto('_rateContractPerformance');">>> Rate performance</a>
					</td>
				</tr>
			<?php 
				}
			?>
				<tr>
					<td class="ongreycolumnheader">Date rated</td>
					<td class="ongreycolumnheader">Milestones/<br>Deadlines</td>
					<td class="ongreycolumnheader">Meeting the<br>Requirements</td>
					<td class="ongreycolumnheader">Quality of Work</td>
					<td class="ongreycolumnheader">Comments</td>
					<td class="ongreycolumnheader">Edit / Update</td>
				</tr>
				<?php 
				$SQL = "SELECT * FROM owners_comments WHERE agreement_ref=".$agreement_id." ORDER BY comment_date";
				$rs = mysqli_query($SQL);
				if (mysqli_num_rows($rs) > 0) {
					while ($row = mysqli_fetch_array($rs)) {
	
						$rate_id = $row["comment_id"];
						$rate_date = $row["comment_date"];
						$editPerformance = ($isOverview && !$isAdministrator && !$isManager) ? " ":'<a href="javascript:newAgreementPerformance('.$rate_id.');moveto(\'_rateContractPerformance\');">Edit</a>';
						$rate_comment_len = strlen($row['CHEcomment']);
						$rate_comment = $row['CHEcomment'];
						if ($rate_comment_len > $init_display_len){  // add link to full comment if comment longer than displayed length
							$rate_comment_excerpt = substr($rate_comment, 0, $init_display_len);
							$rate_comment = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$rate_id.'&table=d_agreement_comments&return_field=comment&id_name=agreement_comment_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$rate_comment_excerpt.'</a>';
						}
	
						$html =<<< TEXT
							<tr>
								<td class="ongreycolumn">$rate_date</td>
								<td class="ongreycolumn">$row[deliverydate_deadlines]</td>
								<td class="ongreycolumn">$row[meeting_requirements]</td>
								<td class="ongreycolumn">$row[quality_work]</td>
								<td class="ongreycolumn">$rate_comment</td>
								<td class="ongreycolumn">$editPerformance</td>
	
							</tr>
TEXT;
						echo $html;
					}  //end while
	
				} else {
					echo "<tr><td> - No ratings have been made -</td></tr>";
				}
			
			} // end agreement != NEW
			?>
			</table>
		</td>
	</tr>
</table>


<br>

<script>
function newAgreementDoc(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_agreement_docs|'+val;
}
function newAgreementComment(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_agreement_comments|'+val;
}
function newAgreementPerformance(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='owners_comments|'+val;
}
</script>
