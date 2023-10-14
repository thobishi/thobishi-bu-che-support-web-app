<?php 
	$user_id = $this->currentUserID;

	//Get the contract id
	$contractId = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
//	$contractId = isset($_POST['FLD_agreement_ref'])? $_POST['FLD_agreement_ref'] : "";
//	$msg = "";
?>
	<br>
	<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td colspan="2">
				<span class="loud">Rate performance of contract</span>
				<hr>
			</td>
		</tr>


<?php 
	if($contractId != ""){
		//Select the relevant contract data to display above the form
		$sql = "SELECT d_consultant_agreements.*, d_consultants.name, d_consultants.surname, d_consultants.type, d_consultants.company, lkp_consultant_type.lkp_consultant_type_desc
				FROM d_consultant_agreements
		        LEFT JOIN d_consultants ON d_consultants.consultant_id = d_consultant_agreements.consultant_ref
				LEFT JOIN lkp_consultant_type ON lkp_consultant_type_id = d_consultants.type
		        WHERE agreement_id = ".$contractId;

		$rs = mysqli_query($sql) or die(mysqli_error());

		if(mysqli_num_rows($rs) == 1){
			$row = mysqli_fetch_array($rs) or die(mysqli_error());
			$consultant_name = $row['name']." ".$row['surname'];
?>
			<tr>
		   		<td class="oncolourcolumnheader" width="30%">Consultant name:<br>
		   		</td>
		   		<td class="oncolourcolumn"><?php echo echo $row['name']." ".$row['surname']; ?></td>
		   	</tr>
			<tr>
		   		<td class="oncolourcolumnheader">Company:<br>
		   		</td>
		   		<td class="oncolourcolumn"><?php echo echo $row['company']; ?></td>
		   	</tr>
			<tr>
		   		<td class="oncolourcolumnheader">Type:<br>
		   		</td>
		   		<td class="oncolourcolumn"><?php echo echo $row['lkp_consultant_type_desc']; ?></td>
		   	</tr>
			<tr>
		   		<td class="oncolourcolumnheader">Contract description:<br>
		   		</td>
		   		<td class="oncolourcolumn"><?php echo echo $row['description']; ?></td>
		   	</tr>
<?php 
//			echo "<input type='hidden' name='FLD_agreement_ref' value='".$contractId."'>";
//			echo "<input type='hidden' name='consultant_name' value='".$consultant_name."'>";
//			echo "<input type='hidden' name='description' value='".$row['description']."'>";
			echo "</table><br>";

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