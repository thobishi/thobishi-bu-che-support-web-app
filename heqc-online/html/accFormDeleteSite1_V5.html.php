<?php

	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$ia_site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
?>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2" align="left" class="special1">
			<br>
			<span class="specialb">REMOVE PROGRAMME FOR SITE</span>
		</td>
	</tr>
	</table>
	<?php 	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $ia_site_id); ?>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
			To remove this programme from being offered on this site check the delete confirmation box 
			and then click on <span class="specialb">Delete site information for this programme</span> 
			in the Actions menu.
			<br>
			<br>
			Confirm that you want to delete all programme information for this site and remove the site for this programme by checking this box: 
			<?php $this->showField("confirm_site_delete"); ?>
			<br>
			<br>
			<span class="visi">NOTE: All the displayed information below will also be deleted when you delete this site.  You will NOT be able to retrieve this information  
			after you proceed and delete.</span>
			<br>
		</td>
	</tr>
	</table>
	<hr>
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

	$this->displayChildForm($ia_site_id, "accForm5_2_v4_3",$settings);
	//$this->displayChildForm($ia_site_id, "accForm8b_2_v2",$settings);
	//$this->displayChildForm($ia_site_id, "accForm15_2_v2",$settings);
	//$this->displayChildForm($ia_site_id, "accForm17_2_v2",$settings);
	//$this->displayChildForm($ia_site_id, "accForm19_2_v2",$settings);

	ob_end_flush();

?>
	