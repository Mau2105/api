<?php
require_once __DIR__ . '/../config.php';

class TrabajadorController {

    public function listar() {
        $user = verificarToken();

        if ($user['rol'] !== 'admin') {
            json_response(false, "Acceso denegado", null, 403);
        }

        global $conexion;

        $sql = "SELECT t.id_trabajador, u.nombre, u.email 
                FROM trabajadores t 
                JOIN usuarios u ON t.id_usuario = u.id_usuario 
                ORDER BY u.nombre";

        $result = $conexion->query($sql);
        $trabajadores = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        json_response(true, "Lista de trabajadores obtenida", $trabajadores);
    }
}
?>