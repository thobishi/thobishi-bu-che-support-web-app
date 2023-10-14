

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION C: PROGRAMME / QUALIFICATION INFORMATION</h2>
</span>
</td></tr>
</table>
<br>

<?php 
require_once ('/var/www/html/common/TreeMenu/TreeMenu.class.php');
$TreeMenu  = new HTML_TreeMenu();

	$ins_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }

	$prov_type = $this->checkAppPrivPubl($current_id);

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
	$cesm_generation = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';
	
	$ins_ref  = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$inst_name = $this->getInstitutionName($ins_ref);
	
?>

<a name="application_form_question1"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">



<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Is approval / endorsement/ validation by a statutory professional body a requirement for this programme / qualification? </b></td>
	<td valign="top" class="oncolour"><?php echo $this->showField("1_9_yn") ?></td>
</tr>


<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($current_id, '1_9_yn', '2') != "") ? $this->displayifConditionMetInstitutions_applications($current_id, '1_9_yn', '2') : "none"; ?>

<tr>
	<!-- <td>&nbsp;</td> -->
	<td ALIGN=RIGHT valign="top" width="35%"><b></b></td>
	<td valign="top">
		<div style="display:<?php echo $displayStyle?>" id="1_9_prof_approval_div">
		1.	Provide the name of the relevant statutory professional body as recognised by SAQA: 
			<br>
			<?php echo $this->showField("prof_body_name") ?>
			<br>
		2.	Upload the approval / endorsement / validation letter issued by the statutory professional body (in no.1 above) to the institution to confirm that it will support the licensing and registration of students graduating with this qualification :
			<?php echo $this->makeLink("1_9_prof_approval_doc") ?>
		</div>
	 </td>
</tr>

<!--//ISAIAH AND VUKILE REMEMBER TO DO THE PUBLIC PRIVATE VALIDATION CHECK THE HEQC ONLINE FILE-->
<?php 
switch ($prov_type){
	case 1: //private
?>
<tr>	
	<!-- <td valign="top"><b></b></td><td valign="top"><b>If you are a public institution: </b></td> -->
	<!-- <td>&nbsp;</td> -->
	<td ALIGN=RIGHT valign="top" width="35%"><b>For an existing private institution:</b></td>
	<td  valign="top">
		<div>
		<!-- <b>If you are a public institution: </b>		 -->
		Upload the ‘report of good standing’ provided by the DHET:
		<?php echo $this->makeLink("doe_pqm_doc") ?>
		</div>
	 </td>
</tr>
<tr>	
	<!-- <td valign="top"><b></b></td><td valign="top"><b>If you are a public institution: </b></td> -->
	<!-- <td>&nbsp;</td> -->
	<td ALIGN=RIGHT valign="top" width="35%"><b></b></td>

	<td valign="top">
		<div>
		<!-- <b>If you are a public institution: </b>		 -->
		Upload the confirmation letter from HEQCIS to verify institutional bi-annual uploads:
		<?php echo $this->makeLink("heqcis_confirm_doc") ?>
		</div>
	 </td>
</tr>
<?php 
	break;
	case 2: //public
?>
<!--//if it is public do not display the upload functionality-->
<tr>	
	<!-- <td valign="top"><b></b></td><td valign="top"><b>If you are a public institution: </b></td> -->
	<!-- <td>&nbsp;</td> -->
	<td ALIGN=RIGHT valign="top" width="35%"><b>For a public institution:</b></td>
	<td valign="top">
		<div>
		<!-- <b>If you are a public institution: </b>		 -->
		Upload the Programme and Qualification Mix (PQM) clearance notification from the DHET:
		<?php echo $this->makeLink("doe_pqm_doc") ?>
		</div>
	 </td>
</tr>
<?php 
	break;
	}
?>


<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Is this an education programme/ qualification?</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("MRTEQ_yn") ?></td>
</tr>

<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($current_id, 'MRTEQ_yn', '2') != "") ? $this->displayifConditionMetInstitutions_applications($current_id, 'MRTEQ_yn', '2') : "none"; ?>

<tr>	
	<td ALIGN=RIGHT valign="top" width="35%"><b></b></td>
	<td valign="top">
		<div style="display:<?php echo $displayStyle?>" id="department_approval_div">		
		Upload the approval from the DHET e.g. MRTEQ:
			<?php echo $this->makeLink("department_approval_doc") ?>
		</div>
	 </td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>HEQSF Qualification Type:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("qualification_type_ref") ?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>NQF level of the programme / qualification:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("NQF_ref") ?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Number of credits linked to the qualification type as prescribed in the HEQSF</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("min_credits_heqsf");?></td>
</tr>	

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Number of total minimum credits as per Professional Body requirements (may exceed the total minimum credits on the HEQSF)</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("min_credits_pb");?></td>
</tr>	

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Total number of credits for this programme / qualification:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("num_credits");?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>If the total number of credits exceeds the minimum total credits as prescribed in the HEQSF, provide a motivation (Note: the total number of credits for the programme / qualification may not be exceeded by more than 10%) :</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("excess_credit_motivation");?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Minimum duration (years) for completion - Full Time:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("full_time") ?><span class="specials">(Enter only numeric values)</span></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Minimum duration (years) for completion - Part Time:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("part_time") ?><span class="specials">(Enter only numeric values)</span></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>If this is a postgraduate programme / qualification, indicate the number of research credits:</b></td>
	<td valign="top" class="oncolour"><?php $this->showField("research_credits") ?><span class="specials">(Enter only numeric values)</span></td>
</tr>



<br>
</table>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<br>
	<tr>
		<td valign="top"><b></b></td>
		<td valign="top"><b>If this is a postgraduate programme / qualification indicate the accredited underpinning qualification/s of the institution.</b></td>
	</tr>
	<br>
	</table>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">


<tr>
<td colspan="2">
	<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
	<?php

      $dFields = array();

	//   echo($inst_name);
	//   array_push($dFields, "type__text|name__institution_name");
	  array_push($dFields, "type__text|name__CHE_reference_code");
	  array_push($dFields, "type__text|name__SAQA_ID");
	  array_push($dFields, "type__text|name__Programme");
 
	//   $hFields = array("Institution Name", "CHE Reference Code", "SAQA ID", "Programme/Qualification Name");
	  $hFields = array("CHE Reference Code", "SAQA ID", "Programme/Qualification Name");
	  $this->gridShowRowByRow("ia_underpin_qualification","id","application_ref__".$current_id,$dFields,$hFields, 40, 10, "true", "true",1);
 
    ?>
	</table>
</td>
</tr>


<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Indicate the National Qualifications Framework Organising Field</b></td>
</tr>
<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Field:</b></td>
 <td valign="top" class="oncolour"><?php $this->showField("field_ID") ?></td> 
<!--		<td valign="top" class="oncolour"><?php //$this->showField("SAQA_Field_code1") ?></td>-->
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Sub-Field:</b></td>
	 <td valign="top" class="oncolour"><?php $this->showField("subfield_ID") ?></td> 
	<!--<td valign="top" class="oncolour"><?php //$this->showField("SAQA_Sub_Field_id") ?></td>-->
</tr>




<!--
<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Indicate the Classification of Education Subject Matter (CESM)</b></td>
</tr>
<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>CESM Classification (e.g. Education):</b></td>
	<td valign="top" class="oncolour"><?php //$this->showField("CESM_code1") ?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%">	
		<b>First Qualifier (e.g. 0703 - Education Management and Leadership):</b>
	</td>
	<td valign="top" class="oncolour"><?php //$this->showField("CESM_level2_ref") ?></td>
</tr>

<tr>
	<td ALIGN=RIGHT valign="top" width="35%"><b>Second Qualifier (e.g. 070305 Higher Education):</b></td>
	<td valign="top" class="oncolour">
		<?php //$this->showField("CESM_level3_ref") ?>
		<br>
		<?php //$this->showField("CESM_level3_defn") ?>
	</td>
</tr>
-->
<tr>
	<td align="center" width="50%"  valign="top">
	
	</td>

	
</tr>
<?php 

function BuildTree($node, $lvl=0, $code = false, $length = 0) {
	$icon  = 'f.gif';
	switch($lvl){
		case 0:
			$SQL = 'SELECT * FROM SpecialisationCESM_code1 WHERE generation3_ind = 1  ORDER BY DOE_CESM_code';
			break;
		case 1:
			$SQL = 'SELECT * FROM SpecialisationCESM_qualifiers WHERE generation3_ind = 1  AND level = 2 AND SUBSTRING(SpecialisationCESM_qualifiers_id, 1, '.$length.') = '.$code.' ORDER BY SpecialisationCESM_qualifiers_id';
			break;
		case 2:
			$SQL = 'SELECT * FROM SpecialisationCESM_qualifiers WHERE generation3_ind = 1  AND level = 3 AND SUBSTRING(SpecialisationCESM_qualifiers_id, 1, '.$length.') = '.$code.' ORDER BY SpecialisationCESM_qualifiers_id';
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
			$link = ($lvl == 1) ? $row["SpecialisationCESM_qualifiers_id"] : '';
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
<tr>
	<td colspan="2" align="left">
		<br>
		Please select the specialisation areas for this applicant:
		<hr>
<?php
	if ($this->view != 1){?>
		To add a specialistion areas click on the required specialisation area on the right hand side.<br>
		To remove a specialisation area click on the specialisation area to remove and click on the remove button.<br> 
<?php
	}
?>
	</td>
</tr>
<tr>
	<td colspan="2">


<tr>
<td>
<?php
	if ($this->view != 1){?>
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
        if ($this->view != 1){
?>
		Select Broad and Specific area(s) of specialisation.<br>
<br>
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
<br>
<br>
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
			  url: 'http://192.168.1.122/heqc-online/pages/checkDuplicateEvaluatorEmail.php',
			 // url: 'http://192.168.0.200/heqc-online/pages/checkDuplicateEvaluatorEmail.php',
			  data: { userEmail: userEmail },
			  success: function(data){retval = data;},
			  async:false
		});
		return retval;
		
	}


});
</script>

</table>



