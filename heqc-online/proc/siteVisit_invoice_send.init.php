<?php 
$file = array();
$siteVisit_Invoice = $this->generateReport("generateSiteVisitInvoice ()");
$ext = strrchr($siteVisit_Invoice,".");
copy($siteVisit_Invoice, $this->TmpDir."siteVisit-Invoice".$ext);
unlink($siteVisit_Invoice);
$siteVisit_Invoice = $this->TmpDir."siteVisit-Invoice".$ext;
array_push($file, $siteVisit_Invoice);

$message = nl2br ($this->getTextContent ("siteVisit_invoice2", "Site visit invoice"));
$subject = "Site visit invoice";

$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
$this->mimemail ($to, "", $subject, $message, $file);
?>