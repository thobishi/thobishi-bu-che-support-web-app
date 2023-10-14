<?php 


$path = "../heqc-docs/";
require_once ("/var/www/html/common/_systems/heqc-online.php");
$app = new HEQConline (1);

//$dbConnect = new dbConnect("../");
?>
<html>
<head>
<title>Evaluator Information</title>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>



<script>
	function addEvaluator() {
<?php 
		$uName = $uSurname = "";
		$SQL = "SELECT Names, Surname FROM Eval_Auditors WHERE Persnr= '".$_GET["userid"]."'";
		//file_put_contents('php://stderr', print_r("URL : ".$SQL, TRUE));
		//echo $SQL;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

		/* $sm = $conn->prepare($SQL);
		$sm->bind_param("s", $_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result(); */
		
		$rs = mysqli_query($conn, $SQL);
		if ($row = mysqli_fetch_array($rs)) {
			$uName = $row["Names"];
			$uSurname = $row["Surname"];
	//file_put_contents('php://stderr', print_r("URL : ".$uName , TRUE));
		
		}

		
?>	
		window.opener.parent.addGridEntries("<?php echo $_GET["userid"] ?>");
//		window.opener.parent.moveto("stay");


	}
</script>


</head>
<body>
<br>
<table width="98%" align="center">
<tr>
	<td align="center" colspan="2">
	<fieldset class="go">
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<span class="msgn">
			If you decide to select this evaluator to become part of the Accreditation Process, click the 'Add' button. 
			If you do not want to include this evaluator in the Process, click the 'Close & do not add' button.<br>			
			</span>
			</td>
		</tr>
	</table>
	</fieldset>
	</td>
</tr>
<tr>
	<td colspan="2">
	&nbsp;
	</td>
</tr>
<tr><td align="left"><input type="button" class="btn" value="Add" onClick="addEvaluator(), window.close();"></td>
	<td align="right"><input type="button" class="btn" value="Close & do not add" onClick="window.close();"></td>
</tr>
</table>
<br>
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	if ($_GET["userid"] > 0) {

		$PERSNR = $_GET["userid"];
		$SQL = <<<SQL
		SELECT  *, dis.lkp_yn_desc as Disability_yn, act.lkp_yn_desc as active_yn, arat.lkp_yn_desc as A_rated_yn 
		FROM Eval_Auditors
			LEFT JOIN lkp_ETQA_cat on ETQA_cat_id = ETQA_ref
			LEFT JOIN lkp_full_part on lkp_full_part_id = Full_part
			LEFT JOIN lkp_gender on lkp_gender_id = Gender
			LEFT JOIN lkp_province on lkp_province_id = Province
			LEFT JOIN lkp_race on lkp_race_id = Race
			LEFT JOIN lkp_auditor_evaluator on lkp_auditor_evaluator_id = auditor_evaluator 
			LEFT JOIN lkp_title on lkp_title_id = Title_ref
			LEFT JOIN lkp_employer on lkp_employer_id = employer_ref
			LEFT JOIN lkp_employer_type on employer_type_id = Employer_type_ref
			LEFT JOIN lkp_eval_sector on Eval_sector_id = Eval_sector_ref
			LEFT JOIN lkp_organisation_type on Organisation_type_id = Organisation_type_ref
			LEFT JOIN lkp_yes_no as dis on dis.lkp_yn_id = Disability
			LEFT JOIN lkp_yes_no as act on act.lkp_yn_id = active
			LEFT JOIN lkp_yes_no as arat on arat.lkp_yn_id = A_rated
			LEFT JOIN lkp_qualifications ON lkp_qualifications_id = qualifications_ref
			LEFT JOIN lkp_historical_status ON lkp_historical_status_id = historical_status_ref
			LEFT JOIN lkp_merged_status ON lkp_merged_status_id = merged_status_ref
			LEFT JOIN documents ON document_id = 1_cv_doc
		WHERE 
			Persnr = $PERSNR
SQL;

		$rs = mysqli_query($conn, $SQL);

?>
	<table width="98%" border="0" cellpadding="1" align="center">
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$rs = mysqli_query($conn,$SQL);
		if ($row = mysqli_fetch_array($rs)) {
			$appA = array();
			$postA = array();

			$val = ($row["Auditor"] == 1) ? "Auditor" : "";
			if ($val > "") array_push($appA, $val);
			$val = ($row["Evaluator"] == 1) ? "Evaluator" : "";
			if ($val > "") array_push($appA, $val);
			$val = ($row["National_Review_Evaluator"] == 1) ? "National Review Evaluator" : "";
			if ($val > "") array_push($appA, $val);
			$app = implode(", ",$appA);
			if ($row["Post_adr1"]>"") array_push($postA, $row["Post_adr1"]);
			if ($row["Post_adr2"]>"") array_push($postA, $row["Post_adr2"]);
			if ($row["Post_suburb"]>"") array_push($postA, $row["Post_suburb"]);
			if ($row["Post_city"]>"") array_push($postA, $row["Post_city"]);
			if ($row["Post_code"]>"") array_push($postA, $row["Post_code"]);
			$post = implode(", ",$postA);
?>
		<tr><td colspan="2" class="specialb">Individual has applied as: <?php echo $app;?></td></tr>
		<tr><td colspan="2" class="specialb">Available: <?php echo $row["active_yn"];?></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td class="desc"><b>Surname:</b></td><td class='desc'><?php echo $row["Surname"];?></td></tr>
		<tr><td class="desc"><b>Name:</b></td><td class='desc'><?php echo $row["Names"];?></td></tr>
		<tr><td class="desc"><b>Initials:</b></td><td class='desc'><?php echo $row["Initials"];?></td></tr>
		<tr><td class="desc"><b>Title:</b></td><td class='desc'><?php echo $row["lkp_title_desc"];?></td></tr>
		<tr><td class="desc"><b>ID no:</b></td><td class='desc'><?php echo $row["ID_Number"];?></td></tr>
		<tr><td class="desc"><b>Race:</b></td><td class='desc'><?php echo $row["lkp_race_desc"];?></td></tr>
		<tr><td class="desc"><b>Gender:</b></td><td class='desc'><?php echo $row["lkp_gender_desc"];?></td></tr>
		<tr><td class='desc'><b>Date Of Birth:</b></td><td class='desc'><?php echo $row["Date_of_Birth"];?></td></tr>
		<tr><td class='desc'><b>WorkNumber:</b></td><td class='desc'><?php echo $row["Work_Number"];?></td></tr>
		<tr><td class='desc'><b>Mobile:</b></td><td class='desc'><?php echo $row["Mobile_Number"];?></td></tr>
		<tr><td class='desc'><b>Fax Number:</b></td><td class='desc'><?php echo $row["Fax_Number"];?></td></tr>
		<tr><td class='desc'><b>Home Number:</b></td><td class='desc'><?php echo $row["Home_Number"];?></td></tr>
		<tr><td class='desc'><b>E-mail:</b></td><td class='desc'><?php echo $row["E_mail"];?></td></tr>
		<tr><td class='desc'><b>Postal Address:</b></td><td class='desc'><?php echo $post;?></td></tr>
		<tr><td class='desc'><b>Highest Qualification:</b></td><td class='desc'><?php echo $row["lkp_qualifications_desc"];?></td></tr>
		<tr><td class='desc'><b>Highest Qualification Date:</b></td><td class='desc'><?php echo $row["Qualif_date"];?></td></tr>
		<tr><td class='desc'><b>ETQA:</b></td><td class='desc'><?php echo $row["ETQA_cat_desc"];?></td></tr>
		<tr><td class='desc'><b>Province:</b></td><td class='desc'><?php echo $row["lkp_province_desc"];?></td></tr>
		<tr><td class='desc'><b>Full/Part Time:</b></td><td class='desc'><?php echo $row["lkp_full_part_desc"];?></td></tr>
		<tr><td class='desc'><b>Disability</b></td><td class='desc'><?php echo $row["Disability_yn"];?></td></tr>
		<tr><td class='desc'><b>Employer:</b></td><td class='desc'><?php echo $row["lkp_employer_name"];?></td></tr>
		<tr><td class='desc'><b>Employer type:</b></td><td class='desc'><?php echo $row["Employer_type_desc"];?></td></tr>
		<tr><td class='desc'><b>Historical Status:</b></td><td class='desc'><?php echo $row["lkp_historical_status_desc"];?></td></tr>
		<tr><td class='desc'><b>Merged status:</b></td><td class='desc'><?php echo $row["lkp_merged_status_desc"];?></td></tr>
		<tr><td class='desc'><b>Department:</b></td><td class='desc'><?php echo $row["Department"];?></td></tr>
		<tr><td class='desc'><b>Sector:</b></td><td class='desc'><?php echo $row["Eval_sector_desc"];?></td></tr>
		<tr><td class='desc'><b>Organisation type:</b></td><td class='desc'><?php echo $row["Organisation_type_desc"];?></td></tr>
		<tr><td class='desc'><b>Job Title:</b></td><td class='desc'><?php echo $row["Job_title"];?></td></tr>
		<tr><td class='desc'><b>A Rated:</b></td><td class='desc'><?php echo $row["A_rated_yn"];?></td></tr>
<?php 
		}
	}
?>
			<tr><td colspan="2">&nbsp;</td></tr>
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		$PERSNR = $_GET["userid"];

		$SQL = <<<SQL
		SELECT  experience1.lkp_experience_desc, experience2.lkp_experience_desc, experience3.lkp_experience_desc,
				experience4.lkp_experience_desc, experience5.lkp_experience_desc, Eval_Auditors.Other_Experience_Desc
		FROM Eval_Auditors
			LEFT JOIN lkp_experience AS experience1 
				ON experience1.lkp_experience_id = Teaching_experience 
			LEFT JOIN lkp_experience AS experience2
				ON experience2.lkp_experience_id = Research_expereince 
			LEFT JOIN lkp_experience AS experience3
				ON experience3.lkp_experience_id = Admin_experience 
			LEFT JOIN lkp_experience AS experience4
				ON experience4.lkp_experience_id = Manage_experience 
			LEFT JOIN lkp_experience AS experience5
				ON experience5.lkp_experience_id = Other_Experience_from 
		WHERE 
			Persnr=$PERSNR
SQL;

		$rs = mysqli_query($conn,$SQL);
		if ($rowe = mysqli_fetch_array($rs)) {
?>
			<tr><td class='desc'><b>Teaching Experience:</b></td><td class='desc'><?php echo $rowe[0];?></td></tr>
			<tr><td class='desc'><b>Research Experience:</b></td><td class='desc'><?php echo $rowe[1];?></td></tr>
			<tr><td class='desc'><b>Administration Experience:</b></td><td class='desc'><?php echo $rowe[2];?></td></tr>
			<tr><td class='desc'><b>Management Experience:</b></td><td class='desc'><?php echo $rowe[3];?></td></tr>
			<tr><td class='desc'><b>Other Experience:</b></td><td class='desc'><?php echo $rowe[4] . " " . $rowe[5];?></td></tr>
<?php 	
		}
?> 
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td class='desc'><b>Curriculum Vitae:</b></td><td class='desc'>
<?php 
		if ($row["document_url"]>""){
?>
			<a target="_blank" href="<?php echo $path;?><?php echo $row["document_url"];?>"><?php echo $row["document_name"];?></a></td></tr>
<?php 
		} else {
			echo "&nbsp;";
		}
?>
		</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		// Display Academic Excellence
		$SQL = "SELECT * FROM eval_auditors_academic_expertise where persnr_ref='".$_GET["userid"]."'";

		$rsa = mysqli_query($conn,$SQL);
		if (mysqli_num_rows($rsa) > 0){
?>
			<table width="98%" border="1" cellpadding="0" align="center">
			<tr><td colspan="4" class="desc"><b>Academic Excellence</b></td></tr>
			<tr>
				<td class="desc"><b>Major Field</b></td>
				<td class="desc"><b>Sub Field</b></td>
				<td class="desc"><b>Qualification</b></td>
				<td class="desc"><b>Highest Level</b></td>
			</tr>
<?php 
			while ($rowa = mysqli_fetch_array($rsa)) {
?>
				<tr>
					<td><?php echo $rowa["major_field"];?></td><td><?php echo $rowa["sub_field"];?></td>
					<td><?php echo $rowa["qualification"];?></td><td><?php echo $rowa["highest_level"];?></td>
				</tr>
<?php 
			}
			echo "</table>";
		}


$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		// Note order important for population of CESM arrays - code nn00 must come first or parent is duplicated in CESM Code 1
		$SQL = "SELECT * FROM SpecialisationLink, CESM_Tree WHERE CESM_code_ref = CESM_code AND Persno_ref='".$_GET["userid"]."' ORDER BY CESM_code, Description";
		$rs = mysqli_query($conn, $SQL);
		$cesm = $cesm1_array = $cesm2_array = array();
		while ($row = mysqli_fetch_array($rs)) {
			$cesm[$row["CESM_code"]] = $row["Description"];
		}
		foreach ($cesm AS $key=>$value) {
			if ((substr($key, 2, 2) == "00")) {
				$cesm1_array[$key] = $value;
			}else{
				$SQL2 = "SELECT Description FROM CESM_Tree WHERE CESM_code='".substr($key,0,2)."00"."'";
				$rs2 = mysqli_query($conn, $SQL2);
				if ($row2 = mysqli_fetch_array($rs2)){
					if (!(in_array($row2["Description"], $cesm1_array))) {
						$cesm1_array[$key] = $row2["Description"];
					}
					$cesm2_array[$key] = $row2["Description"]." - ".$value;
				}
			}
		}
		if (sizeOf($cesm1_array) > 0) {
?>
	<br>
	<table width="98%" border="1" cellpadding="1" align="center">
	<tr><td class="desc" colspan="2"><b>CESM classification 1:</b></td></tr>
<?php 
			foreach ($cesm1_array AS $key=>$value) {
				echo '<tr><td class="desc" colspan="2">'.$value.'</td></tr>';
			}
		}
?>
	</table><br>
<?php 		
		if (sizeOf($cesm2_array) > 0) {
?>
	<table width="98%" border="1" cellpadding="1" align="center">
	<tr><td><b>CESM classification 2:</b></td></tr>
<?php 
			foreach ($cesm2_array AS $key=>$value) {
				echo '<tr><td class="desc" colspan="2">'.$value.'</td></tr>';
			}
		}
?>
	</table>
<br>
	<table width="98%" border="1" cellpadding="1" align="center">
<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$uid = $_GET["userid"];
	$SQL = "SELECT application_ref, user_ref, date_format(date, '%Y %m %e') AS date1, date, comment FROM `eval_comments` WHERE eval_ref = ".$uid." ORDER BY date";
	$RS = mysqli_query($conn, $SQL);
	while ($RS && ($row=mysqli_fetch_array($RS))) {
	
	$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$app->getValueFromTable("Institutions_application", "application_id", $row["application_ref"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_ref"];
?>
		<tr>
			<td class='desc'><b>Application:</b></td>
			<td class='desc'><b>Author:</b></td>
			<td class='desc'><b>Date:</b></td>
		</tr><tr>
			<td class='desc'><a href="javascript:winPrintApplicationForm('Application Form','<?php echo $row["application_ref"]?>','<?php echo base64_encode($tmpSettings) ?>', '../');">View application</a></td>
			<td class='desc'><?php echo $app->getValueFromTable("users", "user_id", $row["user_ref"], "name")." ".$app->getValueFromTable("users", "user_id", $row["user_ref"], "surname")?></td>
			<td class='desc'><?php echo $app->convertDateForEmail($row["date1"])?></td>
		</tr><tr>
			<td class='desc' colspan="3"><b>Comment:</b> <i>(below)</i></td>
		</tr><tr>
			<td class='desc' colspan="3"><?php echo $row["comment"]?></td>
		</tr><tr>
			<td class='desc' colspan="3">&nbsp;</td>
		</tr>
<?php 
	}
?>	
	</table>
</body>
</html>
