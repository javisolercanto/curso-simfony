<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Pelicula;

class PeliculaController extends AbstractController {

    /**
     * @Route("/peliculas", name="lista_peliculas")
     */
    public function lista_peliculas() {
        $repositorio = $this->getDoctrine()->getRepository(Pelicula::class);
        $peliculas = $repositorio->findAll();

        if (count($peliculas) > 0) {
            $resultado = "";
            foreach ($peliculas as $pelicula) 
                $resultado .= $pelicula->getTitulo() . " (" . $pelicula->getAnyo() . ")\n";

            return new Response($resultado);

        } else {
            return new Response("No se han encontrado películas");
        }
    }
}

?>