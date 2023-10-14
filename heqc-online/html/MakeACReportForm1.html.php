<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
<?php 
$date = $this->getValueFromTable("AC_Meeting","ac_id",$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date");
?>
The decisions made at the Accreditation Committee meeting held on <strong><?php echo $date?></strong> need to be communicated to the DoE.
<br><br>
Using the screen below prepare the report on the results of the accreditation process for each institution in an HEQC letterhead (Word document). The text of the letter must be approved by your supervisor.
<br><br>
To create the required document<br>
<ul>
<li>Go into <em>My Documents</em> </li>
<li>On the P drive select the <em>Administration 2003 folder</em></li>
<li>Select <em>letterheads and memos</em>,</li>
<li>Look for the HEQC letterhead. </li>
<li>Open it, write your report </li>
<li>Save the report on the P drive in the <em>AC Meeting 2005/6 folder</em></li>
<li>Go back to the Accreditation System</li>
</ul>
Upload the report so that it can be sent to your supervisor for approval.<br> 
<br>
<table cellpadding='2' cellspacing='2' border="1">
<tr>
<td class="oncolourb">INSTITUTION</td>
<td class="oncolourb">PROGRAMME</td>
<td class="oncolourb">REFERENCE</td>
<td class="oncolourb">DECISION</td>
<td class="oncolourb">CONDITIONS</td>
</tr>
<pre>
<?php 
$institution = $this->getValueFromTable("AC_Meeting_reports","report_id",$this->dbTableInfoArray["AC_Meeting_reports"]->dbTableCurrentID,"ins_ref");
$SQL = "SELECT application_id,institution_id,HEI_name,program_name,CHE_reference_code,AC_desision,AC_conditions FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and AC_Meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID." AND institution_id=".$institution." ORDER BY HEI_name,program_name";
$rs = mysqli_query($SQL);
while ($row = mysqli_fetch_array($rs)){

	echo "<tr>";
	echo "<td valign='top'>".$row["HEI_name"]."&nbsp;</td>";
	echo "<td valign='top'>".$row["program_name"]."&nbsp;</td>";
	echo "<td valign='top'>".$row["CHE_reference_code"]."&nbsp;</td>";
	echo "<td valign='top'>".$this->getValueFromTable("lkp_desicion","lkp_id",$row["AC_desision"],"lkp_title")."&nbsp;</td>";
	echo "<td valign='top'>".$row["AC_conditions"]."&nbsp;</td>";
	echo "</tr>";
}
?>
</pre>
</table>
<br>
<?php $this->makeLink('file_ref')?>
</td>
</tr></table>
</td></tr></table>
<script>
function checkFiles(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_file_ref.value == "0"){
			alert("Please upload a report");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
		return true;	
		}
	}	
}
</script>
