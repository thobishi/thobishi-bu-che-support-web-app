<?php 

$SQL = "UPDATE sec_Groups SET sec_group_desc='".addslashes($_POST["groupName"])."' WHERE sec_group_id=".$_POST["groupID"];
$rs = mysqli_query($SQL);

$SQL = "DELETE FROM sec_UserGroups WHERE sec_group_ref =".$_POST["groupID"];
$rs = mysqli_query($SQL);

if (readPOST('members')) {
	foreach ($_POST["members"] as $key=>$value){
		$SQL = "INSERT INTO sec_UserGroups (sec_user_ref,sec_group_ref) VALUES (".$value.",".$_POST["groupID"].")";
		$rs = mysqli_query($SQL);
	}
}

?>