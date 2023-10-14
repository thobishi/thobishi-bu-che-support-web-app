
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION H: REQUIRED DOCUMENTS</h2>
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






<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
1.	Workplace-based learning agreements or contracts / Service Level Agreements
</td>

<td>
<?php $this->makeLink("1_contract_arragement_doc");?>
</td>
</tr>

<tr>
<td>
2.	SUPPORT STAFF MEMBERS for this programme / qualification – CVs (incl. librarian / information specialist) 
</td>

<td>
<?php $this->makeLink("1_support_staff_cv_doc");?>
</td>
</tr>


<tr>
<td>
3.	External examiners’ CVs
</td>

<td>
<?php $this->makeLink("1_external_examiners_cv_doc");?> 
</td>
</tr>


<tr>
<td>
4.	External moderators’ CVs 
</td>

<td>
<?php $this->makeLink("1_external_moderators_cv_doc");?>
</td>
</tr>

<tr>
<td>
5.	Approved budget for the programme / qualification 
</td>

<td>
<?php $this->makeLink("1_approved_budget_doc");?>
</td>
</tr>

<tr>
<td>
6.	Budget for the development of learning materials for the programme
</td>

<td>
<?php $this->makeLink("5_budgetdevteachtech_doc");?> 
</td>
</tr>

<tr>
<td>
7.	Prescribed and recommended reading list for the programme / qualification is the correct field.
</td>

<td>
<?php $this->makeLink("1_outline_courses_doc");?> 
</td>
</tr>

<tr>
<td>
8.	Study guides & programme handbooks
</td>

<td>
<?php $this->makeLink("1_study_guide_doc");?> 
</td>
</tr>


<tr>
<td>
9.	In the case of an existing institution, upload the minutes from meeting(s) of Senate / Academic Board / governance and management structure indicating approval of this programme / qualification.
If this is a new institution, upload evidence of processes that were followed to approve the application for accreditation
</td>

<td>
<?php $this->makeLink("1_approval_meeting_minutes_doc");?> 
</td>
</tr>


<tr>
<td>
10.Library holdings/budget specific to programme </td>

<td>
<?php $this->makeLink("7_librarybudget_doc");?> 
</td>
</tr>

<tr>
<td>
11.For postgraduate programme / qualification: ethical clearance process
</td>

<td>
<?php $this->makeLink("9_codeethics_doc");?> 
</td>
</tr>
</table>



















<!--verification and confirmation ends -->
<br>
<hr>









