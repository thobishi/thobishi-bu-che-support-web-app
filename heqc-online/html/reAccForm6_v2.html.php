<?php
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
	<br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($progID);
			
			//echo $progID;
			 ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="loud">Templates for download, completion and upload<hr></td>
	</tr>
	<tr>
		<td><br/><b>
Templates for the re-accreditation submission are listed below.  All templates should be completed and submitted per programme.  Please read the introduction and the instructions in each document carefully before proceeding to complete and upload each template.  
<br></td>
	</tr>
	
	<tr>
		<br/><b>

<br>
	</tr>
	<tr>
	

	</tr>
	
</table>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
Description
</td>
<td>
Template to download
</td>
<td>
Upload the template
</td>
</tr>
<tr>
<td>
1. CONDITIONS
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/Reaccreditationcondition.docx"?>">CONDITIONS.docx</a>
</td>
<td>
<?php $this->makeLink("conditions_evidence_doc");?>
</td>
</tr>

<tr>
<td>
2. PROGRAMME DESIGN
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/ReaccreditationPROGRAMMEDESIGN.docx"?>">PROGRAMME DESIGN.docx</a>
</td>
<td>
<?php $this->makeLink("programme_design_evidence_doc");?>
</td>
</tr>
<tr>
<td>
3. STUDENT RECRUITMENT ADMISSION AND SELECTION
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/3. STUDENT RECRUITMENT ADMISSION AND SELECTION.docx"?>">3. STUDENT RECRUITMENT ADMISSION AND SELECTION.docx</a>
</td>
<td>
<?php $this->makeLink("student_recruitment_evidence_doc");?>
</td>
</tr>

<tr>
<td>
4. ASSESSMENT PRACTICES
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/ASSESSMENTPRACTICES4.docx"?>">ASSESSMENT PRACTICES.docx</a>
</td>
<td>
<?php $this->makeLink("assessment_evidence_doc");?>
</td>
</tr>
<tr>
<td>
5. INFRASTRUCTURE
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/INFRASTRUCTURE6.docx"?>">INFRASTRUCTURE.docx</a>
</td>
<td>
<?php $this->makeLink("infrastructure_evidence_doc");?>
</td>
</tr>
<tr>
<td>
6. PROGRAMME COORDINATION AND PROGRAMME REVIEW
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/PROGRAMMECOORDINATIONANDPROGRAMME REVIEW8.docx"?>">PROGRAMME COORDINATION AND PROGRAMME REVIEW.docx</a>
</td>
<td>
<?php $this->makeLink("programme_coordination_evidence_doc");?>
</td>
</tr>
<tr>
<td>
7. ACADEMIC DEVELOPMENT INCLUDING STUDENT SUPPORT
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/ACADEMICDEVELOPMENTINCLUDINGSTUDENTSUPPORT5.docx"?>">ACADEMIC DEVELOPMENT INCLUDING STUDENT SUPPORT.docx</a>
</td>
<td>
<?php $this->makeLink("academic_development_evidence_doc");?>
</td>
</tr>
<tr>
<td>
8. TEACHING AND LEARNING INTERACTIONS 
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/TEACHING AND LEARNING INTERACTIONS.docx"?>">TEACHING AND LEARNING INTERACTIONS.docx</a>
</td>
<td>
<?php $this->makeLink("teaching_learning_evidence_doc");?>
</td>
</tr>
<tr>
<td>
9. STUDENT AND RETENTION THROUGHPUT RATES
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/STUDENTANDETENTIONTHROUGHPUTRATES7.docx"?>"> STUDENT AND RETENTION THROUGHPUT RATES.docx</a>
</td>
<td>
<?php $this->makeLink("student_retention_evidence_doc");?>
</td>
</tr>


</table>








