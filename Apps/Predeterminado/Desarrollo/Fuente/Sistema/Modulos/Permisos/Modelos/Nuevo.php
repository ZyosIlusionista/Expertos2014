<?php
	
	namespace Modelo\Modulo\Permisos;
	use \Entidades\Expertos\PermisosAcceso;
	use \Entidades\Expertos\PermisosModulos;
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
		
		/**
		 * Nuevo::existenciaModulo()
		 *
		 * Genera la validacion de la existencia del modulo en la
		 * base de datos y retorna true para existe false no existe
		 *  
		 * @param array $array
		 * @return bool
		 */
		public function existenciaModulo($array = false) {
			$consulta = $this->entidad->getRepository('\Entidades\Expertos\PermisosModulos')->findOneBy($array);
			return (count($consulta) >= 1) ? true : false;
		}
		
		/**
		 * Nuevo::guardarModulo()
		 * 
		 * Genera el proceso para guardar los accesos correspondientes
		 * @param array $array
		 * @return integer
		 */
		public function guardarModulo($array = false) {
			$modulo = new PermisosModulos();
			$modulo->setNombre($array['nombre']);
			$modulo->setEstado($this->entidad->getRepository('\Entidades\Expertos\Estados')->findOneBy(array('id' => 1)));
			
			$this->entidad->persist($modulo);
			$this->entidad->flush();
			return $modulo->getId();
		}
		
		/**
		 * Nuevo::existenciaPermiso()
		 * 
		 * Genera la validacion de la existencia del permiso
		 * dentro de la base de datos
		 * 
		 * @param array $array
		 * @return bool
		 */
		public function existenciaPermiso($array = false) {
			$consulta = $this->entidad->getRepository('\Entidades\Expertos\Permisos')->findOneBy($array);
			return (count($consulta) >= 1) ? true : false;
		}
		
		/**
		 * Nuevo::consultaPermisos()
		 *
		 * Genera la consulta de los modulos activos 
		 * @return object
		 */
		public function consultaModulos() {
			return $this->entidad->getRepository('\Entidades\Expertos\PermisosModulos')->findBy(array('estado' => 1), array('nombre' => 'ASC'));
		}
		
		/**
		 * Nuevo::consultaAccesos()
		 *
		 * Genera la consulta de los accesos disponibles 
		 * @return object
		 */
		public function consultaAccesos() {
			return $this->entidad->getRepository('\Entidades\Expertos\PermisosAcceso')->findBy(array(), array('nombre' => 'ASC'));
		}
	}