<?php 
	require_once ('_systems/heqc-online.php');
	//octoDB::connect ();
?>

<html>
<head>
	<LINK REL=StyleSheet HREF="styles.css" TYPE="text/css">
</head>
<body>

<table cellpadding="2" cellspacing="2"><tr><td>

<?php 

$username = "heqc";
	$password = "workflow";
	$hostname = "localhost";
	$dbname = "CHE_heqconline";

	//connection to the ~*****WWW*******~~ database
	$dbhandle = mysqli_connect($hostname, $username, $password)
	  or die("Unable to connect to MySQL");

	//select a database to work with
	$selected = mysqli_select_db($dbname,$dbhandle)
	  or die("Could not select database");

	$application_id = "1144";	  //test application

	HEQConline::displayPopulatedApplicationForm($application_id, "html");

//close the connection
mysqli_close($dbhandle);

?>

</td></tr></table>

</body>
</html>

