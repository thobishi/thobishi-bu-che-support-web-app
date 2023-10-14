<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$SQL = "UPDATE sec_Groups SET sec_group_desc='".addslashes($_POST["groupName"])."' WHERE sec_group_id=".$_POST["groupID"];
$rs = mysqli_query($conn, $SQL);

$SQL = "DELETE FROM lnk_SecGroup_process WHERE secGroup_ref=".$_POST["groupID"];
$rs = mysqli_query($conn, $SQL);

if (isset($_POST["processes"])){
	foreach ($_POST["processes"] as $key=>$value){
		$SQL = "INSERT INTO lnk_SecGroup_process (process_ref,secGroup_ref) VALUES (".$value.",".$_POST["groupID"].")";
		$rs = mysqli_query($conn, $SQL);
	}
}

$SQL = "DELETE FROM sec_UserGroups WHERE sec_group_ref =".$_POST["groupID"];
$rs = mysqli_query($conn, $SQL);

if (isset($_POST["members"])){
	foreach ($_POST["members"] as $key=>$value){
		$SQL = "INSERT INTO sec_UserGroups (sec_user_ref,sec_group_ref) VALUES (".$value.",".$_POST["groupID"].")";
		$rs = mysqli_query($conn, $SQL);
	}
}
?>