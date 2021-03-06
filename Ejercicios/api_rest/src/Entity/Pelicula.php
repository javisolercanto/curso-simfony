<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeliculaRepository")
 */
class Pelicula
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $anyo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getAnyo(): ?int
    {
        return $this->anyo;
    }

    public function setAnyo(int $anyo): self
    {
        $this->anyo = $anyo;

        return $this;
    }
}
