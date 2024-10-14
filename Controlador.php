<?php
include_once("ConexionBD.php");
include_once("Usuario.php");
include_once("ConexionMail.php");
class Controlador{
    //rutas /ADMIN
    public static function mostrarUsuarios($datosRecibidos) {
        $datosRecibidos = json_decode($datosRecibidos, true);
    
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $interesado = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        $usuarios = ConexionBD::obtenerUsuarios();
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
            $interesado = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
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
                            ConexionBD::insertarUsuario($usuario);
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
            $interesado = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
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
                            ConexionBD::actualizarUsuario($usuario);
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
            $interesado = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($interesado != null) {
                if ($interesado->getPassword() == $datosRecibidos['password']) {
                    if ($interesado->getAdmin() == 1) {
                        ConexionBD::eliminarUsuario($id);
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
    
    //rutas /USER
    public static function mostrarPerfil($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        
        if (isset($datosRecibidos['email']) && isset($datosRecibidos['password'])) {
            $usuario = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
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
            $usuario = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
            
            if ($usuario != null) {
                if ($usuario->getPassword() == $datosRecibidos['password']) {
                    $usuario->generatePassword();
                    ConexionBD::actualizarUsuario($usuario);
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
}