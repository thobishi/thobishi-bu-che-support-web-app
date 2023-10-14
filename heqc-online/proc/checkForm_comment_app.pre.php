<?php 
	if ( (isset($this->logicVars["doCancelProc"]) && ($this->logicVars["doCancelProc"] == 1)) OR  (isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 1)) ) {
		$this->formFields["doCancelProc"]->fieldValue = 1;
	}else {
		$this->formFields["gotoInst"]->fieldValue = 1;
	}
?>
