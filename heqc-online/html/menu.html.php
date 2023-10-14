<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
<?php

		switch ($this->userInterface) {
			case 1:
				$img = "images/top_int.jpg";
				$colour = "#CC3300";
				break;
			case 2:
				$img = "images/top.jpg";
				$colour = "#336699";
				break;
			case 3:
				$img = "images/top_eval.jpg";
				$colour = "#336699";
				break;
			default:
				$img = "images/top.jpg";
				$colour = "#CC3300";
				break;
		}
?>
		<td height="87" bgcolor="<?php echo $colour ?>"><img src="<?php echo $img ?>" width="750" height="87"></td>
	</tr>
	<tr>
		<td height="16" bgcolor="#336699">&nbsp;</td>
	</tr>
	<tr>
		<td height="17" bgcolor="#D6E0EB">&nbsp;&nbsp;&nbsp;
<?php
	//	if(isset($this->NavigationBar)) echo $this->NavigationBar;
		if ( isset ($this->active_processes_id) && isset ($this->flowID) ) {
			echo "<span class=pathdesc>";
			echo $this->workflowDescription ($this->active_processes_id, $this->flowID);
			echo "</span>";
		}
?>
		&nbsp;&nbsp;
		</td>
	</tr>
</table>
