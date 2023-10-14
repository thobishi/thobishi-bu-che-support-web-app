<table width="90%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align=center class="special1">
	<br>
	<?php
	/*
	<table>
	<tr align="right">
		<td colspan="2">Search for HEQC reference number:
			<?php echo $this->showField("searchText"); ?>
		</td>
		<td align="left">
			<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
		</td>
	</tr>
	</table>
	*/
	?>
	<span class="specialrb"><?php echo $this->displayUserMessage; ?></span>
	<br>
	<span class="specialb">
	Welcome <?php echo $this->getCurrentUserInfo()?>. You have the following active processes...
	</span>
	<br><br>
	</td>
</tr>
<tr>
	<td>
	Application forms/processes that are not fully completed are listed below. To continue with them, click on the corresponding links below.
	If you just finished completing an application/process you may log out.
	<br><br>
	</td>
<tr><td>
<?php
/*	echo $_POST['submitButton'];
	if (isset($_POST['submitButton']) != "Search")
{*/
	$sortorder = readPost("sortorder");

	$this->showActiveProcesses($sortorder);
//}
?>
<br><br>

</td></tr></table>
