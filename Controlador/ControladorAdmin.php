<?php

require_once __DIR__ . '/../BD/ConexionBDUsuario.php';
require_once  __DIR__ . "/../model/Usuario.php";

class ControladorAdmin {
    public static function mostrarUsuarios($datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($interesado == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($interesado->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        if ($interesado->getAdmin() != 1) {
            http_response_code(403);
            echo json_encode(['message' => 'este usuario no es administrador']);
            return;
        }

        $usuarios = ConexionBDUsuario::obtenerUsuarios();
        http_response_code(200);
        echo json_encode($usuarios);
    }

    public static function crearUsuarios($datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($interesado == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($interesado->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        if ($interesado->getAdmin() != 1) {
            http_response_code(403);
            echo json_encode(['message' => 'este usuario no es administrador']);
            return;
        }

        if (!isset($datosRecibidos['accion']) || empty($datosRecibidos['accion'])) {
            http_response_code(400);
            echo json_encode(['message' => 'no se han pasado los datos necesarios']);
            return;
        }

        $accion = $datosRecibidos['accion'];
        $usuario = new Usuario(
            $accion["id"],
            $accion["name"],
            $accion["email"],
            $accion["password"],
            $accion["admin"]
        );
        ConexionBDUsuario::insertarUsuario($usuario);
        http_response_code(201);
        echo json_encode(['message' => 'el usuario ha sido creado']);
    }

    public static function modificarUsuario($datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($interesado == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($interesado->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        if ($interesado->getAdmin() != 1) {
            http_response_code(403);
            echo json_encode(['message' => 'este usuario no es administrador']);
            return;
        }

        if (!isset($datosRecibidos['accion']) || empty($datosRecibidos['accion'])) {
            http_response_code(400);
            echo json_encode(['message' => 'no se han pasado los datos necesarios']);
            return;
        }

        $accion = $datosRecibidos['accion'];
        $usuario = new Usuario(
            $accion["id"],
            $accion["name"],
            $accion["email"],
            $accion["password"],
            $accion["admin"]
        );
        ConexionBDUsuario::actualizarUsuario($usuario);
        http_response_code(200);
        echo json_encode(['message' => 'el usuario ha sido modificado']);
    }

    public static function eliminarUsuario($id, $datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($interesado == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($interesado->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        if ($interesado->getAdmin() != 1) {
            http_response_code(403);
            echo json_encode(['message' => 'este usuario no es administrador']);
            return;
        }

        ConexionBDUsuario::eliminarUsuario($id);
        http_response_code(200);
        echo json_encode(['message' => 'el usuario ha sido eliminado']);
    }
}
