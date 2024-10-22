<?php
require_once __DIR__ . "/../model/Usuario.php";
require_once __DIR__ . '/../Config.php';
class ConexionBD{
    public static function obtenerConexionBD() {
        try {
            return new mysqli('localhost', $GLOBALS['BDuser'], $GLOBALS['BDpassword'], $GLOBALS['BDname']);
        } catch (Exception $e) {
            return null;
        }
    }
    

}