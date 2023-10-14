<?php 
	require_once ('_systems/heqc-online.php');
	require_once ('document_generator/cl_xml2driver.php');

	//octoDB::connect ();
?>

<html>
<head>
	<LINK REL=StyleSheet HREF="styles.css" TYPE="text/css">
</head>
<body>

<table cellpadding="2" cellspacing="2"><tr><td>

<?php 

$username = "";
	$password = "";
	$hostname = "localhost";
	$dbname = "";

	//connection to the ~*****WWW*******~~ database
	$dbhandle = mysqli_connect($hostname, $username, $password)
	  or die("Unable to connect to MySQL");

	//select a database to work with
	$selected = mysqli_select_db($dbname,$dbhandle)
	  or die("Could not select database");

	//HEQConline::reportAccreditedInstitutions("0", "html");
	include('html/recommGenerate.html.php');

//close the connection
mysqli_close($dbhandle);

?>

</td></tr></table>

</body>
</html>

