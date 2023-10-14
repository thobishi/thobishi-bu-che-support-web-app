<script language="JavaScript" src="js/popupcalendar.js"></script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop();?>


<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right"><b>Site Name:</b> </td>
	<td class="oncolour"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
</td></tr></table>
<br><br>
<table border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="14"><b>Please record in these tables the transport and accommodation arrangements made with Reynold Travels for this site visit.</b></td>
</tr></table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "FROM");
	array_push($headArr, "TO");
	array_push($headArr, "TIME");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "airfare_date");
	array_push($fieldsArr, "airfare_from");
	array_push($fieldsArr, "airfare_to");
	array_push($fieldsArr, "airfare_time");
	array_push($fieldsArr, "airfare_reference");
?>
<br><br>
<b>Air Fare Arrangements:</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "FROM");
	array_push($headArr, "TO");
	array_push($headArr, "TIME");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "airfare_date_back");
	array_push($fieldsArr, "airfare_from_back");
	array_push($fieldsArr, "airfare_to_back");
	array_push($fieldsArr, "airfare_time_back");
	array_push($fieldsArr, "airfare_reference_back");
?>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "FROM");
	array_push($headArr, "TO");
	array_push($headArr, "TIME");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "shuttle_date");
	array_push($fieldsArr, "shuttle_from");
	array_push($fieldsArr, "shuttle_to");
	array_push($fieldsArr, "shuttle_time");
	array_push($fieldsArr, "shuttle_reference");
?>
<br><br>
<b>Shuttle Arrangements:</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "FROM");
	array_push($headArr, "TO");
	array_push($headArr, "TIME");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "shuttle_date_back");
	array_push($fieldsArr, "shuttle_from_back");
	array_push($fieldsArr, "shuttle_to_back");
	array_push($fieldsArr, "shuttle_time_back");
	array_push($fieldsArr, "shuttle_reference_back");
?>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "car_hire_date");
	array_push($fieldsArr, "car_hire_reference");
?>
<br><br>
<b>Car Hire Arrangements:</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<?php 
	$headArr = array();
	array_push($headArr, "PANEL MEMBER");
	array_push($headArr, "DATE");
	array_push($headArr, "REF");
	
	$evalArr = array();
	array_push($evalArr, "Names");
	array_push($evalArr, "Surname");
	
	$fieldsArr = array();
	array_push($fieldsArr, "car_hire_date_back");
	array_push($fieldsArr, "car_hire_reference_back");
?>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("Eval_Auditors,evalReport", $evalArr, "Persnr", "(eval_site_visit_status_confirm=1 AND Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID.")", "siteVisit_transport", "siteVisit_transport_id", "Persnr_ref", "siteVisit_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $fieldsArr, $headArr);
?>
</table>
<script>
	function setDefaultDates () {
		obj = document.defaultFrm;
		for (i=0; i<obj.length; i++) {
			if (obj[i].name.indexOf("_date") > 0) {
				obj[i].value = "<?php echo $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "final_date_visit")?>";
			}
		}
	}
	setDefaultDates ();
</script>