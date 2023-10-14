<?php	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID; ?>
	
	<br>

	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr><td><?php $this->getApplicationInfoTableTopForHEI_sites($app_id); ?></td></tr>
	</table>

	<table width="95%" align="center" border="0">
	<tr><td><?php $this->displayPopulatedApplicationForm($app_id, "html"); ?></td></tr>
	</table>

<?php 	
	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);

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

$this->displayApplicationFormOverview($app_id, $settings);

ob_end_flush();

?>
	<table width="95%" align="center" border="0">
	<tr><td><hr></td></tr>
	<tr><td align="right">[<a href="#">Back to Top</a>]</td></tr>
	<tr><td></td></tr>
	</table>


