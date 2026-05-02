<?php
require_once __DIR__ . '/../config.php';

class AsignacionController {

    public function asignar() {
        $user = verificarToken();

        if ($user['rol'] !== 'admin') {
            json_response(false, "Solo el administrador puede asignar técnicos", null, 403);
        }

        global $conexion;
        $data = json_decode(file_get_contents('php://input'), true);

        $id_solicitud  = intval($data['id_solicitud'] ?? 0);
        $id_trabajador = intval($data['id_trabajador'] ?? 0);

        if ($id_solicitud <= 0 || $id_trabajador <= 0) {
            json_response(false, "ID de solicitud y trabajador son requeridos", null, 400);
        }

        // Insertar asignación
        $sql1 = "INSERT INTO asignaciones (id_solicitud, id_trabajador) 
                 VALUES ($id_solicitud, $id_trabajador)";
        
        // Actualizar estado de solicitud
        $sql2 = "UPDATE solicitudes SET estado = 'asignada' 
                 WHERE id_solicitud = $id_solicitud";

        if ($conexion->query($sql1) && $conexion->query($sql2)) {
            json_response(true, "Técnico asignado correctamente");
        } else {
            json_response(false, "Error al asignar el técnico", null, 500);
        }
    }
}
?>