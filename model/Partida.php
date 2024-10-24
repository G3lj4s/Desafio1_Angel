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
    public function distribuirTropasAleatoriamente($idUsuario) {
        $tropasARepartir = intval($this->numTropas / 2);
        $territoriosUsuario = self::obtenerTerritorioUsuario($idUsuario);


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
    public function obtenerTerritorioUsuario($idUsuario){
        $territoriosUsuario = [];
        foreach ($this->territorios as $territorio) {
            if ($territorio->getPropietario() == $idUsuario) {
                $territoriosUsuario[] = $territorio;
            }
        }
        return $territoriosUsuario;
    }
    public function contarTropasUsuario($distribuciones){
        $comprobacion = 0;
        foreach ($distribuciones as $posicion => $numTropas) {
                $comprobacion += $numTropas;
            }
            return $comprobacion;
    }
    public function comprobarCeldasVacias(){
        foreach ($this->getTerritorios() as $territorio) {
                if ($territorio->getNumTropas() == 0) {
                    return true;
                }
            }
        return false;
    }
    public function comprobarNumCelda($movimiento){
        if ($movimiento['origen']-1 <0 || $movimiento['origen']-1 >=count($this->getTerritorios())) {
            return true;
        }
        if ($movimiento['destino']-1 <0 || $movimiento['destino']-1 >=count($this->getTerritorios())) {
            return true;
        }
        return false;
    }
    public function comprobarCercania($movimiento){
        $distancia = $movimiento['origen'] - $movimiento['destino'];
        if ($distancia > 1 || $distancia < -1) {
            return true;
        }
        return false;
    }
    public function comprobarPropietarioCelda($movimiento){
        $propietarioOrigen = $this->getTerritorios()[$movimiento['origen']-1]->getPropietario();
        $propietarioDestino = $this->getTerritorios()[$movimiento['destino']-1]->getPropietario();
        if($propietarioOrigen != $propietarioDestino){
            return true;
        };
        return false;
    }
    public function comprobarPropietario($movimiento,$idUsuario){
        $propietarioOrigen = $this->getTerritorios()[$movimiento['origen']-1]->getPropietario();
        if($propietarioOrigen != $idUsuario){
            return true;
        };
        return false;
    }
    public function comprobarCantidades($movimiento){
        $cantidad = $movimiento['cantidad'];
        $numTropasOrigen = $this->getTerritorios()[$movimiento['origen']-1]->getNumTropas();
        if ($cantidad <= 0) {
            return true;
        }
        if($numTropasOrigen - $cantidad <= 0){
            return true;
        };
    }
    public function realizarMovimiento($movimiento){
        $cantidad = $movimiento['cantidad'];

        $origen = $this->getTerritorios()[$movimiento['origen'] - 1];
        $origen->setNumTropas($origen->getNumTropas() - $cantidad);

        $destino = $this->getTerritorios()[$movimiento['destino'] - 1];
        $destino->setNumTropas($destino->getNumTropas() + $cantidad);
    }
    public function realizarAtaque($movimiento) {
        $cantidad = $movimiento['cantidad'];
        $origen = $this->getTerritorios()[$movimiento['origen'] - 1];
        $destino = $this->getTerritorios()[$movimiento['destino'] - 1];
        
        $origen->setNumTropas($origen->getNumTropas() - $cantidad);
        $dadosAtacante = $this->lanzarDados(min($cantidad, 3));
        $dadosDefensor = $this->lanzarDados(min($destino->getNumTropas(), 2));
        
        rsort($dadosAtacante);
        rsort($dadosDefensor);
        
        foreach ($dadosDefensor as $i => $dadoDef) {
            if (isset($dadosAtacante[$i])) {
                if ($dadosAtacante[$i] > $dadoDef) {
                    $destino->setNumTropas($destino->getNumTropas() - 1);
                } else {
                    $cantidad--;
                    if ($cantidad <= 0) break;
                    $origen->setNumTropas($origen->getNumTropas() - 1);
                }
            }
        }
        
        if ($destino->getNumTropas() <= 0) {
            $destino->setPropietario($origen->getPropietario());
            $destino->setNumTropas($cantidad);
            return true;
        }
    
        $origen->setNumTropas($origen->getNumTropas() + $cantidad);
        return false;
    }
    
    public function lanzarDados($numDados) {
        $dados = [];
        for ($i = 0; $i < $numDados; $i++) {
            $dados[] = rand(1, 6);
        }
        return $dados;
    }
    public function obtenerEjercitosIniciales($idUsuario) {
        $numTerritorios = count(self::obtenerTerritorioUsuario($idUsuario));
        $ejercitos = max(floor($numTerritorios / 3), 3);
        return $ejercitos;
    }

    public function colocarEjercitosAleatoriamente($idUsuario) {
        $territorios = self::obtenerTerritorioUsuario($idUsuario);
        $ejercitos = self::obtenerEjercitosIniciales($idUsuario);
        while ($ejercitos > 0) {
            $territorio = $territorios[array_rand($territorios)];
            $territorio->setNumTropas(
                $territorio->getNumTropas() + 1
            );
            $ejercitos--;
        }
    }
    public function comprobarGanador() {
        $territorios = $this->getTerritorios();
        
        $propietarioInicial = $territorios[0]->getPropietario();
        foreach ($territorios as $territorio) {
            if ($territorio->getPropietario() != $propietarioInicial) {
                return false;
            }
        }
        if ($propietarioInicial == 0) {
            $this->setEstado(3);
        }else{
            $this->setEstado(2);
        }
        return true;
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