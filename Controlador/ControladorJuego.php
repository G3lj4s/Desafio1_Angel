<?php
require_once __DIR__ . "/../model/Partida.php";
require_once __DIR__ . "/../model/Territorio.php";
require_once  __DIR__ . "/../BD/ConexionBDPartida.php";
require_once  __DIR__ . "/../BD/ConexionBDUsuario.php";
require_once __DIR__ . "/../Factory/TerritorioFactory.php";

class ControladorJuego{
    public static function verPartida($datosRecibidos, $idPartida){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($usuario == null) {
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
        $partida = ConexionBDPartida::obtenerPartida($idPartida, $usuario->getId());
        if ($partida == null) {
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
        
        echo json_encode(['Partida' => $partida]);
    }
    public static function iniciarPartida($datosRecibidos, $numCasillas, $numTropas) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($usuario == null) {
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
        $numPartidasCreadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(),0);
        $numPartidasIniciada = ConexionBDPartida::numPartidasPorEstado($usuario->getId(),0);
        if ($numPartidasCreadas+$numPartidasIniciada >= 2) {
            echo json_encode(['message' => 'ya tienes dos partidas abiertas']);
            return;
        }
    
        if ($numCasillas == 0) {
            $numCasillas = 20; //por defecto
        }
    
        if ($numTropas == 0) {
            $numTropas = 30; // por defecto
        }
    
        if ($numCasillas < 2 || $numCasillas > 20 || $numCasillas % 2 != 0 || $numTropas < $numCasillas) {
            echo json_encode(['message' => 'Número de casillas no válido o superior al número de tropas']);
            return;
        }
    
        $idPartida = ConexionBDPartida::crearPartida($usuario, $numTropas);
        $territorios = TerritorioFactory::generarTerritorios($numCasillas);
        ConexionBDPartida::crearTerritorios($territorios, $idPartida);
    
        echo json_encode(['message' => 'partida creada con id: ' . $idPartida]);
    }
    
    public static function distribuir($datosRecibidos, $idPartida){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($usuario == null) {
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
        $partida = ConexionBDPartida::obtenerPartida($idPartida, $usuario->getId());
        if ($partida == null) {
            echo json_encode(['message' => 'error al encontrar la partida']);
            return;
        }
        if ($partida->getEstado() != 0) {
            echo json_encode(['message' => 'no puedes distribuir varias veces']);
            return;
        }
        if (isset($datosRecibidos['distribucion'])) {
            $numTropas = $partida->contarTropasUsuario($datosRecibidos['distribucion']);
            if (($partida->getNumTropas()/2) != $numTropas) {
                echo json_encode(['message' => 'el numero de tropas a repartir no es el correcto tienes que repartir '.($partida->getNumTropas()/2).' y estas repartirendo '.$numTropas]);
                return;
            }
            $partida->distribuirTropasManual($datosRecibidos['distribucion'],'U');
            $partida->distribuirTropasAleatoriamente('M');
        }else{
            $partida->distribuirTropasAleatoriamente('U');
            $partida->distribuirTropasAleatoriamente('M');
        }
        $partida->setEstado('1');
        ConexionBDPartida::actualizarTerritorios($partida->getTerritorios());
        ConexionBDPartida::actualizarPartida($partida);
        echo json_encode(['message' => ['listo para jugar' => $partida]]);
    }
    public static function mover($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (!isset($datosRecibidos['email']) || !isset($datosRecibidos['password'])) {
            echo json_encode(['message' => 'datos incompletos']);
            return;
        }
    
        $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
    
        if ($usuario == null) {
            echo json_encode(['message' => 'este usuario no existe en el sistema']);
            return;
        }
    
        if ($usuario->getPassword() != $datosRecibidos['password']) {
            echo json_encode(['message' => 'la password del usuario no es correcta']);
            return;
        }
    }
    public static function atacar($datosRecibidos){
        
    }
    public static function cambiarTurno($datosRecibidos){
        
    }
}