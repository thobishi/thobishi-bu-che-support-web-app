<?php
//	session_set_cookie_params(0,'/', '.octoplus.co.za', false );
//	session_name('HEQConline');
	if (isset($_GET['sid']) && isset($_GET['cmd']) && $_GET['cmd'] === 'outside_visitor') {
		session_id($_GET['sid']);
		session_start();

		unset($_GET['sid']);

		header('Location:?' . http_build_query($_GET));
	}
	session_start();

	//print_r(session_get_cookie_params());

	if (empty($path)) $path = "";
	define('SYS_PATH', $path);

	require_once ('/var/www/common/simple/functions.http.php');
	require_once ('/var/www/common/simple/class.octoEncrypt.php');

	/*
	 - get the config we need
	 - We will test for each option even if we have a winner, to ensure we
		 only have ONE winner.
	*/
	$url = getServerURL(); 

	if (stristr($url, "http://ra") && stristr($url, "che/nr-online"))
		define('CONFIG', 'OCTODEV');

	if (stristr($url, "https://nr-online-1.che.ac.za") )
		define('CONFIG', 'LIVE');
	
		
if (stristr($url, "https://nr-support-1.che.ac.za") )
		define('CONFIG', 'LOCAL');
		


	// If we do not have a config by now, we have a problem
	if (! defined('CONFIG') ) {
		die ('ERROR: Configs could not be initialised at this time');
	}

	// SWITCH SYSTEM OFF - the use the code below
/*
	switch (CONFIG) {
		case 'ADSL':
		case 'TENET':
			systemDown ();
			break;
	}
*/
	// Set group settings
	switch (CONFIG) {
		

		case 'LIVE':
			//define('SYSTEM_APP', '/var/www/nr-online/lib');
			define('SYSTEM_APP', '/var/www/nr-support/lib');
			define('REL_PATH', '/');
			define('SYSTEM_ENGINE', '/var/www/common/workflow-2');

			define('OCTODOCGEN_URL', 'https://nr-online-1.che.ac.za/docgen/');
			define('OCTODOC_DIR', '/var/www/docs-nr/');

			define('DB_SERVER', 'localhost');
			define('DB_DATABASE', 'nr_support');
			define('DB_USER', 'root');
			define('DB_PASSWD', 'H@ppy123');
			define('SEC_USER_INTERNAL_IP', '192.168.1.7');

			break;
			
			case 'LOCAL':
			define('SYSTEM_APP', '/var/www/nr-support/lib');
			define('REL_PATH', '/');
			define('SYSTEM_ENGINE', '/var/www/common/workflow-2');

			define('OCTODOCGEN_URL', 'https://nr-support-1.che.ac.za/docgen/');
			define('OCTODOC_DIR', '/var/www/docs-nr/');

			define('DB_SERVER', 'localhost');
			define('DB_DATABASE', 'nr_support');
			define('DB_USER', 'root');
			define('DB_PASSWD', 'H@ppy123');
			define('SEC_USER_INTERNAL_IP', '192.168.1.7');

			break;

	
		
		default:
			die ('ERROR: Config could not be initialised at this time');
			break;
	}

	// Set individual settings
	switch (CONFIG) {
		case 'LIVE':
                        define ('WRK_DEBUG_MODE', false);
                        define ('SMTP_SERVER', '192.168.1.7');
                        error_reporting(E_ALL);
			break;

		case 'CGDEV':
		case 'RTNDEV':
		case 'EBDEV':
		case 'OCTODEV':
		case 'OCTODEMO':
		case 'DDRDEV':
		case "WLDEV":
		case "SUPPORT":
			
			
			case "LOCAL":
			define('WRK_DEBUG_MODE', true);
			define('SMTP_SERVER', '192.168.1.7');
			error_reporting(E_ALL);
                       define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
			break;
		default:
			die ('ERROR: Config could not be initialised at this time');
			break;
	}

	define('WRK_DOCUMENTS', SYS_PATH . 'documents');

	define('UPLOAD_FILE', REL_PATH . 'pages/uploadFile.php');

	define('WRK_SYSTEM_EMAIL', "phokontsi.m@che.ac.za");


	// Security option
	define('SEC_USER_TABLE', 'users');
	define('SEC_USER_KEY', 'user_id');
	define('SEC_USER_NAME', 'email');
	define('SEC_USER_PWD', 'password');

	// Workflow settings
	define("__HOMEPAGE", 2);
	define("__WELCOMEPAGE", 1);

	define('WRK_TMPDIR', '/tmp/');
	define('WRK_IMAGE_OK', 'check_mark.gif');
	define('WRK_IMAGE_WRONG', 'question_mark.gif');
	define('WRK_LOG_LEVEL', 1000);
	define('WRK_AUDIT_LEVEL', 1000);
	define('WRK_MAYMAIL', true);

	define('WRK_TABLE_SETTINGS', 'settings');

	define('SYS_SECRET', 'sys%nr@che,ac,za');

	// general functions
	require_once ('/var/www/common/simple/functions.html.php');
	require_once ('/var/www/common/simple/functions.escape.php');
	require_once ('/var/www/common/simple/functions.xml.php');
//	require_once ('TreeMenu/TreeMenu.class.php');
	require_once ('/var/www/common/phpmailer/class.phpmailer.php');
	require_once ('/var/www/common/pdf/phpwkhtmltopdf/WkHtmlToPdf.php');
	// include required system libraries
	/*
	require_once (SYSTEM_ENGINE.'/class.dbConnect.php');
	require_once (SYSTEM_ENGINE.'/class.dbFunctions.php');
	require_once (SYSTEM_ENGINE.'/class.security.php');
	require_once (SYSTEM_ENGINE.'/class.createPage.php');
	require_once (SYSTEM_ENGINE.'/class.dbTableInfo.php');
	require_once (SYSTEM_ENGINE.'/class.pageForm.php');
	require_once (SYSTEM_ENGINE.'/class.handleDocs.php');
	require_once (SYSTEM_ENGINE.'/class.flowLogic.php');
	require_once (SYSTEM_ENGINE.'/class.formActions.php');
	require_once (SYSTEM_ENGINE.'/class.formFields.php');
	require_once (SYSTEM_ENGINE.'/class.reports.php');
	require_once (SYSTEM_ENGINE.'/class.workFlow.php');
	*/

	// include application libraries
	require_once (SYSTEM_APP . '/class.NRonline.php');

	$heqcEncrypt = new octoEncrypt('56nr147');

//	PHP 5 function

//	function __autoload($class_name) {
//		require_once (SYSTEM_ENGINE.'/class.'.$class_name.'.php');
//	}

	function __autoload($class_name) {
		$path = explode(':', ini_get("include_path"));
		$file = SYSTEM_ENGINE . '/class.' . $class_name . '.php';
		$got = false;
		foreach ($path as $p) {
			if (file_exists($p . '/' . $file) || file_exists($p . '/' . strtolower($file))) {
				$got = true;
				break;
			}
		}
		if ($got || file_exists($file)) {
			require_once ($file);
		}
	}
//systemDown(); 
	function systemDown() {
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
	?>
<center><big>
<br><br>
System is down for maintenance.
<br><br>
Please read the notes at:<br>
<a href="http://nr-online-1.che.ac.za/">http://nr-online-1.che.ac.za/</a>
</big></center>
	<?php
		die ();
	}

?>
