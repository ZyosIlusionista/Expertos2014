<?php
	
	namespace Controlador\MVC;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Neural\Sesion\SesionPHP;
	
	class LogOut extends Controlador {
		
		private $sesionPHP = false;
		
		function __construct() {
			parent::__construct();
			$this->sesionPHP = new SesionPHP(APP);
			$this->sesionPHP->finalizar();
			$this->cabecera->redireccion($this->ruta->modulo('Index'));
		}
		
		public function Index() {
			
		}
	}