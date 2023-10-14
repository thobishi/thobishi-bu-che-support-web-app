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
   <td colspan="2" class="loud">2.6 Work-integrated learning<hr></td>
</tr>
<tr>
   <td><br>
   <fieldset>
   Complete section 2.6 if the programme requires work-integrated learning as a fundamental requirement for the completion of the qualification.</fieldset></td>
 </tr>
 <tr>
	<td colspan="2">Does this programme require work-integrated learning as a fundamental requirement for the completion of the qualification?
	<?php $this->showField("is_workbase_learning_ref"); ?>
	</td>
 </tr>
 <tr>
   	<td colspan="2">
		<?php $displayStyle = $this->div_reacc($progID, 'is_workbase_learning_ref', '2'); ?>
		<div id="is_wb" style="display:<?php echo $displayStyle?>">
		<table>
		 <tr>
		     <td colspan="2"><hr><b>2.6.1</b> Is the work-integrated learning component credit-bearing? <?php $this->showField("workbase_learning");?>
			 <?php $displayStyle = $this->div_reacc($progID, 'workbase_learning', '2'); ?>
			 <div id="is_creditb" style="display:<?php echo $displayStyle?>">
			 	<br>
			 	<table align="center" class="oncolour">
				<tr>
		      		<td>How many SAQA credits are allocated to work-base learning? 
					<?php $this->showField("saqa_credits_allocated");?>
					</td>
		  		</tr>
				</table>
				<br>
			 </div>
			 <hr>
			 </td>
		 </tr>
		 <tr>
		    <td><b>2.6.2</b> Does the work-integrated learning component of the programme require formal agreements between the work-place, the student and provider?
		    <?php $this->showField("programme_formal_agreements");?>
			   			 <?php $displayStyle = $this->div_reacc($progID, 'programme_formal_agreements', '2'); ?>
			 <div id="is_formala" style="display:<?php echo $displayStyle?>">
			 	<br>
			 	<table align="center" class="oncolour" width="60%">
				<tr>
		      		<td align="center">Are the required formal agreements in place? (Provide appropriate detail.) 
					<?php $this->showField("required_formal_agreements");?>
					</td>
		  		</tr>
				</table>
				<br>
			 </div>
			 <hr>
			   </td>
		   </tr>
		  <tr>
		       <td colspan="2"><b>2.6.3</b> Please describe how all parties (institution, student, workplace managers, workplace mentors) 
			   are informed about guidelines on roles and responsibilities relating to ethical and educational considerations.</td>
		  </tr>
		  <tr>
		       <td><?php $this->showField("programme_guidelines");?><br><br></td>
		 </tr>
		 <tr>
		      <td colspan="2"><b>2.6.4</b> Please provide details of work-integrated learning environments and how they relate to the purpose of the programme.</td>
		 </tr>
		 <tr>
		      <td><?php $this->showField("learning_environments");?><br><br></td>
		 </tr>
		 <tr>
		     <td colspan="2"><b>2.6.5</b> Who takes responsibility for placement of students in appropriate work-integrated learning sites, and how does the responsible person organize the placements?</td>
		 </tr>
		 <tr>
		     <td><?php $this->showField("students_placement");?><br><br></td>
		 </tr>
		 <tr>
		      <td colspan="2"><b>2.6.6</b> Are the academic and administrative staff engaged in the programme suitably informed about and engaged in the work-integrated learning component to ensure that the academic, administrative and work-integrated learning components of the programme are well coordinated, monitored and assessed? If Yes, please provide details.</td>
		 </tr>
		 <tr>
		      <td><?php $this->showField("academic_staff_engaged");?><br><br></td>
		 </tr>
		 <tr>
		      <td colspan="2"><b>2.6.7</b> Do the coordination, infrastructure and mentoring systems associated with work-integrated learning promote occupational development and professionalism? If Yes, please provide details.</td>
		 </tr>
		 <tr>
		      <td><?php $this->showField("mentoring_systems");?><br><br></td>
		 </tr>
		 <tr>
		      <td colspan="2"><b>2.6.8</b> How is work-integrated learning assessed?</td>
		 </tr>
		 <tr>
		      <td><?php $this->showField("workbased_learning_assessed");?><br><br></td>
		</tr>
		</table>
		</div>
	</td>
</tr>
</table>