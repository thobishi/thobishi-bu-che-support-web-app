<?php
$run_in_script_mode = true;

define ('CONFIG', 'CHETEST2');

require_once ('/var/www/common/_systems/heqc-online.php');

$app = new HEQConline (1);

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}


function goGetActive ($expired, $conn) {
        if ($expired){
                $SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status=0 AND (expiry_date <> \"1000-01-01 00:00:00\") AND (expiry_date < NOW())";
        }else{
                $SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status=0 AND (due_date <> \"1000-01-01 00:00:00\") AND (due_date < NOW()) AND (expiry_date <> \"1000-01-01 00:00:00\") AND (expiry_date >= NOW())";
        }
        return( mysqli_query($conn, $SQL));
}

// First check with proccess expired
        $rs = goGetActive (true, $conn);
        if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_array ($rs)) {
                        $textVars = array ();
                        $textVars["workflowDesc"] = $app->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
                        $textVars["username"] = $row["name"] . " " . $row["surname"];
                        $textVars["dueDate"] = $row["due_date"];
                        $textVars["expiryDate"] = $row["expiry_date"];
                        echo "<br>Expired processes: Emailing " . $row["email"] . " regarding " . $textVars["workflowDesc"];
                        //$app->mimemail ($row["email"], "CHE <che@octoplus.co.za>", "Overdue 2", $app->getTextContent("scripts", "PassedExpiryDate", $textVars));
                        $app->misMailByName($row["email"], "Expired processes", $app->getTextContent("scripts", "PassedExpiryDate", $textVars), "", false);
                }
        }

// Check with proccess due
        $rs = goGetActive (false, $conn);
        if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_array ($rs)) {
                        $textVars = array ();
                        $textVars["workflowDesc"] = $app->workflowDescription ($row["active_processes_id"], $row["processes_ref"]);
                        $textVars["username"] = $row["name"] . " " . $row["surname"];
                        $textVars["dueDate"] = $row["due_date"];
                        $textVars["expiryDate"] = $row["expiry_date"];
                        echo "<br>Overdue processes: Emailing " . $row["email"] . " regarding " . $textVars["workflowDesc"];
                        //$app->mimemail ($row["email"], "CHE <che@octoplus.co.za>", "Overdue 1", $app->getTextContent("scripts", "PassedDueDate", $textVars));
                        $app->misMailByName($row["email"], "Overdue processes", $app->getTextContent("scripts", "PassedDueDate", $textVars),"", false);
                }
        }

?>
