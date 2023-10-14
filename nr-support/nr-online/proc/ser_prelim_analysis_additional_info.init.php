<?php

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$additional_docArr = readPost('additional_doc');
	if(!empty($additional_docArr)){
		$this->saveProgrammeDocs($additional_docArr, $prog_id);
	}

 ?>
