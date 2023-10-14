<?php

if (!defined('DB_SERVER') || !defined('DB_DATABASE') || !defined('DB_USER') || !defined('DB_PASSWD')) die ('ERROR: DB need settings');
				
class octoDB {

	function __construct() {
	}
	
	public static function connect () {
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

	}

}	
?>
