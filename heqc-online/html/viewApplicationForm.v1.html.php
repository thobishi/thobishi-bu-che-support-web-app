<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
</td></tr></table>
<?php 
/*	$forms = array ("insForm1", "accForm1", "accForm1b", "accForm3-1", "accForm6", "accForm8", "accForm8b", "accForm9", "accForm14", "accForm15", "accForm17", "accForm19");

	foreach ($forms as $form) {
		$tmpApp = new HEQConline (1);

		$tmpApp->template = $form;
		$tmpApp->formStatus = FLD_STATUS_DISABLED;
		$tmpApp->readTemplate();
		$tmpApp->createHTML($tmpApp->body);
		
		unset ($tmpApp);
	}
*/

	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);
	
	$forms = array ("accForm1", "accForm1b", "accForm3-1", "accForm6", "accForm8", "accForm8b", "accForm9", "accForm14", "accForm15", "accForm17", "accForm19", "done");

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
	</td>
</tr></table>

<?php 
ob_end_flush();
?> 
