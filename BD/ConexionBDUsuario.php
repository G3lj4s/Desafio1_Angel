<?php

require_once  __DIR__ . "/ConexionBD.php";
class ConexionBDUsuario {
    public static function obtenerUsuarios() {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return null;
        }
        $query = "SELECT * FROM usuarios";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $resultados = $stmt->get_result();
        $usuarios = [];
        if ($resultados->num_rows != 0) {
            while ($userData = $resultados->fetch_assoc()) {
                $usuario = new Usuario($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['admin']);
                $usuarios[] = $usuario;
            }
            $conexion->close();
            return $usuarios;
        } else {
            $conexion->close();
            return null;
        }
    }
    public static function obtenerUsuarioId($id) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return null;
        }

        $query = "SELECT * FROM usuarios where id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultados = $stmt->get_result();

        if ($resultados->num_rows != 0) {
           $userData = $resultados->fetch_assoc();
            $usuario = new Usuario($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['admin']);
            $conexion->close();
            return $usuario;
        } else {
            $conexion->close();
            return null;
        }
    }
    public static function obtenerUsuarioEmail($email) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return null;
        }
    
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultados = $stmt->get_result();
    
        if ($resultados->num_rows != 0) {
            $userData = $resultados->fetch_assoc();
            $usuario = new Usuario($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['admin']);
            $conexion->close();
            return $usuario;
        }
        $conexion->close();
        return null;
    }
    
    public static function insertarUsuario($usuario) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
        $query = "INSERT INTO usuarios (name, email, password, admin) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
    
        $name = $usuario->getName();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $admin = $usuario->getAdmin();
    
        $stmt->bind_param("sssi", $name, $email, $password, $admin);
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
    
    
    public static function eliminarUsuario($id) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
    
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
    public static function actualizarUsuario($usuario) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
    
        $query = "UPDATE usuarios SET name = ?, email = ?, password = ?, admin = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $id = $usuario->getId();
        $name = $usuario->getName();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $admin = $usuario->getAdmin();
    
        $stmt->bind_param("sssii", $name, $email, $password, $admin, $id);
        $resultado = $stmt->execute();
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
}