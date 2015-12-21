<?php
	
	namespace Controlador\Modulo\Permisos;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Neural\JQuery\ValidarForm AS Formulario;
	use \Utilidades\Sesion;
	
	class Nuevo extends Controlador {
		
		private $sesionPHP = false;
		private $metodos = false;
		
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
			$this->metodos = array(
				'1268771b' => array('titulo' => 'Acceso', 'metodo' => 'guardarAcceso', 'namespace' => '\Formularios\Permisos\Nuevo\Acceso')
			);
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
		
		/**
		 * Nuevo::acceso()
		 *
		 * Genera la plantilla de formulario para agregar nuevo
		 * acceso
		 * 
		 * @permiso escritura 
		 * @return void
		 */
		public function acceso() {
			$this->plantilla->parametroGlobal('sesion', $this->sesionPHP->obtenerInfo());
			$this->plantilla->parametro('script', $this->accesoJQuery());
			$this->plantilla->parametro('tipo', '1268771b');
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'acceso.html');
			
			//print_r(hash('crc32b', 'acceso'));
		}
		
		/**
		 * Nuevo::accesoJQuery()
		 *
		 * Genera el script para generar la validacion con jQuery validate
		 * para la validacion del formulario del lado del cliente
		 *  
		 * @permiso escritura
		 * @return string
		 */
		private function accesoJQuery() {
			$val = new Formulario(APP, false, true);
			$val->nombre()->_requerido('Ingrese el Nombre del Acceso')->_minCaracteres(5, 'Debe ingresar minimo 5 caracteres')->_maxCaracteres(200, 'No puede contener más de 200 Caracteres');
			$val->lectura()->_requerido('Seleccione el tipo de permiso a asignar');
			$val->escritura()->_requerido('Seleccione el tipo de permiso a asignar');
			$val->actualizar()->_requerido('Seleccione el tipo de permiso a asignar');
			$val->eliminar()->_requerido('Seleccione el tipo de permiso a asignar');
			$val->peticionAjax('peticion.init(formulario);');
			
			return $val->mostrarValidacion('formulario');
		}
		
		/**
		 * Nuevo::accesoJs()
		 *
		 * Genera el script correspondiente del transporte
		 * de la peticion ajax a traves de js
		 * 
		 * @permiso escritura 
		 * @return string
		 */
		public function accesoJs() {
			$this->cabecera->header('js');
			echo $this->plantilla->mostrarPlantilla('Nuevo', 'accesoJs.js');
		}
		
		/**
		 * Nuevo::accesoProcesar()
		 * 
		 * Inicial el proceso correspondiente para la 
		 * validacion y almacenamiento de los datos
		 * correspondientes
		 * 
		 * @permiso escritura
		 * @return string
		 */
		public function accesoProcesar() {
			$this->procesar('1268771b');
		}
		
		/**
		 * Nuevo::procesar()
		 * 
		 * Genera el proceso de validacion de los datos
		 * y el formato del registro correspondiente para
		 * ser almacenado
		 * 
		 * @permiso escritura
		 * @return string
		 */
		private function procesar($tipo = false) {
			if($this->peticion->ajax() == true):
				$this->procesarExistencia($tipo);
			else:
				throw new Excepcion('No es posible procesar su petición', 0, APP);
			endif;
		}
		
		/**
		 * Nuevo::procesarExistencia()
		 * 
		 * Genera el proceso de validacion de la existencia
		 * de datos post
		 * 
		 * @permiso escritura
		 * @return scrint
		 */
		private function procesarExistencia($tipo = false) {
			if($this->peticion->postExistencia($tipo) == true):
				$this->procesarSeleccion($tipo);
			else:
				throw new Excepcion('No hay datos para ser procesados', 0, APP, 'peticionAjax');
			endif;
		}
		
		/**
		 * Nuevo::procesarSeleccion()
		 * 
		 * Genera la seleccion del formulario a validar
		 * 
		 * @permiso escritura
		 * @param string $tipo
		 * @return string
		 */
		private function procesarSeleccion($tipo = false) {
			if(array_key_exists($tipo, $this->metodos) == true):
				$this->procesarValidarFormulario($this->metodos[$tipo]['namespace'], $this->metodos[$tipo]['metodo'], $this->metodos[$tipo]['titulo']);
			else:
				throw new Excepcion('No es posible procesar la información solicitada', 0, APP, 'peticionAjax');
			endif;
		}
		
		/**
		 * Nuevo::procesarValidarFormulario()
		 *
		 * Se genera la validacion del formulario correspondiente
		 * 
		 * @permiso escritura 
		 * @param string $namespace
		 * @return string
		 */
		private function procesarValidarFormulario($namespace = false, $metodo = false, $titulo = false) {
			if($this->validarFormulario->validar($namespace) == true):
				$this->procesarGuardar($metodo, $titulo);
			else:
				throw new Excepcion(implode('\\n ', $this->validarFormulario->mensajeError()), 0, APP, 'peticionAjax');
			endif;
		}
		
		/**
		 * Nuevo::procesarGuardar()
		 * 
		 * Ejecuta el proceso de almacenamiento en la base de datos
		 * 
		 * @permiso escritura
		 * @param string $metodo
		 * @return string
		 */
		private function procesarGuardar($metodo = false, $titulo = false) {
			$consulta = $this->modelo->{$metodo}($this->validarFormulario->datosFormulario());
			
			if($consulta >= 1):
				$this->cabecera->header('json');
				echo json_encode(array('status' => true, 'mensaje' => $titulo.' creado con exito', 'codigo' => $consulta));
			else:
				throw new Excepcion('Se ha presentado un error al guardar el '.$titulo, 0, APP, 'peticionAjax');
			endif;
		}
	}