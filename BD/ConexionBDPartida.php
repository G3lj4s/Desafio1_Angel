<?php

require_once  __DIR__ . "/ConexionBD.php";
class ConexionBDPartida {
    public static function numPartidasPorEstado($id, $estado) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return null;
        }
    
        $query = "SELECT COUNT(*) as total FROM partidas WHERE id_usuario = ? AND estado = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $id, $estado);
        $stmt->execute();
        $resultados = $stmt->get_result();
    
        if ($resultados->num_rows > 0) {
            $data = $resultados->fetch_assoc();
            $conexion->close();
            return $data['total'];
        } else {
            $conexion->close();
            return 0;
        }
    }
    
    public static function crearPartida($usuario,$numTropas){
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
        $query = "INSERT INTO partidas (id_usuario, num_Tropas, estado) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
    

        $idusuario = $usuario->getId();
        $estado = 0;
    
        $stmt->bind_param("iii", $idusuario, $numTropas,$estado);
        $resultado = $stmt->execute();
    
        if ($resultado) {
            $partidaId = $conexion->insert_id;
        } else {
            $partidaId = false; 
        }
    
        $conexion->close();
        return $partidaId;
    }
    public static function crearTerritorios($territorios, $idPartida) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
    
        $query = "INSERT INTO territorios (nombre, propietario, id_partida, tropas) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
    
    
        foreach ($territorios as $territorio) {
            $nombre = $territorio->getNombre();
            $propietario = $territorio->getPropietario();
            $tropas = 0;
            $stmt->bind_param("ssii", $nombre, $propietario, $idPartida, $tropas);
            $stmt->execute();
        }
    
    
        $conexion->close();
    }
    
}