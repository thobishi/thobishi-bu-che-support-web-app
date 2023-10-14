<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please in the table below tick off the name of the AC members who attended the meeting held on <?php echo $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID, "ac_start_date")?>.</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->createACMembersAttendanceList($this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);?></td>
</tr></table>
<br><bR>
</td></tr></table>