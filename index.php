<?php

header("Content-Type:application/json");

require_once __DIR__ . "/Controlador/ControladorAdmin.php";
require_once __DIR__ . "/Controlador/ControladorUsuario.php";
require_once __DIR__ . "/Controlador/ControladorJuego.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametros = explode("/",$paths);
unset($parametros[0]);

$datosRecibidos = file_get_contents("php://input");
$action = strtoupper($parametros[1]);

if ($action == 'ADMIN') {
    $adminAction = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';

    if ($requestMethod == 'GET' && $adminAction == 'USERS' && $datosRecibidos) {
        //GET /admin/users: Consultar lista de usuarios.
        ControladorAdmin::mostrarUsuarios($datosRecibidos);

    } else if ($requestMethod == 'POST' && $adminAction == 'USERS' && $datosRecibidos) {
        //POST /admin/users: Crear un nuevo usuario (con detalles como nombre, rol, etc.)
        ControladorAdmin::crearUsuarios($datosRecibidos);

    }else if ($requestMethod == 'PUT' && $adminAction == 'USERS' && $datosRecibidos) {
        //PUT /admin/users: Modificar detalles de un usuario existente (nombre, contraseña, rol, etc.).
        ControladorAdmin::modificarUsuario($datosRecibidos);

    }else if($requestMethod == 'DELETE' && $adminAction == 'USERS' && isset($parametros[3]) && !empty($parametros[3])){
        //DELETE /admin/users{$id}: Eliminar un usuario por su ID.
        $id = $parametros[3];
        ControladorAdmin::eliminarUsuario($id,$datosRecibidos);

    }else {
        echo json_encode(['error' => 'error en la ruta de admin']);
    }

}else if($action == 'USER'){
    $userAction = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';

    if ($requestMethod == 'GET' && $userAction == 'PROFILE' && $datosRecibidos) {
        //GET /user/profile: Consultar información del perfil del usuario.
        ControladorUsuario::mostrarPerfil($datosRecibidos);

    } else if ($requestMethod == 'PUT' && $userAction == 'PROFILE' && $datosRecibidos) {
        //PUT /user/profile: Modificar detalles del perfil (cambiar contraseña que genere el servido y la pasa al gmail asociado).
        ControladorUsuario::cambiarPassword($datosRecibidos);

    }else if ($requestMethod == 'GET' && $userAction == 'STATS' && $datosRecibidos) {
        //GET /user/stats: Consultar estadísticas del jugador (partidas ganadas, perdidas, etc.).
        ControladorUsuario::mostrarStats($datosRecibidos);

    }else{
        echo json_encode(['error' => 'error en la ruta de user']);
    }
    
}else if($action == 'GAMER'){
    $gameAction = isset($parametros[2]) && !empty($parametros[2]) ? strtoupper($parametros[2]) :'';
    
    if ($requestMethod == 'POST' && $gameAction == 'CREATE' && $datosRecibidos){
        //POST /gamer/create${numerocasillas?}/${numerotropas}: crea una partida personalizada o crea una partida estandar
        $numCasillas = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;
        $numTropas = isset($parametros[4]) && !empty($parametros[4]) ? intval($parametros[4]) : 0;

        ControladorJuego::iniciarPartida($datosRecibidos, $numCasillas, $numTropas);
    }else if ($requestMethod == 'POST' && $gameAction == 'DISTRIBUTE' && $datosRecibidos) {
        //POST /gamer/distribute/${idPartida}: distribulle las tropas como se lo pases o distrubulle las tropas automaticamente
        $idPartida = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;
        
        ControladorJuego::distribuir($datosRecibidos,$idPartida);
    }else if ($requestMethod == 'GET' && $gameAction == 'VIEW' && $datosRecibidos){
        //GET /gamer/view//${idPartida}: muestra la partida
        $idPartida = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;

        ControladorJuego::verPartida($datosRecibidos, $idPartida);
    }else if ($requestMethod == 'POST' && $gameAction == 'MOVE' && $datosRecibidos) {
        //POST /gamer/move/${idPartida}: mueve tus tropas
        $idPartida = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;

        ControladorJuego::mover($datosRecibidos,$idPartida,'U');
    }else if($requestMethod == 'POST' && $gameAction == 'ATTACK' && $datosRecibidos){
        //POST /gamer/attack/${idPartida}: realiza un ataque
        $idPartida = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;

        ControladorJuego::atacar($datosRecibidos);
        echo json_encode(['message' => 'se ataca']);
    }else if($requestMethod == 'GET' && $gameAction == 'FINISH') {
        //POST /gamer/finish//${idPartida} termina el tuno de el jugador y si el oponente es una maquina realiza su turno
        ControladorJuego::cambiarTurno($datosRecibidos);
        $idPartida = isset($parametros[3]) && !empty($parametros[3]) ? intval($parametros[3]) : 0;

        echo json_encode(['message' => 'cambio de turno']);
    }else{
        echo json_encode(['error' => 'error ruta gamer']);
    }
}else{
    echo json_encode(['error' => 'error rutas']);
}