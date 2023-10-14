<?php
	$curentUserEdit_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
	$rg_groupId = $this->getGroupId('RC member');
	$nrc_groupId = $this->getGroupId('NRC member');
	
	$isNRC_member = $this->sec_partOfGroup($nrc_groupId, $curentUserEdit_id);	

	$isRgMember = $this->sec_partOfGroup($rg_groupId, $curentUserEdit_id);		

	$this->title			= "CHE National Reviews";
	$this->bodyHeader		= "formHead";
	$this->body				= "manageUsersEdit";
	$this->bodyFooter		= "formFoot";
	$this->NavigationBar	= array('Admin', 'Manage Users', 'Edit');
	if($isRgMember || $isNRC_member){
		$this->formOnSubmit = "return selectAll()";
	}
?>