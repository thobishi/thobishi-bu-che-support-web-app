<?php

if (!defined('DB_SERVER') || !defined('DB_DATABASE') || !defined('DB_USER') || !defined('DB_PASSWD')) die ('ERROR: DB need settings');
				
class octoDB {

	function __construct() {}
	
	public static function connect () {
		$cn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if (!$cn) {
			die("Data Base Connection down");
		}
		
		/*$conectDB = mysqli_select_db(DB_DATABASE);
	      	if (!$conectDB) {
			die("Data Base Connection down");
		}*/
		

	}

}	
?>
