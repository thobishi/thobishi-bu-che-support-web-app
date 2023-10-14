<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<?php 
$minutes = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"prev_minutes_doc");
$agenda = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"agenda_doc");
$site = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"site_visit_doc");
$paper = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"paper_eval_doc");
$filelist = array();
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $minutes,"document_url"),$this->getValueFromTable("documents", "document_id", $minutes,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $agenda,"document_url"),$this->getValueFromTable("documents", "document_id", $agenda,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $site,"document_url"),$this->getValueFromTable("documents", "document_id", $site,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $paper,"document_url"),$this->getValueFromTable("documents", "document_id", $paper,"document_name")));
$AC_attendance_list = $this->generateReport("generateAttendanceList(".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.")");
$ext = strrchr($AC_attendance_list,".");
copy($AC_attendance_list, $this->TmpDir."AC_meeting_Attendance_list_".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date").$ext);
unlink($AC_attendance_list);
$AC_attendance_list = $this->TmpDir."AC_meeting_Attendance_list_".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date").$ext;
array_push($filelist, $AC_attendance_list);

$fsize_time = $this->getFileSize($filelist);
?>
<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2"><b>The size of the email will be roughly <?php echo $fsize_time[0]?> and will take <?php echo $fsize_time[1]?> to download. You have the option to courier the documentation or email the documentation to the AC members.</b></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td align="right">Please choose:</td>
	<td><?php $this->showField("docs_email");?> - Email</td>
</tr><tr>
	<td>&nbsp;</td>
	<td><?php $this->showField("docs_courier");?> - Courier</td>
</tr></table>
<br><br>
</td></tr></table>
