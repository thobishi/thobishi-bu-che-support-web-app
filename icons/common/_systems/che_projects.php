<?php
	if (empty($path)) $path = "";
	define ('SYS_PATH', $path);

	session_start ();

	// general functions
	require_once ('simple/functions.http.php');
	require_once ('simple/functions.html.php');
	require_once ('simple/class.octoEncrypt.php');
	$heqcEncrypt = new octoEncrypt ('34heqc387');

	/*
	   - get the config we need
	   - We will test for each option even if we have a winner, to ensure we
		   only have ONE winner.
	*/

	$url = getServerURL ();

	if (stristr($url, "http://projects"))
		define ('CONFIG', 'CHE');
	if (stristr($url, "http://ra") AND stristr($url, "che/projects/www"))
		define ('CONFIG', 'OCTOLIVE');
	if (stristr($url, "http://ra") AND stristr($url, "che/projects/dev"))
		define ('CONFIG', 'OCTODEV');


	// If we do not have a config by now, we have a problem
	if (! defined ('CONFIG') ) {
		die ('ERROR: Config could not be initialised at this time');
	}

	// SWITCH SYSTEM OFF  - the use the code below
	/*
	switch (CONFIG) {
		case 'ADSL':
		case 'TENET':
			systemDown ();
			break;
	}

	*/

	define ('SYSTEM_ENGINE', 'workflow-1.0');

	// Set group settings
	switch (CONFIG) {
		case 'CHE':
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_project_register');
 			define('DB_USER',     'projreg');
 			define('DB_PASSWD',   'fibonacci');

			define ('WRK_DEBUG_MODE', false);
			define ('SYSTEM_APP', '/var/www/projects/lib');
			define ('REL_PATH', '');
			define ('OCTODOC_DIR', '/var/www/docs-prj/');
			define ('OCTODOCGEN_URL', 'http://projects.che.ac.za/docgen/');

			define ('SMTP_SERVER', '127.0.0.1;192.168.1.2');
			break;
		case 'OCTOLIVE':
			error_reporting(E_ALL);

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_project_register');
 			define('DB_USER',     'projreg');
 			define('DB_PASSWD',   'fibonacci');

			define ('WRK_DEBUG_MODE', false);
			define ('SYSTEM_APP', '/var/www/html/che/projects/www/lib');
			define ('REL_PATH', '/che/projects/www/');
			define ('OCTODOC_DIR', '/var/www/html/che/docs-prj/');

			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/che/projects/www/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "systems@octoplus.co.za");
			break;
		case 'OCTODEV':
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_project_register_dev');
 			define('DB_USER',     'trainprojreg');
 			define('DB_PASSWD',   'trainfibonacci');

			error_reporting(E_ALL);

			define ('WRK_DEBUG_MODE', true);
			define ('SYSTEM_APP', '/var/www/html/che/projects/dev/lib');
			define ('REL_PATH', '/che/projects/dev/');
			define ('OCTODOC_DIR', '/var/www/html/che/docs-prj/');
			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/che/projects/dev/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
			break;

		default:
			die ('ERROR: Config could not be initialised at this time');
			break;
	}

	define ('WRK_DOCUMENTS', SYS_PATH.'documents');

	define ('UPLOAD_FILE', REL_PATH.'pages/uploadFile.php');

	define ('WRK_SYSTEM_EMAIL', "systems@octoplus.co.za");

	// Security option
	define ('SEC_USER_TABLE', 'users');
	define ('SEC_USER_KEY', 'user_id');
	define ('SEC_USER_NAME', 'email');
	define ('SEC_USER_PWD', 'passwd');

	// Workflow settings
	define ("__HOMEPAGE", 2);
	define ("__WELCOMEPAGE", 1);

	define ('WRK_TMPDIR', '/tmp/');
	define ('WRK_IMAGE_OK', 'check_mark.gif');
	define ('WRK_IMAGE_WRONG', 'question_mark.gif');
	define ('WRK_LOG_LEVEL', 1000);
	define ('WRK_AUDIT_LEVEL', 1000);
	define ('WRK_MAYMAIL', true);

	define ('WRK_TABLE_SETTINGS', 'settings');

	define ('SYS_SECRET', 'sys%heqc@che,ac,za');


	// general functions
	require_once ('simple/functions.escape.php');
	require_once ('simple/functions.xml.php');
	require_once ('TreeMenu/TreeMenu.class.php');
	require_once ('phpmailer/class.phpmailer.php');
	require_once ('document_generator/cl_xml2driver.php');


	// include application libraries
	require_once (SYSTEM_APP.'/class.CHEprojects.php');
	require_once (SYSTEM_APP.'/class.reportGenerator.php');


//	PHP 5 function

	function __autoload($class_name) {
		$path = explode(':', ini_get("include_path"));
		$file = SYSTEM_ENGINE.'/class.'.$class_name.'.php';
		$got=false;
		foreach($path as $p) {
			if (file_exists($p.'/'.$file)) {
				$got=true;
				break;
			}
		}
		if ($got) {
			require_once ($file);
		}

	}

	function systemDown () {

		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
	?>
<center><big>
<br><br>
System is down for maintenenace.
<br><br>
Please read the notes at:<br>
<a href="http://che_projects.che.ac.za/">http://che_projects.che.ac.za/</a>
</big></center>
	<?php
		die ();
	}

?>
