<?php

use Taf\TafAuth;

try {
    require './config.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    /* 
        $params
        contient tous les parametres envoyés par la methode POST
     */
    // toutes les actions nécéssitent une authentification
    $auth_reponse = $taf_auth->check_auth();
    if ($auth_reponse["status"] == false && count($params) == 0) {
        echo json_encode($auth_reponse);
        die;
    }

    $reponse["data"]["les_formulaires"] = $taf_config->get_db()->query("select * from formulaire")->fetchAll(PDO::FETCH_ASSOC);
$reponse["data"]["les_type_categories"] = $taf_config->get_db()->query("select * from type_categorie")->fetchAll(PDO::FETCH_ASSOC);


    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
