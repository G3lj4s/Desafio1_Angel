<?php
    require_once  __DIR__ . "/../model/Usuario.php";
    require_once  __DIR__ . "/../Mail/ConexionMail.php";
class ControladorUsuario{
    public static function mostrarPerfil($datosRecibidos){
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
        http_response_code(200);
        echo json_encode($usuario);
    }
    
    public static function cambiarPassword($datosRecibidos){
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
        
        $usuario->generatePassword();
        ConexionBDUsuario::actualizarUsuario($usuario);
        $resultado = ConexionMail::mandarNuevaPassword($usuario);
        http_response_code(200);
        echo json_encode(['message' => 'la contraseña ha sido cambiada y enviada por correo']);
    }
    
    public static function mostrarStats($datosRecibidos){
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
    
        $partidasEmpezadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 1); 
        $partidasGanadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 2);
        $partidasPedidas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 3);
        
        http_response_code(200);
        echo json_encode([
            'Empezadas' => $partidasEmpezadas,
            'Ganadas' => $partidasGanadas,
            'Perdidas' => $partidasPedidas
        ]);
    }
    
}