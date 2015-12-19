<?php

namespace Entidades\Expertos;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @Table(name="USUARIOS", indexes={@Index(name="IDX_C8C51BBED6A52665", columns={"ESTADO"}), @Index(name="IDX_C8C51BBE8792A44A", columns={"EMPRESA"}), @Index(name="IDX_C8C51BBECCBA95C1", columns={"CARGO"}), @Index(name="IDX_C8C51BBEC23F5584", columns={"PERMISO"})})
 * @Entity
 */
class Usuarios
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
     * @Column(name="USUARIO", type="string", length=255, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @Column(name="PASSWORD", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @Column(name="NOMBRE", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @Column(name="APELLIDO", type="string", length=255, nullable=false)
     */
    private $apellido;

    /**
     * @var integer
     *
     * @Column(name="CEDULA", type="bigint", nullable=false)
     */
    private $cedula;

    /**
     * @var string
     *
     * @Column(name="USUARIO_RR", type="string", length=255, nullable=false)
     */
    private $usuarioRr;

    /**
     * @var string
     *
     * @Column(name="CORREO", type="string", length=255, nullable=false)
     */
    private $correo;

    /**
     * @var \Entidades\Expertos\UsuariosEmpresa
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\UsuariosEmpresa")
     * @JoinColumns({
     *   @JoinColumn(name="EMPRESA", referencedColumnName="ID")
     * })
     */
    private $empresa;

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
     * @var \Entidades\Expertos\UsuariosCargo
     *
     * @ManyToOne(targetEntity="Entidades\Expertos\UsuariosCargo")
     * @JoinColumns({
     *   @JoinColumn(name="CARGO", referencedColumnName="ID")
     * })
     */
    private $cargo;

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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Usuarios
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuarios
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuarios
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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Usuarios
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set cedula
     *
     * @param integer $cedula
     *
     * @return Usuarios
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set usuarioRr
     *
     * @param string $usuarioRr
     *
     * @return Usuarios
     */
    public function setUsuarioRr($usuarioRr)
    {
        $this->usuarioRr = $usuarioRr;

        return $this;
    }

    /**
     * Get usuarioRr
     *
     * @return string
     */
    public function getUsuarioRr()
    {
        return $this->usuarioRr;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Usuarios
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set empresa
     *
     * @param \Entidades\Expertos\UsuariosEmpresa $empresa
     *
     * @return Usuarios
     */
    public function setEmpresa(\Entidades\Expertos\UsuariosEmpresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \Entidades\Expertos\UsuariosEmpresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set permiso
     *
     * @param \Entidades\Expertos\Permisos $permiso
     *
     * @return Usuarios
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
     * Set cargo
     *
     * @param \Entidades\Expertos\UsuariosCargo $cargo
     *
     * @return Usuarios
     */
    public function setCargo(\Entidades\Expertos\UsuariosCargo $cargo = null)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return \Entidades\Expertos\UsuariosCargo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set estado
     *
     * @param \Entidades\Expertos\Estados $estado
     *
     * @return Usuarios
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

