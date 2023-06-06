<?php

// accès cors-origin
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
$headers = array(
    'X-SYCA-MERCHANDID:C_61C434167C830',
    'X-SYCA-APIKEY:pk_syca_4391dfd96f233c9be0ceeb528ed1ebccbfad3e63',
    'X-SYCA-REQUEST-DATA-FORMAT: JSON',
    'X-SYCA-RESPONSE-DATAFORMAT: JSON'
);

$paramsend = array(
    "montant" => "2500",
    "curr" => "XOF"
);

$url = 'https://secure.sycapay.net/login';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    $headers
);
$data_json = json_encode($paramsend);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
$response = json_decode(curl_exec($ch), TRUE);

if (empty($response)) {
    echo "Error Number:" . curl_errno($ch) . "<br>";
    echo "Error String:" . curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}
curl_close($ch);
if ($response['code'] == 0) {
    $token = $response['token'];
    $responses = [
        'success' => true,
        'message' => 'Inscription reussie !',
        'token' => $token,
        'merchandid' => 'C_61C434167C830',
        'amount' => '2500',
        'currency' => 'XOF'
    ];
} else {
    var_dump($response['code']);
    $responses = [
        'success' => false,
        'message' => 'Inscription échouées !'
    ];
}

header('Content-Type: application/json');
echo json_encode($responses);
}