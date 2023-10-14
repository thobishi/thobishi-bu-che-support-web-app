<?php

if (!defined ('SYS_SECRET')) define ('SYS_SECRET', 'dderoos%menes@octoplus');
	
class octoToken {
	public static function create($string, $session = true) {
		return self::makeToken ($string, $session);
	}

	public static function check($string, $token, $session = true) {
		return (self::makeToken ($string, $session) == $token)?(true):(false);
	}

	private static function makeToken ($string, $session) {
		if ($session)
			return (MD5(SYS_SECRET."-".$string."-".session_id()));
		else
			return (MD5(SYS_SECRET."-".$string));		
	}
}

?>