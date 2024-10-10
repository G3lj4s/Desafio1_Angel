<?php
include("Usuario.php");
class ConexionBD{
    public static function obtenerConexion() {
        try {
            return new mysqli('localhost', 'angel', '1234', 'risk');
        } catch (Exception $e) {
            return null;
        }
    }
    
    public static function obtenerUsuarios() {
        $conexion = self::obtenerConexion();
        if ($conexion === null) {
            return null;
        }
        $query = "SELECT * FROM usuario";
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
        $conexion = self::obtenerConexion();
        if ($conexion === null) {
            return null;
        }

        $query = "SELECT * FROM usuario where id = ?";
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
    public static function insertarUsuario($usuario) {
        $conexion = self::obtenerConexion();
        if ($conexion === null) {
            return false;
        }
        $query = "INSERT INTO usuario (name, email, password, admin) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssi", $usuario->getName(), $usuario->getEmail(), $usuario->getPassword(), $usuario->getAdmin());
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
    
    public static function eliminarUsuario($id) {
        $conexion = self::obtenerConexion();
        if ($conexion === null) {
            return false;
        }
    
        $query = "DELETE FROM usuario WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
    public static function actualizarUsuario($usuario) {
        $conexion = self::obtenerConexion();
        if ($conexion === null) {
            return false;
        }
    
        $query = "UPDATE usuario SET name = ?, email = ?, password = ?, admin = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssii", $usuario->getName(), $usuario->getEmail(), $usuario->getPassword(), $usuario->getAdmin(), $usuario->getId());
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }
    
}