<?php
	
	namespace Controlador\Modulo\Permisos;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Index extends Controlador {
		
		private $sesionPHP = false;
		
		/**
		 * Index::__construct()
		 * 
		 * Genera las variables correspondientes para el
		 * proceso de permisos
		 * @return void
		 */
		function __construct() {
			parent::__construct();
			$this->sesionPHP = new Sesion();
		}
		
		/**
		 * Index::Index()
		 *
		 * Genera la plantilla correspondiente de la
		 * lista de permisos correspondientes
		 *  
		 * @permiso lectura
		 * @return string
		 */
		public function Index() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			$this->plantilla->parametro('consulta', $this->modelo->consultarPermisos());
			echo $this->plantilla->mostrarPlantilla('Index', 'Index.html');
		}
		
		/**
		 * Index::modulos()
		 * 
		 * Genera la plantilla correspondiente de la
		 * lista de modulos correspondientes
		 *  
		 * @permiso lectura
		 * @return string
		 */
		public function modulos() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			$this->plantilla->parametro('consulta', $this->modelo->consultarModulos());
			echo $this->plantilla->mostrarPlantilla('Index', 'modulos.html');
		}
		
		/**
		 * Index::accesos()
		 * 
		 * Genera la plantilla correspondiente de la
		 * lista de accesos correspondientes
		 *  
		 * @permiso lectura
		 * @return string
		 */
		public function accesos() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			$this->plantilla->parametro('consulta', $this->modelo->consultarAccesos());
			echo $this->plantilla->mostrarPlantilla('Index', 'accesos.html');
		}
	}