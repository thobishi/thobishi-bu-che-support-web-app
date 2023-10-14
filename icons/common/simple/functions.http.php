<?php

function readPOST($field, $default="") {
  $result=$default;
  if (isset($_POST["$field"])) $result=$_POST["$field"];
  return $result;
}

function readSERVER($field, $default="") {
  $result=$default;
  if (isset($_SERVER["$field"])) $result=$_SERVER["$field"];
  return $result;
}

function readGET($field, $default="") {
  $result=$default;
  if (isset($_GET["$field"])) $result=$_GET["$field"];
  return $result;
}

function readREQUEST($field, $default="") {
  $result=$default;
  if (isset($_REQUEST["$field"])) $result=$_REQUEST["$field"];
  return $result;
}

function readGETPOST($field, $default="") {
	return ( readPOST($field, readGET($field, $default)) );
}

function altValue ($val, $alt) {
	return ($val)?($val):($alt);
}

function readPOSTCheckBoxes($startsWith) {
  $result=array();
  $len=strlen($startsWith);
  foreach ($_POST as $key=>$value) {
    if (strncmp($key, $startsWith, $len)==0) $result[]=substr($key, $len);
  }
  return $result;
}

function writePhpHeader($expire_offset=7200) {
  header("Expires: ".gmdate("D, d M Y H:i:s", time() - $expire_offset)." GMT");    // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified

  // HTTP/1.1
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);

  // HTTP/1.0
  header("Pragma: no-cache");
}

// 20060617 (Diederik): Created to get the actual URL the client typed in.

function getServerURL($relPath=-1) {
	// identify the server if running from command line or cron
	if (!isset($_SERVER["REQUEST_URI"])){
		if (isset($_SERVER['HOSTNAME'])){ //probably running from command line: php .....
			return ($_SERVER['HOSTNAME']);
		} else {  //probably running from cron
			if (preg_match('/HOSTNAME=(.*)/', file_get_contents('/etc/sysconfig/network'), $network)){
				$hostname = explode("=", $network[0]);
				return $hostname[1];
			} else {
				return "Host not found";
			}
		}
	}

	// identify URL if running from a browser
	if (! strncasecmp ($_SERVER["REQUEST_URI"], "https://", 7)) {
		$url = $_SERVER["REQUEST_URI"];
	} else {
		$url = "https://".(isset($_SERVER["HTTP_X_FORWARDED_SERVER"])?$_SERVER["HTTP_X_FORWARDED_SERVER"]:$_SERVER["SERVER_NAME"]).$_SERVER["REQUEST_URI"];
	}

	if ($relPath!=-1) {
		for ($i=substr_count($relPath, "/")+1; $i>0; $i--) {
			$url = ($p=strrpos ($url, "/"))?(substr($url,0,$p)):($url);
		}
		$url .= "/";
	}

	return ($url);
}

function system_htmlspecialchars ($str) {
		$str = htmlspecialchars ($str);
	return ($str);
}

function system_filenotfound ($error="404 Not Found") {
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
	die($error);
}


?>
