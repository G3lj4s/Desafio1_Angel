<?php

class Usuario {
    public $id;
    public $name;
    public $email;
    public $password;
    public $admin;

    public function __construct($id = 0,$name,$email,$password,$admin = false) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
    }

    public function generatePassword() {
        $newPassword = random_int(1000,9999);
        $this->password = $newPassword;
    }

        // Getters
        public function getId() {
            return $this->id;
        }
    
        public function getName() {
            return $this->name;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getPassword() {
            return $this->password;
        }
    
        public function getAdmin() {
            return $this->admin;
        }
    
        // Setters
        public function setId($id) {
            $this->id = $id;
        }
    
        public function setName($name) {
            $this->name = $name;
        }
    
        public function setEmail($email) {
            $this->email = $email;
        }
    
        public function setPassword($password) {
            $this->password = $password;
        }
    
        public function setAdmin($admin) {
            $this->admin = $admin;
        }
}