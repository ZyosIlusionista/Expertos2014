<?php
	
	namespace Controlador\Modulo\Usuarios;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Neural\JQuery\ValidarForm;
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
			$this->plantilla->parametro('empresas', $this->modelo->consultaEmpresas());
			$this->plantilla->parametro('cargos', $this->modelo->consultaCargos());
			$this->plantilla->parametro('permisos', $this->modelo->consultaPermisos());
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'Index.html');
		}
		
		private function jQueryFormulario() {
			$val = new ValidarForm(APP, false, true);
			$val->usuario()->_requerido('Ingrese el usuario')->_remoto($this->ruta->modulo('Usuarios', 'Nuevo', 'usuarioExistencia'), 'POST', 'El usuario ya existe en la base de datos')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			$val->nombre()->_requerido('Ingrese los nombres del usuario')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			$val->apellido()->_requerido('Ingrese los apellidos del usuario')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			
			
		}
	}