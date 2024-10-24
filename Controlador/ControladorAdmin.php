<?php

require_once __DIR__ . '/../BD/ConexionBDUsuario.php';
require_once  __DIR__ . "/../model/Usuario.php";
class ControladorAdmin {
    public static function mostrarUsuarios($datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        $usuarios = ConexionBDUsuario::obtenerUsuarios();
                        echo json_encode($usuarios);
                    } else {
                        echo json_encode(['message' => 'este usuario no es administrador']);
                    }
                }else{
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
    public static function crearUsuarios($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        if (isset($datosRecibidos['accion']) && $datosRecibidos['accion'] != '') {
                            $accion = $datosRecibidos['accion'];
                            $usuario = new Usuario(
                                $accion["id"],
                                $accion["name"],
                                $accion["email"],
                                $accion["password"],
                                $accion["admin"]);
                            ConexionBDUsuario::insertarUsuario($usuario);
                            echo json_encode(['message' => 'el usuario a sido creado']);
                        }else{
                            echo json_encode(['message' => 'no se an pasado los datos necesarios']);
                        }
                    } else {
                        echo json_encode(['message' => 'este usuario no es administrador']);
                    }
                }else{
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
    public static function modificarUsuario($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        if (isset($datosRecibidos['accion']) && $datosRecibidos['accion'] != '') {
                            $accion = $datosRecibidos['accion'];
                            $usuario = new Usuario(
                                $accion["id"],
                                $accion["name"],
                                $accion["email"],
                                $accion["password"],
                                $accion["admin"]);
                            ConexionBDUsuario::actualizarUsuario($usuario);
                            echo json_encode(['message' => 'el usuario a sido modificado']);
                        }else{
                            echo json_encode(['message' => 'no se han pasado los datos necesarios']);
                        }
                    } else {
                        echo json_encode(['message' => 'este usuario no es administrador']);
                    }
                }else{
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
    public static function eliminarUsuario($id, $datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $interesado = ConexionBDUsuario::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        ConexionBDUsuario::eliminarUsuario($id);
                        echo json_encode(['message' => 'el usuario ha sido eliminado']);
                    } else {
                        echo json_encode(['message' => 'este usuario no es administrador']);
                    }
                }else{
                    echo json_encode(['message' => 'la password del usuario no es correcta']);
                }
            } else {
                echo json_encode(['message' => 'este usuario no existe en el sistema']);
            }
        } else {
            echo json_encode(['message' => 'datos incompletos']);
        }
    }
}