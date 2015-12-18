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
			$actual = strtotime(date("Y-m-d H:i:s"));
			$final = $actual - $this->info['validacion']['secuencia'];
			if($final >= $this->tiempo):
			
			else:
				header("Location: ".$this->rutas->mvc('LogOut'));
				exit();
			endif;
		}
		
		private function validarComentario() {
			$clase = '\\'.implode('\\', array('Controlador', 'Modulo', $this->modReWrite['modulo'], $this->modReWrite['controlador']));
			$comentario = new \Sistema\Controlador\ValidarFormulario\Reflexion($clase);
			$comentario->obtener();
		}
	}