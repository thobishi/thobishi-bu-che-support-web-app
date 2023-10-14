<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$SQL = "SELECT file_ref FROM AC_Meeting_reports WHERE ac_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID." AND ins_ref=".$this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
$to = $this->getValueFromTable("HEInstitution","HEI_id",$this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref"),"Contact_email");
$rs = mysqli_query($conn, $SQL);
$row = mysqli_fetch_array($rs);
$from = "HEQC Accreditation Directorate";
$subject = "Programme Results";
$message = nl2br ($this->getTextContent ("AcceptACReportForm2", "EmailInsResults"));
$report = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"agenda_doc");
$filelist = array();
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $report,"document_url"),$this->getValueFromTable("documents", "document_id", $report,"document_name")));
//$this->mimemail ($to, $from, $subject, $message, $filelist);
//EMAIL BUG:
//$this->mimemail ("louwtjie@octoplus.co.za", $from, $subject, $message, $filelist);

$institution = $this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
$SQL = "SELECT application_id FROM Institutions_application WHERE AC_Meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID." AND institution_id=".$institution;
$rs = mysqli_query($conn, $SQL);
while ($row = mysqli_fetch_array($rs)){
	global $r;
	$site_pay_sql = "SELECT siteVisit.site_ref AS SITE, siteVisit.site_visit_payed AS PAYED FROM 	Institutions_application, HEInstitution, siteVisit WHERE 	Institutions_application.institution_id = HEInstitution.HEI_id AND siteVisit.application_ref = Institutions_application.application_id AND application_id =".$row[0];
	$site_pay_rs = mysqli_query($site_pay_sql);
	$not_payed = 0;
	while ($site_pay_rs && ($site_pay_row=mysqli_fetch_array($site_pay_rs))) {
		if ($site_pay_row["PAYED"] == 0) {
			$not_payed = 1;
		}
	}
	$S = "SELECT 	Institutions_application.CHE_reference_code AS REFNR, Institutions_application.program_name AS PROGNAME, SpecialisationCESM_code1.Description AS CESMCAT, HEInstitution.HEI_name AS INST, NQF_level.NQF_level AS NQF, Institutions_application.AC_conditions AS CONDITIONS, Institutions_application.AC_desision AS DESCISION FROM 		Institutions_application, SpecialisationCESM_code1, HEInstitution, NQF_level WHERE		SpecialisationCESM_code1.CESM_code1 = Institutions_application.CESM_code1 AND Institutions_application.institution_id = HEInstitution.HEI_id AND NQF_level.NQF_id = Institutions_application.NQF_ref AND application_id =".$row[0];
	$rrss = mysqli_query($conn, $S);
	if ($rrss && ($r=mysqli_fetch_array($rrss))) {
		if (! ($not_payed) ) {
			switch ($r["DESCISION"]){
				case 1:
					$message = $this->getTextContent ("AcceptACReportForm2", "PROVISIONAL ACCREDITATION","",$r);
					break;
				case 2:
					$message = $this->getTextContent ("AcceptACReportForm2", "CONDITIONAL ACCREDITATION", "",$r);
					break;
				case 3:
					$message = $this->getTextContent ("AcceptACReportForm2", "NOT PROVISIONALLY ACCREDITED","",$r);
					break;
			}
		}else {
			$message = $this->getTextContent ("AcceptACReportForm2", "SITE VISIT NOT PAYED","",$r);
		}
	}
	//EMAIL BUG:
	$this->misMailByName("louwtjie@octoplus.co.za", $r["REFNR"]." - Accreditation Outcome", $message);
}






?>
