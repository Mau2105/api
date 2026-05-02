<?php
require_once __DIR__ . '/../config.php';

class AuthController {
    
    public function login() {
        global $conexion;
        $input = json_decode(file_get_contents('php://input'), true);

        $email = $conexion->real_escape_string(trim($input['email'] ?? ''));
        $password = trim($input['password'] ?? '');

        if (empty($email) || empty($password)) {
            json_response(false, "Email y contraseña son obligatorios", null, 400);
        }

        $sql = "SELECT id_usuario, nombre, rol FROM usuarios 
                WHERE email = '$email' AND password = '$password' LIMIT 1";

        $result = $conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $payload = [
                'id'     => (int)$user['id_usuario'],
                'rol'    => $user['rol'],
                'nombre' => $user['nombre'],
                'exp'    => time() + (60 * 60 * 24 * 7)   // 7 días de validez
            ];

            require_once __DIR__ . '/../jwt.php';
            $token = JWT::encode($payload);

            json_response(true, "Inicio de sesión exitoso", [
                'token' => $token,
                'user'  => [
                    'id'     => (int)$user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'rol'    => $user['rol']
                ]
            ]);
        } else {
            json_response(false, "Email o contraseña incorrectos", null, 401);
        }
    }
}
?>