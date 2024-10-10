<?php
include_once("ConexionBD.php");
include_once("Usuario.php");
class Controlador{
    //rutas /ADMIN
    public static function mostrarUsuarios(){
        $usuarios = ConexionBD::obtenerUsuarios();
        echo json_encode($usuarios);

    }
    public static function crearUsuarios($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        $usuario = new Usuario($datosRecibidos["id"],$datosRecibidos["name"],$datosRecibidos["email"],$datosRecibidos["password"],$datosRecibidos["admin"]);
        ConexionBD::insertarUsuario($usuario);

    }
    public static function modificarUsuario($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        $usuario = new Usuario($datosRecibidos["id"],$datosRecibidos["name"],$datosRecibidos["email"],$datosRecibidos["password"],$datosRecibidos["admin"]);
        ConexionBD::actualizarUsuario($usuario);
    }
    public static function eliminarUsuario($id){
        ConexionBD::eliminarUsuario($id);
    }
    //rutas /USER
    public static function mostrarPerfil($datosRecibidos){
        $datosRecibidos = json_decode($datosRecibidos, true);
        
        $usuario = ConexionBD::obtenerUsuarioEmail($datosRecibidos['email']);
        echo json_encode($usuario);
    }
}