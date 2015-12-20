<?php
	
	namespace Modelo\Modulo\Usuarios;
	use \Entidades\Expertos\Usuarios;
	use \Neural\BD\Conexion;
	
	class Nuevo {
		
		private $entidad = false;
		
		/**
		 * Nuevo::__construct()
		 * 
		 * Genera el proceso del ORM para la conexion
		 * a la base de datos
		 * @return void
		 */
		function __construct() {
			$conexion = new Conexion(APPBD, APP);
			$this->entidad = $conexion->doctrineORM();
		}
		
		/**
		 * Nuevo::consultaEmpresas()
		 * 
		 * Genera la consulta de las empresas que se encuentran registradas
		 * en la base de datos
		 * 
		 * @return object
		 */
		public function consultaEmpresas() {
			return $this->entidad->getRepository('\Entidades\Expertos\UsuariosEmpresa')->findBy(array('estado' => 1));
		}
		
		/**
		 * Nuevo::consultaCargos()
		 * 
		 * Genera la consulta de los cargos correspondientes
		 * 
		 * @return object
		 */
		public function consultaCargos() {
			return $this->entidad->getRepository('\Entidades\Expertos\UsuariosCargo')->findAll();
		}
		
		/**
		 * Nuevo::consultaPermisos()
		 * 
		 * Genera la consulta de los permisos disponibles
		 * 
		 * @return object
		 */
		public function consultaPermisos() {
			return $this->entidad->getRepository('\Entidades\Expertos\Permisos')->findBy(array('estado' => 1), array('nombre' => 'ASC'));
		}
		
		/**
		 * Nuevo::consultaExistenciaUsuario()
		 *
		 * Genera la consulta de la existencia del usuario
		 * return true si encuentra un usuario y false en 
		 * caso contrario
		 * 
		 * @param array $array
		 * @return bool
		 */
		public function consultaExistenciaUsuario($array = false) {
			$consulta = $this->entidad->getRepository('\Entidades\Expertos\Usuarios')->findOneBy(array('usuario' => $array['usuario']));
			return (count($consulta) >= 1) ? true : false;
		}
		
		/**
		 * Nuevo::guardarUsuario()
		 * 
		 * Genera el proceso de guardar el usuario correspondiente
		 * 
		 * @param array $array
		 * @return object
		 */
		public function guardarUsuario($array = false) {
			
			$empresa = $this->entidad->getRepository('\Entidades\Expertos\UsuariosEmpresa')->findOneBy(array('id' => $array['empresa']));
			$cargo = $this->entidad->getRepository('\Entidades\Expertos\UsuariosCargo')->findOneBy(array('id' => $array['cargo']));
			$permiso = $this->entidad->getRepository('\Entidades\Expertos\Permisos')->findOneBy(array('id' => $array['permiso']));
			$estado = $this->entidad->getRepository('\Entidades\Expertos\Estados')->findOneBy(array('id' => 1));
			
			$usuario = new Usuarios();
			$usuario->setUsuario($array['usuario']);
			$usuario->setPassword($array['password']);
			$usuario->setNombre($array['nombre']);
			$usuario->setApellido($array['apellido']);
			$usuario->setCedula($array['cedula']);
			$usuario->setUsuarioRr($array['usuarioRr']);
			$usuario->setCorreo($array['correo']);
			$usuario->setEmpresa($empresa);
			$usuario->setCargo($cargo);
			$usuario->setPermiso($permiso);
			$usuario->setEstado($estado);
			
			$this->entidad->persist($usuario);
			$this->entidad->flush();
			return $usuario->getId();
		}
	}