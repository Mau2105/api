<?php
header("Content-Type: application/json");

$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri)[0];
$uri = trim($uri, '/');

$segments = explode('/', $uri);

// quitar "api" si aparece
if ($segments[0] === 'api') {
    array_shift($segments);
}

$controller = $segments[0] ?? '';
$action = $segments[1] ?? '';

switch ($controller) {

    case 'auth':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();

        if ($action === 'login') {
            $ctrl->login();
        } else {
            echo json_encode(["success" => false, "message" => "Ruta no válida"]);
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Endpoint no encontrado"]);
        break;
}