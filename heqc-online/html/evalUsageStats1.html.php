<?php
	$html = "";
	
	$rpt = <<<REPORT
	SELECT concat(Names, ' ', Surname) as evaluator , count(*) as n_apps_eval, GROUP_CONCAT(CONCAT(`CHE_reference_code`,'-', program_name) separator ',') as programme
	FROM `evalReport` , Institutions_application, Eval_Auditors
	WHERE application_id = application_ref
	AND Persnr = Persnr_ref
	GROUP BY Persnr
	ORDER BY evaluator
REPORT;

	$rs = mysqli_query($this->getDatabaseConnection(), $rpt);
	while ($row = mysqli_fetch_array($rs)){
		$html .= "<tr><td>".$row['evaluator']."</td><td>".$row['n_apps_eval']."</td><td>".$row['programme']."</td></tr>";
	}
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<br><br>
	<table  class="lineunder" width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="3" class="loud">List of evaluators and programmes evaluated</td>
	</tr>
	<tr>
		<td class="lineunder">Evaluator</td>
		<td class="lineunder">Number of programmes evaluated</td>
		<td class="lineunder">List of programmes evaluated</td>
	</tr>
	<?php 
	echo $html;
	?>
	</table>
<br><br>
</td></tr>
</table>