<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
</td></tr></table>
<?php 

	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);
	
	$forms = array ("evaluatorForm2");

function doOutPutBuffer ($buffer) {
	$h = fopen ("/tmp/che_mis_output.html", "w+");
	$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:openFileWin|changeCMD|winContentText.*).*\>)(.*)(\<\/a\>)/U");
	$replace_array = array("", "\\2");
	
	$html = $buffer;
	$html = preg_replace ($search_array, $replace_array, $buffer);
	
	fwrite($h, $html);
	
	return $html;
}

ob_start("doOutPutBuffer");	
	
	foreach ($forms as $form) {
		$app = new HEQConline (1);
		$app->parseWorkFlowString($settings);
		$app->template = $form;
		$app->view = 1;
		$app->formStatus = FLD_STATUS_TEXT;
		$app->readTemplate();
		$app->createHTML($app->body);
		
		unset ($app);
	}
?>	

<?php 
ob_end_flush();
?> 
