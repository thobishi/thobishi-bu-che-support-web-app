<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->savePanelUsers('panel_members', $prog_id, 'lnk_prelim_analysis_user', 'nr_programme_id');
?>