<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "instSiteInvoice3";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Prepare for invoicing </span>";


$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == 'previous')  {
            if (document.defaultFrm.FLD_paymentdate.value =='0000-00-00'  || document.defaultFrm.FLD_paymentdate.value ==''){
                alert('Please enter payment date ');
                    return false;
            }
		
		if (parseInt(document.defaultFrm.FLD_institution_Payment.value) == 0  || document.defaultFrm.FLD_institution_Payment.value ==''){
			alert('Please enter amount paid by the institution ');
				return false;
		}


        if (parseInt(document.defaultFrm.FLD_institution_Payment.value) != parseInt(document.defaultFrm.FLD_invoice_total.value)  ){
			alert('The amount paid by the institution must match the invoice total ');
				return false;
		}
       
		return true;
    }
	}
SCRIPTTAIL;

?>
