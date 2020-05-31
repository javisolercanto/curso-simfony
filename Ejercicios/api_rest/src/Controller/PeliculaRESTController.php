<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use App\Entity\Pelicula;

/**
 * @Route("/peliculas/api")
 */
class PeliculaRESTController extends FOSRestController {

    /**
     * @Rest\Get("/", name="lista_peliculas")
     */
    public function lista_peliculas() {
        $serializer = $this->get('jms_serializer');

        $repositorio = $this->getDoctrine()->getRepository(Pelicula::class);
        $peliculas = $repositorio->findAll();

        if (count($peliculas) > 0) {
            $respuesta = [
                'ok' => true,
                'peliculas' => $peliculas
            ];

        } else {
            $respuesta = [
                'ok' => false,
                'error' => "No se han encontrado películas"
            ];
        }

        return new Response($serializer->serialize($respuesta, "json"));
    }

    /**
     * @Rest\Get("/{id}", name="busca_pelicula")
     */
    public function busca_pelicula($id) {
        $serializer = $this->get('jms_serializer');

        $repositorio = $this->getDoctrine()->getRepository(Pelicula::class);
        $pelicula = $repositorio->find($id);

        if ($pelicula) {
            $respuesta = [
                'ok' => true,
                'pelicula' => $pelicula
            ];

        } else {
            $respuesta = [
                'ok' => false,
                'error' => "No se ha encontrado la película con id: $id"
            ];
        }

        return new Response($serializer->serialize($respuesta, "json"));
    }

    /**
     * @Rest\Delete("/{id}", name="borra_pelicula")
     */
    public function borra_pelicula($id) {
        $serializer = $this->get('jms_serializer');

        $entityManager = $this->getDoctrine()->getManager();
        $repositorio = $this->getDoctrine()->getRepository(Pelicula::class);
        $pelicula = $repositorio->find($id);

        if ($pelicula) {
            $entityManager->remove($pelicula);
            $entityManager->flush();

            $respuesta = [
                'ok' => true,
                'pelicula' => $pelicula
            ];

        } else {
            $respuesta = [
                'ok' => false,
                'error' => "No se ha encontrado la película"
            ];
        }

        return new Response($serializer->serialize($respuesta, "json"));
    }

    /**
     * @Rest\Post("/", name="nueva_pelicula")
     */
    public function nueva_pelicula(Request $request, ValidatorInterface $validator) {
        $serializer = $this->get('jms_serializer');

        $pelicula = new Pelicula();
        $pelicula->setTitulo($request->get('titulo'));
        $pelicula->setAnyo($request->get('anyo'));

        $errores = $validator->validate($pelicula);
        if (count($errores) == 0) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pelicula);
            $entityManager->flush();

            $respuesta = [
                'ok' => true,
                'pelicula' => $pelicula
            ];

        } else {
            $respuesta = [
                'ok' => false,
                'error' => "Los datos no son correctos"
            ];
        }

        return new Response($serializer->serialize($respuesta, "json"));
    }

    /**
     * @Rest\Put("/{id}", name="modifica_pelicula")
     */
    public function modifica_pelicula($id, Request $request, ValidatorInterface $validator) {
        $serializer = $this->get('jms_serializer');

        $repositorio = $this->getDoctrine()->getRepository(Pelicula::class);
        
        $pelicula = $repositorio->find($id);
        if ($pelicula) {
            $entityManager = $this->getDoctrine()->getManager();

            $pelicula->setTitulo($request->get('titulo'));
            $pelicula->setAnyo($request->get('anyo'));

            $errores = $validator->validate($pelicula);
            if (count($errores) == 0) {
                $entityManager->flush();

                $respuesta = [
                    'ok' => true,
                    'pelicula' => $pelicula
                ];
                
            } else {
                $respuesta = [
                    'ok' => false,
                    'error' => "Los datos no son correctos"
                ];
            }

        } else {
            $respuesta = [
                'ok' => false,
                'error' => "No se ha podido modificar la pelicula"
            ];
        }

        return new Response($serializer->serialize($respuesta, "json"));
    }
}

?>