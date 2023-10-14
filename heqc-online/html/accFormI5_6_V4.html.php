
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION I: INTERNAL QUALITY ASSURANCE</h2>
</span>
</td></tr>
</table>
<?php 
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);


	// $site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	// $app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	// $this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);


?>




<!--section e starts -->

<br>
<br>

<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
1.	INTERNAL QUALITY ASSURANCE
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/INTERNAL QUALITY ASSURANCE.docx"?>"> Internal Quality Assurance.docx</a>
</td>
<td>
<?php $this->makeLink("iqa_doc");?> 
</td>
</tr>
</table>
<!--section e ends -->


<!--verification and confirmation starts-->

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>VERIFICATION AND CONFIRMATION</h2>
</span>
</td></tr>
</table>
<br>

<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
VERIFICATION AND CONFIRMATION BY THE DEPUTY VICE-CHANCELLOR (ACADEMIC / TEACHING & LEARNING) / ACADEMIC HEAD / CEO
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/SIGNED VERIFICATION AND CONFIRMATION.docx"?>"> Signed Verification and Confirmation.docx</a>
</td>
<td>
<?php $this->makeLink("verification_confirmation_doc");?> 
</td>
</tr>
</table>
<!--verification and confirmation ends -->
<br>
<hr>









