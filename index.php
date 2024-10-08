<?php

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametros = explode("/",$paths);
unset($parametros[0]);

$datosRecibidos = file_get_contents("php://input");
$action = strtoupper($parametros[1]);

if ($action == 'ADMIN') {
    $adminAction = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';

    if ($requestMethod == 'GET' && $adminAction == 'USERS') {
        //GET /admin/users: Consultar lista de usuarios.
        print('mustro usuarios');

    } else if ($requestMethod == 'POST' && $adminAction == 'USERS' && !empty($datosRecibidos)) {
        //POST /admin/users: Crear un nuevo usuario (con detalles como nombre, rol, etc.).fv9h
        print('$datosRecibidos');

    }else if ($requestMethod == 'PUT' && $adminAction == 'USERS' && isset($parametros[3]) && !empty($parametros[3]) && !empty($datosRecibidos)) {
        //PUT /admin/users/{id}: Modificar detalles de un usuario existente (nombre, contraseña, rol, etc.).
        print($parametros[3]);

    }else if($requestMethod == 'DELETE' && $adminAction == 'USERS' && isset($parametros[3]) && !empty($parametros[3])){
        //DELETE /admin/users/{id}: Eliminar un usuario por su ID.
        print($parametros[3]);

    }else {
        
        print('error en la ruta de admin');
    }

}else if($action == 'USER'){
    $userAction = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';
    
    if ($requestMethod == 'GET' && $userAction == 'PROFILE' && !empty($datosRecibidos)) {
        //GET /user/profile: Consultar información del perfil del usuario.
        print('mustro perfil');

    } else if ($requestMethod == 'PUT' && $userAction == 'PROFILE' && !empty($datosRecibidos)) {
        //PUT /user/profile: Modificar detalles del perfil (cambiar contraseña, nombre, etc.).

        print('$te llegara un gmail');

    }else if ($requestMethod == 'GET' && $userAction == 'STATS' && !empty($datosRecibidos)) {
        //GET /user/stats: Consultar estadísticas del jugador (partidas ganadas, perdidas, etc.).
        print("te mostrara un json con las estadisticas");

    }else{
        print('error en la ruta de user');
    }
    
}
