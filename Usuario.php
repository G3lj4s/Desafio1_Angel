<?php

class Usuario {
    private $id;
    private $name;
    private $email;
    private $password;
    private $admin;

    public function __construct($id,$name,$email,$password,$admin = false) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
    }

}