<?php

if (!defined ('SYS_SECRET')) define ('SYS_SECRET', 'dderoos%menes@octoplus');
	
class octoToken {

        function __construct(){}
        
	public static function create($string) {
		return self::makeToken ($string);
	}

	public static function check($string, $token) {
		return (self::makeToken ($string) == $token)?(true):(false);
	}

	private static function makeToken ($string) {
		return (MD5(SYS_SECRET."-".$string."-".session_id()));
	}
}

?>
