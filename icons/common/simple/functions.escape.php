<?php

	/*function system_escape ($str) {
		if (!get_magic_quotes_gpc()) {
			if (is_array($str)) {
				array_walk($str, 'system_reslash_multi');
			} else {
				$str = system_reslash ($str);
			}
		}
		return ($str);
	}
	
	function system_reslash_multi (&$val,$key)
	{
		if (is_array($val)) {
			array_walk($val,'system_reslash_multi',$new);
		} else {
			$val = system_reslash($val);
		}
	}

	
	function system_reslash ($string, $conn = null)
	{
		if (!get_magic_quotes_gpc()) {
			$string = mysqli_real_escape_string($conn, $string);
		}
		return $string;
	}
	*/
	
	function system_escape_i ($str, $conn) {
		if (!get_magic_quotes_gpc()) {
			if (is_array($str)) {
				array_walk($str, 'system_reslash_multi_i', $conn);
			} else {
				$str = system_reslash_i ($str, $conn);
			}
		}
		return ($str);
	}
	
	function system_reslash_multi_i (&$val, $key, $conn)
	{
		if (is_array($val)) {
			array_walk($val,'system_reslash_multi_i',$new);
		} else {
			$val = system_reslash_i($val, $conn);
		}
	}

	
	function system_reslash_i ($string, $conn)
	{
		if (!get_magic_quotes_gpc()) {
			$string = mysqli_real_escape_string($conn, $string);
		}
		return $string;
	}


?>
