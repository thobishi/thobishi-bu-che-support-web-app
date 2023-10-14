<html>
<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
<script>
	function showInfo(uid) {
		URL = "evalCheckForm1.infowin.html.php?userid=";
		URL = URL + uid;
		infoWinLeft = (screen.width-700)/2;
		infoWinTop = (screen.height-400)/2;
		options = "status=yes,scrollbars=yes, resizable=yes,width=700,height=400,top="+infoWinTop+",left="+infoWinLeft;
		var infoWin = open(URL,'',options);
	}
</script>
</head>
<body>
<?php 
require_once ("_systems/heqc-online.php");
$dbConnect = new dbConnect();

	$searchArr = array();
	$sqlArr = array();
	$iframeText = "";
	$post_array = array("active","A_rated","Race", "Gender", "Disability", "Province", "Eval_sector_ref", "Organisation_type_ref", "Full_part", "ETQA_ref", "Teaching_experience", "Research_expereince", "qualifications_ref", "employer_ref", "Employer_type_ref", "Auditor", "Evaluator", "National_Review_Evaluator","historical_status_ref","merged_status_ref");

	foreach ($post_array AS $key => $value) {
	    if (isset($_POST[$value]) && ($_POST[$value] > 0)) {
					if (strpos(strtolower($value), "experience")) {
						array_push($sqlArr, $value . ">=" . $_POST[$value]);
					}else {
						array_push($sqlArr, $value . "=" . $_POST[$value]);
					}
	    }
	}

	if (isset($_POST["searchText"]) && ($_POST["searchText"] > "")) {
		array_push($searchArr, "MATCH(Names, Surname, Initials, ID_Number) AGAINST('".$_POST["searchText"]."') ");
	}

	if (isset($_POST["searchText1"]) && ($_POST["searchText1"] > "")) {
		array_push($searchArr, "Job_title LIKE '%".$_POST["searchText1"]."%'");
	}

	$tableArray = array();
	$tableArray[0] = "Eval_Auditors";
	if ($_POST["CESM_code1"] != 0) {
		array_push ($tableArray, "SpecialisationLink");
		array_push ($sqlArr, "Persnr=Persno_ref");
		array_push ($sqlArr, "CESM_code_ref LIKE '".$_POST["CESM_code1"]."%'");
	}
	if ($_POST["CESM_code2"] != 0) {
		array_push ($tableArray, "SpecialisationLink");
		array_push ($sqlArr, "Persnr=Persno_ref");
		array_push ($sqlArr, "CESM_code_ref LIKE '".$_POST["CESM_code2"]."%'");
	}

	$SQL = "SELECT Persnr, Names, Surname, Work_Number, E_mail FROM ".implode (", ", $tableArray)." WHERE Evaluator = '1' ";
	$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
	$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL):($SQL);
	$SQL = (count($searchArr) > 0)?($SQL." AND (".implode(" OR ", $searchArr).")"):($SQL);
	$SQL .= " ORDER BY number_evals, Surname,Names";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	if ($rs = mysqli_query($conn, $SQL)) {
	    $iframeText .= "<table width='95%' align='center'>\n";
		if (mysqli_num_rows($rs) > 0){
			$iframeText .= "<tr><td colspan=\"4\">&nbsp;</td>";
			$iframeText .= "<tr><td><b>INFO</b></td><td><b>NAME</b></td><td><b>TEL NO</b></td><td><b>EMAIL:</b></td></tr>\n";
		    while ($row = mysqli_fetch_array($rs)) {
				$iframeText .= "<tr onmouseover='this.bgColor=\"#EAEFF5\"' onmouseout='this.bgColor=\"#FFFFFF\"'><td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'><img border='0' src='../images/info.png'></a></td>\n";
				$iframeText .= "<td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'>" .$row["Surname"]. ", " .$row["Names"] . "</a></td>\n";
				$iframeText .= "<td valign='top'>". $row["Work_Number"] ."</td>\n";
				$iframeText .= "<td valign='top'>".$row["E_mail"]."</td></tr>\n";
			} 
		}else {
			$iframeText .= "<tr><td colspan='2' align='center'><b>No results found!</b></td></tr>\n";
		}
	    $iframeText .= "</table>\n";
	} 
	echo $iframeText;
?>
</body>
</html>
