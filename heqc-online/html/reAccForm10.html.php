<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
   <td colspan="2"class="loud"><b>2.8</b> Student recruitment, admission and selection<br><hr></td>
</tr>
<!--<tr>
   <td colspan="2"><br/><b>2.8.1</b> Describe the admission and selection criteria for the programme.</td>
</tr>
<tr>
     <td><?php//$this->showField("admission_selection_criteria");?><br><br></td>
</tr>
<tr>
   <td colspan="2"><b>2.8.2</b> Describe the procedures in place to ensure that admission and selection criteria are clearly documented and communicated to applicants.</td>
</tr>
<tr>
     <td><?php//$this->showField("admission_selection_applicants");?><br><br></td>
</tr>
<tr>
   <td colspan="2"><b>2.8.3</b> Describe the procedures in place to ensure that recruitment, admission and selection of students take into account the institution's equity plan. Include appropriate details.</td>
</tr>
<tr>
     <td><?php//$this->showField("recruitment_selection_students");?><br><br></td>
</tr>-->
<tr>
   <td colspan="2"><b>2.8.1</b> What measures are taken to ensure that the number of students selected for the programme is compatible with the learning outcomes of the programme, the infrastructure available for its delivery, its capacity to provide sound professional preparation in the area of specialization, and the needs of the target market for qualifying students?</td>
</tr>
<tr>
     <td><?php $this->showField("measures_student_selected");?><br><br></td>
</tr>
<tr>
    <td colspan="2"><b>2.8.2</b> Does the programme makes provision for admission via RPL? <?php $this->showField("is_rpl_ref"); ?></td>
</tr>
<tr>
    <td colspan="2">
		<?php $displayStyle = $this->div_reacc($progID, 'is_rpl_ref', '2'); ?>
		<div id="is_rpl" style="display:<?php echo $displayStyle?>">
		<table>
		<tr>
		    <td colspan="2">How many students have been admitted via RPL?</td>
		</tr>
		<tr>
		     <td><?php $this->showField("students_admitted_rpl");?><br><br></td>
		</tr>
		<tr>
		    <td colspan="2">What criteria have been applied to RPL admissions?</td>
		</tr>
		<tr>
		     <td><?php $this->showField("rpl_admissions");?><br><br></td>
		</tr>
		</table>
		</div>
	</td>
</tr>
<tr>
    <td colspan="2"><b>2.8.3</b> Does the programme make provision for advanced standing/exemption from modules or courses constitutive of the qualification via RPL?
	<?php $this->showField("is_advanced_credit_ref"); ?>
	</td>
</tr>
<tr>
    <td colspan="2">
<?php $displayStyle = $this->div_reacc($progID, 'is_advanced_credit_ref', '2'); ?>
<div id="is_advanced_credit" style="display:<?php echo $displayStyle?>">
	<table>
	<tr>
	    <td>How many students have been granted advanced standing/exemption from modules via RPL?</td><td><?php $this->showField("students_advanced_credit");?></td>
	</tr>
	<tr>
	    <td>What is the limit for advanced standing/exemption from modules as a percentage of the total programme credit value?</td><td><?php $this->showField("courses_units");?></td>
	</tr>
	<tr>
	    <td colspan="2">What criteria/HEQC policies have been applied to the granting of advanced standing/exemption from modules?</td>
	</tr>
	<tr>
	     <td colspan="2"><?php $this->showField("granting_credit");?><br><br></td>
	</tr>
	</table>
</div>
</td>
</tr>
</table>