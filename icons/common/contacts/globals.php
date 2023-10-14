<?php

error_reporting(E_ALL);

require_once ('simple/functions.http.php');

$url = getServerURL ();
if (stristr($url, "http://ra/contacts") or stristr($url, "ra.octoplus.co.za"))
	define ('CONFIG', 'DIEDERIK');
if (stristr($url, "http://localhost") or stristr($url, "paulh"))
	define ('CONFIG', 'PAUL');
if (stristr($url, "http://contacts.octoplus.co.za") or stristr($url, "burst.octoplus.co.za"))
	define ('CONFIG', 'BURST');

if (CONFIG=="BURST") {
  define("ABS_HTDOCS_PATH", "/var/www/sites/contacts.octoplus.co.za/"); // Needed for bgsender.php to read the inline email images and attachments
  define("ADMIN_EMAIL", "diederik@octoplus.co.za");
  define("BASEURL", "http://contacts.octoplus.co.za/");
  define("MAIL_SEND_MODE", "SMTP"); // SMTP/Mail
  define("MYMAIL_MODE", "SEND");    // SEND/ECHOALL/ECHO
  define("CONTEXT_ROOT", "/"); // A non empty string must end on a /
} else if (CONFIG=="DIEDERIK") {
  define("ABS_HTDOCS_PATH", "/var/www/html/contacts/"); // Needed for bgsender.php to read the inline email images and attachments
  define("ADMIN_EMAIL", "diederik@octoplus.co.za");
  define("BASEURL", "http://ra/contacts/");
  define("MAIL_SEND_MODE", "SMTP"); // SMTP/Mail
  define("MYMAIL_MODE", "SEND");    // SEND/ECHOALL/ECHO
  define("CONTEXT_ROOT", "/contacts/"); // A non empty string must end on a /
} else if (CONFIG=="PAUL") {
  define("ABS_HTDOCS_PATH", "c:/Program Files/Apache Software Foundation/Apache2.2/htdocs/"); // Needed for bgsender.php to read the inline email images and attachments
  define("ADMIN_EMAIL", "hauptpa@telkom.co.za");
  define("BASEURL", "http://localhost/");
  define("MAIL_SEND_MODE", "Mail"); // SMTP/Mail
  define("MYMAIL_MODE", "ECHOALL");    // SEND/ECHOALL/ECHO
  define("CONTEXT_ROOT", ""); // A non empty string must end on a /
}

// Don't use OPENWYSIWYG in production - sending images does not work with it
define("EDITOR", "CUTEEDITOR");        // TINYMCE/OPENWYSIGWYG
//define("EDITOR", "TINYMCE");        // TINYMCE/OPENWYSIGWYG
//define("EDITOR", "OPENWYSIWYG");    // TINYMCE/OPENWYSIGWYG

define("DBSERVER", "localhost");
define("DBUSER", "octo_contacts");
define("DBPWD", "work");
define("DBNAME", "octo_contacts");

// define("SMTPSERVER", get_cfg_var("SMTP"));
define("SMTPSERVER", "192.168.3.6");

define("LOCK_FILE_NAME", ".bgsender.lock");
define("LINKDOWN", "Contact your system Administrator");
define("EXPIRE_WARN_DAYS", "30");
define("MAX_PASSWORD_LENGTH", 5);
define("MAX_LICENCE_LENGTH", 20);
define("MAX_RECIPIENTS", 250);
define("MOST_RECENT_LIST_LENGTH", 10);
define("IMAGE_FILE_UPLOAD", "images/up.png");
define("EMAIL_QUEUE_FOLDER", "email_queue/");
define("EMAIL_QUEUE_ABS_FOLDER", ABS_HTDOCS_PATH.EMAIL_QUEUE_FOLDER);
define("EMAIL_IMAGES_FOLDER", "email_images/");
define("EMAIL_IMAGES_ABS_PATH", ABS_HTDOCS_PATH.EMAIL_IMAGES_FOLDER);
define("EMAIL_FROM_ADDR", "contacts@octoplus.co.za");
define("EMAIL_FROM_NAME", "Octo Contacts");

define("MD5_PASSWORD", "h2f#F34");

define("EMAIL_REGEXP", "/^[A-z0-9'_\\-\\.]+[@]{1}[A-z0-9_\\-]+([\\.][A-z0-9_\\-]+){1,4}$/");
define("TRIM_CHARS", " \t\n\r\0\x0b\x80..\xff");

// we need some additional classes that we can not autoload
require_once("phpmailer/class.phpmailer.php");
require_once ("htmltags/class.htmlTags.php");
require_once('html2text/class.html2text.inc');


function mymail($to, $subject, $msg, $headers, $params="") {
  if (MYMAIL_MODE=='SEND') {
    return mail($to, $subject, $msg, $headers, $params);
  } else if (MYMAIL_MODE=='ECHO') {
    echo "\nFaked sending mail to: $to - Subject: $subject\n";
    return true;
  } else if (MYMAIL_MODE=='ECHOALL') {
    echo "\n<xmp>Faked To: $to\nSubject: $subject\nHeaders: $headers\nMessage: $msg\nParams: $params</xmp>\n";
    return true;
  } else {
    echo "\nError : MYMAIL_MODE=".MYMAIL_MODE;
    return false;
  }
}

function checkConfiguration() {
  if (EDITOR!='CUTEEDITOR' && EDITOR!='TINYMCE' && EDITOR!='OPENWYSIWYG') redirect('error.php', 'EDITOR not configured');
  if (MAIL_SEND_MODE!='SMTP' && MAIL_SEND_MODE!='Mail') redirect('error.php', 'MAIL_SEND_MODE not configured');
}

function __autoload($class_name) {
  require_once 'contacts/'.$class_name.'.php';
}

function writeHeader() {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
  header("Cache-Control: post-check=0, pre-check=0", false);
}

function redirect($location, $message='Redirecting') {
  echo "<script>window.top.location='http://".$_SERVER['HTTP_HOST']."/".CONTEXT_ROOT.$location."?msg=".$message."'</script>";
  die($message);
}

function getUserImageRelPath ($UID) {
	return (CONTEXT_ROOT.EMAIL_IMAGES_FOLDER.$UID."/");
}

function getUserImageDiskPath ($UID) {
	$diskPath = EMAIL_IMAGES_ABS_PATH.$UID;
	if (! file_exists ($diskPath) ) mkdir($diskPath, 0777);
	return ($diskPath."/");
}

?>
