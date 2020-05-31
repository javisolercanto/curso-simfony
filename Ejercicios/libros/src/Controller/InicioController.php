<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BDPruebaLibro;
use App\Entity\Libro;

class InicioController extends AbstractController    {
 
    private $libros;

    public function __construct(BDPruebaLibro $datos) {
        $this->libros = $datos->get();
    }

    /**
     * @Route("/", name="inicio")
     */
    public function inicio() {
        return $this->render('inicio.html.twig', array(
            'libros' => $this->getDoctrine()->getRepository(Libro::class)->findAll()
        ));
    }
}

?>