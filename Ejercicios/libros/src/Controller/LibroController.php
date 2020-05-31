<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\BDPruebaLibro;
use App\Entity\Libro;
use App\Entity\Editorial;

class LibroController extends AbstractController {

    private $libros;

    public function __construct(BDPruebaLibro $datos) {
        $this->libros = $datos->get();
    }

    /**
     * @Route("/libro/nuevo", name="nuevo_libro")
     */
    public function nuevo(Request $request) {
        $libro = new Libro();

        $formulario = $this->createFormBuilder($libro)
        ->add('isbn', TextType::class, array('label' => 'ISBN'))
        ->add('titulo', TextType::class)
        ->add('autor', TextType::class)
        ->add('paginas', IntegerType::class)
        ->add('editorial', EntityType::class, array('class' => Editorial::class, 'choice_label' => 'nombre'))
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();

        $formulario->handleRequest($request);
        return $this->guardarCambios($formulario);
    }

    /**
     * @Route("/libro/editar/{isbn}", name="editar_libro", requirements={"codigo"="\d+"})
     */
    public function editar(Request $request, $isbn) {
        $libro = $this->getDoctrine()->getRepository(Libro::class)->find($isbn);

        $formulario = $this->createFormBuilder($libro)
        ->add('isbn', TextType::class, array('label' => 'ISBN'))
        ->add('titulo', TextType::class)
        ->add('autor', TextType::class)
        ->add('paginas', IntegerType::class)
        ->add('editorial', EntityType::class, array('class' => Editorial::class, 'choice_label' => 'nombre'))
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();

        $formulario->handleRequest($request);
        return $this->guardarCambios($formulario);
    }

    public function guardarCambios($formulario) {
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $libro = $formulario->getData();

            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($libro);
            $entitymanager->flush();

            return $this->redirectToRoute('inicio');

        } else {
            return $this->render('nuevo.html.twig', array('formulario' => $formulario->createView()));
        }
    }

    /**
     * @Route("/libro/insertar", name="insertar_libro")
     */
    public function insertar() {
        $entitymanager = $this->getDoctrine()->getManager();

        $libro = new Libro();
        $libro->setIsbn("1111AAAA");
        $libro->setTitulo("Libro de prueba");
        $libro->setAutor("Autor de prueba");
        $libro->setPaginas(100);

        $entitymanager->persist($libro);

        try {
            $entitymanager->flush();

            return new Response("Libro insertado correctamente");

        } catch (\Exception $e) {
            return new Response("Este libro ya ha sido insertado");
        }
    }

    /**
     * @Route("/libro/insertarConEditorial", name="insertar_libro_editorial")
     */
    public function insertarConEditorial() {
        $entitymanager = $this->getDoctrine()->getManager();

        $nombreditorial = "Alfaguara";
        $editorial = $this->getDoctrine()->getRepository(Editorial::class)->findOneBy(["nombre" => $nombreditorial]);
        if (!$editorial) {
            $editorial = new Editorial();
            $editorial->setNombre($nombreditorial);

            $entitymanager->persist($editorial);
            $entitymanager->flush();

            echo "La editorial no existía pero se ha creado correctamente. ";
        }

        $libro = new Libro();
        $libro->setIsbn("3333CCCC");
        $libro->setTitulo("Libro de prueba w Editorial");
        $libro->setAutor("Autor de prueba w Editorial");
        $libro->setPaginas(50);
        $libro->setEditorial($editorial);

        $entitymanager->persist($libro);

        try {
            $entitymanager->flush();

            return new Response("Libro insertado correctamente");

        } catch (\Exception $e) {
            return new Response("Este libro ya ha sido insertado");
        }
    }

    /**
     * @Route("/libro/paginas/{paginas}", name="filtrar_pagina")
     */
    function filtrarPaginas($paginas) {
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libros = $repositorio->findByPages($paginas);

        return $this->render('lista_libros.html.twig', array('libros' => $libros));
    }

    /**
     * @Route("/libro/{isbn}", name="buscar_libro")
     */
    function buscar($isbn) {
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libro = $repositorio->findOneBy(["isbn" => $isbn]);

        if ($libro) {
            return $this->render('ficha_libro.html.twig', array('libro' => $libro));

        } else {
            return $this->render('ficha_libro.html.twig', array('libro' => NULL));
        }
    }
}

?>