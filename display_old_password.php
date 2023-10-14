<?php

//include_once('database.php');
//$conn = connect();
/* For testing on support */
$conn = mysqli_connect("localhost", "heqc_healthdb", "H@ppy123"); //connect to the database
if (!$conn) {
	echo 'Could not connect to mysql';//die('Could not connect: ' . mysql_error());
}

if (!mysql_select_db("heqc_healthdb", $conn)) {		//select correct database		
	echo 'Could not select database';
	exit;
}



   function oldPassword($input, $hex = true) {
        $nr    = 1345345333;
        $add   = 7;
        $nr2   = 0x12345671;
        $tmp   = null;
        $inlen = strlen($input);
        for ($i = 0; $i < $inlen; $i++) {
            $byte = substr($input, $i, 1);
            if ($byte == ' ' || $byte == "\t") {
                continue;
            }
            $tmp = ord($byte);
            $nr ^= ((($nr & 63) + $add) * $tmp) + (($nr << 8) & 0xFFFFFFFF);
            $nr2 += (($nr2 << 8) & 0xFFFFFFFF) ^ $nr;
            $add += $tmp;
        }
        $out_a  = $nr & ((1 << 31) - 1);
        $out_b  = $nr2 & ((1 << 31) - 1);
        $output = sprintf("%08x%08x", $out_a, $out_b);
        if ($hex) {
            return $output;
        }

        return hexHashToBin($output);
    }


	$sql_src = <<<SQLSRC
			SELECT email, password from users;
SQLSRC;
	echo $sql_src;
	$rs_src = mysqli_query($conn, $sql_src) or die(mysqli_error());
	echo "<table>";
	while ($row = mysqli_fetch_array($rs_src)){
		$old_password = $row['password'];
		$calc_password = oldPassword('Grbwok323');
		$tr = "<tr><td>".$row['email']."</td><td>".$old_password."</td><td>".$calc_password."</td></tr>";
		echo $tr;
	}
	echo "</table>";


?>