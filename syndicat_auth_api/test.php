<?php
$pdo = new PDO("mysql:host=localhost;dbname=managemoi", 'root', '');

$statements = $pdo->prepare("SELECT * FROM access");
$statements->execute();

set_time_limit(0); // Réinitialiser la limite de temps d'exécution à 0 pour une exécution sans limite

while ($row = $statements->fetch()) {
    $password = $row["password"];
    $contact = $row["pseudo"];
    $id = $row['id'];
    $rcontact = str_replace(' ', '', $contact);
    $rcontact = str_replace('+225', '', $rcontact);
    $rcontact = str_replace('225', '', $rcontact);
    $rcontact = str_replace("µ", "", $rcontact);
    $rcontact = str_replace(' ', '', $rcontact);
    $rcontact = str_replace('@', '', $rcontact);
    $rcontact = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '', $rcontact);
    $rcontact = str_replace('MTNmoney', '', $rcontact);
    $rcontact = strlen($rcontact) === 10 ? $rcontact : '';
    $password = strlen($rcontact) === 10 ? $password : '';

    if (!empty($rcontact) && !empty($password)) {
        $param = array(
            'username' => 'MANAGE MOI',
            'password' => 'CvbaMf7Gu6@c2w2',
            'sender' => 'MANAGE MOI',
            'text' => "https://app.managemoi.online \nContact: " . $contact . "\nMot de passe: " . $password . "\n Plus d'infos: \nhttps://managemoi.me/infos/ \nLes missions c'est le soir.--",
            'type' => 'text',
            'datetime' => date('Y-m-d H:i:s'),
        );
        $recipients = array('225' . $rcontact);
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
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>contact</th>
                <th>password</th>
            </tr>
        </thead>
        <tbody>
            <?php

            ?>

            <?php  ?>
        </tbody>
    </table>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>