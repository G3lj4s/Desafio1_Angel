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
            $resultados = $resultados->fetch_assoc();
            $conexion->close();
            return $resultados['total'];
        } else {
            $conexion->close();
            return 0;
        }
    }
    public static function obtenerPartida($id, $idUsuario) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return null;
        }

        $query = "SELECT * FROM partidas WHERE id = ? AND id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $resultados = $stmt->get_result();

        if ($resultados->num_rows > 0) {
            $partidaData = $resultados->fetch_assoc();
            $conexion->close();
            $territorios = self::obtenerTerritorios($id);
            $partida = new Partida(
                $partidaData['id'],
                $territorios,
                $partidaData['estado'],
                $partidaData['num_tropas'],
                $partidaData['ultimo_jugador']
            );

            return $partida;
        } else {
            $conexion->close();
            return null;
        }
    }

    private static function obtenerTerritorios($idPartida) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return [];
        }

        $query = "SELECT * FROM territorios WHERE id_partida = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        $resultados = $stmt->get_result();

        $territorios = [];
        while ($territoriosData = $resultados->fetch_assoc()) {
            $territorios[] = new Territorio(
                $territoriosData['id'], 
                $territoriosData['posicion'],
                $territoriosData['nombre'], 
                $territoriosData['propietario'], 
                $territoriosData['tropas']);
        }

        $conexion->close();
        return $territorios;
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
    
        $query = "INSERT INTO territorios (posicion, nombre, propietario, id_partida, tropas) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
    
    
        for ($i = 0; $i < count($territorios); $i++) {
            $territorio = $territorios[$i];
            $posicion = $i+1;
            $nombre = $territorio->getNombre();
            $propietario = $territorio->getPropietario();
            $tropas = 0;
            $stmt->bind_param("ssiii", $posicion,$nombre, $propietario, $idPartida, $tropas);
            $stmt->execute();
        }
        
        $conexion->close();
    }
    public static function actualizarTerritorios($territorios) {
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }

        $query = "UPDATE territorios SET posicion = ?, nombre = ?, propietario = ?, Tropas = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);

        foreach ($territorios as $territorio) {
            $id = $territorio->getId();
            $posicion = $territorio->getPosicion();
            $nombre = $territorio->getNombre();
            $propietario = $territorio->getPropietario();
            $numTropas = $territorio->getNumTropas();

            $stmt->bind_param("sssii", $posicion, $nombre, $propietario, $numTropas, $id);
            $stmt->execute();
            $resultado = $stmt->execute();
        }

        $conexion->close();
        return $resultado;
    }
    public static function actualizarPartida($partida){
        $conexion = ConexionBD::obtenerConexionBD();
        if ($conexion === null) {
            return false;
        }
    
        $query = "UPDATE partidas SET estado = ?, num_Tropas = ?, ultimo_jugador = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $id = $partida->getId();
        $estado = $partida->getEstado();
        $numTropas = $partida->getNumTropas();
        $ultimo = $partida->getUltimoJugador();
    
        $stmt->bind_param("iiii", $estado, $numTropas, $ultimo, $id);
        $resultado = $stmt->execute();
    
        $conexion->close();
        return $resultado;
    }

}