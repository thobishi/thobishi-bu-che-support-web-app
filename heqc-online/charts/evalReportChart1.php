<?php 
$run_in_script_mode = true;
require_once('_systems/heqc-online.php');
require_once('chartdirector/phpchartdir.php');
$db = new dbConnect();

$id = readGET("id", false);

if (! $id) die("ERROR: Institution data not available");

$SQL = "SELECT evalReport_q1_comp, evalReport_q2_comp, evalReport_q3_comp, evalReport_q8_comp, evalReport_q9_comp, evalReport_q4_comp, evalReport_q5_comp, evalReport_q6_comp, evalReport_q7_comp FROM evalReport WHERE evalReport_id = ".$id;
$rs = mysqli_query($SQL);
$data = mysqli_fetch_array($rs, MYSQL_NUM);


#The data for the bar chart
//$data = array(85, 156, 179.5, 211, 123);

#The labels for the bar chart
$labels = array("Question 1", "Question 2", "Question 3", "Question 3 (continued)", "Question 4", "Question 5", "Question 6", "Question 7", "Question 8");

#Create a PieChart object of size 450 x 240 pixels
$c = new PieChart(600, 400);

#Set the center of the pie at (150, 100) and the radius to 80 pixels
$c->setPieSize(260, 170, 100);

#Add a title at the bottom of the chart using Arial Bold Italic font
$c->addTitle2(Bottom, "Evaluation per question", "arialbi.ttf");

#Draw the pie in 3D
$c->set3D();

#add a legend box where the top left corner is at (330, 40)
//$tmp = $c->addLegend(500, 40);

#add line to join sectors with labels
$c->setLabelPos(20, LineColor);

#modify the label format for the sectors to $nnnK (pp.pp%)
$c->setLabelFormat("{label} ({value}%)");

#Set the pie data and the pie labels
$c->setData($data, $labels);

#Explode the 1st sector (index = 0)
for ($i=0; $i<count($data); $i++) {
	$c->setExplode($i);
}

#output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));

?>

