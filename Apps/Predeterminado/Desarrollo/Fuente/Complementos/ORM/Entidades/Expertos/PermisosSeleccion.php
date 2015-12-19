<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosSeleccion
 *
 * @Table(name="PERMISOS_SELECCION", indexes={@Index(name="IDX_6A6AFAE4C23F5584", columns={"PERMISO"}), @Index(name="IDX_6A6AFAE41C0908B0", columns={"MODULO"}), @Index(name="IDX_6A6AFAE4E290B09D", columns={"ACCESO"})})
 * @Entity
 */
class PermisosSeleccion
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
     * @var \Entidades\Expertos\PermisosModulos
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\PermisosModulos")
     * @JoinColumns({
     *   @JoinColumn(name="MODULO", referencedColumnName="ID")
     * })
     */
    private $modulo;

    /**
     * @var \Entidades\Expertos\Permisos
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\Permisos")
     * @JoinColumns({
     *   @JoinColumn(name="PERMISO", referencedColumnName="ID")
     * })
     */
    private $permiso;

    /**
     * @var \Entidades\Expertos\PermisosAcceso
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\PermisosAcceso")
     * @JoinColumns({
     *   @JoinColumn(name="ACCESO", referencedColumnName="ID")
     * })
     */
    private $acceso;


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
     * Set modulo
     *
     * @param \Entidades\Expertos\PermisosModulos $modulo
     *
     * @return PermisosSeleccion
     */
    public function setModulo(\Entidades\Expertos\PermisosModulos $modulo = null)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo
     *
     * @return \Entidades\Expertos\PermisosModulos
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Set permiso
     *
     * @param \Entidades\Expertos\Permisos $permiso
     *
     * @return PermisosSeleccion
     */
    public function setPermiso(\Entidades\Expertos\Permisos $permiso = null)
    {
        $this->permiso = $permiso;

        return $this;
    }

    /**
     * Get permiso
     *
     * @return \Entidades\Expertos\Permisos
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Set acceso
     *
     * @param \Entidades\Expertos\PermisosAcceso $acceso
     *
     * @return PermisosSeleccion
     */
    public function setAcceso(\Entidades\Expertos\PermisosAcceso $acceso = null)
    {
        $this->acceso = $acceso;

        return $this;
    }

    /**
     * Get acceso
     *
     * @return \Entidades\Expertos\PermisosAcceso
     */
    public function getAcceso()
    {
        return $this->acceso;
    }
}

