<?php

namespace App\Service;

class BDPruebaLibro {
    
    private $libros = array (
        array("isbn" => "A111B3", "titulo" => "El juego de Ender", "autor" => "Orson Scott Card", "paginas" => 350),
        array("isbn" => "A111B4", "titulo" => "La tabla de Flandes", "autor" => "Orson Scott Card", "paginas" => 250),
        array("isbn" => "A111B5", "titulo" => "La historia interminable", "autor" => "Orson Scott Card", "paginas" => 850)
    );

    public function get() {
        return $this->libros;
    }
}
?>