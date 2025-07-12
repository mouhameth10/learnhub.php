<?php

use Taf\TafAuth;
use Taf\TableQuery;

try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';

    $taf_auth = new TafAuth();
    $auth_reponse = $taf_auth->check_auth();
    if ($auth_reponse["status"] == false) {
        echo json_encode($auth_reponse);
        die;
    }

    $table_query = new TableQuery($table_name);

    if (empty($params)) {
        echo json_encode([
            "status" => false,
            "erreur" => "Parameters required"
        ]);
        exit;
    }

    // Hash le champ `password` avec md5
    if (isset($params['password'])) {
        $params['password'] = md5($params['password']);
    }

    list($query, $stmt) = $table_query->dynamicInsert($params, $taf_config->get_db());

    if ($stmt->execute()) {
        $params["id_$table_name"] = $taf_config->get_db()->lastInsertId();
        echo json_encode([
            "status" => true,
            "data" => $params
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "erreur" => $stmt->errorInfo()
        ]);
    }

} catch (\Throwable $th) {
    echo json_encode([
        "status" => false,
        "erreur" => $th->getMessage()
    ]);
}
