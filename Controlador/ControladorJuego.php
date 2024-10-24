<?php
require_once __DIR__ . "/../model/Partida.php";
require_once __DIR__ . "/../model/Territorio.php";
require_once __DIR__ . "/../BD/ConexionBDPartida.php";
require_once __DIR__ . "/../BD/ConexionBDUsuario.php";
require_once __DIR__ . "/../Factory/TerritorioFactory.php";

class ControladorJuego {
    public static function verPartida($datosRecibidos, $idPartida) {
        $datosRecibidos = json_decode($datosRecibidos, true);

        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }

        http_response_code(200);
        echo json_encode(['Partida' => $partida]);
    }

    public static function iniciarPartida($datosRecibidos, $numCasillas, $numTropas) {
        $datosRecibidos = json_decode($datosRecibidos, true);

        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }

        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }

        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }

        $numPartidasCreadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 0);
        $numPartidasIniciada = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 0);
        if ($numPartidasCreadas + $numPartidasIniciada >= 2) {
            http_response_code(403);
            echo json_encode(['message' => 'ya tienes dos partidas abiertas']);
            return;
        }

        if ($numCasillas == 0) {
            $numCasillas = 20; // por defecto
        }

        if ($numTropas == 0) {
            $numTropas = 30; // por defecto
        }

        if ($numCasillas < 2 || $numCasillas > 20 || $numCasillas % 2 != 0 || $numTropas < $numCasillas) {
            http_response_code(400);
            echo json_encode(['message' => 'Número de casillas no válido o superior al número de tropas']);
            return;
        }

        $idPartida = ConexionBDPartida::crearPartida($usuario, $numTropas);
        $territorios = TerritorioFactory::generarTerritorios($numCasillas, $usuario->getId());
        ConexionBDPartida::crearTerritorios($territorios, $idPartida);

        http_response_code(201);
        echo json_encode(['message' => 'partida creada con id: ' . $idPartida]);
    }
    public static function distribuir($datosRecibidos, $idPartida) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
    
        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
    
        if ($partida->getEstado() != 0) {
            http_response_code(409);
            echo json_encode(['message' => 'no puedes distribuir varias veces']);
            return;
        }
    
        if (isset($datosRecibidos['distribucion'])) {
            $numTropas = $partida->contarTropasUsuario($datosRecibidos['distribucion']);
            if (($partida->getNumTropas()/2) != $numTropas) {
                http_response_code(400);
                echo json_encode(['message' => 'el numero de tropas a repartir no es el correcto tienes que repartir ' . ($partida->getNumTropas()/2) . ' y estas repartiendo ' . $numTropas]);
                return;
            }
            $partida->distribuirTropasManual($datosRecibidos['distribucion'], $usuario->getId());
            $partida->distribuirTropasAleatoriamente($usuario->getId());
        } else {
            $partida->distribuirTropasAleatoriamente($usuario->getId());
            $partida->distribuirTropasAleatoriamente(-1);
        }
    
        if ($partida->comprobarCeldasVacias()) {
            http_response_code(400);
            echo json_encode(['message' => 'no puedes dejar celdas sin tropas']);
            return;
        }
    
        $partida->setEstado('1');
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
    
        http_response_code(200);
        echo json_encode(['message' => ['listo para jugar' => $partida]]);
    }
    public static function mover($datosRecibidos, $idPartida) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
    
        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
    
        if ($partida->getEstado() == 0) {
            http_response_code(409);
            echo json_encode(['message' => 'tienes que distribuir las tropas primero']);
            return;
        }
    
        if ($partida->getEstado() != 1) {
            http_response_code(410);
            echo json_encode(['message' => 'la partida ya ha terminado']);
            return;
        }
    
        if ($partida->getUltimoJugador() == $usuario->getId()) {
            http_response_code(409);
            echo json_encode(['message' => 'no es tu turno']);
            return;
        }
    
        if (!isset($datosRecibidos['move'])) {
            http_response_code(400);
            echo json_encode(['message' => 'te falta especificar el movimiento']);
            return;
        }
    
        if ($partida->comprobarNumCelda($datosRecibidos['move'])) {
            http_response_code(404);
            echo json_encode(['message' => 'no existe esa casilla']);
            return;
        }
    
        if ($partida->comprobarCercania($datosRecibidos['move'])) {
            http_response_code(400);
            echo json_encode(['message' => 'estas casillas no estan adyacentes']);
            return;
        }
    
        if ($partida->comprobarPropietario($datosRecibidos['move'], $usuario->getId())) {
            http_response_code(403);
            echo json_encode(['message' => 'no te pertenecen esa casilla']);
            return;
        }
    
        if ($partida->comprobarPropietarioCelda($datosRecibidos['move'])) {
            http_response_code(403);
            echo json_encode(['message' => 'una de las casillas no es tuya']);
            return;
        }
    
        if ($partida->comprobarCantidades($datosRecibidos['move'])) {
            http_response_code(400);
            echo json_encode(['message' => 'error en la cantidad de tropas']);
            return;
        }
    
        $partida->realizarMovimiento($datosRecibidos['move']);
        $partida->setUltimoJugador($usuario->getId());
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
    
        http_response_code(200);
        echo json_encode(['message' => 'el movimiento se ha realizado con exito']);
    }    
    public static function atacar($datosRecibidos, $idPartida) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
    
        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
    
        if ($partida->getEstado() == 0) {
            http_response_code(409);
            echo json_encode(['message' => 'tienes que distribuir las tropas primero']);
            return;
        }
    
        if ($partida->getEstado() != 1) {
            http_response_code(410);
            echo json_encode(['message' => 'la partida ya ha terminado']);
            return;
        }
    
        if ($partida->getUltimoJugador() == $usuario->getId()) {
            http_response_code(409);
            echo json_encode(['message' => 'no es tu turno']);
            return;
        }
    
        if (!isset($datosRecibidos['attack'])) {
            http_response_code(400);
            echo json_encode(['message' => 'te falta especificar el ataque']);
            return;
        }
    
        if ($partida->comprobarNumCelda($datosRecibidos['attack'])) {
            http_response_code(404);
            echo json_encode(['message' => 'no existe esa casilla']);
            return;
        }
    
        if ($partida->comprobarCercania($datosRecibidos['attack'])) {
            http_response_code(400);
            echo json_encode(['message' => 'estas casillas no estan adyacentes']);
            return;
        }
    
        if ($partida->comprobarPropietario($datosRecibidos['attack'], $usuario->getId())) {
            http_response_code(403);
            echo json_encode(['message' => 'no te pertenecen esa casilla']);
            return;
        }
    
        if (!$partida->comprobarPropietarioCelda($datosRecibidos['attack'])) {
            http_response_code(400);
            echo json_encode(['message' => 'no puedes atacarte a ti mismo']);
            return;
        }
    
        if ($partida->comprobarCantidades($datosRecibidos['attack'])) {
            http_response_code(400);
            echo json_encode(['message' => 'error en la cantidad de tropas']);
            return;
        }
    
        $respuesta = ['message' => 'has perdido el ataque'];
        if ($partida->realizarAtaque($datosRecibidos['attack'])) {
            $respuesta = ['message' => 'has ganado el ataque'];
            if ($partida->comprobarGanador()) {
                $respuesta['message'] .= ' y la partida ha terminado';
                $respuesta['ganador'] = $usuario->getName();
            }
        }
    
        echo json_encode($respuesta);
    
        $partida->setUltimoJugador($usuario->getId());
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
    }
    public static function nuevasTropas($datosRecibidos,$idPartida){
        $datosRecibidos = json_decode($datosRecibidos, true);

        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
        if ($usuario == null) {
            http_response_code(404);
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            http_response_code(401);
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
    
        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
    
        $partida->colocarEjercitosAleatoriamente($usuario->getId());
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
    
        http_response_code(200);
        echo json_encode(['message' => ['tus nuevas tropas estan listas' => $partida]]);
    }
    
    public static function cambiarTurno($idPartida) {
        $idMaquina = -1;
        $partida = ConexionBDPartida::obtenerPartida($idPartida);
        if ($partida == null) {
            http_response_code(404);
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
    
        if ($partida->getEstado() == 0) {
            http_response_code(409);
            echo json_encode(['message' => 'tienes que distribuir las tropas primero']);
            return;
        }
    
        if ($partida->getEstado() != 1) {
            http_response_code(410);
            echo json_encode(['message' => 'la partida ya ha terminado']);
            return;
        }
    
        if ($partida->getUltimoJugador() == $idMaquina) {
            http_response_code(409);
            echo json_encode(['message' => 'no es el turno de la máquina']);
            return;
        }
    
        $partida->setUltimoJugador($idMaquina);
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
    
        http_response_code(200);
        echo json_encode(['message' => 'la maquina ha actuado']);
        //aun no hace ninguna accion la maquina solo pasa su turno
    }
    
    
}