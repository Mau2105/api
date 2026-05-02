<?php

class AuthController {

    public function login() {

        require_once __DIR__ . '/../config/database.php';

        $data = json_decode(file_get_contents("php://input"), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos"
            ]);
            return;
        }

        $query = "SELECT * FROM usuarios WHERE email = $1";
        $result = pg_query_params($conn, $query, [$email]);

        if (!$result || pg_num_rows($result) === 0) {
            echo json_encode([
                "success" => false,
                "message" => "Usuario no encontrado"
            ]);
            return;
        }

        $user = pg_fetch_assoc($result);

        if ($password !== $user['password']) {
            echo json_encode([
                "success" => false,
                "message" => "Credenciales incorrectas"
            ]);
            return;
        }

        echo json_encode([
            "success" => true,
            "user" => [
                "id" => (int)$user["id_usuario"],
                "nombre" => $user["nombre"],
                "rol" => $user["rol"]
            ],
            "token" => bin2hex(random_bytes(16))
        ]);
    }
}