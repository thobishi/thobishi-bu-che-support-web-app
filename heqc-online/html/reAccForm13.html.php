<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($progID); ?>
	</td>
</tr>
<tr>
    <td colspan="2" class="loud">2.11 Post-Graduate programmes<hr></td>
</tr>
<tr>
   <td>
   <br>
   <fieldset>
        Complete section 2.11 if this is a post-graduate programme.
   </fieldset>
   <br>
   </td>
</tr>
 <tr>
	<td colspan="2">Is this a post-graduate programme?
	<?php $this->showField("is_postgraduate_ref"); ?>
	</td>
 </tr>
 <tr>
   	<td colspan="2">
		<br>
		<?php $displayStyle = $this->div_reacc($progID, 'is_postgraduate_ref', '2'); ?>
		<div id="is_postgrad" style="display:<?php echo $displayStyle?>">
		<table>
		<tr>
		  	<td colspan="2">
		  	<b>2.11.1</b> Does the institution have a policy for promoting research? <?php $this->showField("policy_promoting_research");?>
			<br>
			<?php $displayStyle = $this->div_reacc($progID, 'policy_promoting_research', '2'); ?>
			<div id="is_rp_yes" style="display:<?php echo $displayStyle?>">
				<br>Please ensure that the <b>institution's policy on research</b> has been uploaded in the Institutional Profile section.<br>
			</div>
			<?php $displayStyle = $this->div_reacc($progID, 'policy_promoting_research', '1'); ?>
			<div id="is_rp_no" style="display:<?php echo $displayStyle?>">
				<br>
				<table>
				<tr>
				  <td>What steps have been taken over the last three years to develop research capacity and increase research output by members of the academic staff?<br></td>
				</tr>
				<tr>
				  <td><?php $this->showField("years_develop_research_capacity");?><br></td>
				</tr>
				</table>
				<br>
			</div>
			<br>
			</td>
		</tr>
		<tr>
		  <td colspan="2"><b>2.11.2</b> Budget allocations for research<br></td>
		</tr>
		<tr>
			<td valign="top">
			<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
			<?php
		
			if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
				$cmd = explode("|", $_POST["cmd"]);
				switch ($cmd[0]) {
					case "new":
						$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
						break;
					case "del":
						$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
						break;
				}
				echo '<script>';
				echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
				echo 'document.defaultFrm.MOVETO.value = "stay";';
				echo 'document.defaultFrm.submit();';
				echo '</script>';
			}
		
				$dFields = array();
		
				array_push($dFields, "type__text|size__15|name__budget_allocations");
				array_push($dFields, "type__textarea|name__specify_research");
		
				$hFields = array();
				array_push($hFields,"Year");
				array_push($hFields,"Budget Allocation");
				array_push($hFields,"Specify the research project(s)/activities");
		
				$this->gridShow("reaccred_budget_allocations", "reaccred_budget_allocations_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, "lkp_year", "lkp_year_desc", "lkp_year_desc", "budget_year_ref",1,40,5,FALSE,""," lkp_year_desc BETWEEN 2013 AND 2017");
			?>
			</table>
		</td>
		</tr>
		<tr>
		  <td colspan="2"><br><b>2.11.3</b> Details of the research experience and output of academic staff members involved in the teaching and/or supervision of post-graduate programmes<br></td>
		</tr>
		<tr>
			<td valign="top">
			<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
			<?php
		
				$dFields = array();
		
				array_push($dFields, "type__text|size__15|name__staff_member");
				array_push($dFields, "type__text|size__10|name__period_covered");
				array_push($dFields, "type__text|size__10|name__accredited_peerreviewed");
				array_push($dFields, "type__text|size__10|name__conference_papers");
				array_push($dFields, "type__text|size__10|name__research_projects");
				array_push($dFields, "type__text|size__10|name__students_supervised");
		
				$hFields = array();
				array_push($hFields,"Name of staff member");
				array_push($hFields,"Period covered (e.g. 2008-2011)");
				array_push($hFields,"Accredited articles or peer-reviewed books published");
				array_push($hFields,"Conference papers");
				array_push($hFields,"Research projects (indicate scale of contribution)");
				array_push($hFields,"No. of students supervised to completion");
		
		
				$this->gridShowRowByRow("reaccred_research_outputstaff", "reaccred_research_outputstaff_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, 40, 5, "true", "true", 1);				
		
			?>
			</table>
		  </td>
		</tr>
		<tr>
		  <td colspan="2">
		  <br><b>2.11.4</b> Does the institution have a policy for the supervision of student dissertations and/or theses, including the development of supervision capacity and the practice of supervision?
			<?php $this->showField("supervision_dissertations_theses");?>
			<br>
			<?php $displayStyle = $this->div_reacc($progID, 'supervision_dissertations_theses', '2'); ?>
			<div id="is_ssdp_yes" style="display:<?php echo $displayStyle?>">
				<br>Please ensure that the <b>institution's post-graduate supervision policy</b> has been uploaded in the Institutional Profile section.<br>
			</div>
			<?php $displayStyle = $this->div_reacc($progID, 'supervision_dissertations_theses', '1'); ?>
			<div id="is_ssdp_no" style="display:<?php echo $displayStyle?>">
				<br>
				<table>
				<tr>
				  <td colspan="2">What steps have been taken over the last three years to develop supervision training and capacity?<br></td>
				</tr>
				<tr>
				  <td><?php $this->showField("supervision_training_capacity");?><br><br></td>
				</tr>
				</table>
				<br>
			</div>
			<br>
			</td>
		</tr>
		<tr>
		  <td colspan="2"><b>2.11.5</b> What steps are taken to foster research skills and capacity in students?<br></td>
		</tr>
		<tr>
		  <td><?php $this->showField("foster_research_skills");?><br><br></td>
		</tr>
		</table>
		</div>
	</td>
</tr>
</table>


