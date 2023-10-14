<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "instSiteInvoice2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Sites > Prepare for invoicing </span>";


$this->formOnSubmit = "return checkFrm();";

$this->scriptHead = <<<SCRIPTHEAD
	function checkSendInvoice(obj){

		if (!(obj.checked == true)) {
			alert('Please check the send invoice checkbox');
			return false;
		}else{
			moveto('stay');
		}
	}
SCRIPTHEAD;

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			try {
				if (document.defaultFrm.FLD_invoice_sent_count.value == 0) {
					if (! (document.defaultFrm.send_invoice.checked) ) {
						alert('Please send the invoice first.');
						return false;
					}
				}
			}catch(e){}
	  	}
		return true;
	}
	function firstEmailMsg(invdate) {
       
		var obj = document.defaultFrm;
		obj.FLD_invoice_sent.value = 1;
		obj.FLD_invoice_sent_count.value = 1;
		obj.FLD_date_invoice.value = invdate;
      
        
	}
	function resendEmailMsg() {
		var obj = document.defaultFrm;
		var intCount = 0;
		intCount = parseInt(obj.FLD_invoice_sent_count.value) + parseInt(1);
		obj.FLD_invoice_sent_count.value = intCount;
	}
SCRIPTTAIL;

?>
