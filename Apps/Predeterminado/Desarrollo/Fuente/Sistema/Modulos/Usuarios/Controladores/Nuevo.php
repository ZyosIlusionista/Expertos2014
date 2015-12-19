<?php
	
	namespace Controlador\Modulo\Usuarios;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Nuevo extends Controlador {
		
		private $sesionPHP = false;
		
		/**
		 * Nuevo::__construct()
		 * 
		 * Genera el proceso de la validacion de la sesion
		 * 
		 * @return void
		 */
		function __construct() {
			parent::__construct();
			$this->sesionPHP = new Sesion();
		}
		
		/**
		 * Nuevo::Index()
		 * 
		 * Genera el formulario correspondiente para el
		 * registro del nuevo usuario
		 * 
		 * @permiso escritura
		 * @return string
		 */
		public function Index() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'Index.html');
		}
	}