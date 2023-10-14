<head>
<title><?php echo $_GET["title"]?></title>
</head>
<frameset rows=150,* cols=*>
<frame src="printReaccApplicProcessNavigation.php?workflow_settings=<?php echo $_GET["workflow_settings"]?>&title=<?php echo $_GET["title"]?>&appid=<?php echo $_GET["appid"]?>" name="navFrame" id="navFrame" marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=auto>
<frame src="printReaccApplicProcessInfo.php?workflow_settings=<?php echo $_GET["workflow_settings"]?>&title=<?php echo $_GET["title"]?>&appid=<?php echo $_GET["appid"]?>" name="bodyFrame" id="bodyFrame" marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=auto>
</frameset>
<noframes>
</noframes>