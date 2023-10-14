<br>
<span class="specialb">Regenerate recommendations</span> 
<br>
<br>
Recommendations were generated before being assigned to an AC meeting and therefore do not have their AC meeting date.  
Thus all recommendations for proceedings that have been to an AC meeting must be re-generated.
<br>
<br>
<?php
$sql = <<<SQL
			SELECT ia_proceedings_id
			FROM ia_proceedings
			WHERE ac_meeting_ref > 0
			AND recomm_doc > 0
SQL;

$rs = mysqli_query($this->getDatabaseConnection(), $sql); // or die(mysqli_error());

$i = 0;
while ($row = mysqli_fetch_array($rs)){
	$fileName = "recomm_" . $row["ia_proceedings_id"] . ".rtf";
	$this->generateDocument($row["ia_proceedings_id"],"dir_recomm_document",$fileName,"ia_proceedings","ia_proceedings_id","recomm_doc");
	$i++;
	echo "<br>" . $i . "  " . $fileName . " re-generated";
}
?>
