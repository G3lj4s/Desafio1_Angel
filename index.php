<?php

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametros = explode("/",$paths);
unset($parametros[0]);

$datosRecibidos = file_get_contents("php://input");
$accion = strtoupper($parametros[1]);

if ($accion == 'ADMIN') {
    $accionesAdmin = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';

    if ($requestMethod == 'GET' && $accionesAdmin == 'USERS') {
        //GET /admin/users: Consultar lista de usuarios.
        print('mustro usuarios');

    } else if ($requestMethod == 'POST' && $accionesAdmin == 'USERS' && !empty($datosRecibidos)) {
        //POST /admin/users: Crear un nuevo usuario (con detalles como nombre, rol, etc.).fv9h
        print('$datosRecibidos');

    }else if ($requestMethod == 'PUT' && $accionesAdmin == 'USERS' && isset($parametros[3]) && !empty($datosRecibidos)) {
        //PUT /admin/users/{id}: Modificar detalles de un usuario existente (nombre, contraseña, rol, etc.).
        print($parametros[3]);

    }else if($requestMethod == 'DELETE' && $accionesAdmin == 'USERS' && isset($parametros[3]) && !empty($parametros[3])){
        //DELETE /admin/users/{id}: Eliminar un usuario por su ID.
        print($parametros[3]);

    }else {
        
        print('erro en la ruta');
    }

}
