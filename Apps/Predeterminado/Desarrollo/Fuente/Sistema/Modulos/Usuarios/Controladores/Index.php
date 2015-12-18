<?php
	
	namespace Controlador\Modulo\Usuarios;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Index extends Controlador {
		
		private $sesionPHP = false;
		
		/**
		 * Index::__construct()
		 * 
		 * Genera las variables necesarias para el proceso
		 * de permisos de la clase
		 * 
		 * @return void
		 */
		function __construct() {
			parent::__construct();
			$this->sesionPHP = new Sesion();
		}
		
		/**
		 * Index::Index()
		 * 
		 * Genera el proceso correspondiente
		 * a la plantilla que lista los usuarios
		 * correspondientes
		 * 
		 * @permiso lectura
		 */
		public function Index() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			$this->plantilla->parametro('consulta', $this->modelo->consultaUsuarios());
			echo $this->plantilla->mostrarPlantilla('Index', 'Index.html');
		}
	}