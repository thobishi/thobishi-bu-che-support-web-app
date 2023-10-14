<?php 
$path = "../";
require_once ('_systems/heqc-online.php');
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
		$SQL = "SELECT Names, Surname FROM Eval_Auditors WHERE Persnr=?";
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($rs)) {
			$uName = $row["Names"];
			$uSurname = $row["Surname"];
		}
?>	
		window.opener.parent.document.defaultFrm.eval_id.value = "<?php echo $_GET["userid"]?>";
		window.opener.parent.document.defaultFrm.eval_display.value = "<?php echo $uSurname?>, <?php echo $uName?>";
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
			If you do not want to include this evaluotor in the Process, click the 'Close & do not add' button.<br>			
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
<tr><td align="left"><input type="button" class="btn" value="Add" onClick="addEvaluator();window.close();"></td>
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
                $SQL = "SELECT  * ";
		$SQL .= "FROM Eval_Auditors, lkp_ETQA_cat, ".
				"lkp_full_part, lkp_gender, lkp_province, lkp_race, lkp_sector, lkp_title, lkp_yes_no ";
		$SQL .= "WHERE ETQA_cat_id = ETQA_ref AND lkp_full_part_id = Full_part AND lkp_gender_id = Gender AND lkp_province_id = Province AND lkp_race_id = Race AND lkp_sector_id = Sector AND lkp_title_id = Title_ref AND lkp_yn_id = Disability AND Persnr = ?";

		$sm = $conn->prepare($SQL);
		$sm->bind_param($_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
?>
	<table width="98%" border="1" cellpadding="1" align="center"><tr>
<?php 
		if ($row = mysqli_fetch_array($rs)) {
?>
		<td class="desc"><b>INITIALS:</b></td>
		<td class="desc"><b>NAME:</b></td>
		<td class="desc"><b>SURNAME:</b></td>
		<td class="desc"><b>TITLE:</b></td>
		<td class="desc"><b>ID no:</b></td>
		<td class="desc"><b>RACE:</b></td>
		<td class="desc"><b>GENDER:</b></td>
		</tr><tr>
<?php 	
			echo "<td class='desc'>".$row["Initials"]."</td>";
			echo "<td class='desc'>".$row["Names"]."</td>";
			echo "<td class='desc'>".$row["Surname"]."</td>";
			echo "<td class='desc'>".$row["lkp_title_desc"]."</td>";
			echo "<td class='desc'>".$row["ID_Number"]."</td>";
			echo "<td class='desc'>".$row["lkp_race_desc"]."</td>";
			echo "<td class='desc'>".$row["lkp_gender_desc"]."</td>";
?>
			</tr><tr>
			<td colspan="7">&nbsp</td>
			</tr><tr>
			<td class='desc'><b>Date Of Birth:</b></td>
			<td class='desc'><b>WorkNumber:</b></td>
			<td class='desc'><b>Mobile:</b></td>
			<td class='desc'><b>Fax Number:</b></td>
			<td class='desc'><b>Home Number:</b></td>
			<td class='desc'><b>E-mail:</b></td>
			<td class='desc'><b>Highest Qualification:</b></td>
			</tr><tr>
<?php 
			echo "<td class='desc'>".$row["Date_of_Birth"]."</td>";
			echo "<td class='desc'>".$row["Work_Number"]."</td>";
			echo "<td class='desc'>".$row["Mobile_Number"]."</td>";
			echo "<td class='desc'>".$row["Fax_Number"]."</td>";
			echo "<td class='desc'>".$row["Home_Number"]."</td>";
			echo "<td class='desc'>".$row["E_mail"]."</td>";
			echo "<td class='desc'>".$row["Highest_Qual"]."</td>";
?>
			</tr><tr><td colspan="7">&nbsp;</td>
			</tr><tr>
			<td class='desc'><b>ETQA:</b></td>
			<td class='desc'><b>Province:</b></td>
			<td class='desc'><b>Full/Part Time:</b></td>
			<td class='desc'><b>Sector:</b></td>
			<td class='desc'><b>Disability</b></td>
			<td class='desc'><b>Employer:</b></td>
			<td class='desc'><b>Job Title:</b></td>
			</tr><tr>
<?php 			
			echo "<td class='desc'>".$row["ETQA_cat_desc"]."</td>";
			echo "<td class='desc'>".$row["lkp_province_desc"]."</td>";
			echo "<td class='desc'>".$row["lkp_full_part_desc"]."</td>";
			echo "<td class='desc'>".$row["lkp_sector_desc"]."</td>";
			echo "<td class='desc'>".$row["lkp_yn_desc"]."</td>";
			echo "<td class='desc'>".$row["Empoloyer"]."</td>";
			echo "<td class='desc'>".$row["Job_title"]."</td>";
		}
	}
?>
			</tr><tr><td colspan="7">&nbsp;</td>
			</tr>
<?php 
		$SQL = "SELECT  experience1.lkp_experience_desc, experience2.lkp_experience_desc, experience3.lkp_experience_desc, experience4.lkp_experience_desc ";
		$SQL .= "FROM Eval_Auditors, ".
				"lkp_experience AS experience1, ".
				"lkp_experience AS experience2, ".
				"lkp_experience AS experience3, ".
				"lkp_experience AS experience4 ";
		$SQL .= "WHERE experience1.lkp_experience_id = Teaching_experience AND experience2.lkp_experience_id = Research_expereince AND experience3.lkp_experience_id = Other_Experience_from AND experience4.lkp_experience_id = Audit_Eval_Experience AND Persnr=?";
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param($_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($rs)) {
?>
		<tr>
		<td class='desc' colspan="2"><b>Teaching Experience:</b></td>
		<td class='desc' colspan="2"><b>Research Expereince:</b></td>
		<td class='desc' colspan="2"><b>Other Experience:</b></td>
		<td class='desc'><b>Audit Eval Experience:</b></td>
		</tr><tr>
<?php 	
			echo "<td class='desc' colspan='2'>".$row[0]."</td>";
			echo "<td class='desc' colspan='2'>".$row[1]."</td>";
			echo "<td class='desc' colspan='2'>".$row[2]."</td>";
			echo "<td class='desc'>".$row[3]."</td>";
		}
		
		$SQL = "SELECT  Willingtobetrained,  Performance, Improvements, Recomm_Training, lkp_yn_desc ";
		$SQL .= "FROM Eval_Auditors, lkp_yes_no ";
		$SQL .= "WHERE lkp_yn_id = Willingtobetrained AND Persnr=?";
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param($_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
?>
			</tr><tr><td colspan="7">&nbsp;</td>
			</tr><tr>
			<td class='desc'><b>Willing to Be Trained:</b></td>
			<td class='desc' colspan="2"><b>Performance:</b></td>
			<td class='desc' colspan="2"><b>Improvements:</b></td>
			<td class='desc' colspan="2"><b>Recommend Training:</b></td>
			</tr><tr>
	</tr></table>
<?php 
		if ($row = mysqli_fetch_array($rs)) {
			echo "<td class='desc'>".$row[4]."</td>";
			echo "<td class='desc' colspan='2'>&nbsp;".$row[1]."</td>";
			echo "<td class='desc' colspan='2'>&nbsp;".$row[2]."</td>";
			echo "<td class='desc' colspan='2'>&nbsp;".$row[3]."</td>";
		}
?>
			</tr><tr><td colspan="7">&nbsp;</td>
			</tr>
<?php 
		$SQL = "SELECT * FROM SpecialisationLink, CESM_Tree WHERE CESM_code_ref = CESM_code AND Persno_ref=? ORDER BY Description";
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $_GET["userid"]);
		$sm->execute();
		$rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		$cesm = $cesm1_array = $cesm2_array = array();
		while ($row = mysqli_fetch_array($rs)) {
			$cesm[$row["CESM_code"]] = $row["Description"];
		}
		foreach ($cesm AS $key=>$value) {
			if ((substr($key, 2, 2) == "00")) {
				$cesm1_array[$key] = $value;
			}else{
				$SQL2 = "SELECT Description FROM CESM_Tree WHERE CESM_code1=?";
				
				$sm = $conn->prepare($SQL2);
                                $sm->bind_param("s", substr($key,0,2));
                                $sm->execute();
                                $rs2 = $sm->get_result();
		
				//$rs2 = mysqli_query($SQL2);
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
	$uid = 144;
	$SQL = "SELECT application_ref, user_ref, date_format(date, '%Y %m %e') AS date1, date, comment FROM `eval_comments` WHERE eval_ref = ? ORDER BY date";
	
	$sm = $conn->prepare($SQL);
        $sm->bind_param("s", $uid);
        $sm->execute();
        $RS = $sm->get_result();
		
	//$RS = mysqli_query($SQL);
	while ($RS && ($row=mysqli_fetch_array($RS))) {
	
	$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$app->getValueFromTable("Institutions_application", "application_id", $row["application_ref"], "institution_id")."&DBINF_Institutions_application___application_id=".$row["application_ref"];
?>
		<tr>
			<td class='desc'><b>Application:</b></td>
			<td class='desc'><b>User:</b></td>
			<td class='desc'><b>Date:</b></td>
		</tr><tr>
			<td class='desc'><a href="javascript:winPrintApplicationForm('Application Form','<?php echo $row["application_ref"]?>','<?php echo base64_encode($tmpSettings)?>', '../');">View application</a></td>
			<td class='desc'><?php echo $app->getValueFromTable("users", "user_id", $row["user_ref"], "name")." ".$app->getValueFromTable("users", "user_id", $row["user_ref"], "surname")?></td>
			<td class='desc'><?php echo $app->convertDateForEmail($row["date1"])?></td>
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
