<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tarea;

class TareaController extends AbstractController {

    /**
     * @Route("/tareas", name="lista_tareas")
     */
    public function lista_tareas() {
        $resultado = "";
        foreach ($this->getDoctrine()->getRepository(Tarea::class)->findAll() as $tarea)
            $resultado .= "[" . $tarea->getFecha()->format("d/m/Y") . "]\n- " . 
            $tarea->getDescripcion() . "\nPrioridad (" . $tarea->getPrioridad() . ")\n\n";

        return new Response($resultado);
    }
}

?>