<?php

	namespace Modelo\Modulo\Permisos;
	use \Neural\BD\Conexion;
	
	class Index {
		
		private $entidad = false;
		
		/**
		 * Index::__construct()
		 * 
		 * Genera la variable del entity manager de doctrine
		 * para el proceso de base de datos
		 * 
		 * @return void
		 */
		function __construct() {
			$conexion = new Conexion(APPBD, APP);
			$this->entidad = $conexion->doctrineORM();
		}
		
		/**
		 * Index::consultarPermisos()
		 *
		 * Genera la consulta del listado de permisos 
		 * @return object
		 */
		public function consultarPermisos() {
			return $this->entidad->getRepository('\Entidades\Expertos\Permisos')->findBy(array('estado' => array(1, 2)), array('estado' => 'ASC', 'nombre' => 'ASC'));
		}
		
		/**
		 * Index::consultarModulos()
		 * 
		 * Genera la consulta del listado de modulos
		 * @return object
		 */
		public function consultarModulos() {
			return $this->entidad->getRepository('\Entidades\Expertos\PermisosModulos')->findBy(array('estado' => array(1, 2)), array('estado' => 'ASC', 'nombre' => 'ASC'));
		}
		
		/**
		 * Index::consultarAccesos()
		 * 
		 * Genera la consulta del listado de accesos
		 * @return object
		 */
		public function consultarAccesos() {
			return $this->entidad->getRepository('\Entidades\Expertos\PermisosAcceso')->findBy(array(), array('nombre' => 'ASC'));
		}
	}