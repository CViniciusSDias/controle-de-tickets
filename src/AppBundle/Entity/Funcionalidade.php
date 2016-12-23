<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Funcionalidade
 *
 * @ORM\Table(name="funcionalidade")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FuncionalidadeRepository")
 */
class Funcionalidade
{
    use ModelTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=128, unique=true)
     */
    private $nome;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario")
     * @ORM\JoinTable(
     *     name="funcionalidades_usuarios",
     *     joinColumns={@ORM\JoinColumn(name="funcionalidade_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")}
     * )
     */
    private $usuarios;

    /**
     * @ORM\ManyToMany(targetEntity="Grupo")
     * @ORM\JoinTable(
     *     name="funcionalidades_grupos",
     *     joinColumns={@ORM\JoinColumn(name="funcionalidade_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="grupo_id", referencedColumnName="id")}
     * )
     */
    private $grupos;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->grupos = new ArrayCollection();
    }
}

