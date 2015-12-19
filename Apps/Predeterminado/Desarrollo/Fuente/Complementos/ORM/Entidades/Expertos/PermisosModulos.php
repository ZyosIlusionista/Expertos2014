<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosModulos
 *
 * @Table(name="permisos_modulos", indexes={@Index(name="IDX_73D5C4ABD6A52665", columns={"ESTADO"})})
 * @Entity
 */
class PermisosModulos
{
    /**
     * @var integer
     *
     * @Column(name="ID", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="NOMBRE", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \Entidades\Expertos\Estados
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\Estados")
     * @JoinColumns({
     *   @JoinColumn(name="ESTADO", referencedColumnName="ID")
     * })
     */
    private $estado;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return PermisosModulos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set estado
     *
     * @param \Entidades\Expertos\Estados $estado
     *
     * @return PermisosModulos
     */
    public function setEstado(\Entidades\Expertos\Estados $estado = null)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return \Entidades\Expertos\Estados
     */
    public function getEstado()
    {
        return $this->estado;
    }
}

