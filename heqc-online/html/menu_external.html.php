<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td height="87" bgcolor="#336699"><img src="images/top.gif" width="750" height="87"></td></tr>
<tr>
	<td height="16" bgcolor="#C1D1E0">&nbsp;</td>
</tr>
<tr><td height="17" bgcolor="#ECF1F6">&nbsp;&nbsp;&nbsp;<?php 
//	if(isset($this->NavigationBar)) echo $this->NavigationBar;
	if ( isset ($this->active_processes_id) && isset ($this->flowID) ) {
		echo "<span class=pathdesc>";
		echo $this->workflowDescription ($this->active_processes_id, $this->flowID);
		echo "</span>";
	}
?>&nbsp;&nbsp;</td></tr>
</table>
