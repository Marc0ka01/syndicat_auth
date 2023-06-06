<?php

// accès cors-origin
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD'] == "GET"){

    // chargement de la base de données
    require('./config/database.php');

    $id = $_GET['id_syndicat'];

    $sql = "SELECT * FROM syndicat_auth WHERE id_auth=:id";
    $data= [
        ":id" => $id
    ];
    $statement = $pdo -> prepare($sql);
    $exe = $statement -> execute($data);

    if($exe){
        $fetch = $statement -> fetch();
        if(!empty($fetch)){
            $response = [
                "status" => true
            ];
        }else{
            $response = [
                "status" => false
            ];
        }
        
    }else{
        $response = [
            "status" => false
        ];
    }

    header("Content-Type:application/json");
    echo json_encode($response);
}