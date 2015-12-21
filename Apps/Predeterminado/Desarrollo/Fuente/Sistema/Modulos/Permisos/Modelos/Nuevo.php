<?php
	
	namespace Modelo\Modulo\Permisos;
	use \Entidades\Expertos\PermisosAcceso;
	use \Neural\BD\Conexion;
	
	class Nuevo {
		
		private $entidad = false;
		
		/**
		 * Nuevo::__construct()
		 *
		 * Se genera la variable correspondiente al entity manager
		 * de doctrine para la consulta a la base de datos
		 *  
		 * @return void
		 */
		function __construct() {
			$conexion = new Conexion(APPBD, APP);
			$this->entidad = $conexion->doctrineORM();
		}
		
		/**
		 * Nuevo::guardarAcceso()
		 *
		 * Genera el proceso para guardar los accesos correspondientes
		 *  
		 * @param array $array
		 * @return integer
		 */
		public function guardarAcceso($array = false) {
			$acceso = new PermisosAcceso();
			
			foreach ($array AS $columna => $valor):
				$acceso->{'set'.$columna}($valor);
			endforeach;
			
			$this->entidad->persist($acceso);
			$this->entidad->flush();
			return $acceso->getId();
		}
	}