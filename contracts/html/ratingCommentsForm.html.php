<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="3">
			<span class="loud">Performance/progress of contracts</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>

<?php
	$user_id = $this->currentUserID;

	if($user_id > ""){
		$whr_arr = array();

		// Administrators may see all contracts
		// All other users may only see contracts that they supervise
		$isAdmin = $this->is_userPartOfGroup(1,$user_id);
		$isOverview = $this->is_userPartOfGroup(4,$user_id);
		$isManager = $this->is_userPartOfGroup(3,$user_id);
		if (!$isAdmin && !$isOverview){
			array_push($whr_arr, "che_supervisor_user_ref = $user_id ");
		}

		$where = (count($whr_arr)>0) ? implode(" AND ",$whr_arr) : "1";

		$sql = "SELECT d_consultant_agreements.che_supervisor_user_ref,
						d_consultants.type,
						lkp_consultant_type.lkp_consultant_type_desc,
						concat(d_consultants.name,' ',d_consultants.surname) as name,
						d_consultants.company,
						d_consultant_agreements.agreement_id,
						d_consultant_agreements.description
				FROM d_consultant_agreements
				LEFT JOIN d_consultants ON  d_consultants.consultant_id = d_consultant_agreements.consultant_ref
				LEFT JOIN lkp_consultant_type ON d_consultants.type= lkp_consultant_type.lkp_consultant_type_id
				WHERE $where
				ORDER BY d_consultant_agreements.description ASC,d_consultant_agreements.quality_work ASC, d_consultants.name ASC";

		$rs = mysqli_query($sql) or die(mysqli_error());
		$rateLabel = ($isOverview && !$isManager) ? "" : '<td class="oncolourcolumnheader">Rate</td>';
		
	
		if(mysqli_num_rows($rs) > 0){
			echo "<br>";
			echo "<table border=\"0\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
			echo "<tr>
						$rateLabel
						<td class=\"oncolourcolumnheader\">Contract description</td>
						<td class=\"oncolourcolumnheader\">Consultant name</td>
						<td class=\"oncolourcolumnheader\">Company</td>
						<td class=\"oncolourcolumnheader\">Consultant type</td>
						<td class=\"oncolourcolumnheader\">Manager</td>
			</tr>";
			while($rows = mysqli_fetch_array($rs)){
			    $con_id = $rows["agreement_id"];
				$rateLink = ($isOverview && !$isManager) ? "" : '<td class="oncolourcolumn">' . '<a href="javascript:setContract('.$con_id.');moveto(\'_ratingCommForm\');">rate</a></td>'; 
			   	$consultant = $rows['name']; // service provider or individual
				$company =  $rows['company'];
				$type_desc =  $rows['lkp_consultant_type_desc'];
				$sup = $this->displaySupervisor($rows["che_supervisor_user_ref"]);
				echo "<tr>
						$rateLink
						<td class=\"oncolourcolumn\">".$rows['description']."</td>
						<td class=\"oncolourcolumn\">".$consultant."</td>
						<td class=\"oncolourcolumn\">".$company."</td>
						<td class=\"oncolourcolumn\">".$type_desc."</td>
						<td class=\"oncolourcolumn\">".$sup."</td>
					</tr>";
			}
			echo "</table>";
			echo "<br>";
		}
	}
?>
	</td>
	</tr>
</table>
<!--
<td class=\"oncolourcolumn\"><a href=\"javascript:setContract('".$con_id."');moveto('_ratingCommForm');\">rate</a></td>
<td class=\"oncolourcolumnheader\">Rate</td> -->
<script>
function setContract(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_consultant_agreements|'+val;
}
</script>
