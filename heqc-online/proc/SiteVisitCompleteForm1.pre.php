<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}


$application_id = $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "application_ref");

$SQL = "UPDATE siteVisit SET siteVisit_complete = 1 WHERE siteVisit_id=?";
$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);

$SQL = "SELECT count(*) FROM siteVisit WHERE application_ref=?";
$sm = $conn->prepare($SQL);
$sm->bind_param("s", $application_id);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$counter = $row[0];

$SSQL = "SELECT count(*) FROM siteVisit WHERE siteVisit_complete=1 AND application_ref=?";
$sm = $conn->prepare($SSQL);
$sm->bind_param("s", $application_id);
$sm->execute();
$rrs = $sm->get_result();

//$rrs = mysqli_query($SSQL);
$rrow = mysqli_fetch_array($rrs);
$ccounter = $rrow[0];


// BUG: this should actually be checked.
//if ($counter == $ccounter){
$SQL = "UPDATE Institutions_application SET application_status  = 1, AC_Meeting_ref=0 WHERE application_id=?";
$sm = $conn->prepare($SQL);
$sm->bind_param("s", $application_id);
$sm->execute();
$rs = $sm->get_result();
//$rs = mysqli_query($SQL);
//}

?>
