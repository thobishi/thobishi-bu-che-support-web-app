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

	if (stristr($url, "http://contracts.che.ac.za"))
		define ('CONFIG', 'CONTRACTLIVE');
	if (stristr($url, "http://ra") AND (stristr($url, "che/contract/dev") || stristr($url, "usr/eb/che-contracts")))
		define ('CONFIG', 'CONTRACTDEV');
	if (stristr($url, "http://ra") AND stristr($url, "usr/rtn/che-contracts"))
		define ('CONFIG', 'CONTRACTRTN');
	if ((stristr($url, "http://ra") || stristr($url, "http://open.octoplus.co.za")) AND stristr($url, "che/contract/www"))
		define ('CONFIG', 'CONTRACTWWW');
	if ( stristr($url, "http://demo.octoplus.co.za") )
		define ('CONFIG', 'OCTODEMO');
	if (stristr($url, "http://ra") AND stristr($url, "test/train/contract"))
		define ('CONFIG', 'TRAIN');

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
		case 'CONTRACTLIVE':
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_contract_register');
 			define('DB_USER',     'contract_owner');
 			define('DB_PASSWD',   'holograph');

			define ('WRK_DEBUG_MODE', false);
			define ('SYSTEM_APP', '/var/www/contracts/lib');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/docs-contract/');

			define ('OCTODOCGEN_URL', 'http://contracts.che.ac.za/docgen/');
			define ('SMTP_SERVER', '192.168.1.2');
			break;
		case 'CONTRACTDEV':
			error_reporting(E_ALL);

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_contract_register');
 			define('DB_USER',     'contract_owner');
 			define('DB_PASSWD',   'holograph');
//			define('DB_DATABASE', 'CHE_contract_register_live');
// 			define('DB_USER',     'contract_owner');
// 			define('DB_PASSWD',   'holograph');

			define ('WRK_DEBUG_MODE', true);
			define ('SYSTEM_APP', '/var/www/html/che/contract/dev/lib');
			define ('REL_PATH', '/che/contract/dev/');
			define ('OCTODOC_DIR', '/var/www/html/che/contract/docs-contract/');

			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/che/contract/dev/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "evrard@octoplus.co.za");
			break;
		case 'CONTRACTRTN':
			error_reporting(E_ALL);

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_contract_register');
 			define('DB_USER',     'contract_owner');
 			define('DB_PASSWD',   'holograph');

			define ('WRK_DEBUG_MODE', true);
			define ('SYSTEM_APP', '/var/www/html/usr/rtn/che-contracts/lib');
			define ('REL_PATH', '/usr/rtn/che-contracts/');
			define ('OCTODOC_DIR', '/var/www/html/che/contract/docs-contract/');

			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/usr/rtn/che-contracts/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
			break;
		case 'CONTRACTWWW':
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_contract_register_live');
 			define('DB_USER',     'contract_owner');
 			define('DB_PASSWD',   'holograph');

			define ('WRK_DEBUG_MODE', true);
			define ('SYSTEM_APP', '/var/www/html/che/contract/www/lib');
			define ('REL_PATH', '/che/contract/www/');
			define ('OCTODOC_DIR', '/var/www/html/che/contract/docs-contract/');

			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/che/contract/www/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');

			define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
			break;
		case 'TRAIN':
			error_reporting(E_ALL);

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'train_contract_register');
 			define('DB_USER',     'trcontractowner');
 			define('DB_PASSWD',   'trholograph');

			define ('WRK_DEBUG_MODE', true);
			define ('SYSTEM_APP', '/var/www/html/test/train/contract/lib');
			define ('REL_PATH', '/test/train/contract/');
			define ('OCTODOC_DIR', '/var/www/html/test/train/contract/docs-contract/');

			define ('SEC_USER_INTERNAL_IP', '192.168.10');

			define ('OCTODOCGEN_URL', 'http://ra/test/train/contract/docgen/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
			break;
		case 'OCTODEMO':
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_contract_register');
 			define('DB_USER',     'contract_owner');
 			define('DB_PASSWD',   'holograph');

			define ('SYSTEM_APP', '/var/www/demo/contract/lib');
			define ('REL_PATH', '/contract/');
			define ('OCTODOCGEN_URL', 'http://demo.octoplus.co.za/contract/docgen/');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			define ('WRK_DEBUG_MODE', true);
			define ('OCTODOC_DIR', '/var/www/html/che/contract/docs-contract/');
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
	define ('SEC_USER_PWD', 'password');

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

	define ('SYS_SECRET', 'sys%contract@che,ac,za');


	// general functions
	require_once ('simple/functions.escape.php');
	require_once ('simple/functions.xml.php');
	require_once ('TreeMenu/TreeMenu.class.php');
	require_once ('phpmailer/class.phpmailer.php');
	require_once ('document_generator/cl_xml2driver.php');


	// include application libraries
	require_once (SYSTEM_APP.'/class.contractRegister.php');


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
<!-- Robin - setup when goes live. -->
<a href="http://???????????/">http://???????????????/</a>
</big></center>
	<?php
		die ();
	}

?>
