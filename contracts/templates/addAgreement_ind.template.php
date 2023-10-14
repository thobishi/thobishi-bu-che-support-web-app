<?php 

$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "addAgreement_ind";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Consultant > Contract</span>";

$this->formOnSubmit = "return checkFrm(this);";

$currDate = date('Y-m-d');

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (((obj.MOVETO.value == '_startContractList') || (obj.MOVETO.value == 'stay')) && (obj.VIEW.value != -1)) {
			if (!valTextRequired(obj.FLD_description,'Please enter a value for description.')) {return false};
			if (!valSelectRequired(obj.FLD_che_supervisor_user_ref,'Please select the CHE manager for this contract.')) {return false};
			if (!valDateRequired(obj.FLD_start_date,'Please enter a value for start date.')) {return false};
			if ((obj.FLD_end_date.value <= '$currDate') && (obj.FLD_status.value == 0)) {
				alert('Please select a contract status.');
				return false;
			}
			if ((obj.FLD_end_date.value != '') && (obj.FLD_end_date.value != '1970-01-01') && (obj.FLD_end_date.value <= '$currDate') && (obj.FLD_status.value == 1)) {
				alert('The contract has either expired already or is expiring today, and you have selected the status as "Active". If you would like to extend the contract, please adjust the date accordingly.');
				return false;
			}
			if ((obj.FLD_end_date.value > '$currDate') && (obj.FLD_status.value == 2)) {
				alert('The contract\'s end date indicates that it has not expired yet, and you have selected the status as "Expired/Terimated". If you would like to terminate the contract, please adjust the date accordingly.');
				return false;
			}
			if (!valTextRequired(obj.FLD_payment_rate,'Please enter the fees from Annexure B.')) {return false};
			if (!valNumberWarning(obj.FLD_budget,'Warning: You have not entered budget!! It is highly recommended that you estimate a budget using fees and duration. Click Cancel to return and enter budget - no R,comma or spaces.  Click OK to continue with no budget.')) {return false};

		return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;
?>
