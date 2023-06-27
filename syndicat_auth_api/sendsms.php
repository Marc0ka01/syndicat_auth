<?php

$table = [

    [
        "id" => 1,
        "contact" => "0555129163",
        "password" => "1234",
    ],

    [
        "id" => 1,
        "contact" => "0576105239",
        "password" => "4321",
    ]

];

foreach ($table as $row) {
    $param = array(
        'username' => 'MANAGE MOI',
        'password' => 'CvbaMf7Gu6@c2w2',
        'sender' => 'MANAGE MOI',
        'text' => "Informations de connexion: \n" . 'Contact: '. $row['contact']. "\nMot de passe: ".$row['password'],
        'type' => 'text',
        'datetime' => date('Y-m-d H:i:s'),
    );
    $recipients = array('225'.$row['contact']);
    $post = 'to=' . implode(';', $recipients);
    foreach ($param as $key => $val) {
        $post .= '&' . $key . '=' . rawurlencode($val);
    }
    $url = "https://africasmshub.mondialsms.net/api/api_http.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $result = "cURL ERROR: " . curl_errno($ch) . " " . curl_error($ch);
    } else {
        $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch ($returnCode) {
            case 200:
                break;
            default:
                $result = "HTTP ERROR: " . $returnCode;
        }
    }
    curl_close($ch);
    print $result;
}
