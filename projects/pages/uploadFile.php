<?
$path = "../";
require_once ('_systems/che_projects.php');
$val = readGETPOST("val");
$cmd = readGETPOST('cmd');
$field = readGETPOST('field');
$sid = readREQUEST('sid', md5(uniqid(rand())));

$docID = $val;
?>
<html>
<head>
<title>File Upload</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
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
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#1a5090" height="2"></td></tr><tr><td bgcolor="#4c9fe5"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
<?
if ( $cmd == "doUpload"){
	//kyk of folder exist
	$dbConnect = new dbConnect();
	if(!file_exists(OCTODOC_DIR)){mkdir (OCTODOC_DIR, 0755);}
	//	$filename = "FILE_FLD_".substr($key,12);

	$qstr = join("",file("/tmp/{$sid}_qstring"));
	unlink("/tmp/{$sid}_qstring");

	parse_str($qstr);

	$k = count($file['name']);

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
			$rs = mysqli_query($SQL) or die ("Error adding document to database");
			$docID = mysqli_insert_id();
			$SQL = "UPDATE documents set document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
			$rs = mysqli_query($SQL) or die ("Error updating new document to database");
		}else{
			//update Document
			$SQL = "UPDATE documents set last_update_date = now(), document_name=\"".$fileName."\", document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
			$rs = mysqli_query($SQL) or die ("Error updating document to database");
		}

		if(! rename($file['tmp_name'][$i], OCTODOC_DIR.$docID.$extention)) {
			die("Error moving file to the upload directory. from='".$file['tmp_name'][$i]."' to='".OCTODOC_DIR.$docID.$extention."'");
		}
	}
?>
		<script>
			window.opener.setUploaded('<?php echo $field?>',<?php echo $docID?>);
			self.close();
		</script>
<?
	} else {
?>
<form  enctype="multipart/form-data" action="/cgi-bin/progressbar/upload.cgi?sid=<?php echo $sid; ?>&returnFile=<?php echo  UPLOAD_FILE ?>&cmd=doUpload&val=<?php echo $val?>&field=<?php echo $field?>" method="post">
<table border="0" cellpadding="10" align="center"><tr>
<td  valign="top">
	<table border=0 align="left" cellpadding=3>
	<tr><td><input type="file" name="file[0]"></td></tr>
	<tr><td colspan=2 align="center">
		<input type="hidden" name="sessionid" value="<?php echo $sid?>">
		<input type="button" value="Upload" onClick="setDoF(0);postIt();">
<?php echo if ( isset($_SESSION["ses_userid"]) && $_SESSION["ses_userid"] == 795) { ?>
		<!-- uncomment the following to test with out the progress bar -->
		<input type="submit" value="Upload without progress bar">
<?php echo } ?>
	</td></tr></table>
</td>
</tr></table>
</form>
<?
}
?>
</body>
</html>
