
<?php

//require_once ('/var/www/html/heqc-online/images/ico_change.gif');

 	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID; 
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
<?php 
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
?>
</td>
</tr>
</table>
<!--
<table width="95%" align="center">
	<tr>
		<td>
			<?php $this->displayPopulatedApplicationForm($app_id, "html"); ?>
		</td>
	</tr>
</table>
-->
<?php 
	$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);
	$child = array();
	//$forms = array ("accForm1_v4", "accForm2_v4", "accForm3-1_v4", "accForm3-2_v4","accFormCreateUsersDescriptive_v2", "accForm5_v4_3", "accForm5_2_v4_3","accForm6_v4_3", "accForm7_v4_3", "accForm5_2_v4_3", "accForm5_v4");
	//$forms = array ("accForm1_v4","accForm2_v4","accForm3-1_v4","accForm3-2_v4","accForm7_v4_3","accForm6_v4_3","accFormab7_v4", "accForm5_v4_3","accForm5_v4","accFormI5_6_V4");
	//$forms = array ("accForm1_v4","accForm2_v4","accForm3-1_v4","accForm3-2_v4","accForm7_v4_3","accForm6_v4_3","accFormab7_v4", "accForm5_v4_3","accForm5_v4","accFormI5_6_V4");
	$forms = array ("accForm1_v4","accForm3-1_v4","accForm3-2_v4","accForm7_v4_3","accForm6_v4_3","accFormab7_v4", "accForm5_v4_3","accForm5_v4","accFormI5_6_V4");


	//$child["accForm8_1_v2"] = "accForm8_2_v2";
	//$child["accForm8b_v2"] = "accForm8b_2_v2";
	//$child["accForm15_v2"] = "accForm15_2_v2";
	//array_push($forms, "accForm5_v4_3");
	$child["accForm5_v4_3"] = "accForm5_2_v4_3";
	
	//$child["accForm5_v4_3"] = "accForm5_2_v4_3";

	foreach ($forms as $form) {
		$this->displayForm($app_id,$settings,$form,$child);
	}

// function doOutPutBuffer ($buffer) {
// 	$h = fopen ("/tmp/che_mis_output.html", "w+");
// 	$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:openFileWin|changeCMD|winContentText.*).*\>)(.*)(\<\/a\>)/U");
// 	$replace_array = array("", "\\2");

// 	$html = $buffer;
// 	$html = preg_replace ($search_array, $replace_array, $buffer);

// 	fwrite($h, $html);

// 	return $html;
// }

// ob_start("doOutPutBuffer");

// 	foreach ($forms as $form) {
// 		$app = new HEQConline (1);
// 		$app->parseWorkFlowString($settings);
// 		$app->template = $form;
// 		$app->view = 1;
// 		$app->formStatus = FLD_STATUS_TEXT;
// 		$app->readTemplate();
// 		$app->createHTML($app->body);

// 		unset ($app);
// 	}
// ?>
 	<!-- </td>
// </tr>
// </table> -->

 <?php 
// ob_end_flush();
// ?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right"><a href="#">________________________________________________________________________________________________________________________________________________</a></td>
</tr>		
<tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
<table width="85%" border=0  >
<tr>
	<td><b>

</b></td>
</tr><tr>
	<td>
	<br>
	<b>When you have finished checking the above application and completed the checklist document then upload it and click <a  href="javascript:moveto('_Start Process_V5');">here</a> to continue the screening process.</b>
	<br>
	<br>
	<table border="1" style="width:100%" >	
	<tr>
		
		</tr>
		<tr> 
		<td>Upload the checklisting report</td>
			<td><?php $this->makeLink("checklist_doc") ?>	</td>
		</tr>
	</table>
	
</td>
</tr>
<tr>

</tr>
</table>