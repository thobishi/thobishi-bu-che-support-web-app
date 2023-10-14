<?php 
	$user_id = $this->currentUserID;

	//Get the contract id
//	$contractId = isset($_POST['FLD_agreement_ref'])? $_POST['FLD_agreement_ref'] : "";
	$contractId = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
	$msg = "";

	//Display consultant name and contract description
//	$name = isset($_POST['consultant_name'])? $_POST['consultant_name']: "";
//	$description = isset($_POST['description'])? $_POST['description']: "";
	$description = $this->getValueFromTable("d_consultant_agreements","agreement_id",$contractId,"description");
	$consultant_id = $this->getValueFromTable("d_consultant_agreements","agreement_id",$contractId,"consultant_ref");
	$consultant_name = $this->getConsultantName($consultant_id,2);

	$this->formFields["agreement_ref"]->fieldValue = $contractId;
	$this->showField("agreement_ref");

	$this->formFields["comment_date"]->fieldValue = date("Y-m-d H:m:s");
	$this->showField("comment_date");

	$this->formFields["user_ref"]->fieldValue = $user_id;
	$this->showField("user_ref");

?>

	<br>
	<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td colspan="2">
				<span class="loud">Rate performance of contract</span>
				<hr>
			</td>
		</tr>
		<tr>
	   		<td class="oncolourcolumnheader">Consultant name:<br>
	   		</td>
	   		<td class="oncolourcolumn"><?php echo echo $consultant_name; ?></td>
	   	</tr>
		<tr>
	   		<td class="oncolourcolumnheader">Contract description:<br>
	   		</td>
	   		<td class="oncolourcolumn"><?php echo echo $description; ?></td>
	   	</tr>
		<tr>
	      	<td class="oncolourcolumnheader" colspan="2">Rate the performance on this contract according to the following criteria:</td>
	   	</tr>
		<tr>
	   		<td class="oncolourcolumnheader">Date:<br>
	   		</td>
	   		<td class="oncolourcolumn"><?php echo echo date("Y-m-d H:m:s"); ?></td>
	   	</tr>
	   	<tr>
	   		<td class="oncolourcolumnheader">Delivery Date Deadlines:<br>
	   		</td>
	   		<td class="oncolourcolumn"><?php echo $this->showField("deliverydate_deadlines") ?></td>
	   	</tr>
	   	<tr>
	     	<td class="oncolourcolumnheader">Meeting Requirements:<br>
	     	</td>
	     	<td class="oncolourcolumn"><?php echo $this->showField("meeting_requirements") ?></td>
	   	</tr>
	   	<tr>
	     	<td class="oncolourcolumnheader">Quality of work:<br>
	     	</td>
	     	<td class="oncolourcolumn"><?php echo $this->showField("quality_work") ?></td>
		</tr>
		<tr>
	      	<td class="oncolourcolumnheader" colspan="2">Any comment concerning the performance of the consultant for this contract</td>
	   	</tr>
	   	<tr>
	   		<td class="oncolourcolumnheader">Comment:<br>
	   		</td>
	   		<td class="oncolourcolumn"><?php echo $this->showField("CHEcomment") ?></td>
	   	</tr>
	</table>
	<br>
	