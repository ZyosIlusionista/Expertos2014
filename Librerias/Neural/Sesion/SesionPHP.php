<?php
	
	/**
	 * NeuralPHP Framework
	 * Marco de trabajo para aplicaciones web.
	 * 
	 * @author Zyos (Carlos Parra) <Neural.Framework@gmail.com>
	 * @copyright 2006-2015 NeuralPHP Framework
	 * @license GNU General Public License as published by the Free Software Foundation; either version 2 of the License.  
	 * @see http://neuralframework.com/
	 * @version 4.0
	 * 
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or 1 any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 */
	
	namespace Neural\Sesion;
	use \Neural\Excepcion;
	use \Neural\Criptografia\Codificar;
	use \Neural\Criptografia\Decodificar;
	use \Sistema\Utilidades\ConfigAcceso;
	
	class SesionPHP {
		
		private $app = false;
		private $clave = false;
		private $codificacion = false;
		private $decodificacion = false;
		
		/**
		 * SesionPHP::__construct()
		 * 
		 * Genera las variables necesarias para el proceso de 
		 * sesiones 
		 * 
		 * @param string $app
		 * @param string $clave
		 * @return void
		 */
		function __construct($app = false, $clave = false) {
			$this->app = $this->inputApp($app);
			$this->clave = $this->inputClave($clave);
			$this->codificacion = new Codificar($app, $clave);
			$this->decodificacion = new Decodificar($app, $clave);
			
			## Se agrega opcion de proceso en dado caso
			## que ya se encuentre la sesion iniciada
			if(isset($_SESSION) == false):
				session_start();
			endif;
		}
		
		/**
		 * SesionPHP::finalizar()
		 * 
		 * Genera el proceso de eliminar la sesion actual
		 * en caso de que se presente algun error se generara
		 * la eliminacion de la variable de sesion para borrar 
		 * los datos alli contenidos
		 * 
		 * @return bool
		 */
		public function finalizar() {
			if(session_destroy() == true):
				return true;
			else:
				unset($_SESSION);
				throw new Excepcion('Se ha presentado un error al destruir la sesión en el proceso de SesionPHP', 0);
			endif;
		}
		
		/**
		 * SesionPHP::obtenerExistencia()
		 * 
		 * Genera la validacion si existe el nombre asociativo
		 * de la sesion generada
		 * 
		 * @param string $nombre
		 * @return bool
		 */
		public function obtenerExistencia($nombre = false) {
			if(is_string($nombre) == true OR is_numeric($nombre) == true):
				return (array_key_exists($nombre, $_SESSION) == true) ? (boolean) true : (boolean) false;
			else:
				throw new Excepcion('Debe ingresar el nombre de la sesión en el proceso de SesionPHP', 0);
			endif;
		}
		
		/**
		 * SesionPHP::obtener()
		 * 
		 * Genera el proceso de obtener los datos de la
		 * sesion correspondiente
		 * 
		 * @param string $nombre
		 * @return mixed
		 */
		public function obtener($nombre = false) {
			if(is_string($nombre) == true OR is_numeric($nombre) == true):
				return $this->obtenerNombre($nombre);
			else:
				throw new Excepcion('Debe ingresar el nombre de la sesión en el proceso de SesionPHP', 0);
			endif;
		}
		
		/**
		 * SesionPHP::obtenerNombre()
		 * 
		 * Genera el proceso de obtener los datos codificados
		 * y retornarlos
		 * 
		 * @param string $nombre
		 * @return mixed
		 */
		private function obtenerNombre($nombre = false) {
			if(array_key_exists($nombre, $_SESSION) == true):
				return $this->decodificacion->procesar($_SESSION[$nombre]);
			else:
				throw new Excepcion('');
			endif;
		}
		
		/**
		 * SesionPHP::asignar()
		 * 
		 * Genera el proceso de asignacion de la informacion
		 * dentro de la sesion correspondiente
		 * Valor asignado regresa true 
		 * 
		 * @param string $nombre
		 * @param mixed $valor
		 * @return bool
		 */
		public function asignar($nombre = false, $valor = false) {
			if(is_string($nombre) == true OR is_numeric($nombre) == true):
				$_SESSION[$nombre] = $this->codificacion->procesar($valor);
			else:
				throw new Excepcion('Debe ingresar el nombre que se asignara a la sesión en el proceso de SesionPHP', 0);
			endif;
			return true;
		}
		
		/**
		 * SesionPHP::inputClave()
		 * 
		 * Genera la validacion de la clave correspondiente
		 * @param string $clave
		 * @return mixed
		 */
		private function inputClave($clave = false) {
			if(is_string($clave) == true OR is_numeric($clave) == true OR is_bool($clave) == true):
				return $clave;
			else:
				throw new Excepcion('No es valido el formato de la clave para el proceso de SesionPHP', 0);
			endif;
		}
		
		/**
		 * SesionPHP::inputApp()
		 * 
		 * Genera la validacion del ingreso de una aplicacion
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Debe indicar el nombre de la aplicación para el proceso de la SesionPHP', 0);
			endif;
		}
		
		/**
		 * SesionPHP::inputAppExistencia()
		 * 
		 * Genera el proceso de la existencia de
		 * la aplicacion en el archivo de configuracion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s, no existe en el archivo de configuración para el proceso de SesionPHP', $app), 0);
			endif;
		}
	}