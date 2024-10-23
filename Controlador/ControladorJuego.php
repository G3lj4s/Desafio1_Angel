<?php
require_once __DIR__ . "/../model/Partida.php";
require_once __DIR__ . "/../model/Territorio.php";
require_once  __DIR__ . "/../BD/ConexionBDPartida.php";
require_once  __DIR__ . "/../BD/ConexionBDUsuario.php";
require_once __DIR__ . "/../Factory/TerritorioFactory.php";

class ControladorJuego{
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
        $numPartidas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(),0);
        if ($numPartidas >= 2) {
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
    
    public static function distribuir(){
        
    }
    public static function mover(){
        
    }
    public static function atacar(){
        
    }
    public static function cambiarTurno(){
        
    }
}