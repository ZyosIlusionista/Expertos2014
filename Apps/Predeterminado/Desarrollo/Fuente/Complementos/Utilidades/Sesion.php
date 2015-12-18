<?php
	
	namespace Utilidades;
	use \Mvc\Rutas;
	use \Neural\Excepcion;
	use \Neural\Sesion\SesionPHP;
	use \Sistema\Utilidades\ModReWrite;
	
	class Sesion {
		
		private $nombre = false;
		private $modReWrite = false;
		private $rutas = false;
		private $sesion = false;
		private $tiempo = 3600;
		private $info = false;
		private $reflexion = false;
		private $permisos = false;
		
		/**
		 * Sesion::__construct()
		 * 
		 * Genera el proceso de validacion de la 
		 * clase del modulo correspondiente
		 * 
		 * @return void
		 */
		function __construct() {
			$this->nombre = hash('crc32b', APP);
			$mod = ModReWrite::leer();
			$this->modReWrite = $mod['modulo'];
			$this->rutas = new Rutas(APP);
			$this->sesion = new SesionPHP(APP);
			$this->permisos = array_flip(array('lectura', 'escritura', 'actualizar', 'eliminar'));
			
			//Ejecuta el proceso
			$this->validar();
		}
		
		/**
		 * Sesion::validar()
		 * 
		 * Genera el proceso correspondiente
		 * de validacion de la sesion actual
		 * 
		 * @return void
		 */
		private function validar() {
			if($this->sesion->obtenerExistencia($this->nombre) == true):
				$this->validarArrayDatos();
			else:
				header("Location: ".$this->rutas->mvc('LogOut'));
				exit();
			endif;
		}
		
		/**
		 * Sesion::validarArrayDatos()
		 * 
		 * Genera la validacion que sea un array de datos
		 * @return void
		 */
		private function validarArrayDatos() {
			$this->info = $this->sesion->obtener($this->nombre);
			if(is_array($this->info) == true):
				$this->validarLlave();
			else:
				header("Location: ".$this->rutas->mvc('LogOut'));
				exit();
			endif;
		}
		
		/**
		 * Sesion::validarLlave()
		 * 
		 * Genera la validacion de la llave 
		 * de la sesion
		 * 
		 * @return void
		 */
		private function validarLlave() {
			$llave = hash('snefru', implode('_', array($this->info['info']['nombre'], $this->info['info']['apellido'], $this->info['validacion']['secuencia'])));
			if($llave == $this->info['validacion']['llave']):
				$this->validarTiempo();
			else:
				header("Location: ".$this->rutas->mvc('LogOut'));
				exit();
			endif;
		}
		
		/**
		 * Sesion::validarTiempo()
		 * 
		 * Genera la validacion del tiempo generado para
		 * el usuario de la aplicacion
		 * 
		 * @return void
		 */
		private function validarTiempo() {
			$final = strtotime("now") - $this->info['validacion']['secuencia'];
			if($final <= $this->tiempo):
				$this->validarModulo();
			else:
				header("Location: ".$this->rutas->mvc('LogOut'));
				exit();
			endif;
			
		}
		
		/**
		 * Sesion::validarModulo()
		 * 
		 * Genera la validacion si existe el modulo
		 * dentro de los permisos
		 * 
		 * @return void
		 */
		private function validarModulo() {
			if(array_key_exists($this->modReWrite['modulo'], $this->info['permisos']) == true):
				$this->validarComentario();
			else:
				throw new Excepcion(sprintf('No es posible mostrar el Modulo: [ %s ] no tiene permisos', $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::validarComentario()
		 * 
		 * Genera la validacion si hay un comentario para
		 * ser procesado y validado dentro del proceso de 
		 * permisos
		 * 
		 * @throws \Neural\Excepcion
		 */
		private function validarComentario() {
			$clase = '\\'.implode('\\', array('Controlador', 'Modulo', $this->modReWrite['modulo'], $this->modReWrite['controlador']));
			$this->reflexion = new \ReflectionClass($clase);
			if(is_string($this->reflexion->getMethod($this->modReWrite['metodo'])->getDocComment()) == true):
				$this->validarSeleccionPermiso();
			else:
				throw new Excepcion(sprintf('No es posible mostrar el controlador: [ %s ] del Modulo: [ %s ] no existen permisos', $this->modReWrite['controlador'], $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::validarSeleccionPermiso()
		 * 
		 * Genera el proceso de validacion del
		 * permiso correspondiente
		 * 
		 * @throws \Neural\Excepcion
		 */
		private function validarSeleccionPermiso() {
			$comentarios = $this->formatoComentario();
			if(array_key_exists('permiso', $comentarios) == true):
				$this->validarPermisoExistente($comentarios['permiso']);
			else:
				throw new Excepcion(sprintf('No es posible mostrar el controlador: [ %s ] del Modulo: [ %s ] no existen permisos', $this->modReWrite['controlador'], $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::validarPermisoExistente()
		 * 
		 * Genera el proceso de validacion del tipo de
		 * permiso generado
		 * 
		 * @param string $permiso
		 * @throws \Neural\Excepcion
		 */
		private function validarPermisoExistente($permiso = false) {
			if(array_key_exists($permiso, $this->permisos) == true):
				$this->validarPermisoProceso($permiso);
			else:
				throw new Excepcion(sprintf('No es posible mostrar el controlador: [ %s ] del Modulo: [ %s ] no existe un permiso valido', $this->modReWrite['controlador'], $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::validarPermisoProceso()
		 * 
		 * Genera la validacion del permiso actual
		 * 
		 * @param string $permiso
		 * @throws Excepcion
		 */
		private function validarPermisoProceso($permiso = false) {
			if($this->info['permisos'][$this->modReWrite['modulo']][$permiso] == 0):
				throw new Excepcion(sprintf('No es posible mostrar el controlador: [ %s ] del Modulo: [ %s ] no tiene permiso para observarlo', $this->modReWrite['controlador'], $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::formatoComentario()
		 * 
		 * genera el formato de los comentarios
		 * @throws \Neural\Excepcion
		 */
		private function formatoComentario() {
			$comentario = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $this->reflexion->getMethod($this->modReWrite['metodo'])->getDocComment());
			$comentario = explode("\n", trim($comentario));
			if(count($comentario) >=1):
				return $this->formatoVariable($comentario);
			else:
				throw new Excepcion(sprintf('No es posible mostrar el controlador: [ %s ] del Modulo: [ %s ] no existen permisos', $this->modReWrite['controlador'], $this->modReWrite['modulo']), 100, APP, 'NoPermisos');
			endif;
		}
		
		/**
		 * Sesion::formatoVariable()
		 * 
		 * Genera la matriz de comentarios
		 * 
		 * @param string $comentario
		 * @return array
		 */
		private function formatoVariable($comentario = false) {
			foreach ($comentario AS $valor):
				if(preg_match('/@/', $valor, $resultado, PREG_OFFSET_CAPTURE) == true):
					$param = explode(' ', $valor);
					$lista[mb_strtolower(str_replace('@', '', $param[0]))] = (isset($param[1]) == true) ? mb_strtolower($param[1]) : (boolean) false;
				endif;
			endforeach;
			return (isset($lista) == true) ? $lista : array();
		}
		
		/**
		 * Sesion::obtenerInfo()
		 * 
		 * Retorna los datos de permisos e informacion
		 * del usuario
		 * 
		 * @return array
		 */
		public function obtenerInfo() {
			unset($this->info['validacion']);
			return $this->info;
		}
		
	}