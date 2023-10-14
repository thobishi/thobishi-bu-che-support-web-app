<?php

	function checkURL ($url) {
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);

		// grab URL and pass it to the browser
		$web = curl_exec($ch);

		$status = (curl_errno($ch)>0)?("Down"):("Up");

		curl_close($ch);

		return ($status);
	}

?>
