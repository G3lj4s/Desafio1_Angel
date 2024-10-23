<?php
    require_once  __DIR__ . "/../model/Usuario.php";
    require_once  __DIR__ . "/../Mail/ConexionMail.php";
class ControladorUsuario{
    public static function mostrarPerfil($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($usuario != null) {
                if ($usuario->getPassword() == $datosRecibidos['password']) {
                    echo json_encode($usuario);
                } else {
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
    public static function cambiarPassword($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $usuario = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($usuario != null) {
                if ($usuario->getPassword() == $datosRecibidos['password']) {
                    $usuario->generatePassword();
                    ConexionBDUsuario::actualizarUsuario($usuario);
                    $resultado = ConexionMail::mandarNuevaPassword($usuario);
                    if ($resultado) {
                        echo json_encode(['message' => 'revise su correo para ver su nueva password']);
                    }else{
                        echo json_encode(['message' => $resultado]);
                    }
                } else {
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
    public static function mostrarStats($datosRecibidos){
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
        $partidasEmpezadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 0);
        $partidasGanadas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 1);
        $partidasPedidas = ConexionBDPartida::numPartidasPorEstado($usuario->getId(), 2);
        echo json_encode([
            'Empezadas' => $partidasEmpezadas,
            'Ganadas'=> $partidasGanadas,
            'Perdidas'=> $partidasPedidas
        ]);
    }
}