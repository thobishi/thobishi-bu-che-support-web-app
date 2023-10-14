<?php

	function simple_text2html ($string, $format=0) {
		switch ($format) {
			case "docgen": 	return ( str_replace("\n","<br />",$string) );
							break;
			default: return ( str_replace("\n","<br>",$string) );
					break;
		}
	}

	function makeHumanFileSize ($size) {
		$types = array ('Gb', 'Mb', 'Kb', 'b');
				
		$type = array_pop($types);
		while ( ($size >= 1024) && ($type = array_pop($types)) ) {
			$size = $size / 1024;
		}

		return (sprintf("%.1f %2s", $size, $type));
	}
?>
