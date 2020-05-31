<?php

namespace App\Service;

class BDPrueba {

    private $contactos = array (
        array("codigo" => 1, "nombre" => "Juan Pérez", "telefono" => "966112233", "email" => "juan@mail.com"),
        array("codigo" => 2, "nombre" => "Ana López", "telefono" => "966112233", "email" => "ana@mail.com"),
        array("codigo" => 3, "nombre" => "Mario Montero", "telefono" => "966112233", "email" => "mario@mail.com"),
        array("codigo" => 4, "nombre" => "Laura Martínez", "telefono" => "966112233", "email" => "laura@mail.com"),
        array("codigo" => 5, "nombre" => "Nora Jover", "telefono" => "966112233", "email" => "nora@mail.com")
    );

    public function get() {
        return $this->contactos;
    }
}   

?>