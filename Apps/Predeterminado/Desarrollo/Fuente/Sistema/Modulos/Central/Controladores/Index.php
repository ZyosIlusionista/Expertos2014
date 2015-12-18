<?php
	
	namespace Controlador\Modulo\Central;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Index extends Controlador {
		
		private $sesionPHP = false;
		
		function __construct() {
			$this->sesionPHP = new Sesion();
		}
		
		public function Index() {
			echo 'Cargando Modulo Central Controlador Index';
		}
	}