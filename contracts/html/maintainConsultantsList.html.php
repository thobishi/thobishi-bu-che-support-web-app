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
	if ($isManager && !$isAdministrator ) {
		$validusr = true;

		$this->formActions["Add"]->actionMayShow = 0;

		$cid_arr = $this->getConsultantIdList($usr);
		$cidlist = (count($cid_arr) > 0) ? implode(",",$cid_arr) : "''";
		array_push($whereArr,"consultant_id IN (".$cidlist.")");
	}
	// Users with the overview role are authorised to view only not to edit
	$isOverview = $this->sec_partOfGroup(4);
	if($isOverview && !$isAdministrator && !$isManager){
		$validusr = true;
		$this->formActions["Add"]->actionMayShow = 0;
	}
	$labelConsultantDetails = ($isOverview && !$isManager && !$isAdministrator) ? "View Consultant details" : "Edit Consultant details";
	$labelContractDetails = ($isOverview && !$isManager && !$isAdministrator) ? "View Contracts" : "Edit Contracts";
?>

<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Consultant Details</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			The following displays a list of consultants currently in the system.
			<ul>
				<li>To view/edit a consultant's <u>contact details</u>, click on the <img src="images/ico_info.gif"> button next to the relevant consultant</li>
				<li>To view/edit a consultant's <u>contracts</u>, click on the <img src="images/ico_print.gif"> button next to the relevant consultant</li>
				<li>To add a consultant that is not already in the list, click on the <img src='images/ico_next.gif'> button in the Actions menu</li>
			</ul>
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
			$activeSearch = "";
		}

		if ($nameSearch != ''){ array_push($haveArr,"consultant LIKE '%".$nameSearch."%'"); }
		if (($typeSearch != '') && ($typeSearch != 0)){ array_push($whereArr,"type=".$typeSearch." "); }
		if (($activeSearch != '') && ($activeSearch != 0)){ array_push($whereArr,"status=".$activeSearch." "); }

		if (count($whereArr) > 0) $where = "WHERE " . implode(" AND ",$whereArr);
		if (count($haveArr) > 0) $having = "HAVING " . implode(" AND ",$haveArr);

		$SQL = <<<Sql
			SELECT consultant_id,
			CONCAT(surname,", ",name,IF (company > '',CONCAT(" (",company,")"),"")) AS consultant,
			email,
			type,
			status,
			(select count(*) from d_consultant_agreements a where a.consultant_ref = c.consultant_id ) as n_contracts
			FROM d_consultants c
			$where
			$having
			ORDER BY consultant
Sql;

//echo $SQL;

?>
		<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td width="20%" align="right" class="loud">Search by: </td>
			<td align="right" class="specialb" width="20%">Consultant or company name:</td>
			<td><?php 
				$this->formFields['nameSearch']->fieldValue = $nameSearch;
				$this->showField('nameSearch');?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right" class="specialb">Type of consultant:</td>
			<td><?php 
				$this->formFields['typeSearch']->fieldValue = $typeSearch;
				$this->showField('typeSearch');?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right" class="specialb">Active/inactive consultants:</td>
			<td><?php 
				$this->formFields['activeSearch']->fieldValue = $activeSearch;
				$this->showField('activeSearch');?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_startConsultantDisplayList');"></td>
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
						<td class="oncolourcolumnheader" colspan="<?php echo echo $n_cols; ?>" align="right">Number of consultants: <?php echo echo $nc; ?></td>
					</tr>
					<tr>
						<td class="oncolourcolumnheader" align="center"><?php echo $labelConsultantDetails;?></td>
						<td class="oncolourcolumnheader" align="center"><?php echo $labelContractDetails;?></td>
						<td class="oncolourcolumnheader">Name</td>
						<td class="oncolourcolumnheader">Email</td>
						<td class="oncolourcolumnheader">Type of consultant</td>
						<td class="oncolourcolumnheader">Status</td>
						<td class="oncolourcolumnheader">Number<br>contracts</td>						
						<?php 
						if ($isAdministrator){
							echo '<td width="5%" class="oncolourcolumnheader"><strong>Delete Consultant</strong></td>';
						}
						?>
					</tr>
					
	<?php 
			if ($nc > 0){
				while ($row = mysqli_fetch_array($rs)){
					$con_id = $row["consultant_id"];
					$consultant = $row["consultant"];
					$email = $row["email"];
					$type = $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_id", $row["type"], "lkp_consultant_type_desc");
					$status = $this->getValueFromTable("lkp_status", "lkp_status_id", $row["status"], "lkp_status_desc");
					$n_contracts = $row["n_contracts"];
					$html = <<< HTML
						<tr>
							<td class="oncolourcolumn" align="center" width="10%">
								<a href="javascript:setConsultant('$con_id');moveto('_startConsultantDetails');"><img src="images/ico_info.gif" border="0"></a>
							</td>
							<td class="oncolourcolumn" align="center" width="10%">
								<a href="javascript:setConsultant('$con_id');moveto('_agreementsList');"><img src="images/ico_print.gif" border="0"></a>
							</td>
							<td class="oncolourcolumn">$consultant</td>
							<td class="oncolourcolumn">$email</td>
							<td class="oncolourcolumn">$type</td>
							<td class="oncolourcolumn">$status</td>
							<td class="oncolourcolumn">$n_contracts</td>
HTML;
							// If user is the Administrator and this consultant has no contracts then allow delete.
							if ($isAdministrator){
								if ($n_contracts == 0){
									$html .= '<td class="oncolourcolumn"><a href="javascript:delConsultant('. $con_id .',\''. $consultant .'\')">[delete]</a></td>';
								}else{
									$html .= '<td class="oncolourcolumn">&nbsp;</td>';
								}
							}
					$html .= '	</tr>';
					echo $html;
				}
			} else {
				$html =<<< HTML
				<tr>
					<td colspan="6" align="center">- No consultants have been found. Possible reasons are that contracts have not been assigned to you or that consultants were not found for the search criteria entered. -</td>
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


