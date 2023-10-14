<?php

class Settings {
	private static $__data = array(
		'currentUserID' => 0,
		'flowID' => 0,
		'active_processes_id' => 0,
		'template' => '',
		'securityLevel' => 0
	);

	public static function set($key, $value) {
		if (strpos($key, '.')) {
			list($parent, $key) = explode('.', $key);
			if (!isset(self::$__data[$parent])) {
				self::$__data[$parent] = array();
			}

			self::$__data[$parent][$key] = $value;
		} else {
			self::$__data[$key] = $value;
		}
	}

	public static function get($key) {
		if (strpos($key, '.')) {
			list($parent, $key) = explode('.', $key);

			if (!isset(self::$__data[$parent][$key])) {
				return null;
			}

			return self::$__data[$parent][$key];
		} else {
			if (!isset(self::$__data[$key])) {
				return null;
			}

			return self::$__data[$key];
		}
	}

	public static function isIsset($key) {
		$item = self::get($key);

		return isset($item);
	}

	public static function isEmpty($key) {
		$item = self::get($key);
		
		return empty($item);
	}

	public static function printAll() {
		var_dump(self::$__data);
	}
}