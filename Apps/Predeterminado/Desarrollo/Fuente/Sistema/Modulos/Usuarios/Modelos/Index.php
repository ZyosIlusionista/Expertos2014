<?php
	
	namespace Modelo\Modulo\Usuarios;
	use \Neural\BD\Conexion;
	
	class Index {
		
		private $entidad = false;
		
		/**
		 * Index::__construct()
		 * 
		 * Genera la variable de conexion correspondiente
		 * para el proceso de consulta en la base de datos
		 * 
		 * @return void
		 */
		function __construct() {
			$conexion = new Conexion(APPBD, APP);
			$this->entidad = $conexion->doctrineORM();
		}
		
		/**
		 * Index::consultaUsuarios()
		 * 
		 * Genera la consulta correspondiente de los usuario los cuales
		 * solo se obtienen los usuarios con estados 1 - ACTIVO
		 * y 2- INACTIVO
		 * 
		 * @return object
		 */
		public function consultaUsuarios() {
			return $this->entidad
				->getRepository('\Entidades\Expertos\Usuarios')
				->findBy(
					array('estado' => array(1, 2)), 
					array('estado' => 'ASC', 'apellido' => 'DESC', 'empresa' => 'ASC')
				);
		}
	}