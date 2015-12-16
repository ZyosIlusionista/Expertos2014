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
	
	namespace Sistema\Controlador;
	use \Neural\Excepcion;
	
	class Peticion {
		
		private $_get = false;
		private $_post = false;
		
		/**
		 * Peticion::__construct()
		 * 
		 * Genera las variables correspondientes
		 * @return void
		 */
		function __construct() {
			$this->_get = $_GET;
			$this->_post = $_POST;
			$_GET = null;
			$_POST = null;
			$_REQUEST = null;
		}
		
		/**
		 * Peticion::ajax()
		 * 
		 * Valida si se esta generando una peticion
		 * a traves de ajax
		 * 
		 * @return bool
		 */
		public function ajax() {
			return (empty($_SERVER['HTTP_X_REQUESTED_WITH']) == false AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Peticion::getExistencia()
		 * 
		 * Genera la validacion si existe la peticion
		 * en este caso valida si hay datos en la peticion
		 * 
		 * @return bool
		 */
		public function getExistencia() {
			return (is_array($this->_get) == true AND count($this->_get) >= 1) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Peticion::postExistencia()
		 * 
		 * Genera la validacion si existe la peticion
		 * en este caso valida si hay datos en la peticion
		 * 
		 * @return bool
		 */
		public function postExistencia() {
			return (is_array($this->_post) == true AND count($this->_post) >= 1) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Peticion::post()
		 * 
		 * Retorna la peticion post solicitada
		 * @return mixed
		 */
		public function post() {
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				return $this->contenidoSeleccion($this->_post, $parametros);
			else:
				return $this->contenidoExistencia($this->_post, 'POST');
			endif;
		}
		
		/**
		 * Peticion::get()
		 * 
		 * Retorna la peticion get solicitada
		 * @return mixed
		 */
		public function get() {
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				return $this->contenidoSeleccion($this->_get, $parametros);
			else:
				return $this->contenidoExistencia($this->_get, 'GET');
			endif;
		}
		
		/**
		 * Peticion::contenidoExistencia()
		 * 
		 * Determina si hay datos en la peticion solicitada
		 * 
		 * @param array $array
		 * @param string $tipo
		 * @return array
		 */
		private function contenidoExistencia($array = false, $tipo = false) {
			if(count($array) >= 1):
				return $array;
			else:
				throw new Excepcion(sprintf('No hay datos en la petición %s', mb_strtoupper($tipo)), 0);
			endif;
		}
		
		/**
		 * Peticion::contenidoSeleccion()
		 * 
		 * Determina si se genera una peticion de 
		 * multiple retorno de datos
		 * 
		 * @param array $array
		 * @param array $parametros
		 * @return mixed
		 */
		private function contenidoSeleccion($array = false, $parametros = false) {
			if(count($parametros) == 1):
				return $this->contenidoUnico($parametros[0], $array);
			else:
				return $this->contenidoMultiple($parametros, $array);
			endif;
		}
		
		/**
		 * Peticion::contenidoUnico()
		 * 
		 * Genera el proceso de validar el parametro
		 * si se encuentra en la base de la matriz de 
		 * la peticion correspondiente
		 * 
		 * @param string $parametro
		 * @param array $array
		 * @return mixed
		 */
		private function contenidoUnico($parametro = false, $array = false) {
			if(array_key_exists($parametro, $array) == true):
				return (is_array($array[$parametro]) == true) ? $array[$parametro] : trim($array[$parametro]);
			else:
				throw new Excepcion(sprintf('La llave: %s no se encuentra en la petición', $parametro), 0);
			endif;
		}
		
		/**
		 * Peticion::contenidoMultiple()
		 * 
		 * Retorna los datos solicitados
		 * 
		 * @param array $parametros
		 * @param array $array
		 * @return array
		 */
		private function contenidoMultiple($parametros = false, $array = false) {
			foreach ($parametros AS $param):
				if(array_key_exists($param, $array) == true):
					$lista[$param] = (is_array($array[$param]) == true) ? $array[$param] : trim($array[$param]);
				else:
					throw new Excepcion(sprintf('La llave: %s no se encuentra dentro de la petición', $param), 0);
					break;
				endif;
			endforeach;
			return (isset($lista) == true) ? $lista : array();
		}
	}