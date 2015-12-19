<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guiones
 *
 * @Table(name="GUIONES", indexes={@Index(name="IDX_D37F645AD6A52665", columns={"ESTADO"})})
 * @Entity
 */
class Guiones
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
     * @var string
     *
     * @Column(name="PLANTILLA", type="text", nullable=false)
     */
    private $plantilla;

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
     * @return Guiones
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
     * Set plantilla
     *
     * @param string $plantilla
     *
     * @return Guiones
     */
    public function setPlantilla($plantilla)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return string
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Set estado
     *
     * @param \Entidades\Expertos\Estados $estado
     *
     * @return Guiones
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

