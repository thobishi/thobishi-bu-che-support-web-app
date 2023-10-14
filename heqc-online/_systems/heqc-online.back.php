<?php
//	session_set_cookie_params(0,'/', '.octoplus.co.za', false );
//	session_name('HEQConline');
	if(isset($_GET['sid']) && isset($_GET['cmd']) && $_GET['cmd'] === 'outside_visitor') {
		session_id($_GET['sid']);
		session_start ();

		unset($_GET['sid']);

		header('Location:?' . http_build_query($_GET));
	}
	session_start ();

	//print_r(session_get_cookie_params());

	if (empty($path)) $path = "";
	define ('SYS_PATH', $path);
	//print_r ($path);
	
	//file_put_contents('php://stderr', print_r("SYS PATH :".SYS_PATH, TRUE));

	require_once ('/var/www/common/simple/functions.http.php');
	require_once ('/var/www/common/simple/class.octoEncrypt.php');

	/*
	   - get the config we need
	   - We will test for each option even if we have a winner, to ensure we
		   only have ONE winner.
	*/

	$url = getServerURL ();

	//file_put_contents('php://stderr', print_r("URL : ".$url, TRUE));
	
	if (stristr($url, "127.0.0.1/heqc-online") || stristr($url, "localhost/heqc-online"))
        define ('CONFIG', 'LOCAL');
        
        if (stristr($url, "http://heqc-online-1.che.ac.za"))
        define ('CONFIG', 'CHETEST');
        
	if (stristr($url, "ra.octoplus.co.za"))
		define ('CONFIG', 'RTNDEV');
	if (stristr($url, "HEQC.che.ac.za"))
		define ('CONFIG', 'ADSL');
	//if (stristr($url, "http://heqc-online"))
	//	define ('CONFIG', 'TENET');
	if (stristr($url, "http://heqconline.che.ac.za/"))
		define ('CONFIG', 'TENET');
	if (stristr($url, "http://heqconline-1.che.ac.za/"))
		define ('CONFIG', 'ADSL');
	if (stristr($url, "http://heqconline-2.che.ac.za/"))
		define ('CONFIG', 'TENET');
	if (stristr($url, "http://heqc.octoplus.co.za/"))
		define ('CONFIG', 'HEQCTEST');
	if ( stristr($url, "http://ra") AND stristr($url, "che/heqc-online/www"))
		define ('CONFIG', 'OCTOLIVE');
	if ( stristr($url, "http://heqcdemo") )
		define ('CONFIG', 'OCTODEMO');
	if (stristr($url, "http://ra") AND stristr($url, "che/heqc-online/dev"))
		define ('CONFIG', 'OCTODEV');
	if ( stristr($url, "http://demo.octoplus.co.za") )
		define ('CONFIG', 'OCTODEV');
	if ( (stristr($url, "http://ra") OR stristr($url, "http://trabajo") ) AND stristr($url, "che/heqc-online/new"))
		define ('CONFIG', 'OCTONEW');
	if (stristr($url, "http://ra") AND stristr($url, "che/heqc-online/reaccred"))
		define ('CONFIG', 'OCTOREACCRED');
	if (stristr($url, "http://heqc-support"))
		define ('CONFIG', 'HEQCTRAIN');
	if ( stristr($url, "http://ra") AND stristr($url, "webroot/heqc-online"))
		define ('CONFIG', 'OCTOCAKE');

	if ((stristr($url, "http://ra") || stristr($url, "http://open")) AND stristr($url, "usr/rtn/heqc-online"))
		define ('CONFIG', 'RTNDEV');

	if ((stristr($url, "http://ra") || stristr($url, "http://open")) AND stristr($url, "usr/ddr/"))
		define ('CONFIG', 'DDRDEV');

	if ((stristr($url, "http://ra")) AND stristr($url, "test/damelin/ml/heqc-online"))
		define ('CONFIG', 'MLDEV');
	if (stristr($url, "http://ra") AND stristr($url, "usr/ddr/heqc-online"))
		define ('CONFIG', 'DDRDEV');
	if (stristr($url, "http://ra") AND stristr($url, "usr/cg/heqc-online"))
		define ('CONFIG', 'CGDEV');		
	if (stristr($url, "http://ra") AND stristr($url, "usr/wl/heqc-online"))
		define ('CONFIG', 'WLDEV');	
	if (stristr($url, "http://ra") && stristr($url, "usr/eb/heqc-online"))
		define ('CONFIG', 'EBDEV');	
  
	if (! defined ('CONFIG') ) {
		die ('ERROR: Configuration could not be initialised at this time');
	}

	switch (CONFIG) {
	//	case 'ADSL':
	//	case 'TENET': 
                
	
		case 'CHETEST':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			//Remove this
			define ('OCTODOCGEN_URL', 'http://heqc-online-1.che.ac.za/docgen/');
			define ('ROOT_URL', 'http://heqc-online-1.che.ac.za/');
			//define ('ROOT_URL2', 'http://heqc-online-1.che.ac.za/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'HEQCONLINEDB');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
			break;
			
/*
		case 'HEQCTEST':
			define ('SYSTEM_APP', '/var/www/html/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/html/heqc-docs');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'HEqcOnline');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '127.0.0.1');
			define ('OCTODOCGEN_URL', 'http://localhost/heqc-online/docgen/');
			define ('ROOT_URL', 'http://localhost/heqc-online/');
			break;
		case 'OCTODEMO':
			define ('SYSTEM_APP', '/var/www/html/che/heqc-online/demo/lib');
			define ('REL_PATH', '/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('OCTODOCGEN_URL', 'http://heqcdemo.octoplus.co.za/docgen/');
                        //define ('OCTODOCGEN_URL', 'http://heqcdemo.octoplus.co.za/heqc-online/docgen/');
			define ('ROOT_URL', 'http://heqcdemo.octoplus.co.za/heqc-online/');
			define('DB_SERVER',   'localhost');
//			define('DB_DATABASE', 'CHE_heqconline_demo');
//		 	define('DB_USER',     'heqcdemo');
//		 	define('DB_PASSWD',   'demo');
			define('DB_DATABASE', 'CHE_heqconline');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'workflow');
//			define('DB_DATABASE', 'CHE_heqconline_rtn');
//		 	define('DB_USER',     'heqc_rtn');
//		 	define('DB_PASSWD',   'rtn_workflow');
			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;
		case 'OCTOLIVE':
			define ('SYSTEM_APP', '/var/www/html/che/heqc-online/www/lib');
			define ('REL_PATH', '/che/heqc-online/www/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			//define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
			define ('OCTODOCGEN_URL', 'http://ra/che/heqc-online/www/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'workflow');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;
		case 'OCTODEV':
			define ('SYSTEM_APP', '/var/www/html/che/heqc-online/dev/lib');
			define ('REL_PATH', '/che/heqc-online/dev/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
//			define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
//			define ('OCTODOC_DIR', '/var/www/html/che/heqc-online/heqc-docs/');

			define ('OCTODOCGEN_URL', 'http://ra/che/heqc-online/dev/docgen/');
			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline_dev');
		 	define('DB_USER',     'cheflow');
		 	define('DB_PASSWD',   'workflow');
			define ('SEC_USER_INTERNAL_IP', '192.168.3');
			break;
//		case 'OCTOREACCRED':
//			define ('SYSTEM_APP', '/var/www/html/che/heqc-online/reaccred/lib');
//			define ('REL_PATH', '/che/heqc-online/reaccred/');
//			define ('SYSTEM_ENGINE', 'workflow-1.0');
//
//			define ('OCTODOCGEN_URL', 'http://ra/che/heqc-online/reaccred/docgen/');
//			define('DB_SERVER',   'localhost');
//			define('DB_DATABASE', 'CHE_heqconline_reaccred');
//		 	define('DB_USER',     'chereaccred');
//		 	define('DB_PASSWD',   'reaccred');

//			define ('SEC_USER_INTERNAL_IP', '192.168.3');
//			break;
//		case 'OCTONEW':
//			define ('SYSTEM_APP', '/var/www/html/che/heqc-online/new/lib');
//			define ('REL_PATH', '/che/heqc-online/new/');
//			define ('SYSTEM_ENGINE', 'workflow-1.5');
//
//			define('DB_SERVER',    'localhost');
//			define('DB_DATABASE',  'CHE_heqconline_new');
//		 	define('DB_USER',      'cheflow');
//		 	define('DB_PASSWD',    'workflow');
//
//			define ('SEC_USER_INTERNAL_IP', '192.168.3');
//			break;
		case 'HEQCTRAIN':
			define ('SYSTEM_APP', '/var/www/heqc-support/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			define ('OCTODOCGEN_URL', 'http://heqc-support.che.ac.za/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqc_support');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'heqc4sup');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;
//		case 'OCTOCAKE':
//			define ('SYSTEM_APP', $path . 'lib');
//			define ('REL_PATH', '/che/heqc-online/www/');
//			define ('SYSTEM_ENGINE', 'workflow-1.0');
//			//define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
//			define ('OCTODOCGEN_URL', 'http://ra/che/heqc-online/www/docgen/');
//
//			define('DB_SERVER',   'localhost');
//			define('DB_DATABASE', 'CHE_heqconline_cake');
//		 	define('DB_USER',     'heqc_cake');
//		 	define('DB_PASSWD',   'workflow');
//
//			define ('SEC_USER_INTERNAL_IP', '192.168.1');
//			break;	
		case 'WLDEV':
			define ('SYSTEM_APP', '/var/www/html/usr/wl/heqc-online/lib');
			define ('REL_PATH', '/usr/wl/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			//define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
			define ('OCTODOCGEN_URL', 'http://ra/usr/wl/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline_dev');
		 	define('DB_USER',     'cheflow');
		 	define('DB_PASSWD',   'workflow');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;
		case 'MLDEV':
			define ('SYSTEM_APP', '/var/www/html/test/damelin/ml/heqc-online/lib');
			define ('REL_PATH', '/test/damelin/ml/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.1-div');
			define ('OCTODOCGEN_URL', 'http://ra/test/damelin/ml/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'workflow');
			
			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;	

		case 'RTNDEV':
			define ('SYSTEM_APP', '/var/www/html/usr/rtn/heqc-online/lib');
			define ('REL_PATH', '/usr/rtn/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('OCTODOCGEN_URL', 'http://ra/usr/rtn/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			// define('DB_DATABASE', 'CHE_heqconline_dev');
			// define('DB_USER',     'cheflow');
		 	// define('DB_PASSWD',   'workflow');
			 define('DB_DATABASE', 'CHE_heqconline');
		 	 define('DB_USER',     'heqc');
		 	 define('DB_PASSWD',   'workflow');
			
			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;	

		case 'DDRDEV':
			define ('SYSTEM_APP', '/var/www/html/usr/ddr/heqc-online/lib');
			define ('REL_PATH', '/usr/ddr/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('OCTODOCGEN_URL', 'http://ra/usr/ddr/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline_dev');
		 	define('DB_USER',     'cheflow');
		 	define('DB_PASSWD',   'workflow');
			
			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;	
		case 'CGDEV':
			define ('SYSTEM_APP', '/var/www/html/usr/cg/heqc-online/lib');
			define ('REL_PATH', '/usr/cg/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			//define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
			define ('OCTODOCGEN_URL', 'http://ra/usr/cg/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'CHE_heqconline_dev');
		 	define('DB_USER',     'cheflow');
		 	define('DB_PASSWD',   'workflow');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;	
		case 'EBDEV':
			define ('SYSTEM_APP', '/var/www/html/usr/eb/heqc-online/lib');
			define ('REL_PATH', '/usr/eb/heqc-online/');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			//define ('OCTODOCGEN_URL', '/var/www/html/che/heqc-online/dev/docgen/');
			define ('OCTODOCGEN_URL', 'http://ra/usr/eb/heqc-online/docgen/');

			define('DB_SERVER',   'localhost');
			// define('DB_DATABASE', 'CHE_heqconline_dev');
		 	// define('DB_USER',     'cheflow');
		 	// define('DB_PASSWD',   'workflow');
			
			define('DB_DATABASE', 'CHE_heqconline');
		 	define('DB_USER',     'heqc');
		 	define('DB_PASSWD',   'workflow');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;				
 */
		default:
			die ('ERRORswe: Config could not be initialised at this time');
			break;
	}

	// Set individual settings
	switch (CONFIG) {
/*
		case 'ADSL':
			define ('WRK_DEBUG_MODE', false);
			define ('SMTP_SERVER', '127.0.0.1:80/');
			define ('OCTODOCGEN_URL', 'http://heqconline-1.che.ac.za/docgen/');
			break;
		case 'TENET':
			define ('WRK_DEBUG_MODE', false);
			define ('SMTP_SERVER', '127.0.0.1:80/');
			define ('OCTODOCGEN_URL', 'http://heqconline-2.che.ac.za/docgen/');
			break;
		case 'OCTOLIVE':
			define ('WRK_DEBUG_MODE', true);
			define ('OCTODOC_DIR', '/var/www/html/che/heqc-online/heqc-docs/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
			break;
		case 'WLDEV':
			define ('WRK_ALT_EMAIL', "walther@octoplus.co.za");
		case 'WLFARDEV':
		case 'CGDEV':
		case 'EBDEV':
			define ('WRK_ALT_EMAIL', "evrard@octoplus.co.za");
		case 'RTNDEV':
				define ('WRK_ALT_EMAIL', "robin@octoplus.co.za");
				//define ('WRK_ALT_EMAIL', "shanz.naude@gmail.com");
		case 'MLDEV':
		case 'LOCAL':
		case 'DDRDEV':
		case 'OCTOCAKE':
		case 'OCTODEV':
		case 'OCTOREACCRED':
		case 'OCTONEW':
 */
		case 'CHETEST':
			define ('WRK_DEBUG_MODE', false);
			//define ('OCTODOC_DIR', '/var/www/html/che/heqc-online/heqc-docs/');
			define ('SMTP_SERVER', '192.168.1.7:25/'); 

  			error_reporting(E_ALL);
		
		//	if (!defined('WRK_ALT_EMAIL')){
                          //       define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
				//define ('WRK_ALT_EMAIL', "naude.r@che.ac.za");
			//}
			break;
/*
		case 'OCTODEMO':
			define ('WRK_DEBUG_MODE', true);
			define ('OCTODOC_DIR', '/var/www/html/che/heqc-online/heqc-docs/');
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
//			define ('WRK_ALT_EMAIL', "kanise.f@che.ac.za");
//			define ('WRK_ALT_EMAIL', "bezuidenhout.t@che.ac.za");
//			define ('WRK_ALT_EMAIL', "heqc@octoplus.ac.za");
			define ('WRK_ALT_EMAIL', "naude.r@che.ac.za");
			break;
		case 'HEQCTEST':
			define ('WRK_DEBUG_MODE', true);
			define ('SMTP_SERVER', '192.168.3.2');
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "naude.r@che.ac.za");
			break;
		case 'HEQCTRAIN':
			define ('WRK_DEBUG_MODE', true);
			define ('SMTP_SERVER', '127.0.0.1:80/'); //IGNUS
			error_reporting(E_ALL);
			define ('WRK_ALT_EMAIL', "naude.r@che.ac.za");
			break;
 */
		default:
			die ('ERRORs: Config could not be initialised at this time');
			break;
	}

	define ('WRK_DOCUMENTS', ROOT_URL.'documents');

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

	define ('SYS_SECRET', 'sys%heqc@che,ac,za');

	// general functions
	require_once ('/var/www/common/simple/functions.html.php');
	require_once ('/var/www/common/simple/functions.escape.php');
	require_once ('/var/www/common/simple/functions.xml.php');
//	require_once ('TreeMenu/TreeMenu.class.php');
	require_once ('/var/www/common/phpmailer/class.phpmailer.php');

	// include required system libraries
	
	
	//$commonDir = $_SERVER['DOCUMENT_ROOT'].'common/';
	define ('COMMON_DIR', $_SERVER['DOCUMENT_ROOT'].'var/www/common');
	define ('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
	require_once ('/var/www/common/workflow-1.0/class.dbConnect.php');
	require_once ('/var/www/common/workflow-1.0/class.dbFunctions.php');
	require_once ('/var/www/common/workflow-1.0/class.security.php');
	require_once ('/var/www/common/workflow-1.0/class.createPage.php');
	require_once ('/var/www/common/workflow-1.0/class.dbTableInfo.php');
	require_once ('/var/www/common/workflow-1.0/class.pageForm.php');
	require_once ('/var/www/common/workflow-1.0/class.handleDocs.php');
	require_once ('/var/www/common/workflow-1.0/class.flowLogic.php');
	require_once ('/var/www/common/workflow-1.0/class.formActions.php');
	require_once ('/var/www/common/workflow-1.0/class.formFields.php');
	require_once ('/var/www/common/workflow-1.0/class.reports.php');
	require_once ('/var/www/common/workflow-1.0/class.workFlow.php');
	require_once ('/var/www/common/workflow-1.0/class.octoDB.php');
	require_once ('/var/www/common/workflow-1.0/class.octoDoc.php');
	require_once ('/var/www/common/workflow-1.0/class.octoDocGen.php');
	require_once ('/var/www/common/workflow-1.0/class.octoToken.php');
        
        
	// include application libraries
	require_once (SYSTEM_APP.'/class.HEQConline.php');
	require_once (SYSTEM_APP.'/class.evalSearch.php');

	$heqcEncrypt = new octoEncrypt ('34heqc387');

        /*spl_autoload_register(function($class) {
            include_once "/var/www/html/common/".SYSTEM_ENGINE.'/class.'.$class.'.php';
        });*/

        /*ini_get("include_path")*/
	/*function autoload($class) {
		$path = explode(':', ".:/var/www/html/common");
                
		$file = SYSTEM_ENGINE.'/class.'.$class.'.php';
		$got=false;
		foreach($path as $p) {
                        if (file_exists($p.'/'.$file)) {
                                $got=true;
				break;
			}
		}
		if ($got || file_exists($file)) {
			require_once ($file);
		}

	}*/  
	


	function systemDown () {
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
	?>
<center><big>
<br><br>
System is down for maintenance.
<br><br>
Please read the notes at:<br>
<a href="http://heqc-online.che.ac.za/">http://heqc-online.che.ac.za/</a>
</big></center>
	<?php
		die ();
	}

/*if (WRK_DEBUG_MODE==TRUE) { 

echo "true";

}

else { 
  echo "false";
  }*/
?>
