

<?php

//require_once ('/var/www/html/heqc-online/images/ico_change.gif');

 	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID; 
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php $this->showInstitutionTableTop (); ?>
	<br>
<?php 

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

	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);

	// Display application report according to its version. A new format was implemented on 1st March 2008.
	$this->displayApplicationFormOverview($app_id, $settings);

	ob_end_flush();
?>
	</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr><td><hr></td></tr>
		<tr>
			<td>
			<b>When you have finished reading click <a href="javascript:moveto('next');">here</a> to continue
			the screening process.</b>
			</td>
		</tr>
		<tr>
			<td><hr></td>
		</tr>
	</table>
	</td>
</tr>
</table>
