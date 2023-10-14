<?php

function doc2text_string($str)
{
	// requires catdoc
	
	// write to temp file
	$tmpfname = tempnam ('/tmp','doc');
	$handle = fopen($tmpfname,'w');
	fwrite($handle,$a);
	fclose($handle);
	
	// run catdoc
	$ret = shell_exec('catdoc -ab '.escapeshellarg($tmpfname) .' 2>&1');
	
	// remove temp file
	unlink($tmpfname);
	
	if (preg_match('/^sh: line 1: catdoc/i',$ret)) {
		return false;
	}
	
	return trim($ret);
}

function doc2text_file($fname)
{
	// requires catdoc

	$path_parts = pathinfo($fname);
	$ext = (isset($path_parts['extension']))?(strtolower($path_parts['extension'])):(false);
	
	switch ($ext) {
		case 'pdf':
			$cmd = 'pdftotext '.escapeshellarg($fname).' -';
			break;
		case 'rtf':
			$cmd = 'unrtf --nopict --text '.escapeshellarg($fname);
			break;
		default:
			$cmd = 'catdoc -ab '.escapeshellarg($fname);
			break;
	}
	
	// run catdoc
	$ret = shell_exec($cmd.' 2>&1');
	
	if (preg_match('/^sh: line 1: catdoc/i',$ret)) {
		return false;
	}
	
	return trim($ret);
}

?>
