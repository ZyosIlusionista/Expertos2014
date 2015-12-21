<?php
	
	namespace Controlador\Modulo\Permisos;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Utilidades\Sesion;
	
	class Nuevo extends Controlador {
		
		private $sesionPHP = false;
		
		/**
		 * Nuevo::__construct()
		 *
		 * Genera el proceso de variables para 
		 * el procesamiento de las sesiones
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
		 * Metodo por defecto redireccion hacia el controlador
		 * central para validaciones adicionales
		 * 
		 * @return void
		 */
		public function Index() {
			$this->cabecera->redireccion($this->ruta->modulo('Central'));
			exit();
		}
		
		public function acceso() {
			
		}
	}