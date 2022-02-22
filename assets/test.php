<?php
echo "<h1>Processing........</h1>";
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("LIST_ID", 99);

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

############# If api is active get all contact details ################
//if ($response->success == 1) {
############# Get Leads Data #############

$leads_data = [];
$all_leads_info = [];
for($i=1; $i <=3 ; $i++){ 
    $url = 'https://seasidemedia.api-us1.com';
    $params = array(
        'api_key' => 'a544e3a9c628cf1c447ffcdef2aabf99d0bac140877a42f4fe51e3a81ad8c26971e857bb',
        'api_action' => 'contact_list',
        'api_output' => 'json',
        'filters[listid]' => LIST_ID,
        'full' => 1,
        'sort'=>'id',
        'sort_direction'=>'DESC',
        'page'=>$i
    );
    // 'page'=>2
    $url = rtrim($url, '/ ');
    $query = http_build_query($params);
    if (!function_exists('curl_init')) {
        die('CURL not supported. (introduced in PHP 4.0.2)');
    }
    if ($params['api_output'] == 'json' && !function_exists('json_decode')) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }
    $api = $url . '/admin/api.php?' . $query;
    $request = curl_init($api);
    curl_setopt($request, CURLOPT_HEADER, 0);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
    $res = (string)curl_exec($request);
    curl_close($request);
    if (!$res) {
        die('Nothing was returned. Please try again later.');
    }
    $result = json_decode($res, true);
    
    foreach ($result as $row) {
        if(!empty($row['id'])){
            $sql = "select * from leads where crm_lead_id='".$row['id']."'";
            $query = mysqli_query($conn, $sql);
            if(mysqli_num_rows($query) == 0){

                if($row['email']=="Kylie_bickmore@hotmail.co.uk"){
                    $lead_fields = (!empty($row['tags'][2])?$row['tags'][2]:'');
                    echo $row['email']."<br />";
                    print_r($lead_fields);
                    exit;
                }
                $leads_data[] = $row;
            }
        }
    }
}

exit;


echo "<h1>Done</h1>";
echo "<hr>";

?>