<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * GuionesAsignacion
 *
 * @Table(name="guiones_asignacion", indexes={@Index(name="IDX_A823236749EC05C6", columns={"GUION"}), @Index(name="IDX_A8232367D6A52665", columns={"ESTADO"})})
 * @Entity
 */
class GuionesAsignacion
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
     * @var \Entidades\Expertos\Guiones
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\Guiones")
     * @JoinColumns({
     *   @JoinColumn(name="GUION", referencedColumnName="ID")
     * })
     */
    private $guion;

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
     * @return GuionesAsignacion
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
     * Set guion
     *
     * @param \Entidades\Expertos\Guiones $guion
     *
     * @return GuionesAsignacion
     */
    public function setGuion(\Entidades\Expertos\Guiones $guion = null)
    {
        $this->guion = $guion;
    
        return $this;
    }

    /**
     * Get guion
     *
     * @return \Entidades\Expertos\Guiones
     */
    public function getGuion()
    {
        return $this->guion;
    }

    /**
     * Set estado
     *
     * @param \Entidades\Expertos\Estados $estado
     *
     * @return GuionesAsignacion
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

