<?php
	
	namespace Modelo\Modulo\Usuarios;
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
	}