<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$file = array();
$siteVisit_Report_File = $this->generateReport("buildSiteVisitReportTable('', 0, 0, 1)");
$ext = strrchr($siteVisit_Report_File,".");
copy($siteVisit_Report_File, $this->TmpDir."siteVisit-Report".$ext);
unlink($siteVisit_Report_File);
$siteVisit_Report_File = $this->TmpDir."siteVisit-Report".$ext;
array_push($file, $siteVisit_Report_File);

$message = nl2br ($this->getTextContent ("siteVisit8b", "Site visit report"));
$subject = "Site visit report";

$SQL = "SELECT Persnr_ref, Names, Surname, E_mail, is_manager FROM Eval_Auditors, evalReport WHERE eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND application_ref=?";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$RS = $sm->get_result();
                
//$RS = mysqli_query($SQL);

while ($row = mysqli_fetch_object($RS)) {
	$to = $row->E_mail;
	$this->mimemail ($to, "", $subject, $message, $file);
}
?>
