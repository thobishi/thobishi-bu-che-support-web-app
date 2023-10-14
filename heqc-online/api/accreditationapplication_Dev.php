<?php
	include_once('config.php');
	$CHE_reference_code = isset($_GET['CHE_reference_code']) ? mysqli_real_escape_string($conn, $_GET['CHE_reference_code']) :  "";

   $sql = "SELECT  CHE_reference_code,HEInstitution.HEI_name,  program_name AS CHE_programme_name,  lkp_mode_of_delivery_desc AS mode_of_delivery, NQF_ref + 4 AS NQF_level,min_credits_heqsf, num_credits,	Institutions_application.1_2_comment AS a1_2_comment , Institutions_application.1_4_comment_v2 AS a1_4_comment_v2,Institutions_application.2_2_comment AS a2_2_comment,Institutions_application.1_7_comment AS a1_7_comment,1_7_progression_rules AS a1_7_progression_rules,exit_level_outcomes,1_international_comparability AS a1_international_comparability,1_no_international_comparability_reason AS a1_no_international_comparability_reason, Institutions_application.2_6_comment AS a2_6_comment , 6_policies_rpl_whyNot AS a6_policies_rpl_whyNot,accumulation_transfer_details,Institutions_application.1_3_comment AS a1_3_comment,Institutions_application.6_1_comment AS a6_1_comment,SAQA_Field.Description AS SAQA_Field,submission_date, AC_Meeting_date AS Outcome_date, lkp_dec.lkp_title AS Outcome, lkp_qualification_type.lkp_qualification_type_desc  
   FROM (HEInstitution, Institutions_application,lkp_qualification_type,SAQA_Field)    LEFT JOIN lkp_desicion AS lkp_dec ON lkp_dec.lkp_id = Institutions_application.AC_desision    LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery_id = Institutions_application.mode_delivery    LEFT JOIN lkp_desicion AS lkp_decwith ON lkp_decwith.lkp_id = Institutions_application.withdrawn_decision_ref    LEFT JOIN SAQA_Field AS Field ON (Field.SAQA_Field_code1*1) = Institutions_application.field_ID     WHERE HEInstitution.HEI_id = Institutions_application.institution_id    AND lkp_qualification_type.lkp_qualification_type_id=Institutions_application.qualification_type_ref    AND Institutions_application.submission_date >= '2009-01-01'   AND   CHE_reference_code='{$CHE_reference_code}' 
     group by CHE_reference_code
	 LIMIT 100";
 
   $get_data_query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		if(mysqli_num_rows($get_data_query)!=0){
		$result = array();
		
		while($r = mysqli_fetch_array($get_data_query)){
			extract($r);

			$result[] = array("HEI_name" => $HEI_name , "CHE_reference_code" => $CHE_reference_code  , "1_4_comment_v2" => $a1_4_comment_v2 ,"1_2_comment" => $a1_2_comment );//, "1_4_comment_v2" => $a1_4_comment_v2 ,"1_2_comment" => $a1_2_comment",1_3_comment" => $a1_3_comment, "1_4_comment_v2" => $a1_4_comment_v2,"1_3_comment" => $a1_3_comment, "a1_4_comment_v2" => $a1_4_comment_v2);//   );
		}
		$json = array("status" => 1, "Accreditation Application" => $result);
	}
	else{
		$json = array("status" => 0, "error" => "Accreditation Application not found!");

	}
@mysqli_close($conn);


header('Content-type: application/json');
echo json_encode($json);
