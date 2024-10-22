<?php

class Territorio {
    public $nombre;
    public $propietario;
    public $numTropas;
    public function __construct($nombre,$propietario,$numTropas) {
        $this->nombre = $nombre;
        $this->propietario = $propietario;
        $this->numTropas = $numTropas;
    }
}