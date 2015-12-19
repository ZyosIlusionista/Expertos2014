<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosAcceso
 *
 * @Table(name="permisos_acceso")
 * @Entity
 */
class PermisosAcceso
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
     * @var boolean
     *
     * @Column(name="LECTURA", type="boolean", nullable=false)
     */
    private $lectura = '0';

    /**
     * @var boolean
     *
     * @Column(name="ESCRITURA", type="boolean", nullable=false)
     */
    private $escritura = '0';

    /**
     * @var boolean
     *
     * @Column(name="ACTUALIZAR", type="boolean", nullable=false)
     */
    private $actualizar = '0';

    /**
     * @var boolean
     *
     * @Column(name="ELIMINAR", type="boolean", nullable=false)
     */
    private $eliminar = '0';


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
     * @return PermisosAcceso
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
     * Set lectura
     *
     * @param boolean $lectura
     *
     * @return PermisosAcceso
     */
    public function setLectura($lectura)
    {
        $this->lectura = $lectura;
    
        return $this;
    }

    /**
     * Get lectura
     *
     * @return boolean
     */
    public function getLectura()
    {
        return $this->lectura;
    }

    /**
     * Set escritura
     *
     * @param boolean $escritura
     *
     * @return PermisosAcceso
     */
    public function setEscritura($escritura)
    {
        $this->escritura = $escritura;
    
        return $this;
    }

    /**
     * Get escritura
     *
     * @return boolean
     */
    public function getEscritura()
    {
        return $this->escritura;
    }

    /**
     * Set actualizar
     *
     * @param boolean $actualizar
     *
     * @return PermisosAcceso
     */
    public function setActualizar($actualizar)
    {
        $this->actualizar = $actualizar;
    
        return $this;
    }

    /**
     * Get actualizar
     *
     * @return boolean
     */
    public function getActualizar()
    {
        return $this->actualizar;
    }

    /**
     * Set eliminar
     *
     * @param boolean $eliminar
     *
     * @return PermisosAcceso
     */
    public function setEliminar($eliminar)
    {
        $this->eliminar = $eliminar;
    
        return $this;
    }

    /**
     * Get eliminar
     *
     * @return boolean
     */
    public function getEliminar()
    {
        return $this->eliminar;
    }
}

