<?php 
	$inst_id = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	$priv_publ = $this->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "priv_publ");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
<td class="loud">Validation of institutional profile<br><hr></td>
</tr>
<tr>
	<td>
	<span class="specialb">
	The following list indicates the fields you have not completed. Please complete these fields. 
	<br><br>
	<i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i>
	<br>
	</td>
</tr>
</table>
<br><br>
<b>Institutional Profile:</b>
<table width=90% border=0 align="center" cellpadding="2" cellspacing="2">
	<?php

            
	// If an institution id is not on the application then the user cannot continue.  Email a message to support to fix it.
	// A re-accreditation application should always have an institution id.
	if ($inst_id > 0){
		$inst_infoContinued = array("instProfile30","ADDITIONNAL INSTITUTIONAL PROFILE INFO");
		$inst_forms = array(array("instProfile1","INSTITUTION INFORMATION"));
		if( $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ") == 1){
			array_push($inst_forms, $inst_infoContinued);
		}
		
		foreach($inst_forms as $inst_form){
		?>
	 		<tr>
				<td class="oncolour" colspan="3"><b><?php echo $inst_form[1];?></b></td>
			</tr>
		<?php
			$this->validateFields("$inst_form[0]","institution_ref",$inst_id);
		}

	} else {
		echo "Institution id does not have a value";
	}



	// Common fields for section validation
	$showTitle = "Section: ";
	
	// Specific fields per section
	$title = "MANAGEMENT CONTACT INFORMATION";
	$showField = "Contact details for Institution Management";
	$label = "_startInstProfileContactInstHead";
	$lnk = '<img src="images/'.$this->imageOK.'" border=0>';
	$message = "";
	$instProfileFlag = $this->checkInstitutionalProfileContactInfoHeads ($inst_id);
	if (! $instProfileFlag ) { 
		$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
		$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
		$message = "You are required to fill in this section";
	}	
		
	$htmlRow = <<<htmlRow
		<tr>
			<td class="oncolour" colspan="3"><b>$title</b></td>
		</tr>
	 	<tr>
			<td align="center" class="oncolour">$lnk</td>
			<td class='oncolour'>$showTitle $showField</td>
			<td class='oncolour'><font color="red">$message</font></td>
		</tr>
htmlRow;
	echo $htmlRow;

	// Contact information for the main site
	$title = "SITE CONTACT INFORMATION";
	$showField = "Contact details for the main site";
	$label = "_gotoInstProfileMainSite";
	$lnk = '<img src="images/'.$this->imageOK.'" border=0>';
	$message = "";
	$instProfileFlag = $this->checkInstitutionalProfileContactInfo ($inst_id, "main");
	if (! $instProfileFlag ) {
		$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
		$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
		$message = "You are required to fill in this section";
	}

	$htmlRow = <<<htmlRow
		<tr>
			<td class="oncolour" colspan="3"><b>$title</b></td>
		</tr>
	 	<tr>
			<td align="center" class="oncolour">$lnk</td>
			<td class='oncolour'>$showTitle $showField</td>
			<td class='oncolour'><font color="red">$message</font></td>
		</tr>
htmlRow;
	echo $htmlRow;

	// Contact information for the any additional sites
	$showField = "Contact details for any additional sites";
	$label = "_InstProfileAdditionalSites";
	$lnk = '<img src="images/'.$this->imageOK.'" border=0>';
	$message = "";
	$instProfileFlag = $this->checkInstitutionalProfileContactInfo ($inst_id, "additional");
	if (! $instProfileFlag ) {
		$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
		$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
		$message = "You are required to fill in this section";
	}

		// General policies information for the any additional sites
		
		
	

	$htmlRow = <<<htmlRow
	 	<tr>
			<td align="center" class="oncolour">$lnk</td>
			<td class='oncolour'>$showTitle $showField</td>
			<td class='oncolour'><font color="red">$message</font></td>
		</tr>
htmlRow;

	

	$htmlRow = <<<htmlRow
		<tr>
			<td class="oncolour" colspan="3"><b>GENERAL POLICIES</b></td>
		</tr> 
htmlRow;


	
	echo $htmlRow;
	
	$this->validateFields("General_Policies","institution_ref",$inst_id);
	
echo $inst_id;

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$sql = <<<INSTDOC
		CREATE temporary table tmp_instProfile_doc 
			(lkp_ref int,
			institution_ref int,
			yes_no int,
			comment_text varchar(512),
			inst_uploadDoc int,
			doc_catg_id int,
			doc_catg_desc varchar(512))
		SELECT lkp_pol_budgets_prog_design_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			1 as doc_catg_id, 
			'POLICIES AND PROCEDURES ON PROGRAMME DESIGN' as doc_catg_desc
		FROM  institutional_profile_pol_budgets_prog_design 
		WHERE institution_ref = $inst_id
			UNION
		SELECT lkp_pol_budgets_admission_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			2 as doc_catg_id, 
			'ADMISSION AND SELECTION POLICIES' as doc_catg_desc
		FROM institutional_profile_pol_budgets_admission
		WHERE institution_ref = $inst_id
			UNION 
		SELECT lkp_pol_budgets_hr_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			3 as doc_catg_id, 
			'HUMAN RESOURCES POLICIES AND PROCEDURES' as doc_catg_desc
		FROM institutional_profile_pol_budgets_hr 
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_learning_strat_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			4 as doc_catg_id, 
			'TEACHING AND LEARNING STRATEGY' as doc_catg_desc
		FROM  institutional_profile_pol_budgets_learning_strat 
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_assessment_eval_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			5 as doc_catg_id, 
			'ASSESSMENT AND EVALUATION PROCESS AND PROCEDURE' as doc_catg_desc
		FROM institutional_profile_pol_budgets_assessment_eval
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_certification_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			6 as doc_catg_id, 
			'CERTIFICATION' as doc_catg_desc
		FROM institutional_profile_pol_budgets_certification 
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_post_grad_pol_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			7 as doc_catg_id, 
			'POSTGRADUATE POLICIES AND PROCEDURES' as doc_catg_desc
		FROM institutional_profile_pol_budgets_post_grad_pol 
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_infrastracture_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			14 as doc_catg_id, 
			'INFRASTRUCTURE' as doc_catg_desc
		FROM institutional_profile_pol_budgets_infrastracture 
		WHERE institution_ref = $inst_id		
			UNION
		SELECT lkp_pol_budgets_prog_offerings_ref as lkp_ref,
			institution_ref,
			yes_no,
			comment_text,
			inst_uploadDoc,
			15 as doc_catg_id, 
			'STATUS OF PROGRAMME OFFERINGS' as doc_catg_desc
		FROM institutional_profile_pol_budgets_prog_offerings 
		WHERE institution_ref = $inst_id		
INSTDOC;
        $docs_rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));

	if ($priv_publ == 2){
		$sql = <<<DOCCATG
			SELECT DISTINCT doc_catg, doc_catg_desc, catg_label
			FROM lkp_instProfile_doc
			WHERE doc_catg <= 8
			ORDER BY doc_catg
DOCCATG;
	} else {
		$sql = <<<DOCCATG
			SELECT DISTINCT doc_catg, doc_catg_desc, catg_label
			FROM lkp_instProfile_doc
			ORDER BY doc_catg
DOCCATG;
	}
	$catg_rs = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_array($catg_rs)){
		$catg_desc = $row["doc_catg_desc"];
		$doc_catg = $row["doc_catg"];
		$label = $row["catg_label"];
		$htmlRow = <<<htmlRow
			<tr>
				<td class="oncolour" colspan="3"><b>$catg_desc</b></td>
			</tr>
htmlRow;
		$sql = <<<GETDOC
			SELECT *
			FROM lkp_instProfile_doc 
			LEFT JOIN tmp_instProfile_doc ON (doc_catg = doc_catg_id
			AND doc_id = lkp_ref)
			WHERE doc_catg = $doc_catg
GETDOC;

		$docs_rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		while($docs_row = mysqli_fetch_array($docs_rs)){
			$showTitle = "Document: ";
			$showField = $docs_row["doc_desc"];
			$yesno = $docs_row["yes_no"];
			$comment_text = $docs_row["comment_text"];
			$loadDoc = $docs_row["inst_uploadDoc"];
			
			// image and message for successful validation
			$lnk = '<img src="images/'.$this->imageOK.'" border=0>';
			$message = "";
			
			// link, image and message for failure of validation
			if ($yesno == 0){
				$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
				$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
				$message = "Please indicate whether you have the required policy or document.";
			}
			if ($yesno == 1){
				if (!$comment_text > ""){
					$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
					$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
					$message = "Please enter a comment regarding not having this policy or document.";
				}
			}
			if ($yesno == 2){
				if (!$comment_text > "" && !$loadDoc > 0){
					$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
					$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
					$message = "Please enter a comment or upload the relevant policy or document.";
				}
			}


//			if (!$loadDoc > 0 ){
//				$jscript = $this->scriptGetForm ("institutional_profile", $inst_id, $label);
//				$lnk = "<a href='".$jscript."'>".'<img src="images/'.$this->imageWrong.'" border=0></a>';
//				$message = "Please upload the required document.";
//			}
			$htmlRow .= <<<htmlRow
			 	<tr>
				<td align="center" class="oncolour">$lnk</td>
				<td class='oncolour'>$showTitle $showField</td>
				<td class='oncolour'><font color="red">$message</font></td>
				</tr>
htmlRow;
		}

		echo $htmlRow;
	}
	?>
</table>
<br><br>
<script>
	// label to take user back to the validation page
	document.defaultFrm.VALIDATION.value = '_label_instProfileValidation';
</script>
</td></tr></table>
<br><br>
