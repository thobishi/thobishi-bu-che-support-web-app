<?php
$host='localhost'; //this is the database hostname, Do not change this.
$user='root'; //please set your mysql user name
$pass='H@ppy123'; // please set your mysql user password
$dbname='CHE_heqconline'; //please set your Database name
$charset='utf8'; // specify the character set
$collation='utf8_general_ci'; //specify what collation you wish to use

$db = mysqli_connect('localhost',"$user","$pass") or die("mysql could not CONNECT to the database, in correct user or password " . mysqli_error());
mysqli_select_db($db, "$dbname") or die("Mysql could not SELECT to the database, Please check your database name " . mysqli_error());
$result=mysqli_query($db,'show tables') or die("Mysql could not execute the command 'show tables' " . mysqli_error());
while($tables = mysqli_fetch_array($result)) {
foreach ($tables as $key => $value) {
mysqli_query("ALTER TABLE $value CONVERT TO CHARACTER SET $charset COLLATE $collation") or die("Could not convert the table " . mysqli_error());
}}
mysqli_query($db,"ALTER DATABASE $dbname DEFAULT CHARACTER SET $charset COLLATE $collation") or die("could not alter the collation of the databse " . mysqli_error());
echo "The collation of your database has been successfully changed!";
?>