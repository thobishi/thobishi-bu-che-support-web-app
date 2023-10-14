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
	$user_id = isset($_SESSION['ses_userid'])? $_SESSION['ses_userid']: "";

	$supervisor_email = "";
	$AND = "";
	if($user_id > ""){
		//Get supervisor's from d_consultant_agreements's table in order to know the supervisor
		$where = "";
		$SQL = "SELECT d_consultant_agreements.che_supervisor_email
				FROM d_consultant_agreements
				LEFT JOIN users ON d_consultant_agreements.che_supervisor_email = users.email
				WHERE users.user_id = ".$user_id;

		$query = mysqli_query($SQL) or die(mysqli_error());
		if(mysqli_num_rows($query) > 0){
			$userIDRow = mysqli_fetch_array($query) or die(mysqli_error());
			$supervisor_email = $userIDRow['che_supervisor_email'];
		}

		if($supervisor_email != ""){
			$AND = " AND che_supervisor_email = '$supervisor_email' ";
		}

		$sql = "SELECT users.user_id, d_consultants.consultant_id, d_consultants.type, d_consultants.name, d_consultants.surname,
				d_consultants.company, d_consultant_agreements.*
				FROM users
				LEFT JOIN d_consultant_agreements
				ON d_consultant_agreements.che_supervisor_email = users.email
				LEFT join sec_UserGroups ON users.user_id = sec_UserGroups.sec_user_ref
				LEFT JOIN d_consultants ON  d_consultants.consultant_id = d_consultant_agreements.consultant_ref
				LEFT JOIN lkp_consultant_type ON d_consultants.type= lkp_consultant_type.lkp_consultant_type_id
				WHERE d_consultant_agreements.status = 1 $AND
				ORDER BY d_consultant_agreements.quality_work ASC, d_consultants.name ASC";

		$rs = mysqli_query($sql) or die(mysqli_error());
		if(mysqli_num_rows($rs) > 0){
			echo "<br>";
			echo "<table border=\"0\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
			echo "<tr>
						<td class=\"oncolourcolumnheader\">Supervisor</td>
						<td class=\"oncolourcolumnheader\">Consultant name</td>
						<td class=\"oncolourcolumnheader\">Consultant type</td>
						<td class=\"oncolourcolumnheader\">Contract description</td>
						<td class=\"oncolourcolumnheader\">Rating</td>
			</tr>";
			while($rows = mysqli_fetch_array($rs)){
			    $con_id = $rows["agreement_id"];
			   	$consultant = ($rows["type"] == 2) ? $rows['company'] : $rows['name'] . ' ' . $rows['surname']; // service provider or individual
				$type_desc =  dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$rows['type'], "lkp_consultant_type_desc");
				echo "<tr>
						<td class=\"oncolourcolumn\">".$rows["che_supervisor_email"]."</td>
						<td class=\"oncolourcolumn\">".$consultant."</td>
						<td class=\"oncolourcolumn\">".$type_desc."</td>
						<td class=\"oncolourcolumn\"><a href=\"javascript:setContract('".$con_id."');moveto('_ratingCommForm');\">".$rows['description']."</a></td>
						<td class=\"oncolourcolumn\">".dbConnect::getValueFromTable("lkp_quality","lkp_quality_id",$rows["quality_work"],"lkp_quality_desc")."</td>
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

<script>
function setContract(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_consultants_agreements|'+val;
}
</script>