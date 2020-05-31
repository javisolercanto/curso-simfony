<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class InicioController extends AbstractController {
 
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="inicio")
     */
    public function inicio() {
        $fecha_hora = new \DateTime();
        $this->logger->info("Acceso el " . $fecha_hora->format("d/m/Y H:i:s"));

        return $this->render('inicio.html.twig');
    }
}

?>