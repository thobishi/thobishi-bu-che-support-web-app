<?php 
	$cons_id = $this->dbTableInfoArray["d_consultants"]->dbTableCurrentID;
	$consultant_name = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "name")." ".$this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "surname");
	$company = $this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "company");
	$consultant = ($this->getValueFromTable("d_consultants", "consultant_id", $cons_id, "type") == "2") ? $company : $consultant_name;
	$this->showField("contract_status");

	$usr = $this->currentUserID;

	$where = "";
	$whereArr = array("consultant_ref = ".$cons_id);

	$a_active = '<a href="javascript:document.defaultFrm.contract_status.value=\'Active\';moveto(\'stay\');">Active</a>';
	$a_inactive = '<a href="javascript:document.defaultFrm.contract_status.value=\'Expired\';moveto(\'stay\');">Inactive</a>';
	$a_all = '<a href="javascript:document.defaultFrm.contract_status.value=\'All\';moveto(\'stay\');">All</a>';

	$action = readPost('contract_status');
//echo $action;
	switch ($action){
	case 'Expired':
		array_push($whereArr,"status = 2");  // expired
		break;
	case 'All': // No restriction
		break;
	default:
		array_push($whereArr,"status = 1");  // default is active agreements
	}

	$isAdministrator = $this->sec_partOfGroup(1);
	// Managers may only view consultants that are assigned to them.
	$isManager = $this->sec_partOfGroup(3);

	// Users with the overview role are authorised to view only not to edit
	$isOverview = $this->sec_partOfGroup(4);
	$edit_viewLabel = ($isOverview && !$isAdministrator && !$isManager) ? "View" : "Edit";
	if ($isOverview){
		$this->formActions["Add"]->actionMayShow = 0;
	}
	if ($isManager) {
		$this->formActions["Add"]->actionMayShow = 0;
		array_push($whereArr,"che_supervisor_user_ref = ".$usr);
	}

	$where = "WHERE " . implode(" AND ", $whereArr);
//	$agreement_id = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<?php echo 
				echo $this->displayContractHeader($cons_id,"","List of Contracts");
			?>
			<hr>
		</td>
	</tr>
	<tr>
		<td align="right">
			<?php echo 
				echo $a_active . " | " . $a_inactive . " | " . $a_all;
			?>
		</td>
	</tr>
	<tr>
		<td>
<?php 
	$SQL = "SELECT * FROM d_consultant_agreements $where";

	$rs = mysqli_query($SQL);

	if (mysqli_num_rows($rs) > 0) {
		while($row = mysqli_fetch_array($rs)) {
			$inner = "";
			$idnumber = $row["idnumber"];
			$description = $row["description"];
			$che_supervisor = $this->displaySupervisor($row["che_supervisor_user_ref"]);
			$start_date = $row["start_date"];
			$end_date = $row["end_date"];
			$service = $this->getValueFromTable("lkp_service_delivery","lkp_service_delivery_id", $row["service_delivery_ref"],"lkp_service_delivery_desc");
//			$acc_no = $row["pastel_accnumber"];
			$duration = $row["duration"];
			$payment_rate = $row["payment_rate"];
			$budget = $row["budget"];
			$expenditure = $row["expenditure"];
			$agreement_id = $row["agreement_id"];
			$status = $this->getValueFromTable("lkp_agreement_status", "lkp_agreement_status_id", $row["status"], "lkp_agreement_status_desc");

			// If user is the Administrator and this consultant has no contracts then allow delete.
			$delLink = "";
			if ($isAdministrator){
				$delLink = '<tr><td valign="middle" class="oncolourcolumn" colspan="3"><span style="float:left">Click on delete if you want to delete <b>'.$description.'</b> contract</span><a style="float:right" href="javascript:delContract('. $agreement_id .',\''. $description .'\')">[delete]</a></td></tr>';
			}

			$html = <<< HTML
				<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
					<tr>
						<td class="oncoloursoft" rowspan="16" valign="top" width="5%" align="center"><a href="javascript:setAgreement('$agreement_id');moveto('_addAgreement');">$edit_viewLabel</a></td>
						<td class="oncolourcolumnheader" width="25%" valign="top">Contract Idnumber:</td>
						<td class="oncolourcolumn" valign="top">$idnumber</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" width="25%" valign="top">Contract Description:</td>
						<td class="oncolourcolumn" valign="top">$description</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Manager:</td>
						<td class="oncolourcolumn" valign="top">$che_supervisor</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Start date:</td>
						<td class="oncolourcolumn" valign="top">$start_date</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Expiry/termination date:</td>
						<td class="oncolourcolumn" valign="top">$end_date</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Service Delivery Agreement:</td>
						<td class="oncolourcolumn" valign="top">$service</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Fees from Annexure B:</td>
						<td class="oncolourcolumn" valign="top">$payment_rate</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Duration:</td>
						<td class="oncolourcolumn" valign="top">$duration</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Budget:</td>
						<td class="oncolourcolumn" valign="top">$budget</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Documents:</td>
						<td class="oncolourcolumn" valign="top">
							<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
HTML;
?>
<!-- 2009-05-27: Robin - Removed because cannot get a direct match.  Will relook at it when Pastel Evolution is used.
					<tr>
						<td class="oncolourcolumnheader" valign="top">Pastel extraction criteria:</td>
						<td class="oncolourcolumn" valign="top">$acc_no</td>
					</tr>
-->
<?php 

			echo $html;

			$docSQL = "SELECT * FROM d_agreement_docs WHERE agreement_ref=".$agreement_id;
			$docRs = mysqli_query($docSQL);
			if (mysqli_num_rows($docRs) > 0) {
				$html =<<< HTML
					<tr>
						<td class="oncolourcolumnheader">Title</td>
						<td class="oncolourcolumnheader">Type</td>
						<td class="oncolourcolumnheader">Date added</td>
					</tr
HTML;
				echo $html;
				while ($docRow = mysqli_fetch_array($docRs)) {
					$title = $docRow["document_title"];
					$document 	= new octoDoc($docRow['agreement_doc']);
					$docLink	= "<a href='".$document->url()."' target='_blank'>".$title."</a>";
					$docType 	= $this->getValueFromTable("lkp_document_type", "lkp_document_type_id", $docRow["document_type_ref"], "lkp_document_type_desc");
					$dateUpdated = $this->getValueFromTable("documents", "document_id", $docRow["agreement_doc"], "last_update_date");
					$inner = <<< TEXT
						<tr>
							<td class="oncoloursoft" width="50%">$docLink</td>
							<td class="oncoloursoft">$docType</td>
							<td class="oncoloursoft">$dateUpdated</td>
						</tr>
TEXT;
					echo $inner;
				}
			} else {
				echo "<tr><td> - No documents have been added -</td></tr>";
			}

			$html = <<< HTML
							</table>
						</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Comments on contract:</td>
						<td class="oncolourcolumn" valign="top">
							<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
HTML;
			echo $html;
			$commentSQL = "SELECT * FROM d_agreement_comments WHERE agreement_ref=".$agreement_id." ORDER BY comment_date";
			$commentRs = mysqli_query($commentSQL);
			if (mysqli_num_rows($commentRs) > 0) {
				$html =<<< HTML
					<tr>
						<td class="oncolourcolumnheader" width="50%">Comment Excerpt</td>
						<td class="oncolourcolumnheader">Date comment added</td>
					</tr
HTML;
				echo $html;
				while ($commentRow = mysqli_fetch_array($commentRs)) {
					$comment_excerpt = substr($commentRow['comment'], 0, 75)."...";
					$comment_id = $commentRow["agreement_comment_id"];
					$comment = '<a href="javascript:void window.open(\'pages/viewComment.php?item_id='.$comment_id.'&table=d_agreement_comments&return_field=comment&id_name=agreement_comment_id\',\'\',\'width=600; height=500 top=100; left=100; resizable=1; scrollbars=1;center=no\');">'.$comment_excerpt.'</a>';
					$date_added = $commentRow["comment_date"];
					$inner = <<< TEXT
						<tr>
							<td class="oncoloursoft">$comment</td>
							<td class="oncoloursoft">$date_added</td>
						</tr>
TEXT;
					echo $inner;
				}
			} else {
				echo "<tr><td> - No comments have been made -</td></tr>";
			}

			$html = <<< HTML
							</table>
						</td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" valign="top">Contract status:</td>
						<td class="oncolourcolumn" valign="top">$status</td>
					</tr>
					$delLink
				</table>
				<br>
HTML;
			echo $html;
		}
	} else {
		echo "There are no active contracts for this consultant. Please click on the All or Inactive links to see expired contracts.";
	}

?>
		</td>
	</tr>
</table>
<br>

<script>
function setAgreement(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_consultant_agreements|'+val;
}
</script>




