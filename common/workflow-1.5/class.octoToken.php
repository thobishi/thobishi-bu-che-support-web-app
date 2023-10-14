<?php

if (!defined ('SYS_SECRET')) define ('SYS_SECRET', 'dderoos%menes@octoplus');
	
class octoToken {
	public static function create($string, $type="DEF") {
		return self::makeToken ($string, $type);
	}

	public static function check($string, $token, $type="DEF") {
		return (self::makeToken ($string, $type) == $token)?(true):(false);
	}

	private static function makeToken ($string, $type) {
		return (MD5(SYS_SECRET."-".$string."-".$type."-".session_id()));
	}
}

?>
