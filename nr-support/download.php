<?php
	require_once ('/var/www/html/common/_systems/nr-online.php');

	$file = readGET('file', false);
	$token = readGET('token');

	$dbConnect = new dbConnect();

	if ((!$file) || (!octoToken::check($file, $token))) {
		system_filenotfound ();
	}

	$doc = new octoDoc ($file, $dbConnect->conn);
      //file_put_contents('php://stderr', print_r("URL : ".$doc, TRUE));
var_dump($doc);
	if (!$doc->downloadFile()) {
		system_filenotfound ();
	}
	
?>
