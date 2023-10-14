<?php
	$this->formFields["active_user_ref"]->fieldValue = Settings::get('currentUserID');
	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->savePanelUsers('panel_members', $prog_id, 'lnk_prelim_analysis_user', 'nr_programme_id');
	$additional_docArr = readPost('additional_doc');
	if(!empty($additional_docArr)){
		$this->saveProgrammeDocs($additional_docArr);
	}

?>