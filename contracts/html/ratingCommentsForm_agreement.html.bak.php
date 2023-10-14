<?php 
		$user_id = isset($_SESSION['ses_userid'])? $_SESSION['ses_userid']: "";
		//Get the contract id
		$contractId = isset($_POST['FLD_agreement_ref'])? $_POST['FLD_agreement_ref'] : "";
		$msg = "";


		if($contractId == ""){
			if(isset($_POST['CHANGE_TO_RECORD'])){
					$contrArray = explode("|",$_POST['CHANGE_TO_RECORD']);
					$contractId = $contrArray[1];
			}
		}

		if($contractId != ""){
		//Select the relevant contract data to display above the form
		$sql = "SELECT d_consultant_agreements.*, d_consultants.name, d_consultants.surname, d_consultants.type, d_consultants.company
				FROM d_consultant_agreements
		        LEFT JOIN d_consultants ON d_consultants.consultant_id = d_consultant_agreements.consultant_ref
		        WHERE agreement_id = ".$contractId;

		$rs = mysqli_query($sql) or die(mysqli_error());

		if(mysqli_num_rows($rs) == 1){
			$row = mysqli_fetch_array($rs) or die(mysqli_error());
			$quality = dbConnect::getValueFromTable("lkp_quality", "lkp_quality_id", $row['quality_work'], "lkp_quality_desc");

			$consultant_type = ($row['type'] == 2)? $row['company'] : $row['name']." ".$row['surname'];
			echo "<br>";
			echo "<table border=\"0\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
			echo "<tr><td class=\"specialb\" width=\"15%\">Consultant name</td>
				  <td>".$consultant_type."</td></tr>
				  <td class=\"specialb\">Contract description</td>
				  <td>".$row['description']."</td></tr>
				  <td class=\"specialb\">Start date</td>
				  <td>".$row['start_date']."</td></tr>
				  <td class=\"specialb\">End date</td>
				  <td>".$row['end_date']."</td></tr>
				  <td class=\"specialb\">Budget</td>
				  <td>".$row['budget']."</td></tr>
				  <td class=\"specialb\">Expenditure</td>
				  <td>".$row['expenditure']."</td></tr>
				  <td class=\"specialb\">Duration</td>
				  <td>".$row['duration']."</td></tr>
				  <td class=\"specialb\">Quality of work</td>
				  <td>".$quality."</td></tr>";
			echo "<input type='hidden' name='FLD_agreement_ref' value='".$contractId."'>";
			echo "<input type='hidden' name='consultant_name' value='".$consultant_type."'>";
			echo "<input type='hidden' name='description' value='".$row['description']."'>";
			echo "</table>";

		}

    }

	//Display comments here

	echo $this->displayComments($contractId);

?>
	<script>
	function setContract(val){
		document.defaultFrm.CHANGE_TO_RECORD.value='owners_comments|'+val;
	}
	</script>