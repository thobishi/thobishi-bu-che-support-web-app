<div id="workInternal">
	<div class="special1">
		<div style="width:80%; text-align:center">
			<span class="specialh">Welcome <?php echo $this->getCurrentUserInfo()?></span>
		</div>
	<?
		if ($this->sec_userInGroup("Administrator")){
			$this->makeSumProcTable();
		}

	?>
	<br><br>
		<span class="specialrb"><?php echo $this->displayUserMessage; ?></span>
		<br>
		<span class="specialb">You have the following active processes...</span>
	</div>
	<div>
	<?
		$sortorder = readPost("sortorder");

		$this->showActiveProcesses($sortorder);
	?>

	</div>
</div>
