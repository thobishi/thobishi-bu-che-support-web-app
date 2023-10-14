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

	if (stristr($url, "127.0.0.1/heqc-online") || stristr($url, "localhost/heqc-online"))
        define ('CONFIG', 'LOCAL');
        
  
        if (stristr($url, "http://heqc-online-1.che.ac.za"))
        define ('CONFIG', 'CHEPROD');


        if (stristr($url, "https://heqc-online-1.che.ac.za"))
        define ('CONFIG', 'CHEPROD1');

        if (stristr($url, "http://heqc-support-1.che.ac.za"))
        define ('CONFIG', 'CHETEST');
        
        if (stristr($url, "https://heqc-support-1.che.ac.za"))
        define ('CONFIG', 'CHETEST2');

    	if (stristr($url, "https://heqc-support-alpha.che.ac.za"))
        define ('CONFIG', 'CHETEST3');

    	if (stristr($url, "https://heqc-support-bravo.che.ac.za"))
        define ('CONFIG', 'CHETEST4');

	
	if (stristr($url, "https://heqc-support-3.che.ac.za"))
		define ('CONFIG', 'HEQCTRAIN');
	



	if (! defined ('CONFIG') ) {
		die ('ERROR: Configuration could not be initialised at this time');
	}

	switch (CONFIG) {
		
                
              //PRODUCTION SERVER
  
               case 'CHEPROD':
                        define ('SYSTEM_APP', '/var/www/heqc-online/lib');
                        define ('SYSTEM_ENGINE', 'workflow-1.0');
                        define ('REL_PATH', '/');
                        define ('OCTODOC_DIR', '/var/www/heqc-docs/');
                        //Remove this
                        define ('OCTODOCGEN_URL', 'http://heqc-online-1.che.ac.za/docgen/');
                        define ('ROOT_URL', 'http://heqc-online-1.che.ac.za/');

                        define('DB_SERVER',   'localhost');
                        define('DB_DATABASE', 'heqcsupport');
                        define('DB_USER',     'root');
                        define('DB_PASSWD',   'H@ppy123');

                        define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
                        break;
                     case 'CHEPROD1':
                        define ('SYSTEM_APP', '/var/www/heqc-online/lib');
                        define ('SYSTEM_ENGINE', 'workflow-1.0');
                        define ('REL_PATH', '/');
                        define ('OCTODOC_DIR', '/var/www/heqc-docs/');
                        //Remove this
                        define ('OCTODOCGEN_URL', 'https://heqc-online-1.che.ac.za/docgen/');
                        define ('ROOT_URL', 'https://heqc-online-1.che.ac.za/');

                        define('DB_SERVER',   'localhost');
                        define('DB_DATABASE', 'heqcsupport');
                        define('DB_USER',     'root');
                        define('DB_PASSWD',   'H@ppy123');

                        define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
                        break;
                              
                    
                               
                   //SUPPORT SERVER
			case 'CHETEST':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			//Remove this
			define ('OCTODOCGEN_URL', 'http://heqc-support-1.che.ac.za/docgen/');
			define ('ROOT_URL', 'http://heqc-support-1.che.ac.za/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqcsupport');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
			break;
			case 'CHETEST2':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			//Remove this
			define ('OCTODOCGEN_URL', 'https://heqc-support-1.che.ac.za/docgen/');
			define ('ROOT_URL', 'https://heqc-support-1.che.ac.za/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqcsupport');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
			break;

			case 'CHETEST3':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			//Remove this
			define ('OCTODOCGEN_URL', 'http://heqc-support-bravo.che.ac.za/docgen/');
			define ('ROOT_URL', 'http://heqc-support-bravo.che.ac.za/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqcsupport');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '127.0.0.1');
			break;



case 'CHETEST4':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			//Remove this
			define ('OCTODOCGEN_URL', 'http://heqc-support-bravo.che.ac.za/docgen/');
			define ('ROOT_URL', 'http://heqc-support-bravo.che.ac.za/');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqcsupport');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '192.168.1.7');
			break;
			
			//DEVELOPMENT SERVER


		case 'HEQCTRAIN':
			define ('SYSTEM_APP', '/var/www/heqc-online/lib');
			define ('SYSTEM_ENGINE', 'workflow-1.0');
			define ('REL_PATH', '/');
			define ('OCTODOC_DIR', '/var/www/heqc-docs/');
			define ('OCTODOCGEN_URL', 'https://heqc-support-3.che.ac.za/docgen/');
			define ('ROOT_URL', 'https://heqc-support-3.che.ac.za/heqc-online');

			define('DB_SERVER',   'localhost');
			define('DB_DATABASE', 'heqcsupport');
		 	define('DB_USER',     'root');
		 	define('DB_PASSWD',   'H@ppy123');

			define ('SEC_USER_INTERNAL_IP', '192.168.1');
			break;

		
		default:
			die ('ERROR: Config could not be initialised at this time');
			break;
	}


	// Set individual settings
	//echo CONFIG;
	switch (CONFIG) {
		
                      	case 'CHEPROD':
                        define ('WRK_DEBUG_MODE', false);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        define ('SMTP_SERVER', '192.168.1.7'); 
                        error_reporting(E_ALL);
                        //define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        break;
                        
                        
                      	case 'CHEPROD1':
                        define ('WRK_DEBUG_MODE', false);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        define ('SMTP_SERVER', '192.168.1.7'); 
                        error_reporting(E_ALL);
                        //define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        break;


		 				case 'CHETEST':
                        define ('WRK_DEBUG_MODE', true);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        define ('SMTP_SERVER', '192.168.1.7'); 
                        error_reporting(E_ALL);
                        define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        
                        break; 
                        
                         case 'CHETEST2':
                        define ('WRK_DEBUG_MODE', true);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        define ('SMTP_SERVER', '192.168.1.7'); 
                        error_reporting(E_ALL);
                        define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        
                        break; 

                         case 'CHETEST3': case 'CHETEST4':
                        define ('WRK_DEBUG_MODE', true);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        //define ('SMTP_SERVER', '127.0.0.1'); // THIS LINE CAUSES 500 ERROR???
                        error_reporting(E_ALL);
                        define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        
                        break; 



                
		 case 'HEQCTRAIN':
                        define ('WRK_DEBUG_MODE', true);
                        //define ('OCTODOC_DIR', '/var/www/html/che/heqc-online$
                        define ('SMTP_SERVER', '192.168.1.7'); 
                        error_reporting(E_ALL);
                        define ('WRK_ALT_EMAIL', "phokontsi.m@che.ac.za");
                        /*if (!defined('WRK_ALT_EMAIL')) {
                                define ('WRK_ALT_EMAIL', "m.phokontsi@che.ac.za$
                        }*/
                        
                        break;         
                        
       
		default:
			die ('ERROR: Config could not be initialised at this time');
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
	
//systemDown(); 

	function systemDown () {
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
	?>

<center><big>
<br><br>
The heqc-online system has been shutdown in order to implement few changes.
<br><br>
The system should be online again in few hours.
<br><br>
We sincerely apologise for the inconvenience.
</big></center>
	<?php
		die ();
	}

?>
