<?php

class Partida{
    public $id;
    public $territorios;
    public $estado;
    public $numTropas;
    public $ultimoJugador;

    public function __construct($id, $territorios, $estado, $numTropas ,$ultimoJugador) {
        $this->id = $id;
        $this->territorios = $territorios;
        $this->estado = $estado;
        $this->numTropas =  $numTropas;
        $this->ultimoJugador = $ultimoJugador;
    }
    public function distribuirTropasManual($distribuciones, $usuario) {
        foreach ($distribuciones as $posicion => $numTropas) {
            foreach ($this->territorios as $territorio) {
                if ($territorio->getPosicion() == $posicion) {
                    if ($territorio->getPropietario() == $usuario) {
                        $territorio->setNumTropas($numTropas);
                    }
                }
            }
        }
    }
    public function distribuirTropasAleatoriamente($usuario) {
        $tropasARepartir = intval($this->numTropas / 2);
        $territoriosUsuario = [];

        foreach ($this->territorios as $territorio) {
            if ($territorio->getPropietario() == $usuario) {
                $territoriosUsuario[] = $territorio;
            }
        }

        foreach ($territoriosUsuario as $territorio) {
            $territorio->setNumTropas(1);
            $tropasARepartir--;
        }

        while ($tropasARepartir > 0) {
            $indiceAleatorio = array_rand($territoriosUsuario);
            $territorioAleatorio = $territoriosUsuario[$indiceAleatorio];
            $territorioAleatorio->setNumTropas($territorioAleatorio->getNumTropas() + 1);
            $tropasARepartir--;
        }
    }
    public function contarTropasUsuario($distribuciones){
        $comprobacion = 0;
        foreach ($distribuciones as $posicion => $numTropas) {
                $comprobacion += $numTropas;
            }
            return $comprobacion;
    }
    public function comprobarCeldasVacias(){
        $comprobacion = false;
        foreach ($this->getTerritorios() as $territorio) {
                if ($territorio->getNumTropas() == 0) {
                    $comprobacion = true;
                }
            }
        return $comprobacion;
    }
    public function comprobarNumCelda($movimiento){
        $comprobacion = false;
        if ($movimiento['origen']-1 <0 || $movimiento['origen']-1 >=count($this->getTerritorios())) {
            $comprobacion = true;
        }
        if ($movimiento['destino']-1 <0 || $movimiento['destino']-1 >=count($this->getTerritorios())) {
            $comprobacion = true;
        }
        return $comprobacion;
    }
    public function comprobarCercania($movimiento){
        $distancia = $movimiento['origen'] - $movimiento['destino'];
        $adyacente = false;
        if ($distancia > 1 || $distancia < -1) {
            $adyacente = true;
        }
        return $adyacente;
    }
    public function comprobarPropietario($movimiento, $idUsuario){
        $comprobacion = false;
        $propietarioOrigen = $this->getTerritorios()[$movimiento['origen']-1]->getPropietario();
        $propietarioDestino = $this->getTerritorios()[$movimiento['destino']-1]->getPropietario();
        if($propietarioOrigen != $propietarioDestino){
            $comprobacion = true;
        };
        if ($propietarioDestino != $idUsuario) {
            $comprobacion = true;
        }
        return $comprobacion;
    }
    public function comprobarCantidades($movimiento){
        $comprobacion = false;
        $cantidad = $movimiento['cantidad'];
        $numTropasOrigen = $this->getTerritorios()[$movimiento['origen']-1]->getNumTropas();
        if($numTropasOrigen - $cantidad < 1){
            $comprobacion = true;
        };
        return $comprobacion;
    }
    public function realizarMovimiento($movimiento){
        $cantidad = $movimiento['cantidad'];

        $origen = $this->getTerritorios()[$movimiento['origen'] - 1];
        $origen->setNumTropas($origen->getNumTropas() - $cantidad);

        $destino = $this->getTerritorios()[$movimiento['destino'] - 1];
        $destino->setNumTropas($destino->getNumTropas() + $cantidad);
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTerritorios() {
        return $this->territorios;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getNumTropas() {
        return $this->numTropas;
    }

    public function getUltimoJugador() {
        return $this->ultimoJugador;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTerritorios($territorios) {
        $this->territorios = $territorios;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setNumTropas($numTropas) {
        $this->numTropas = $numTropas;
    }

    public function setUltimoJugador($ultimoJugador) {
        $this->ultimoJugador = $ultimoJugador;
    }
}