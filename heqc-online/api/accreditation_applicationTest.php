<?php
	include_once('config.php');
	$CHE_reference_code = isset($_GET['CHE_reference_code']) ? mysqli_real_escape_string($conn, $_GET['CHE_reference_code']) :  "";

	$Username = isset($_GET['Username']) ? mysqli_real_escape_string($conn, $_GET['Username']) :  "";

	$Password = isset($_GET['Password']) ? mysqli_real_escape_string($conn, $_GET['Password']) :  "";





    $SQLSecurity = "SELECT * FROM  users  WHERE UPPER(email) = UPPER('{$Username}' ) AND password = PASSWORD2('{$Password}');";
						

$get_Security = mysqli_query($conn, $SQLSecurity) or die(mysqli_error($conn));



		if(mysqli_num_rows($get_Security)!=1){
		
		
		
		while($s = mysqli_fetch_array($get_Security)){
			
extract($s);

if ($s["active"] == 1) {

}else{
    echo "<script>alert('You have entered an incorrect username or password, please try again.');</script>";
die();
	
}
     echo "<script>alert('You have entered an incorrect username or password, please try again.');</script>";
	die();
}
   echo "<script>alert('You have entered an incorrect username or password, please try again.');</script>";
die();
}





$word = "AR";
$mystring = $CHE_reference_code;
 

if(strpos($mystring, $word) !== false){
    //echo "Word Found!";

$result = array();
	$resultappTable = array();
	
   $sql = "SELECT  CHE_reference_code,HEInstitution.HEI_name,  program_name AS CHE_programme_name,lkp_qualification_type.lkp_qualification_type_desc,NQF_ref + 4 AS NQF_level,min_credits_heqsf, num_credits,Institutions_application.field_ID ,Institutions_application.subfield_ID ,SAQA_Field.Description AS SAQA_Field , SAQA_Sub_Field.Description As SAQA_SubField,Institutions_application.1_2_comment AS a1_2_comment,Institutions_application. 1_4_comment_v2 AS a1_4_comment_v2,Institutions_application. 2_2_comment AS a2_2_comment,Institutions_application.exit_level_outcomes,Institutions_application.1_international_comparability AS a1_international_comparability ,Institutions_application.1_no_international_comparability_reason AS a1_no_international_comparability_reason,Institutions_application. 2_6_comment AS  a2_6_comment,Institutions_application.6_policies_rpl_whyNot AS a6_policies_rpl_whyNot,Institutions_application.accumulation_transfer_details,Institutions_application.1_3_comment AS a1_3_comment,Institutions_application.6_1_comment AS a6_1_comment,Institutions_application.1_7_comment AS a1_7_comment,Institutions_application.1_7_progression_rules AS a1_7_progression_rules,Institutions_application.associated_assessment_criteria,Institutions_application.application_id
FROM (HEInstitution, Institutions_application,lkp_qualification_type)    LEFT JOIN SAQA_Field  ON (SAQA_Field.SAQA_Field_code1*1) = Institutions_application.field_ID   LEFT JOIN SAQA_Sub_Field  ON (SUBSTRING(SAQA_Sub_Field.Description,1,3)) = Institutions_application.subfield_ID   WHERE HEInstitution.HEI_id = Institutions_application.institution_id    AND   lkp_qualification_type.lkp_qualification_type_id=Institutions_application.qualification_type_ref  And Institutions_application.AC_desision IN (1,2)   AND Institutions_application.submission_date >= '2009-01-01' and CHE_reference_code='{$CHE_reference_code}' ";


   $get_data_query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		if(mysqli_num_rows($get_data_query)!=0){
		
		
		
		while($r = mysqli_fetch_array($get_data_query)){
			extract($r);




		
			$result[] = array("HEI_name" => $HEI_name, "CHE_reference_code" => $CHE_reference_code, "CHE_programme_name" => $CHE_programme_name ,"lkp_qualification_type_desc" => $lkp_qualification_type_desc,"NQF_level" => $NQF_level,"min_credits_heqsf" => $min_credits_heqsf,"field_ID" => $field_ID,"subfield_ID" => $subfield_ID,"SAQA_Field" => $SAQA_Field,"SAQA_SubField" => $SAQA_SubField,"a1_2_comment" => $a1_2_comment ,"a1_4_comment_v2" => $a1_4_comment_v2 ,"a2_2_comment" => $a2_2_comment ,"exit_level_outcomes" => $exit_level_outcomes ,"associated_assessment_criteria" => $associated_assessment_criteria,"a1_international_comparability" => $a1_international_comparability ,"a1_no_international_comparability_reason" => $a1_no_international_comparability_reason,"a2_6_comment" => $a2_6_comment,"a6_policies_rpl_whyNot" => $a6_policies_rpl_whyNot,"accumulation_transfer_details" => $accumulation_transfer_details,"a1_3_comment" => $a1_3_comment,"a6_1_comment" => $a6_1_comment,"a1_7_comment" => $a1_7_comment,"a1_7_progression_rules" => $a1_7_progression_rules,"application_id" => $application_id  );



	$application_id = $r[application_id] ;
	
$sqlappTable="select course_name,nqf_level_ref + 4 AS Level_ref,fund_credits,course_type,year,semester from appTable_1_prog_structure where  application_ref='{$application_id}'";

 $get_data_queryappTable = mysqli_query($conn, $sqlappTable) or die(mysqli_error($conn));

		if(mysqli_num_rows($get_data_queryappTable)!=0){
		$resultappTable = array();
		
	while($y = mysqli_fetch_array($get_data_queryappTable)){
			extract($y);

$resultappTable[] = array("course_name" => $course_name,"Level_ref" => $Level_ref ,"fund_credits" => $fund_credits ,"course_type" => $course_type ,"year" => $year,"semester" => $semester);





		}
		
	}
	else{
		$json = array("status" => 0, "error" => "Accreditation Application not found!");

	}




		}
			
			
		
	$json = array("Institution Application" =>  $result,"Module" => $resultappTable);


	}
	else{
		$json = array("status" => 0, "error" => "Accreditation Application not found!");

	}
@mysqli_close($conn);

// Set Content-type to JSON
header('Content-type: application/json');
echo json_encode($json);


} else{
     echo "<script>alert('CHE reference code must contains AR.');</script>";
die();
}



