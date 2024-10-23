<?php

class Territorio {
    public $id;
    public $posicion;
    public $nombre;
    public $propietario;
    public $numTropas;
    public function __construct($id, $posicion, $nombre, $propietario, $numTropas) {
        $this->id = $id;
        $this->posicion = $posicion;
        $this->nombre = $nombre;
        $this->propietario = $propietario;
        $this->numTropas = $numTropas;
    }
    // Getters
    public function getId() {
        return $this->id;
    }
    public function getPosicion(){
        return $this->posicion;
    }
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
    public function setId($id) {
        return $this->id = $id;
    }
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