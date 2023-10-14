<?php
$typeSearch = readPOST("typeSearch");
$nameSearch = readPOST("nameSearch");
$companySearch = readPOST("companySearch");
$statusSearch = readPOST("statusSearch");
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: Review Expenditure</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<table width="70%" align="left">
			<tr>
				<td width="20%" align="right" class="loud">Search by: </td>
				<td class="specialb" align="right">Consultant type</td>
				<td>
					<?php 
					$this->formFields["typeSearch"]->fieldValue = $typeSearch;
					$this->showField("typeSearch");
					?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="specialb" align="right">Consultant name or surname</td>
				<td><?php 
					$this->formFields["nameSearch"]->fieldValue = $nameSearch;
					$this->showField("nameSearch"); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="specialb" align="right">Company name</td>
				<td><?php echo 
					$this->formFields["companySearch"]->fieldValue = $companySearch;
					$this->showField("companySearch"); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="specialb" align="right">Status of contract</td>
				<td><?php echo 
					$this->formFields["statusSearch"]->fieldValue = $statusSearch;
					$this->showField("statusSearch"); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_reportReviewExpenditure');"></td>
				<td><input class="btn" type="button" name="Clear" value="Clear Fields" onclick="clearFields()"></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<?php 
		$consult_whr = "";
		$contract_whr = "";

		$consult_whr_arr = array();
		$contract_whr_arr = array();

		if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
		if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
		if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
		if ($statusSearch > 0) array_push($contract_whr_arr,"status = $statusSearch");

		if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
		if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);

		$consult_order = "ORDER BY c.type";

		$sql = <<< SQL
			SELECT c.*,
			IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant
			FROM d_consultants AS c
			$consult_whr
			$consult_order
SQL;

		$rs = mysqli_query($sql);
		if (mysqli_num_rows($rs) > 0){
			$style = "oncolourcolumnheader";
			$html =<<< HTML
				<tr>
					<td class="$style">Consultant type</td>
					<td class="$style">Consultant name</td>
					<td class="$style">Contract<br>Description</td>
					<td class="$style">Manager</td>
					<td class="$style">Start date</td>
					<td class="$style">Completion date</td>
					<td class="$style">Budget</td>
					<td class="$style">YTD Expenses</td>
					<td class="$style">Contract<br>Status</td>
				</tr>
HTML;
			echo $html;
			while ($row = mysqli_fetch_array($rs)){
				$con_id = $row["consultant_id"];
				$name = $row["consultant"];
				$type = $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_id", $row["type"], "lkp_consultant_type_desc");

				$a_order = "";
				$a_sql = <<< AGREE
					SELECT *
					FROM d_consultant_agreements
					WHERE consultant_ref = $con_id
					$contract_whr
					$a_order
AGREE;

				$a_rs = mysqli_query($a_sql);
				if ($a_rs){
					$a_num = mysqli_num_rows($a_rs);
					// print assignments
					if ($a_num > 0){
						while ($a_row = mysqli_fetch_array($a_rs)){
							$assign 	= $a_row["description"];
							$supervisor = $this->displaySupervisor($a_row["che_supervisor_user_ref"]);
							$start  	= $a_row["start_date"];
							$end    	= $a_row["end_date"];
							$c_status 	= $a_row["status"];
							$budget		= $a_row["budget"];
							$eacc		= $a_row["pastel_accnumber"];
							$exp    	= round($this->getSumExpenditure($eacc),2);
							$status = $this->getValueFromTable("lkp_agreement_status", "lkp_agreement_status_id", $c_status, "lkp_agreement_status_desc");

							$html = <<< HTML
							<tr>
							<td class="oncolourcolumn">$type</td>
							<td class="oncolourcolumn">$name</td>
							<td class="oncolourcolumn">$assign</td>
							<td class="oncolourcolumn">$supervisor</td>
							<td class="oncolourcolumn">$start</td>
							<td class="oncolourcolumn">$end</td>
HTML;
							$link = ($eacc > '') ? '<a href="javascript:void window.open(\'pages/rept_pastel_accno.php?ecrit='.$eacc.'\',\'\',\'width=400; height=300 top=300; left=400; resizable=1; scrollbars=1;center=no\');">'.sprintf("%01.2f",$exp).'</a>' : sprintf("%01.2f",$exp);
							$html .= '<td class="oncolourcolumn" align="right">R '.sprintf("%d",$budget).'</td>';
							$html .= '<td class="oncolourcolumn" align="right">R '.$link.'</td>';
							$html .= <<< HTML
							<td class="oncolourcolumn">$status</td>
							</tr>
HTML;


							echo $html;
						}
					}
				}

			}
		} else {
			echo "<tr><td align='center' class='oncolourcolumn'>- No contracts found-</td></tr>";
		}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<script>
	function clearFields(){
		document.defaultFrm.statusSearch.options[0].selected=true;
		document.defaultFrm.typeSearch.options[0].selected=true;			
		document.defaultFrm.nameSearch.value='';
		document.defaultFrm.companySearch.value='';
	}
</script>