<?php
// ====================== API CONFIG ======================
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');           // Cambia * por tu dominio cuando publiques la app Flutter
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Incluimos la conexión a la base de datos
include_once __DIR__ . '/../config/conexion.php';

// Incluimos los helpers
require_once __DIR__ . '/helpers.php';
?>