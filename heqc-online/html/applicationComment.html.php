<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
<br><br>
<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$SQL = "SELECT * FROM `application_comments` WHERE application_ref=? ORDER BY date_added DESC";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$RS = $sm->get_result();

//$RS = mysqli_query($SQL);

echo "<table width='90%' border='1' align='center' cellpadding='2' cellspacing='2'>";
if (mysqli_num_rows($RS)){
	echo "<tr>";
	echo "<td valign='top' width='15%' class='oncolourb'>Application</td>";
	echo "<td valign='top' width='15%' class='oncolourb'>User</td>";
	echo "<td valign='top' width='70%' class='oncolourb'>Date Added</td>";
	echo "<td valign='top' width='70%' class='oncolourb'>Comment_type</td>";
	echo "<td valign='top' width='70%' class='oncolourb'>Comment</td>";
	echo "</tr>";
	while($row = mysqli_fetch_array($RS)){
		echo "<tr>";
		echo "<td valign='top'>".$this->getValueFromTable("Institutions_application", "application_id", $row["application_ref"], "CHE_reference_code")."</td>";
		echo "<td valign='top'>".$this->getValueFromTable("users", "user_id", $row["user_ref"], "name")." ".$this->getValueFromTable("users", "user_id", $row["user_ref"], "surname")."</td>";
		echo "<td valign='top'>".$row["date_added"]."</td>";
		echo "<td valign='top'>".$row["comment_type"]."</td>";
		echo "<td valign='top'>".$row["comment"]."</td>";
		echo "</tr>";
	}
}else{
	echo '<tr><td align="center"><b>No previous comments.</b></td></tr>';
}
echo "</table>";
?>
</td>
</tr></table>
</td></tr></table>
