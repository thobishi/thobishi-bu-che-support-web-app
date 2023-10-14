<head>
<title><?php echo $_GET["title"]?></title>
</head>
<frameset rows=60,* cols=*>
<frame src="printEvalReportFormNavigation.php?workflow_settings=<?php echo $_GET["workflow_settings"]?>&title=<?php echo $_GET["title"]?>&appid=<?php echo $_GET["appid"]?>" name="navFrame" id="navFrame" marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=no>
<frame src="printEvalReportForm.php?workflow_settings=<?php echo $_GET["workflow_settings"]?>&title=<?php echo $_GET["title"]?>&appid=<?php echo $_GET["appid"]?>" name="bodyFrame" id="bodyFrame" marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=auto>
</frameset>
<noframes>
</noframes>
