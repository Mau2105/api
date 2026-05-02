<?php
require_once 'config.php';
require_once 'jwt.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$uri = str_replace('api/', '', $uri);
$segments = explode('/', $uri);

$controller_name = $segments[0] ?? '';
$action = $segments[1] ?? '';

switch ($controller_name) {
    case 'auth':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($action === 'login') $ctrl->login();
        else json_response(false, "Ruta no válida", null, 404);
        break;

    case 'solicitudes':
        require_once 'controllers/SolicitudController.php';
        $ctrl = new SolicitudController();
        if ($method === 'POST') $ctrl->crear();
        elseif ($method === 'GET') $ctrl->listar();
        else json_response(false, "Método no permitido", null, 405);
        break;

    case 'asignaciones':
        require_once 'controllers/AsignacionController.php';
        $ctrl = new AsignacionController();
        if ($method === 'POST') $ctrl->asignar();
        else json_response(false, "Método no permitido", null, 405);
        break;

    case 'trabajadores':
        require_once 'controllers/TrabajadorController.php';
        $ctrl = new TrabajadorController();
        if ($method === 'GET') $ctrl->listar();
        else json_response(false, "Método no permitido", null, 405);
        break;

    default:
        json_response(false, "Endpoint no encontrado", null, 404);
        break;
}
?>