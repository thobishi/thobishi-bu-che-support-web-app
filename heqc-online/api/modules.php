<?php


	include_once('config.php');

	//$application_id = isset($_GET['application_id']) ? mysqli_real_escape_string($conn, $_GET['application_id']) :  "";

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









 




	$application_id = isset($_GET['application_id']) ? mysqli_real_escape_string($conn, $_GET['application_id']) :  "";
	
$sqlappTable="select course_name,nqf_level_ref + 4 AS Level_ref,fund_credits,course_type,year,semester from appTable_1_prog_structure where  application_ref='{$application_id}'";

 $get_data_queryappTable = mysqli_query($conn, $sqlappTable) or die(mysqli_error($conn));

		if(mysqli_num_rows($get_data_queryappTable)!=0){
		$resultappTable = array();
		
	while($y = mysqli_fetch_array($get_data_queryappTable)){
			extract($y);

$resultappTable[] = array("course_name" => $course_name,"Level_ref" => $Level_ref ,"fund_credits" => $fund_credits ,"course_type" => $course_type ,"year" => $year,"semester" => $semester);





		}
		$json = array("status" => 1, "Accreditation Application" => $resultappTable);
	}
	else{
		$json = array("status" => 0, "error" => "Accreditation Application not found!");

	}
@mysqli_close($conn);

// Set Content-type to JSON
header('Content-type: application/json');
echo json_encode($json);





?>
