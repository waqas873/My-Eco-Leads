<?php

$postal_code = array(
 'postcode' => 'cr7 8qa'
);
$postal_query = http_build_query($postal_code);
$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://epc.opendatacommunities.org/api/v1/domestic/search?'.$postal_query,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
 'Content-Type: application/json',
 'Accept: application/json',
 'Authorization: Basic YW5keUB3ZWJsZWFkc2NvbXBhbnkuY29tOmY4YmM5ODBjYzU2OWEzMWUwNGUxNDk2MDVlN2Y0Mzc1ZWVhYzE1NjI='
),
));

$epc_response = curl_exec($curl);
curl_close($curl);
// debug($epc_response, true);
echo $epc_response;


?>