<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>
<?php
$dbhandle = mysqli_connect("localhost", "", "")
	  or die("Unable to connect to MySQL");
	$selected = mysqli_select_db("CHE_heqconline",$dbhandle)
	  or die("Could not select database");

$inst_arr = array(533,534,535);
foreach ($inst_arr AS $i){
	$sql1 = "SELECT 'institutional_profile',count(*) AS total FROM institutional_profile WHERE institution_ref = " . $i;
echo $sql1;
	$sql2 = "SELECT 'institutional_profile_aca_struct', count(*) AS total FROM institutional_profile_aca_struct WHERE institution_ref = " . $i;
	$sql3 = "SELECT 'institutional_profile_bak', count(*) AS total FROM institutional_profile_bak WHERE institution_ref =  " . $i;
	$sql4 = "SELECT 'institutional_profile_contacts', count(*) AS total FROM institutional_profile_contacts WHERE institution_ref =  " . $i;
	$sql5 = "SELECT 'institutional_profile_distance_inst', count(*) AS total FROM institutional_profile_distance_inst WHERE institution_ref =  " . $i;
	$sql6 = "SELECT 'institutional_profile_expen_pattern_1999', count(*) AS total FROM institutional_profile_expen_pattern_1999 WHERE institution_ref =  " . $i;
	$sql7 = "SELECT 'institutional_profile_expen_pattern_2003', count(*) AS total FROM institutional_profile_expen_pattern_2003 WHERE institution_ref =  " . $i;
	$sql8 = "SELECT 'institutional_profile_full_equiv_1999', count(*) AS total FROM institutional_profile_full_equiv_1999 WHERE institution_ref = " . $i;
	$sql9 = "SELECT 'institutional_profile_full_equiv_2003', count(*) AS total FROM institutional_profile_full_equiv_2003 WHERE institution_ref = " . $i;
	$sql10 = "SELECT 'institutional_profile_lecture_rooms', count(*) AS total FROM institutional_profile_lecture_rooms WHERE institution_ref = " . $i;
	$sql11 = "SELECT 'institutional_profile_libraries', count(*) AS total FROM institutional_profile_libraries WHERE institution_ref = " . $i;
	$sql12 = "SELECT 'institutional_profile_library_budget', count(*) AS total FROM institutional_profile_library_budget WHERE institution_ref = " . $i;
	$sql13 = "SELECT 'institutional_profile_main_campus_info', count(*) AS total FROM institutional_profile_main_campus_info WHERE institution_ref = " . $i;
	$sql14 = "SELECT 'institutional_profile_management_info_system', count(*) AS total FROM institutional_profile_management_info_system WHERE institution_ref = " . $i;
	$sql15 = "SELECT 'institutional_profile_nr_type_IT_infrastructure', count(*) AS total FROM institutional_profile_nr_type_IT_infrastructure WHERE institution_ref = " . $i;
	$sql16 = "SELECT 'institutional_profile_nr_type_laboratories', count(*) AS total FROM institutional_profile_nr_type_laboratories WHERE institution_ref = " . $i;
	$sql17 = "SELECT 'institutional_profile_other_sites_info', count(*) AS total FROM institutional_profile_other_sites_info WHERE institution_ref = " . $i;
	$sql18 = "SELECT 'institutional_profile_overall_enroll_1999', count(*) AS total FROM institutional_profile_overall_enroll_1999 WHERE institution_ref = " . $i;
	$sql19 = "SELECT 'institutional_profile_overall_enroll_2003', count(*) AS total FROM institutional_profile_overall_enroll_2003 WHERE institution_ref = " . $i;
	$sql20 = "SELECT 'institutional_profile_pol_budgets_admission', count(*) AS total FROM institutional_profile_pol_budgets_admission WHERE institution_ref = " . $i;
	$sql21 = "SELECT 'institutional_profile_pol_budgets_assessment_eval', count(*) AS total FROM institutional_profile_pol_budgets_assessment_eval WHERE institution_ref = " . $i;
	$sql22 = "SELECT 'institutional_profile_pol_budgets_certification', count(*) AS total FROM institutional_profile_pol_budgets_certification WHERE institution_ref = " . $i;
	$sql23 = "SELECT 'institutional_profile_pol_budgets_hr', count(*) AS total FROM institutional_profile_pol_budgets_hr WHERE institution_ref = " . $i;
	$sql24 = "SELECT 'institutional_profile_pol_budgets_infrastracture', count(*) AS total FROM institutional_profile_pol_budgets_infrastracture WHERE institution_ref = " . $i;
	$sql25 = "SELECT 'institutional_profile_pol_budgets_learning_strat', count(*) AS total FROM institutional_profile_pol_budgets_learning_strat WHERE institution_ref = " . $i;
	$sql26 = "SELECT 'institutional_profile_pol_budgets_post_grad_pol', count(*) AS total FROM institutional_profile_pol_budgets_post_grad_pol WHERE institution_ref = " . $i;
	$sql27 = "SELECT 'institutional_profile_pol_budgets_prog_design', count(*) AS total FROM institutional_profile_pol_budgets_prog_design WHERE institution_ref = " . $i;
	$sql28 = "SELECT 'institutional_profile_pol_budgets_prog_offerings', count(*) AS total FROM institutional_profile_pol_budgets_prog_offerings WHERE institution_ref = " . $i;
	$sql29 = "SELECT 'institutional_profile_pol_staff_ft', count(*) AS total FROM institutional_profile_pol_staff_ft WHERE institution_ref = " . $i;
	$sql30 = "SELECT 'institutional_profile_pol_staff_pt', count(*) AS total FROM institutional_profile_pol_staff_pt WHERE institution_ref = " . $i;
	$sql31 = "SELECT 'institutional_profile_sites', count(*) AS total FROM institutional_profile_sites WHERE institution_ref = " . $i;
	$sql32 = "SELECT 'institutional_profile_sites_pg_enrol', count(*) AS total FROM institutional_profile_sites_pg_enrol WHERE s_institution_ref = " . $i;
	$sql33 = "SELECT 'institutional_profile_sites_ug_enrol', count(*) AS total FROM institutional_profile_sites_ug_enrol WHERE s_institution_ref = " . $i;
	$sql34 = "SELECT 'Institutions_application', count(*) AS total FROM Institutions_application WHERE institution_id = " . $i;
	$sql35 = "SELECT 'Institutions_application_reaccreditation', count(*) AS total FROM Institutions_application_reaccreditation WHERE institution_ref = " . $i;
	$sql36 = "SELECT 'siteVisit', count(*) AS total FROM siteVisit WHERE institution_ref = " . $i;
	$sql37 = "SELECT 'users', user_id, active, institution_name, email AS total FROM users WHERE institution_ref = " . $i;
	$sql38 = "SELECT 'user_registration', count(*) AS total FROM user_registration WHERE institution_ref = " . $i;
	$sql39 = "SELECT 'workflow_audit_trail', count(*) AS total FROM workflow_audit_trail WHERE institution_ref = " . $i;
	$rs1 = mysqli_query($sql1);
	$rs2 = mysqli_query($sql2);
	$rs3 = mysqli_query($sql3);
	$rs4 = mysqli_query($sql4);
	$rs5 = mysqli_query($sql5);
	$rs6 = mysqli_query($sql6);
	$rs7 = mysqli_query($sql7);
	$rs8 = mysqli_query($sql8);
	$rs9 = mysqli_query($sql9);
	$rs10 = mysqli_query($sql10);
	$rs11 = mysqli_query($sql11);
	$rs12 = mysqli_query($sql12);
	$rs13 = mysqli_query($sql13);
	$rs14 = mysqli_query($sql14);
	$rs15 = mysqli_query($sql15);
	$rs16 = mysqli_query($sql16);
	$rs17 = mysqli_query($sql17);
	$rs18 = mysqli_query($sql18);
	$rs19 = mysqli_query($sql19);
	$rs20 = mysqli_query($sql20);
	$rs21 = mysqli_query($sql21);
	$rs22 = mysqli_query($sql22);
	$rs23 = mysqli_query($sql23);
	$rs24 = mysqli_query($sql24);
	$rs25 = mysqli_query($sql25);
	$rs26 = mysqli_query($sql26);
	$rs27 = mysqli_query($sql27);
	$rs28 = mysqli_query($sql28);
	$rs29 = mysqli_query($sql29);
	$rs30 = mysqli_query($sql30);
	$rs31 = mysqli_query($sql31);
	$rs32 = mysqli_query($sql32);
	$rs33 = mysqli_query($sql33);
	$rs34 = mysqli_query($sql34);
	$rs35 = mysqli_query($sql35);
	$rs36 = mysqli_query($sql36);
	$rs37 = mysqli_query($sql37);
	$rs38 = mysqli_query($sql38);
	$rs39 = mysqli_query($sql39);
	$row1= mysqli_fetch_array($rs1);
	$row2= mysqli_fetch_array($rs2);
	$row3= mysqli_fetch_array($rs3);
	$row4= mysqli_fetch_array($rs4);
	$row5= mysqli_fetch_array($rs5);
	$row6= mysqli_fetch_array($rs6);
	$row7= mysqli_fetch_array($rs7);
	$row8= mysqli_fetch_array($rs8);
	$row9= mysqli_fetch_array($rs9);
	$row10= mysqli_fetch_array($rs10);
	$row11= mysqli_fetch_array($rs11);
	$row12= mysqli_fetch_array($rs12);
	$row13= mysqli_fetch_array($rs13);
	$row14= mysqli_fetch_array($rs14);
	$row15= mysqli_fetch_array($rs15);
	$row16= mysqli_fetch_array($rs16);
	$row17= mysqli_fetch_array($rs17);
	$row18= mysqli_fetch_array($rs18);
	$row19= mysqli_fetch_array($rs19);
	$row20= mysqli_fetch_array($rs20);
	$row21= mysqli_fetch_array($rs21);
	$row22= mysqli_fetch_array($rs22);
	$row23= mysqli_fetch_array($rs23);
	$row24= mysqli_fetch_array($rs24);
	$row25= mysqli_fetch_array($rs25);
	$row26= mysqli_fetch_array($rs26);
	$row27= mysqli_fetch_array($rs27);
	$row28= mysqli_fetch_array($rs28);
	$row29= mysqli_fetch_array($rs29);
	$row30= mysqli_fetch_array($rs30);
	$row31= mysqli_fetch_array($rs31);
	$row32= mysqli_fetch_array($rs32);
	$row33= mysqli_fetch_array($rs33);
	$row34= mysqli_fetch_array($rs34);
	$row35= mysqli_fetch_array($rs35);
	$row36= mysqli_fetch_array($rs36);
	$row37= mysqli_fetch_array($rs37);
	$row38= mysqli_fetch_array($rs38);
	$row39= mysqli_fetch_array($rs39);
	$disp_arr[1][0] = $row1[0];
	$disp_arr[2][0] = $row2[0];
	$disp_arr[3][0] = $row3[0];
	$disp_arr[4][0] = $row4[0];
	$disp_arr[5][0] = $row5[0];
	$disp_arr[6][0] = $row6[0];
	$disp_arr[7][0] = $row7[0];
	$disp_arr[8][0] = $row8[0];
	$disp_arr[9][0] = $row9[0];
	$disp_arr[10][0] = $row10[0];
	$disp_arr[11][0] = $row11[0];
	$disp_arr[12][0] = $row12[0];
	$disp_arr[13][0] = $row13[0];
	$disp_arr[14][0] = $row14[0];
	$disp_arr[15][0] = $row15[0];
	$disp_arr[16][0] = $row16[0];
	$disp_arr[17][0] = $row17[0];
	$disp_arr[18][0] = $row18[0];
	$disp_arr[19][0] = $row19[0];
	$disp_arr[20][0] = $row20[0];
	$disp_arr[21][0] = $row21[0];
	$disp_arr[22][0] = $row22[0];
	$disp_arr[23][0] = $row23[0];
	$disp_arr[24][0] = $row24[0];
	$disp_arr[25][0] = $row25[0];
	$disp_arr[26][0] = $row26[0];
	$disp_arr[27][0] = $row27[0];
	$disp_arr[28][0] = $row28[0];
	$disp_arr[29][0] = $row29[0];
	$disp_arr[30][0] = $row30[0];
	$disp_arr[31][0] = $row31[0];
	$disp_arr[32][0] = $row32[0];
	$disp_arr[33][0] = $row33[0];
	$disp_arr[34][0] = $row34[0];
	$disp_arr[35][0] = $row35[0];
	$disp_arr[36][0] = $row36[0];
	$disp_arr[37][0] = $row37[0];
	$disp_arr[38][0] = $row38[0];
	$disp_arr[39][0] = $row39[0];

	$disp_arr[1][$i] = $row1['total'];
	$disp_arr[2][$i] = $row2['total'];
	$disp_arr[3][$i] = $row3['total'];
	$disp_arr[4][$i] = $row4['total'];
	$disp_arr[5][$i] = $row5['total'];
	$disp_arr[6][$i] = $row6['total'];
	$disp_arr[7][$i] = $row7['total'];
	$disp_arr[8][$i] = $row8['total'];
	$disp_arr[9][$i] = $row9['total'];
	$disp_arr[10][$i] = $row10['total'];
	$disp_arr[11][$i] = $row11['total'];
	$disp_arr[12][$i] = $row12['total'];
	$disp_arr[13][$i] = $row13['total'];
	$disp_arr[14][$i] = $row14['total'];
	$disp_arr[15][$i] = $row15['total'];
	$disp_arr[16][$i] = $row16['total'];
	$disp_arr[17][$i] = $row17['total'];
	$disp_arr[18][$i] = $row18['total'];
	$disp_arr[19][$i] = $row19['total'];
	$disp_arr[20][$i] = $row20['total'];
	$disp_arr[21][$i] = $row21['total'];
	$disp_arr[22][$i] = $row22['total'];
	$disp_arr[23][$i] = $row23['total'];
	$disp_arr[24][$i] = $row24['total'];
	$disp_arr[25][$i] = $row25['total'];
	$disp_arr[26][$i] = $row26['total'];
	$disp_arr[27][$i] = $row27['total'];
	$disp_arr[28][$i] = $row28['total'];
	$disp_arr[29][$i] = $row29['total'];
	$disp_arr[30][$i] = $row30['total'];
	$disp_arr[31][$i] = $row31['total'];
	$disp_arr[32][$i] = $row32['total'];
	$disp_arr[33][$i] = $row33['total'];
	$disp_arr[34][$i] = $row34['total'];
	$disp_arr[35][$i] = $row35['total'];
	$disp_arr[36][$i] = $row36['total'];
	$disp_arr[37][$i] = $row37['total'];
	$disp_arr[38][$i] = $row38['total'];
	$disp_arr[39][$i] = $row39['total'];
}
print_r($disp_arr);
?>
</body>
</html>