<?php 
	// Assume all users are not valid and are thus not authorised to view consultants.
	$validusr = false;

	$where = "";
	$having = "";
	$haveArr = array();
	$whereArr = array();

	$usr = $this->currentUserID;

	// Administrators are authorised to view/edit consultants
	$isAdministrator = $this->sec_partOfGroup(1);
	if ($isAdministrator) $validusr = true;

	// Managers are authorised to view consultants that are assigned to them.
	$isManager = $this->sec_partOfGroup(3);
	if ($isManager && !$isAdministrator) {
		$validusr = true;

		$this->formActions["Add"]->actionMayShow = 0;

		$cid_arr = $this->getConsultantIdList($usr);
		$cidlist = (count($cid_arr) > 0) ? implode(",",$cid_arr) : "''";
		array_push($whereArr,"consultant_id IN (".$cidlist.")");
	}
	// Users with the overview role are authorised to view only not to edit
	$isOverview = $this->sec_partOfGroup(4);
	$edit_viewLabel = ($isOverview && !$isAdministrator && !$isManager) ? "View" : "Edit";
	if ($isOverview  && !$isAdministrator && !$isManager) {
		$validusr = true;
		$this->formActions["Add"]->actionMayShow = 0;
	}	
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Contract Details</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			The following displays a list of active contracts currently in the system.
		</td>
	</tr>
</table>
<?php 
	if ($validusr === false){
		echo "You are not authorised to view consultants and their contracts.  Please contact the Contract Register Administrator.";
	}

	if ($validusr === true){

		if (readPOST('submitButton') || isset($_POST["nameSearch"])){
			$nameSearch = readPOST('nameSearch');
			$typeSearch = readPOST('typeSearch');
			$activeSearch = readPOST('activeSearch');
		} else {
			$nameSearch = "";
			$typeSearch = "";
			$activeSearch = "1";
		}

		if ($nameSearch != ''){ array_push($haveArr,"contact LIKE '%".$nameSearch."%' OR company LIKE '%".$nameSearch."%'"); }
		if (($typeSearch != '') && ($typeSearch != 0)){ array_push($whereArr,"c.type=".$typeSearch." "); }
		if (($activeSearch != '') && ($activeSearch != 0)){ array_push($whereArr,"a.status=".$activeSearch." "); }

		if (count($whereArr) > 0) $where = "WHERE " . implode(" AND ",$whereArr);
		if (count($haveArr) > 0) $having = "HAVING " . implode(" AND ",$haveArr);

		$SQL = <<<Sql
			SELECT 
				a.agreement_id,
				a.idnumber,
				a.description,
				a.start_date,
				c.company,
				CONCAT(c.surname,", ",c.name) AS contact,
				c.consultant_id,
				c.type,
				a.end_date,
				a.budget,
				a.pastel_accnumber,
				a.expenditure,
				a.status
			FROM d_consultant_agreements a
			LEFT JOIN d_consultants c ON c.consultant_id = a.consultant_ref
			$where
			$having
			ORDER BY a.idnumber
Sql;

//echo $SQL;

?>
		<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td width="20%" align="right" class="loud">Search by: </td>
			<td align="right" class="specialb" width="20%">Consultant or company name:</td>
			<td><?php 
				$this->formFields['nameSearch']->fieldValue = $nameSearch;
				$this->showField('nameSearch');
				?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right" class="specialb">Type:</td>
			<td><?php 
				$this->formFields['typeSearch']->fieldValue = $typeSearch;
				$this->showField('typeSearch');
				?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right" class="specialb">Active/inactive contracts:</td>
			<td><?php 
				$this->formFields['activeSearch']->fieldValue = $activeSearch;
				$this->showField('activeSearch');
				?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_startContractsList');"></td>
			<td><input class="btn" type="button" name="Clear" value="Clear Fields" onclick="clearFields()"></td>
		</tr>
		</table>
<?php 
		$rs = mysqli_query($SQL);
		if ($rs){
			$nc = mysqli_num_rows($rs);
			$n_cols = ($isAdministrator) ? 8 : 7;
?>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
			<tr>
				<td>
					<hr><br>
					<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
					<tr>
						<td class="oncolourcolumnheader" colspan="12" align="right">Number of contracts: <?php echo echo $nc; ?></td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader"><?php echo $edit_viewLabel; ?></td>
						<td class="oncolourcolumnheader" align="center">Contract<br>Id</td>
						<td class="oncolourcolumnheader" align="center">Description</td>
						<td class="oncolourcolumnheader" align="center">Date</td>
						<td class="oncolourcolumnheader" align="center">Company</td>
						<td class="oncolourcolumnheader">Contact person</td>
						<td class="oncolourcolumnheader">Expiry date</td>
						<td class="oncolourcolumnheader">Type</td>
						<td class="oncolourcolumnheader">Budget</td>
						<td class="oncolourcolumnheader">Expenditure to date</td>
						<td class="oncolourcolumnheader">Status</td>
						<td class="oncolourcolumnheader">Comments</td>
					</tr>
	<?php 
			if ($nc > 0){
				while ($row = mysqli_fetch_array($rs)){
					$agreement_id = $row["agreement_id"];
					$con_id = $row["consultant_id"];
					$contact = $row["contact"];
					$idnumber = $row["idnumber"];
					$descrip = $row["description"];
					$start_date = $row["start_date"];
					$end_date = $row["end_date"];
					$company = $row["company"];
					$budget = $row["budget"];
					//$pastel_accnumber = $row["pastel_accnumber"];
					//<td class="oncolourcolumnheader">Pastel <br>Account No.</td>
					//<td class="oncolourcolumn">$pastel_accnumber</td>
					$exp = $row["expenditure"];
					$type = $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_id", $row["type"], "lkp_consultant_type_desc");
					$status = $this->getValueFromTable("lkp_status", "lkp_status_id", $row["status"], "lkp_status_desc");
					$edit_link = $this->scriptGetForm("d_consultant_agreements", $agreement_id, "_addAgreement_ind");
					$comments = $this->getComments($agreement_id);

					$html = <<< HTML
						<tr>
							<td class="oncolourcolumn" align="center">
								<a href='$edit_link'>
									<img src="images/ico_change.gif" border=0>
								</a>
							</td>
							<td class="oncolourcolumn">$idnumber</td>
							<td class="oncolourcolumn">$descrip</td>
							<td class="oncolourcolumn">$start_date</td>
							<td class="oncolourcolumn">$company</td>
							<td class="oncolourcolumn">$contact</td>
							<td class="oncolourcolumn">$end_date</td>
							<td class="oncolourcolumn">$type</td>
							<td class="oncolourcolumn">$budget</td>
							<td class="oncolourcolumn">$exp</td>
							<td class="oncolourcolumn">$status</td>
							<td class="oncolourcolumn">$comments</td>
HTML;

					$html .= '	</tr>';
					echo $html;
				}
			} else {
				$html =<<< HTML
				<tr>
					<td colspan="6" align="center">- No contracts have been found. Possible reasons are that contracts have not been assigned to you or that contracts were not found for the search criteria entered. -</td>
				</tr>
HTML;
				echo $html;
			}
	?>
					</table>
				</td>
			</tr>
			</table>
			<br>
<?php 
		}  // end valid rs
	}  // end (validusr === true)
?>

<script>
	function setConsultant(val){
		document.defaultFrm.CHANGE_TO_RECORD.value='d_consultants|'+val;
	}
	function clearFields(){
		document.defaultFrm.activeSearch.options[0].selected=true;
		document.defaultFrm.typeSearch.options[0].selected=true;			
		document.defaultFrm.nameSearch.value='';
	}
</script>


