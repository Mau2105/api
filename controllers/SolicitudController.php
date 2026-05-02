<?php
require_once __DIR__ . '/../config.php';

class SolicitudController {

    public function crear() {
        $user = verificarToken();   // Función del helpers.php

        if ($user['rol'] !== 'cliente') {
            json_response(false, "Solo los clientes pueden crear solicitudes", null, 403);
        }

        global $conexion;
        $data = json_decode(file_get_contents('php://input'), true);

        $id_cliente = $user['id'];
        $direccion = $conexion->real_escape_string($data['direccion'] ?? '');
        $tipo_servicio = $conexion->real_escape_string($data['tipo_servicio'] ?? '');
        $descripcion = $conexion->real_escape_string($data['descripcion'] ?? '');

        $sql = "INSERT INTO solicitudes (id_cliente, direccion, descripcion, tipo_servicio, estado) 
                VALUES ('$id_cliente', '$direccion', '$descripcion', '$tipo_servicio', 'pendiente')";

        if ($conexion->query($sql)) {
            json_response(true, "Solicitud creada correctamente", [
                'id_solicitud' => $conexion->insert_id
            ]);
        } else {
            json_response(false, "Error al crear solicitud: " . $conexion->error, null, 500);
        }
    }

    public function listar() {
        $user = verificarToken();
        global $conexion;

        if ($user['rol'] === 'admin') {
            $sql = "SELECT * FROM solicitudes ORDER BY fecha DESC";
        } elseif ($user['rol'] === 'cliente') {
            $sql = "SELECT * FROM solicitudes WHERE id_cliente = '{$user['id']}' ORDER BY fecha DESC";
        } else { // trabajador
            $sql = "SELECT s.* FROM solicitudes s 
                    INNER JOIN asignaciones a ON s.id_solicitud = a.id_solicitud 
                    WHERE a.id_trabajador = (
                        SELECT id_trabajador FROM trabajadores WHERE id_usuario = '{$user['id']}'
                    ) ORDER BY s.fecha DESC";
        }

        $result = $conexion->query($sql);
        $solicitudes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        json_response(true, "Solicitudes obtenidas correctamente", $solicitudes);
    }
}
?>