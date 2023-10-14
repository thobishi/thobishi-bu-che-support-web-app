<div id="workExternal">
	<div class="special1">
	<span class="specialrb"><?php echo $this->displayUserMessage; ?></span>
	<br>
	<div style="width:80%; text-align:center">
		<span class="specialb">Welcome <?php $this->getCurrentUserInfo(); ?>. You have the following active processes...</span>
	</div>
	<br><br>
	</div>

	<div style="width:80%; text-align:center">
	Application forms/processes that are not fully completed are listed below. To continue with them, click on the corresponding links below.
	If you just finished completing an application/process you may log out.
	</div>
	
	<div>
	<?php
	/*	echo $_POST['submitButton'];
		if (isset($_POST['submitButton']) != "Search")
	{*/
		$sortorder = readPost("sortorder");

		$this->showActiveProcesses($sortorder);
	//}
	?>
	</div>
</div>
