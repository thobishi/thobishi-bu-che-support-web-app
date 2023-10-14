<?php 
		$user_id = isset($_SESSION['ses_userid'])? $_SESSION['ses_userid']: "";

		//Get the contract id
		$contractId = isset($_POST['FLD_agreement_ref'])? $_POST['FLD_agreement_ref'] : "";
		$msg = "";

		/*
		if($contractId == ""){
			if(isset($_POST['CHANGE_TO_RECORD'])){
					$contrArray = explode("|",$_POST['CHANGE_TO_RECORD']);
					$contractId = $contrArray[1];
			}
		}
		*/

	//Hide the comment form from the administrator
	//if($user_id != "" && $user_id != 1){

	//Display consultant name and contract description
	$name = isset($_POST['consultant_name'])? $_POST['consultant_name']: "";
	$description = isset($_POST['description'])? $_POST['description']: "";
?>

	<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td class="specialb" align="center" valign="top" colspan="3">Please enter your comments on this contract and also choose your rating for it.</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
<?php 
   if($name != ""){
   		echo "<tr><td>&nbsp;</td><td class=\"specialb\" align=\"right\" valign=\"top\">Consultant name:</td><td>".$name."</td></tr>";
   }

   if($description != ""){
      		echo "<tr><td>&nbsp;</td><td class=\"specialb\" align=\"right\" valign=\"top\">Contract description:</td><td>".$description."</td></tr>";
   }
?>
	<tr>
			<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class="specialb" align="right" valign="top">Rate the contract</td>
		<td>
			<?php 
				$this->showField("rating");
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class="specialb" align="right" valign="top">Comments</td>
		<td>
			<?php 
				$this->showField("CHEcomment");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<?php 
				$this->formFields["agreement_ref"]->fieldValue = $contractId;
				$this->showField("agreement_ref");
			?>
		</td>
	</tr>
	<tr>
			<td colspan="3">
				<?php 
					$this->formFields["comment_date"]->fieldValue = date("Y-m-d H:m:s");
					$this->showField("comment_date");
				?>
			</td>
	</tr>
	<tr>
			<td colspan="3">
				<?php 
					$this->formFields["user_ref"]->fieldValue = $user_id;
					$this->showField("user_ref");
				?>
			</td>
	</tr>
	</table>
	<br><br>

<?php 
	//}
?>
	<script>
	function setContract(val){
		document.defaultFrm.CHANGE_TO_RECORD.value='owners_comments|'+val;
	}
	</script>