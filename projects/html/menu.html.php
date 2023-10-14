<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
<?
		switch ($this->userInterface) {
			case 1:
				$img = "images/header.jpg";
				$colour = "#347cb8";
				break;
			case 2:
				$img = "images/header.jpg";
				$colour = "#347cb8";
				break;
			case 3:
				$img = "images/header.jpg";
				$colour = "#347cb8";
				break;
			default:
				$img = "images/header.jpg";
				$colour = "#347cb8";
				break;
		}
?>
		<td height="87" bgcolor="<?php echo $colour?>"><img src="<?php echo $img?>" width="750" height="87"></td>
	</tr>
	<tr>
		<td height="16" bgcolor="#336699">&nbsp;</td>
	</tr>
	<tr>
		<td height="17" bgcolor="#eeebd9">&nbsp;&nbsp;&nbsp;
<?
	//	if(isset($this->NavigationBar)) echo $this->NavigationBar;
		if ( isset ($this->active_processes_id) && isset ($this->flowID) ) {
			echo "<span class=pathdesc>";
			echo $this->navTrailDisplay ($this->active_processes_id, $this->flowID);
			echo "</span>";
		}
?>
		&nbsp;&nbsp;
		</td>
	</tr>
</table>
