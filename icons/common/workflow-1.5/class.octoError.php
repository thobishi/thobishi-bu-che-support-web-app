<?php

class octoError {
	public static function create ($string, $category="General", $severity=true) {
		return self::createException ($string, $category, $severity);
	}

	private static function createException ($string, $category, $severity) {
		die ("ERROR (".$category."):".$string);
	}

}
?>
