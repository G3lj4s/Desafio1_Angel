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
    // Getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getPropietario() {
        return $this->propietario;
    }

    public function getNumTropas() {
        return $this->numTropas;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPropietario($propietario) {
        $this->propietario = $propietario;
    }

    public function setNumTropas($numTropas) {
        $this->numTropas = $numTropas;
    }
}