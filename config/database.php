<?php

$conn = pg_connect("
    host=dpg-d7qldeho3t8c73clvfcg-a.oregon-postgres.render.com
    port=5432
    dbname=db_servigas
    user=db_servigas_user
    password=suagg5599
");

if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Error conexión DB"
    ]);
    exit;
}