<?php
//echo "<h1>Processing........</h1>";
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// $servername = "localhost";
// $username = "debtmonsterco_wlc";
// $password = "nK%4TsPoH.Lm";
// $dbname = "debtmonsterco_wlc";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wlc";

############# Create connection #############
$conn = new mysqli($servername, $username, $password, $dbname);

############# Check connection #############
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
define("TWILIO_SID", "ACdcb321fad468676cf6db19fb4acb10dd");
define("AUTH_TOKEN", "fdd8a0e7baa6164101881764ce501eba");

?>