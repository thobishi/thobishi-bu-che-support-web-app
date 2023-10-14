<?php

if (!function_exists('date_parse')) {
	function date_parse ($dateStr) {
		$partAll = explode (" ", $dateStr);
		$partTime = explode ("-", $partAll[0]);
	
		$pDate['year'] = intval($partTime[0]);
		$pDate['month'] = intval($partTime[1]);
		$pDate['day'] = intval($partTime[2]);

		return ($pDate);
	}
}

?>
