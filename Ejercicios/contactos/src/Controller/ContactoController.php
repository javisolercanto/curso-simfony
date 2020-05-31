<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Service\BDPrueba;
use App\Entity\Contacto;
use App\Entity\Provincia;

class ContactoController extends AbstractController {

    private $contactos;

    public function __construct(BDPrueba $datos) {
        $this->contactos = $datos->get();
    }

    /**
     * @Route("/contacto/{codigo}", name="ficha_contacto", requirements={"codigo"="\d+"})
     */
    public function ficha($codigo = 1) {
        $repositorio = $this->getDoctrine()->getRepository(Contacto::class);
        $contacto = $repositorio->find($codigo);

        if ($contacto) {
            return $this->render('ficha_contacto.html.twig', array(
                'contacto' => $contacto
            ));
        }
        else {
            return $this->render('ficha_contacto.html.twig', array(
                'contacto' => NULL
            ));
        }
    }

    /**
     * @Route("/contacto/nuevo", name="nuevo_contacto")
     */
    public function nuevo(Request $request) {
        $contacto = new Contacto();

        $formulario = $this->createForm(ContactoType::class, $contacto);
        $formulario->handleRequest($request);
        
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $contacto = $formulario->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contacto);
            $entityManager->flush();

            return $this->redirectToRoute('inicio');

        } else {
            return $this->render('nuevo.html.twig', array('formulario' => $formulario->createView()));
        }
    }

    /**
     * @Route("/contacto/editar/{codigo}", name="editar_contacto", requirements={"codigo"="\d+"})
     */
    public function editar(Request $request, $codigo) {
        $contacto = $this->getDoctrine()->getRepository(Contacto::class)->find($codigo);

        $formulario = $this->createForm(ContactoType::class, $contacto);
        $formulario->handleRequest($request);
        
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $contacto = $formulario->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contacto);
            $entityManager->flush();

            return $this->redirectToRoute('inicio');

        } else {
            return $this->render('nuevo.html.twig', array('formulario' => $formulario->createView()));
        }
    }

    /**
     * @Route("/contacto/insertar", name="insertar_contacto")
     */
    public function insertar() {
        $entityManager = $this->getDoctrine()->getManager();

        $contacto = new Contacto();
        $contacto->setNombre("Inserción de prueba");
        $contacto->setTelefono("900110011");
        $contacto->setEmail("insercion.de.prueba@contacto.es");

        $entityManager->persist($contacto);

        try {
            $entityManager->flush(); 

            return new Response("Objeto insertado");
        } catch (\Exception $e) {
            return new Response("Error insertando objeto");
        }
    }

    /**
     * @Route("/contacto/{texto}", name="buscar_contacto")
     */
    public function buscar($texto) {
        $repositorio = $this->getDoctrine()->getRepository(Contacto::class);
        $contactos = $repositorio->findByName($texto);

        return $this->render('lista_contactos.html.twig', array(
            'contactos' => $contactos
        ));
    }
}

?>