<?php
	if($this->formFields['return_to_institution_email']->fieldValue == ""){
		$this->formFields['return_to_institution_email']->fieldValue = $this->getTextContent("return_ser_to_institution", "return_ser_to_institution_email");
	}
 ?>
