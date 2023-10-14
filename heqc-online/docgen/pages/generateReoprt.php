<?php 
/*
Reyno
2004/4/21
This page generates a report for a ac meeting
*/
	$path="../";
	include $path."lib/handleDocs.class.php";

//		if (isset($file)){
//			$file = $path.$file;
//		}

	$dbConnect = new dbConnect("../");
	$doc = new handleDocs();

	require_once ($path."settings-wkf.php");
	if ($type == "paperEval"){
		$file = $doc->generateReport("makePaperEvalReport($id)");
		$ext = strrchr($file,".");
		copy($file,$this->TmpDir.$type."-".$ddate.$ext);
		unlink($file);
	}
	
	if ($type == "paperEvalInternal"){
		$file = $doc->generateReport("makePaperEvalReport2($id)");
		$ext = strrchr($file,".");
		copy($file,$this->TmpDir.$type."-".$ddate.$ext);
		unlink($file);
	}
	
	if ($type == "SiteVisits"){
		$file = $doc->generateReport("makeSiteReport($id)");
		$ext = strrchr($file,".");
		copy($file, $this->TmpDir.$type."-".$ddate.$ext);
		unlink($file);
	}


	if ($type == "agenda"){
		$file = $doc->generateReport("makeACAgenda($ddate,$id)");
		$ext = strrchr($file,".");
		copy($file, $this->TmpDir.$type."-".$ddate.$ext);
		unlink($file);
	}	

	$file = $this->TmpDir.$type."-".$ddate.$ext;
//	echo $file;
	header("Cache-control: private");
	header("Content-type: application/force-download");
	header("Content-Transfer-Encoding: Binary");
	header("Content-length: ".filesize($file));
	header("Content-disposition: attachment; filename=\"".basename($file)."\"");
	readfile($file);

?>	
