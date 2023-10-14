<?php 

if (!defined('OCTODOCGEN_URL')) ;//die ('ERROR: system can not read the DocGen path');
	$path = "../";
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	$val = (isset($_GET['val']))?($_GET['val']):('');
	$cmd = (isset($_GET['cmd']))?($_GET['cmd']):('');

	$field = (isset($_GET['field']))?($_GET['field']):('');
	$sid = readREQUEST('sid', md5(uniqid(rand())));

	$docID = $val;
	//file_put_contents('php://stderr', print_r("\ncmd : ".$cmd." ,field : ".$field." ,docID : ".$docID, TRUE));
?>
<html>
<head>
<title>File Upload</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path ?>styles.css" title="Normal Style">
<script language="javascript" type="text/javascript" src="script.js"></script>
<script>
var doF = 1;
function setDoF(val){
	doF = val;
}

function doFocus(){
	if (doF == 1){
		setTimeout('self.focus()',800);
	}
}

function checkFile() {
	if (document.uploadFile.upFile.value == "") {
		alert("Please select a file to upload, or close the window.");
		return false;
	}
	return true;
}

</script>
</head>
<body onblur="doFocus();">
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
<?php
//file_put_contents('php://stderr', print_r("\ncmd : ".$cmd, TRUE));
if ( $cmd == "doUpload"){
	//kyk of folder exist 
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if(!file_exists(OCTODOC_DIR)){mkdir (OCTODOC_DIR, 0755);}
	//	$filename = "FILE_FLD_".substr($key,12);

	$qstr = join("",file("/tmp/{$sid}_qstring"));
	unlink("/tmp/{$sid}_qstring");

	parse_str($qstr);

	$k = count($file['name']);
	//file_put_contents('php://stderr', print_r("\nk : ".$k, TRUE));
	for($i=0 ; $i < $k ; $i++)
	{
		$fileinfo = $path_parts = pathinfo($file['name'][$i]);
		$fileName = $fileinfo['basename'];
		if ( strrchr ($fileName, '\\') ) {
			$fileName = substr (strrchr ($fileName, '\\'),1);
		}
		$extention = ".".$fileinfo['extension'];
		if ($docID == 0){
			//new document
			$SQL = "INSERT INTO documents (creation_date,last_update_date,document_name) values (now(),now(),\"".$fileName."\")";
			$rs = mysqli_query($conn, $SQL) or die ("Error adding document to database");
			$docID = mysqli_insert_id($conn);
			$SQL = "UPDATE documents set document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
			$rs = mysqli_query($conn, $SQL)or die ("Error updating new document to database");

			
			if ( isset($_SESSION["ses_table"]) && isset($_SESSION["ses_keyFLD"]) && isset($_SESSION["ses_keyVal"])){
				$table = $_SESSION["ses_table"];
				$keyFLD = $_SESSION["ses_keyFLD"];
				$keyVal = $_SESSION["ses_keyVal"];
				//$SQL = "UPDATE ".$table." set application_doc\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal;
				/*$SQL1 = "UPDATE ".$table." set reacc_acmeeting_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal;
				 
				 $rs1 = mysqli_query($conn, $SQL1);
				
				$SQL2 = "UPDATE ".$table." set siteapp_doc\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal;
				
				  $rs2 = mysqli_query($conn, $SQL2);
		    	$SQL3 = "UPDATE ".$table." set condition_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs3 = mysqli_query($conn, $SQL3) ;
                   
                   $SQL4 = "UPDATE ".$table." set FLD_registration_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs4 = mysqli_query($conn, $SQL4) ;
                   
                   $SQL5 = "UPDATE ".$table." set prev_minutes_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs5 = mysqli_query($conn, $SQL5) ;
                   
                    $SQL6 = "UPDATE ".$table." set agenda_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs5 = mysqli_query($conn, $SQL6) ;
                   
                    $SQL7 = "UPDATE ".$table." set minutes_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs7 = mysqli_query($conn, $SQL7) ;
                   
                    $SQL8= "UPDATE ".$table." set deferral_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs8= mysqli_query($conn, $SQL8) ;
                   
                   
                   $SQL9= "UPDATE ".$table." set representation_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs9= mysqli_query($conn, $SQL9) ;
                   
                   
                   $SQL10= "UPDATE ".$table." set initiation_doc=\"".$docID."\" WHERE ".$keyFLD." = ".$keyVal; 
                   $rs10= mysqli_query($conn, $SQL10) ; */
                    
				
				//file_put_contents('php://stderr', print_r("\nSQL : ".$SQL, TRUE));
				//$rs = mysqli_query($conn, $SQL)or die ("Error updating new document to database");				

				unset($_SESSION["ses_table"]);
				unset($_SESSION["ses_keyFLD"]); 
				unset($_SESSION["ses_keyVal"]);
			}

		}else{
			//update Document
			$SQL = "UPDATE documents set last_update_date = now(), document_name=\"".$fileName."\", document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
			$rs = mysqli_query($conn, $SQL) or die ("Error updating document to database");
		}

		if(! rename($file['tmp_name'][$i], OCTODOC_DIR.$docID.$extention)) {
			die("Error moving file to the upload directory. from='".$file['tmp_name'][$i]."' to='".OCTODOC_DIR.$docID.$extention."'");
		}
	}
		
	
?>
		<script>
			window.opener.setUploaded('<?php echo $field ?>',<?php echo $docID ?>);
			self.close();
		</script>
<?php 
	} else {
?>
<form  enctype="multipart/form-data" action="/cgi-bin/progressbar/upload.cgi?sid=<?php echo $sid;?>&returnFile=<?php echo  UPLOAD_FILE?>&cmd=doUpload&val=<?php echo $val?>&field=<?php echo $field?>" method="post">
<table border="0" cellpadding="10" align="center"><tr>
<td  valign="top">
	<table border=0 align="left" cellpadding=3>
	<tr><td><input type="file" name="file[0]"></td></tr>
	<tr><td colspan=2 align="center">
		<input type="hidden" name="sessionid" value="<?php echo $sid?>">
		<input type="button" value="Upload" onClick="setDoF(0);postIt();">
<?php if ( isset($_SESSION["ses_userid"]) && $_SESSION["ses_userid"] == 795) { ?>
		<!-- uncomment the following to test with out the progress bar -->
		<input type="submit" value="Upload without progress bar">
<?php } ?>
	</td></tr></table>
</td>
</tr></table>
</form>
<?php 
}
?>
</body>
</html>
