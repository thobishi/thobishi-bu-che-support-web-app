<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
<table border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td valign="top">Add your comment:</td>
<td><?php $this->showField("comment_text")?></td>
</tr>
</table>
<br><br>
<?php 
$proc_id = 0;
if ((isset($this->active_processes_id)) && ($this->active_processes_id > 0)){
	$proc_id = $this->active_processes_id;
}
if (isset($_POST["CMT_ID"]) && $_POST["CMT_ID"] > ""){
	$proc_id = $_POST["CMT_ID"];
}


$SQL = "SELECT name,surname,DATE_FORMAT(comment_date,'%M %d %Y %H:%i:%s') as date,comment_text as comment FROM process_comments,users WHERE user_id = user_ref AND active_processes_ref=? AND comment_text > '' ORDER BY comment_date DESC";

$conn = $this->getDatabaseConnection();
$sm = $conn->prepare($SQL);
$sm->bind_param("s", $proc_id);
$sm->execute();
$RS = $sm->get_result();

//$RS = mysqli_query($SQL);
if (mysqli_num_rows($RS)){
	echo "<table width='90%' border='1' align='center' cellpadding='2' cellspacing='2'>";
	echo "<tr>";
	echo "<td valign='top' width='15%' class='oncolourb'>Date</td>";
	echo "<td valign='top' width='15%' class='oncolourb'>User</td>";
	echo "<td valign='top' width='70%' class='oncolourb'>Comment</td>";
	echo "</tr>";
	while($row = mysqli_fetch_array($RS)){
	echo "<tr>";
	echo "<td valign='top'>".$row["date"]."</td>";
	echo "<td valign='top'>".$row["name"]." ".$row["surname"]."</td>";
	echo "<td valign='top'>".$row["comment"]."</td>";
	echo "</tr>";
	}
	echo "</table>";	
	
	
}
	
	
$this->showField("active_processes_ref");
$this->showField("user_ref");

?>
</td>
</tr></table>
</td></tr></table>
<script>
function checkComemnts(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_comment_text.value == ""){
			alert("Please add a comment");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
		return true;	
		}
	}	
}
</script>
