<?php

// accès cors-origin
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// vérification de la methode http
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // chargement de la base de données
    require('./config/database.php');

    // initialisation des variables
    $nom = htmlentities(strtoupper($_POST['nom']));
    $prenom = htmlentities(strtoupper($_POST['prenom']));
    $date_naissance = htmlentities($_POST['date_naissance']);
    $lieu_naissance = htmlentities(strtoupper($_POST['lieu_naissance']));
    $domicile = htmlentities(strtoupper($_POST['domicile']));
    $contact = htmlentities($_POST['contact']);
    $categorie = htmlentities($_POST['categorie']);
    $fonction = htmlentities($_POST['fonction']);
    $num_permis = htmlentities(strtoupper($_POST['num_permis']));
    $num_cni = htmlentities(strtoupper($_POST['num_cni']));
    $sang = htmlentities(strtoupper($_POST['sang']));

    // requête pdo préparé
    $data = [
        ":nom" => $nom,
        ":prenom" => $prenom,
        ":date_naissance" => $date_naissance,
        ":lieu_naissance" => $lieu_naissance,
        ":domicile" => $domicile,
        ":contact" => $contact,
        ":categorie" => $categorie,
        ":fonction" => $fonction,
        ":num_permis" => $num_permis,
        ":num_cni" => $num_cni,
        ":sang" => $sang
    ];

    $sql = "INSERT INTO syndicat_auth (nom, prenom, date_naissance, lieu_naissance, domicile, contact, categorie, fonction, num_permis, num_cni, sang) VALUES (:nom, :prenom, :date_naissance, :lieu_naissance, :domicile, :contact, :categorie, :fonction, :num_permis, :num_cni, :sang)";
    $statements = $pdo -> prepare($sql);
    $exe = $statements -> execute($data);

    // on vérifie si les données ont bien été inséré
    if($exe){
        // si exe == true
        $syndicatId = $pdo -> lastInsertId();
        $response = [
            "status" => true,
            "syndicat_id" => $syndicatId
        ];
    }else{
        $response = [
            "status" => false,
            "message" => "Une erreur est survenue lors de l'insertion des données"
        ];
    }

    // on encode la réponse au format json
    header('Content-Type: application/json');
    echo json_encode($response);
}