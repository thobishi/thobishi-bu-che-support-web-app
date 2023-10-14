<?php
	$path = "";
	require_once ('_systems/nr-online.php');
	$val = (isset($_GET['val']))?($_GET['val']):('');
	$cmd = (isset($_GET['cmd']))?($_GET['cmd']):('');
	$field = (isset($_GET['field']))?($_GET['field']):('');
	$sid = readREQUEST('sid', md5(uniqid(rand())));
	$docID = $val;


	function displayForm($sid, $field, $val){
?>
		<div class="iframeLeft">
			<form enctype="multipart/form-data" id="uploadFileForm" action="/cgi-bin/progressbar/upload.cgi?sid=<?php echo $sid; ?>&returnFile=<?php echo  UPLOAD_FILE ?>&cmd=doUpload&val=<?php echo $val; ?>&field=<?php echo $field; ?>" method="post">
				<table border="0" cellpadding="10" align="center">
					<tr>
						<td  valign="top">
							<table border=0 align="left" cellpadding=3>
								<tr><td><input type="file" name="file[0]"></td></tr>
								<tr>
									<td colspan=2 align="center">
										<input type="hidden" id="sessionid" value="<?php echo $sid; ?>">
										<input type="button" value="Upload" onClick="setDoF(0);postIt();">
						<?php
							if( isset($_SESSION["ses_userid"]) && $_SESSION["ses_userid"] == 795){
						?>
								<!-- uncomment the following to test with out the progress bar -->
										<input type="submit" value="Upload without progress bar">
						<?php
							} 
						?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="iframeRight" id="uploadIndicator"></div>
<?php
	}
	
?>
<html>
	<head>
		<title>File Upload</title>
		<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path ; ?>styles.css" title="Normal Style">
		<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<script>
			var doF = 1;
			var postLocation = "pages/pgbar.php";
			var re = /^(\.php)|(\.sh)/;  // disallow shell scripts and php
			var dofilter=true;
			
			function setDoF(val){
				doF = val;
			}

			function doFocus(){
				if (doF == 1){
					setTimeout('self.focus()',800);
				}
			}

			function checkFile(){
				if (document.uploadFile.upFile.value == "") {
					alert("Please select a file to upload, or close the window.");
					return false;
				}
				return true;
			}
			 
			function check_types(){
				if(dofilter==false)
					return true;
				with(document.forms[0])
				{
					/*
					 * with who uses with?
					 * i do, i am an ancient. ok?
					 */
					
					for(i=0 ; i < elements.length ; i++)
					{
						if(elements[i].value.match(re))
						{
							alert('Sorry ' + elements[i].value + ' is not allowed');
							return false;
						}
					}
				}
				return true;
			}

			function populateIframe(baseUrl, title){
				window.parent.showProgressBar(baseUrl, title);
			}

			function postIt(){
				if(check_types() == false)
				{
					return false;
				}
				baseUrl = postLocation;
				sid = document.forms[0].sessionid.value;
				iTotal = escape("-1");
				baseUrl += "?iTotal=" + iTotal;
				baseUrl += "&amp;iRead=0";
				baseUrl += "&amp;iStatus=1";
				baseUrl += "&amp;sessionid=" + sid;

				populateIframe(baseUrl, 'File upload progress');
				document.forms[0].submit();
			}
		</script>
		<style>
			.alert {
				padding: 8px 35px 8px 14px;
				margin-bottom: 20px;
				text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
				background-color: #fcf8e3;
				border: 1px solid #fbeed5;
				-webkit-border-radius: 4px;
					-moz-border-radius: 4px;
						border-radius: 4px;
			}
		</style>
	</head>
	<body>
		<br>
		<?php
		if($cmd == "doUpload"){
			//kyk of folder exist
			$dbConnect = new dbConnect();
			if(!file_exists(OCTODOC_DIR)){
				mkdir (OCTODOC_DIR, 0755);
			}
			//	$filename = "FILE_FLD_".substr($key,12);

			$qstr = join("",file("/tmp/{$sid}_qstring"));
			unlink("/tmp/{$sid}_qstring");

			parse_str($qstr);
			
			if(!isset($file)){
		?>
				<div class="alert">
					<strong>Warning!</strong> No file selected.
				</div>
				<script>
					window.parent.closeIframe("progressBarFrame");
					window.parent.showIframe("uploadFrame");
				</script>
		<?php
				displayForm($sid, $field, $val);
				return;
			}
			
			$k = count($file['name']);

			for($i=0 ; $i < $k ; $i++){
				$fileinfo = $path_parts = pathinfo($file['name'][$i]);
				$fileName = $fileinfo['basename'];
				if ( strrchr ($fileName, '\\') ) {
					$fileName = substr (strrchr ($fileName, '\\'),1);
				}
				$extention = ".".$fileinfo['extension'];
				if ($docID == 0){
					//new document
					$SQL = "INSERT INTO documents (creation_date,last_update_date,document_name) values (now(),now(),\"".$fileName."\")";
					$rs = mysqli_query($dbConnect->conn, $SQL) or die ("Error adding document to database");
					$docID = mysqli_insert_id($dbConnect->conn);
					$SQL = "UPDATE documents set document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
					$rs = mysqli_query($dbConnect->conn, $SQL) or die ("Error updating new document to database");
				}else{
					//update Document
					$SQL = "UPDATE documents set last_update_date = now(), document_name=\"".$fileName."\", document_url=\"".$docID.$extention."\" WHERE document_id=".$docID;
					$rs = mysqli_query($dbConnect->conn, $SQL) or die ("Error updating document to database");
				}

				if(! rename($file['tmp_name'][$i], OCTODOC_DIR.$docID.$extention)) {
					die("Error moving file to the upload directory. from='".$file['tmp_name'][$i]."' to='".OCTODOC_DIR.$docID.$extention."'");
				}
			}
		?>
			<script>
				window.parent.setUploaded('<?php echo $field; ?>', <?php echo $docID; ?>);
			</script>
		<?php
		}
		else{
			displayForm($sid, $field, $val);
		}
		?>
	</body>
</html>
