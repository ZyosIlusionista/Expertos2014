<?php
	
	namespace Controlador\Modulo\Central;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Index extends Controlador {
		
		private $sesionPHP = false;
		
		function __construct() {
			parent::__construct();
			$this->sesionPHP = new Sesion();
		}
		
		/**
		 * 
		 * Se genera la prueba correspondiente del comentario
		 * para el proceso de permisos
		 * 
		 * @permiso lectura
		 */
		public function Index() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			echo $this->plantilla->mostrarPlantilla('Base.html');
		}
	}