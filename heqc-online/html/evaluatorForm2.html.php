<?php 

require_once ('/var/www/html/common/TreeMenu/TreeMenu.class.php');
$TreeMenu  = new HTML_TreeMenu();

//print_r($this->formActions);
//echo $this->flowID;
// This evaluator form is accessed from multiple places: 
//	Process 174: Edit evaluator profile should open the evaluator profile for edit for the evaluator logged in
//  Process 10 (default): Search for an evaluator and then edit that evaluator
if ($this->flowID == 174){
	// Disable all actions in template_action and set the actions for process 174
	foreach ($this->formActions as $action){
		//echo $action->actionName . "<br>";
		$action->actionMayShow = 0;
	}
	$this->createAction ("cancel", "Cancel without saving", "href","javascript:cancelView('2');", "ico_cancel.gif");
	$this->createAction ("next", "Save and return to home page", "submit", "", "ico_next.gif");
}

	function BuildTree($node, $lvl=0, $code = false, $length = 0) {
		$icon  = 'f.gif';
		switch($lvl){
			case 0:
				$SQL = 'SELECT * FROM SpecialisationCESM_code1 WHERE generation = 2 ORDER BY CESM_code1';
				break;
			case 1:
				$SQL = 'SELECT * FROM SpecialisationCESM_qualifiers WHERE generation = 2 AND level = 2 AND SUBSTRING(SpecialisationCESM_qualifiers_id, 1, '.$length.') = '.$code.' ORDER BY SpecialisationCESM_qualifiers_id';
				break;
			case 2:
				$SQL = 'SELECT * FROM SpecialisationCESM_qualifiers WHERE generation = 2 AND level = 3 AND SUBSTRING(SpecialisationCESM_qualifiers_id, 1, '.$length.') = '.$code.' ORDER BY SpecialisationCESM_qualifiers_id';
				break;
		}
            
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
                $rs = mysqli_query($conn, $SQL);
		if ($rs) {
			while ($row = mysqli_fetch_array($rs)){
				$link = ($lvl == 2) ? $row["SpecialisationCESM_qualifiers_id"] : '';
				if (false){
					$leaf = $node->addItem(new HTML_TreeNode(array('text' => $row["TreeDesc"], '' => "", 'icon' => $icon, 'expandedIcon' => $icon)));
				}else{
					$leaf = $node->addItem(new HTML_TreeNode(array('text' => $row["Description"], 'link' => $link, 'icon' => $icon, 'expandedIcon' => $icon, 'obj' => 'document.defaultFrm.elements['."\'FLDS_Specialisations[]\'".']'))); 
				}					
				if ($lvl==0) {
					$leaf = BuildTree($leaf, 1, $row['CESM_code1'], 3);
				}
				if ($lvl==1) {			
					$leaf = BuildTree($leaf, 2, $row['SpecialisationCESM_qualifiers_id'], 5);
				}				
			} // while
		}
		return ($node);
	}	

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>


<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2" align="left">
		<br>
		Please complete the following Personal Information fields
		<hr>
	</td>
</tr>
<tr>
	<td align="right">Title:</td>
	<td class="oncolour"><?php $this->showField("Title_ref") ?></td>
</tr>
<tr>
	<td align="right">Firstname:</td>
	<td class="oncolour"><?php $this->showField("Names") ?></td>
</tr>
<tr>
	<td align="right">Surname:</td>
	<td class="oncolour"><?php $this->showField("Surname") ?></td>
</tr>
<tr>
	<td align="right">Initials:</td>
	<td class="oncolour"><?php $this->showField("Initials") ?></td>
</tr>
<tr>
	<td align="right">Date of birth:</td>
	<td class="oncolour"><?php $this->showField("Date_of_Birth") ?></td>
</tr>
<tr>
	<td align="right">ID number:</td>
	<td class="oncolour"><?php $this->showField("ID_Number") ?></td>
</tr>
<tr>
	<td align="right">Tax number:</td>
	<td class="oncolour"><?php $this->showField("Tax_Number") ?></td>
</tr>
<tr>
	<td align="right">Race:</td>
	<td class="oncolour"><?php $this->showField("Race") ?></td>
</tr>
<tr>
	<td align="right">Gender:</td>
	<td class="oncolour"><?php $this->showField("Gender") ?></td>
</tr>
<tr>
	<td align="right">Disability:</td>
	<td class="oncolour"><?php $this->showField("Disability") ?></td>
</tr>
<tr>
	<td colspan="2" align="left">
	<br>
	Please complete the following fields depending on whether application is for an evaluator or auditor?
	<hr>
	</td>
</tr>
<tr>
	<td  align="right">Are you available for selection?</td>
	<td class="oncolour">
	<?php $this->showField("active") ?>
	</td>
</tr>
<tr>
	<td align="right">Date Entered:</td>
	<td class="oncolour">
		<?php /*if (!($this->formFields["Date_entered"]->fieldValue > 0)) {
			$this->formFields["Date_entered"]->fieldValue = $this->getCurrentDate();
		   }
		   $this->showField("Date_entered");*/
			if (!($this->formFields["modified"]->fieldValue > 0)) {
				$this->formFields["modified"]->fieldValue = $this->getCurrentDate();
			}
			$this->showField("modified");
		   ?>
	</td>
</tr>
<tr>
	<td align="right">Application to assist as:<br>
	<td class="oncolour"><?php $this->showField("Auditor") ?>Auditor<br>
							<?php $this->showField("Evaluator") ?>Evaluator<br>
							<?php $this->showField("National_Review_Evaluator") ?>National Review Evaluator<br>
							<?php $this->showField("Institutional_reviewer") ?>Institutional Reviewer
	</td>
</tr>
<tr>
	<td align="right">Member of Board:</td>
	<td class="oncolour"><?php $this->showField("Board_membership") ?></td>
</tr>
<tr>
	<td align="right">Team (A, B, C etc):</td>
	<td class="oncolour"><?php $this->showField("Team_classification") ?></td>
</tr>
<tr>
	<td align="right">Trained (date and place) e.g. 201509 UWC:</td>
	<td class="oncolour"><?php $this->showField("Training_attended") ?></td>
</tr>
<tr>
	<td align="right">Comment:</td>
	<td class="oncolour"><?php $this->showField("Comments") ?></td>
</tr>
<tr>
		<td colspan="2" width="50%" align="left"><strong>If an evaluator:</strong></td>
</tr>
<tr>
		<td>Level of qualification prepared to evaluate?</td>
		<td class="oncolour"><?php $this->showField("evaluation_qual_level") ?></td>
</tr>
<tr>
		<td colspan="2" width="50%" align="left"><strong>If an auditor:</strong></td>
</tr>
<tr>
		<td align="left">Date nominated:</td>
		<td class="oncolour"><?php $this->showField("Date_nominated") ?></td>
</tr>
<tr>
			<td align="left">Nominating institution/individual:</td>
			<td class="oncolour"><?php $this->showField("Nominating_inst") ?></td>
</tr>
<tr>
	<td colspan="2" align="left">
	<br>
	Please complete the following contact information:
	<hr>
	</td>
</tr>
<!--
<tr>
	<td align="right">Street Address:</td>
	<td class="oncolour"><?php//$this->showField("Street_adr1") ?></td>
</tr><tr>
	<td align="right"></td>
	<td class="oncolour"><?php//$this->showField("Street_adr2") ?></td>
</tr><tr>
	<td align="right">Suburb:</td>
	<td class="oncolour"><?php//$this->showField("Street_suburb") ?></td>
</tr>
-->
<tr>
	<td align="right">City of residence:</td>
	<td class="oncolour"><?php $this->showField("Street_city") ?></td>
</tr>
<!--
<tr>
	<td align="right">Postal Code:</td>
	<td class="oncolour"><?php//$this->showField("Street_post_code") ?></td>
</tr><tr>
	<td align="right">Postal Address:</td>
	<td class="oncolour"><?php//$this->showField("Post_adr1") ?></td>
</tr><tr>
	<td align="right"></td>
	<td class="oncolour"><?php//$this->showField("Post_adr2") ?></td>
</tr><tr>
	<td align="right">Suburb:</td>
	<td class="oncolour"><?php//$this->showField("Post_suburb") ?></td>
</tr><tr>
	<td align="right">City:</td>
	<td class="oncolour"><?php//$this->showField("Post_city") ?></td>
</tr><tr>
	<td align="right">Postal code:</td>
	<td class="oncolour"><?php//$this->showField("Post_code") ?></td>
</tr><tr>
	<td align="right">Which address should we use for correspondence?:</td>
	<td class="oncolour"><?php//$this->showField("Address_to_use") ?></td>
</tr>
-->
<tr>
	<td align="right">Province of residence (if in South Africa):</td>
	<td class="oncolour"><?php $this->showField("Province") ?></td>
</tr><tr>
	<td align="right">Work tel. number:</td>
	<td class="oncolour"><?php $this->showField("Work_Number") ?></td>
</tr>
<!--
<tr>
	<td align="right">Home tel. number:</td>
	<td class="oncolour"><?php//$this->showField("Home_Number") ?></td>
</tr>
-->
<tr>
	<td align="right">Mobile number:</td>
	<td class="oncolour"><?php $this->showField("Mobile_Number") ?></td>
</tr>
<!--
<tr>
	<td align="right">Fax number:</td>
	<td class="oncolour"><?php//$this->showField("Fax_Number") ?></td>
</tr>
-->
<tr>
	<td align="right">E-mail address:</td>
	<td class="oncolour">
		<?php $this->showField("E_mail") ?>
		
	</td>
	
</tr>
<tr>
	<td align="right"></td>
	<td><span id = "email_status"></span></td>
</tr>
<tr>
	<td colspan="2" align="left">
	<br>
	Please complete the following employee information:
	<hr>
	</td>
</tr>
<!--
<tr>
	<td align="right">A rated:</td>
	<td class="oncolour"><?php//$this->showField("A_rated")?> </td>
</tr>
-->
<tr>
	<td align="right">Name of current employer:</td>
	<td class="oncolour">
	<?php $this->showField("employer_ref") ?>&nbsp;Other:<?php $this->showField("new_employer")?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("employer")?> </td>
</tr>
<!--
<tr>
	<td align="right">Employer Type</td>
	<td class="oncolour"><?php//$this->showField("Employer_type_ref")?> </td>
</tr>
<tr>
	<td align="right">Employer historical Status</td>
	<td class="oncolour"><?php//$this->showField("historical_status_ref")?> </td>
</tr>
<tr>
	<td align="right">Employer Merge Status</td>
	<td class="oncolour"><?php//$this->showField("merged_status_ref")?> </td>
</tr>
-->
<tr>
	<td align="right">Department:</td>
	<td class="oncolour"><?php $this->showField("Department") ?></td>
</tr><tr>
	<td align="right">Job title:</td>
	<td class="oncolour"><?php $this->showField("Job_title") ?></td>
</tr><tr>
	<td align="right">Full/Part time:</td>
	<td class="oncolour"><?php $this->showField("Full_part") ?></td>
</tr><tr>
	<td align="right">Highest Qualification:</td>
	<td class="oncolour"><?php $this->showField("qualifications_ref") ?></td>
</tr><tr>
	<td align="right">Qualification Date:</td>
	<td class="oncolour"><?php $this->showField("Qualif_date") ?></td>
</tr>
<!--
<tr>
	<td align="right">Current sector:</td>
	<td class="oncolour"><?php//$this->showField("Eval_sector_ref") ?></td>
</tr>
<tr>
	<td align="right">Organisational Type:</td>
	<td class="oncolour"><?php//$this->showField("Organisation_type_ref") ?></td>
</tr>
<tr>
	<td align="right">ETQA:</td>
	<td class="oncolour"><?php//$this->showField("ETQA_ref") ?></td>
</tr>
-->
<tr>
	<td align="right">Teaching Experience:</td>
	<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
</tr><tr>
	<td align="right">Research Experience:</td>
	<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
</tr><tr>
	<td align="right">Administration Experience:</td>
	<td class="oncolour"><?php $this->showField("Admin_experience") ?></td>
</tr><tr>
	<td align="right">Management Experience:</td>
	<td class="oncolour"><?php $this->showField("Manage_experience") ?></td>
</tr>
<tr>
	<td align="right">Other Experience:</td>
	<td class="oncolour"><?php $this->showField("Other_Experience_from") ?>
		Please describe:
		<?php $this->showField("Other_Experience_Desc") ?>
	</td>
</tr>
<!--
<tr>
	<td align="right">Total number of refereed publications:</td>
	<td class="oncolour"><?php//$this->showField("Refereed_publ") ?></td>
</tr><tr>
	<td align="right">Willing to be trained:</td>
	<td class="oncolour"><?php//$this->showField("Willingtobetrained") ?></td>
</tr>
-->
<tr>
	<td colspan="2">
	<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td>
			<br>
			Please attach the Curriculum Vitae for the applicant:
			<br> 
			<?php echo $this->makeLink("1_cv_doc") ?>
			<br>
			</td>
		</tr>
	</table>

	</td>
</tr>
<tr>
	<td colspan="2" align="left">
		<br>
		Please select the specialisation areas for this applicant:
		<hr>
<?php
	if ($this->view == 0){?>
		To add a specialistion areas click on the required specialisation area on the right hand side.<br>
		To remove a specialisation area click on the specialisation area to remove and click on the remove button.<br> 
<?php
	}
?>
	</td>
</tr>
<tr>
	<td colspan="2">

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
<?php
	if ($this->view == 0){?>
		<input class="btn" type="button" value="Remove" 
			onClick="removeSelectEntries(document.defaultFrm.elements['FLDS_Specialisations[]']);">
<?php
	}
?>
</td>
<td></td>
</tr>
<tr>
	<td>
	<b>Areas of specialisation for applicant</b>
	</td>
</tr>
<tr>
	<td width="50%" valign=top>
		<?php $this->showField("Specialisations"); ?>
	</td>
	<td valign=top>
<?php
        if ($this->view == 0){
?>
		Select Broad and Specific area(s) of specialisation for the evaluator.
<?php 
		$menu = new HTML_TreeMenu();		
		$menu = BuildTree($menu);
		// Create the presentation class
		$treeMenu = new HTML_TreeMenu_DHTML($menu, array('images' => 'images', 'defaultClass' => 'treeMenuDefault'));
		$treeMenu->printMenu();
	}
?>
	</td>
	<td></td>
</tr>
</table>


	</td>
</tr>
</table>


</td></tr>
</table>
<script>
$(document).ready(function(){ 
	$('[name="FLD_E_mail"]').bind('keyup change', function() {
		var userEmail = $(this).val();
		$('#email_status').text('Searching database.');

		if(userEmail != ''){
			var data = checkEmail(userEmail);			
			var message = (data == 'true') ?  'E-mail available' : "An Evaluator with the E-mail " + userEmail +" already exist!!";
			addCss(data);
			$('#email_status').text(message);			
			
		} else {
			$('#email_status').text('');
		}

	});
	$('#action_stay > a, #action_previous > a').click(function(e) {
		var emailValue = $('[name="FLD_E_mail"]').val();
		var data = checkEmail(emailValue);		
		var message = "An Evaluator with the E-mail " + emailValue +" already exist!!";
		addCss(data);		
		if (data == 'false'){
			console.log("data output in if "+data);
//			e.preventDefault();
			$('#email_status').text(message);
			alert(message);
		}
	});
	function addCss(data){
		if(data == 'true'){
			$('#email_status').css("color","green");
		}else{
			$('#email_status').css("color","red");
		}
	}
	function checkEmail(userEmail){
		var retval;
		$.ajax({
			 type: 'POST',
			  url: 'https://heqc-upgrade.che.ac.za/pages/checkDuplicateEvaluatorEmail.php',
			  data: { userEmail: userEmail },
			  success: function(data){retval = data;},
			  async:false
		});
		return retval;
		
	}


});
</script>
