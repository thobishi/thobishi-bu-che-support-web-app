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
                $SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status=0 AND (expiry_date <> \"1000-01-01 00:00:00\") AND (expiry_date < NOW()) ORDER BY processes_ref";
        }else{
                $SQL = "SELECT * FROM active_processes, processes, users WHERE processes_ref = processes_id  AND user_ref = user_id AND status=0 AND (due_date <> \"1000-01-01 00:00:00\") AND (due_date < NOW()) AND (expiry_date <> \"1000-01-01 00:00:00\") AND (expiry_date >= NOW()) ORDER BY processes_ref";
        }
        return( mysqli_query($conn, $SQL));
}

        $textVars["overdueProcesses"] = "Due date\t Expiry Date \t User \t Process\n";

        // First check with proccess expired
        $rs = goGetActive (true, $conn);
        if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_array ($rs)) {
                        $textVars["overdueProcesses"] .= $row["due_date"] . "\t" . $row["expiry_date"] . "\t" . $row["name"] . "\t" . $app->workflowDescription ($row["active_processes_id"], $row["processes_ref"]) . "\n";
                }
        }

        // Check with proccess due
        $rs = goGetActive (false, $conn);
        if (mysqli_num_rows($rs) > 0) {
                while ($row = mysql_fetch_array ($rs)) {
                        $textVars["overdueProcesses"] .= $row["due_date"] . "\t" . $row["expiry_date"] . "\t" . $row["name"] . "\t" . $app->workflowDescription ($row["active_processes_id"], $row["processes_ref"]) . "\n";
                }
        }

        if (count($textVars) > 0){
                $app->misMailByName("naude.r@che.ac.za", "Overdue processes - manage", $app->getTextContent("scripts", "overdueProcesses", $textVars),"", false);
                echo "<br>Overdue processes: Emailing " . "naude.r@che.ac.za" . " regarding " . $textVars["overdueProcesses"];
        }
?>
