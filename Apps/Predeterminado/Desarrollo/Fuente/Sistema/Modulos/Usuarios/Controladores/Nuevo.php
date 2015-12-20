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
			$this->plantilla->parametro('script', $this->jQueryFormulario());
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'Index.html');
		}
		
		/**
		 * Nuevo::jQueryFormulario()
		 * 
		 * Genera el script de validacion en jQuery
		 * 
		 * @permiso escritura
		 * @return void
		 */
		private function jQueryFormulario() {
			$val = new ValidarForm(APP, false, true);
			$val->usuario()->_requerido('Ingrese el usuario')->_remoto($this->ruta->modulo('Usuarios', 'Nuevo', 'usuarioExistencia'), 'POST', 'El usuario ya existe en la base de datos')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			$val->nombre()->_requerido('Ingrese los nombres del usuario')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			$val->apellido()->_requerido('Ingrese los apellidos del usuario')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede superar los 200 caracteres');
			$val->cedula()->_requerido('Ingrese la cédula del usuario')->_numero('Debe ingresar solo numeros en la cédula');
			$val->as400()->_requerido('Ingrese el Usuario de RR (AS400)');
			$val->correo()->_requerido('Ingrese el correo corporativo')->_correo('El correo ingresado es invalido');
			$val->empresa()->_requerido('Seleccione la empresa del usuario');
			$val->cargo()->_requerido('Seleccione el cargo del usuario');
			$val->permiso()->_requerido('Seleccione el permiso que tendra el usuario');
			$val->peticionAjax('peticion.init(formulario);');
			return $val->mostrarValidacion('formulario');
		}
		
		/**
		 * Nuevo::usuarioExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * del usuario en la base de datos
		 * 
		 * @permiso escritura
		 * @return string
		 */
		public function usuarioExistencia() {
			if($this->peticion->ajax() == true):
				$this->usuarioExistenciaVal();
			else:
				throw new Excepcion('No es posible procesar su solicitud', 0, APP);
			endif;
		}
		
		/**
		 * Nuevo::usuarioExistenciaVal()
		 *
		 * Genera la consula a la base de datos si existe el usuario 
		 * @return string
		 */
		private function usuarioExistenciaVal() {
			if($this->validarFormulario->validar('\Formularios\Usuarios\usuarioExistencia') == true):
				echo ($this->modelo->consultaExistenciaUsuario($this->validarFormulario->datosFormulario()) == true) ? 'false' : 'true'; 
			else:
				echo 'false';
			endif;
		}
		
		/**
		 * Nuevo::js()
		 *
		 * Genera la plantilla del archivo de procesamiento de la peticion
		 * ajax para crear el nuevo usuario
		 * 
		 * @permiso escritura 
		 * @return string
		 */
		public function js() {
			$this->cabecera->header('js');
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'js.js');
		}
		
		/**
		 * Nuevo::procesar()
		 * 
		 * Ejecuta todo el proceso de agregar un nuevo usuario
		 * 
		 * @permiso escritura
		 * @return string
		 */
		public function procesar() {
			if($this->peticion->ajax() == true):
				$this->ExistenciaPost();
			else:
				throw new Excepcion('No es posible procesar su solicitud', 0, APP);
			endif;
		}
		
		/**
		 * Nuevo::ExistenciaPost()
		 *
		 * Valida que se envien los datos correspondientes
		 *  
		 * @return string
		 */
		private function ExistenciaPost() {
			if($this->peticion->postExistencia() == true):
				$this->validarForm();
			else:
				throw new Excepcion('No hay datos para procesar en el formulario', 0, APP, 'peticionAjax');
			endif;
		}
		
		/**
		 * Nuevo::validarFormulario()
		 *
		 * Genera el proceso de validacion del formulario
		 *  
		 * @return string
		 */
		private function validarForm() {
			if($this->validarFormulario->validar('\Formularios\Usuarios\Nuevo') == true):
				$this->procesarGuardar();
			else:
				throw new Excepcion(implode("\n", $this->validarFormulario->mensajeError()), 0, APP, 'peticionAjax');
			endif;
		}
		
		/**
		 * Nuevo::procesarGuardar()
		 *
		 * Se genera el proceso correspondiente para guardar
		 * los datos correspondientes
		 *  
		 * @return string
		 */
		private function procesarGuardar() {
			$usuario = $this->modelo->guardarUsuario($this->validarFormulario->datosFormulario());
			if($usuario >= 1):
				$this->cabecera->header('json');
				echo json_encode(array('status' => true, 'mensaje' => 'Usuario creado con exito', 'codigo' => $usuario));
			else:
				throw new Excepcion('Se ha presentado un error al guardar el usuario', 0, APP, 'peticionAjax');
			endif;
		}
	}