<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
<?php

		switch ($this->userInterface) {
			case 1:
				$img = "images/top_int.jpg";
				$colour = "#CC3300";
				break;
			default:
				$img = "images/top_int.jpg";
				$colour = "#CC3300";
				break;
		}
?>
		<td class="header"><img src="<?php echo echo $img ?>" width="750" height="87"></td>
	</tr>
	<tr>
		<td class="navbar">&nbsp;</td>
	</tr>
	<tr>
		<td class="debugbar">&nbsp;&nbsp;&nbsp;
<?php
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
