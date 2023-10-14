<html>
<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
<script>
	function showInfo(uid) {
		URL = "changeEval.infowin.html.php?userid=";
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
$path = "../";
require_once ('_systems/heqc-online.php');
$dbConnect = new dbConnect();

$searchArr = array();
$sqlArr = array();
$iframeText = "";
$post_array = array("race", "gender", "disibility", "province", "sector", "full_part", "ETQA_ref", "Teaching_experience", "Research_experience", "Audit_Eval_Experience");
foreach ($post_array AS $key => $value) {
    if (isset($_POST[$value]) && ($_POST[$value] > 0)) {
        array_push($sqlArr, $value . "=" . $_POST[$value]);
    } 
} 

if (isset($_POST["searchText"]) && ($_POST["searchText"] > "")) {
	array_push($searchArr, "MATCH(Names, Surname, Initials, ID_Number) AGAINST('".$_POST["searchText"]."') ");
}
	
$tableArray = array();
$tableArray[0] = "Eval_Auditors";
if ($_POST["CESM_code"] != 0) {
	array_push ($tableArray, "SpecialisationLink");
	array_push ($sqlArr, "Persnr=Persno_ref");
	array_push ($sqlArr, "CESM_code_ref LIKE '".$_POST["CESM_code"]."%'");
}

$notEvalArray = Array();
array_push($notEvalArray,0);
$SQL = "SELECT Persnr_ref FROM evalReport WHERE evalReport_status_confirm = 1 AND application_ref=".$_POST["app_ref"];
$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while($row = mysqli_fetch_array($rs)){
//		array_push($notEvalArray,$row[0]);
	}
}
//$SQL = "SELECT Persnr, Names, Surname FROM ".implode (", ", $tableArray)." WHERE Persnr not in (".implode(",",$notEvalArray).") AND ";
//$SQL = (count($sqlArr) > 0)?($SQL." (" . implode(" AND ", $sqlArr).")"):($SQL);
//$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL." AND "):($SQL);
//$SQL = (count($searchArr) > 0)?($SQL." (".implode(" OR ", $searchArr).")"):($SQL);
//$SQL .= " ORDER BY Surname,Names";

$SQL = "SELECT Persnr, Names, Surname FROM ".implode (", ", $tableArray)." WHERE 1 ";
$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL):($SQL);
$SQL = (count($searchArr) > 0)?($SQL." AND (".implode(" OR ", $searchArr).")"):($SQL);
$SQL .= "ORDER BY number_evals, Surname,Names";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
                
if ($rs = mysqli_query($conn, $SQL)) {
    $iframeText .= "<table width='95%' align='center'>\n";
	if (mysqli_num_rows($rs) > 0){
		$iframeText .= "<tr><td><b>INFO:</b></td><td><b>NAME:</b></td></tr>\n";
	    while ($row = mysqli_fetch_array($rs)) {
			$iframeText .= "<tr onmouseover='this.bgColor=\"#EAEFF5\"' onmouseout='this.bgColor=\"#FFFFFF\"'><td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'><img border='0' src='../images/info.png'></a></td>\n";
			$iframeText .= "<td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'>" .$row["Surname"]. ", " .$row["Names"] . "</a></td></tr>\n";
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
