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
	
	namespace Neural\JQuery;
	use \Neural\Excepcion;
	
	class AjaxConstructor {
		
		private static $constructor;
		private static $plantilla;
		public static $jQuery = 'jQuery';
		
		/**
		 * AjaxConstructor::url()
		 * 
		 * Asigna la direccion donde se generara la peticion
		 * ajax
		 * 
		 * @param string $url
		 * @return object
		 */
		public static function url($url = false) {
			self::$constructor['url'] = $url;
			return new static;
		}
		
		/**
		 * AjaxConstructor::async()
		 * 
		 * Asigna la opcion asincrona solo acepta
		 * valores booleanos true o false
		 * 
		 * @param bool $async
		 * @return object
		 */
		public static function async($async = false) {
			self::$constructor['async'] = $async;
			return new static;
		}
		
		/**
		 * AjaxConstructor::cache()
		 * 
		 * Asigna el cache de la peticion solo acepta
		 * valores booleanos true o false
		 * 
		 * @param bool $cache
		 * @return object
		 */
		public static function cache($cache = false) {
			self::$constructor['cache'] = $cache;
			return new static;
		}
		
		/**
		 * AjaxConstructor::data()
		 * 
		 * Asigna los valores correspondiente ya sea
		 * una funcion javascript o en dado caso un json
		 * 
		 * @param string $funcion
		 * @return object
		 */
		public static function data($funcion = false) {
			self::$plantilla['{{ data }}'] = trim($funcion);
			self::$constructor['data'] = '{{ data }}';
			return new static;
		}
		
		/**
		 * AjaxConstructor::dataType()
		 *
		 * Genera la asignacion del tipo de respuesta
		 * que se obtendra de la peticion ajax, los
		 * posibles valores son:
		 * 
		 * xml, json, script o html
		 *  
		 * @param string $tipo
		 * @return object
		 */
		public static function dataType($tipo = 'json') {
			self::$constructor['dataType'] = $tipo;
			return new static;
		}
		
		/**
		 * AjaxConstructor::error()
		 * 
		 * Asigna la funcion si se presenta un error
		 * en la peticion ajax
		 * 
		 * @param string $funcion
		 * @return object
		 */
		public static function error($funcion = false) {
			self::$plantilla['{{ error }}'] = trim($funcion);
			self::$constructor['error'] = '{{ error }}';
			return new static;
		}
		
		/**
		 * AjaxConstructor::statusCode()
		 * 
		 * Asigna una funcion personalizada segun el
		 * codigo de error que retorne la peticion ajax
		 * 
		 * @param string $codigo
		 * @param string $funcion
		 * @return object
		 */
		public static function statusCode($codigo = false, $funcion = false) {
			self::$plantilla["{{ $codigo }}"] = trim($funcion);
			self::$constructor['statusCode'][$codigo] = "{{ $codigo }}";
			return new static;
		}
		
		/**
		 * AjaxConstructor::success()
		 * 
		 * Asigna la funcion para manejar la respuesta
		 * de la peticion ajax
		 * 
		 * @param string $funcion
		 * @return object
		 */
		public static function success($funcion = false) {
			self::$plantilla['{{ success }}'] = trim($funcion);
			self::$constructor['success'] = '{{ success }}';
			return new static;
		}
		
		/**
		 * AjaxConstructor::type()
		 * 
		 * Genera la asignacion del tipo de peticion
		 * que debe realizarse ya se POST, GET
		 * 
		 * @param string $tipo
		 * @return object
		 */
		public static function type($tipo = false) {
			self::$constructor['type'] = $tipo;
			return new static;
		}
		
		/**
		 * AjaxConstructor::beforeSend()
		 * 
		 * Asigna la funcion para la espera de la
		 * peticion ajax
		 * 
		 * @param string $funcion
		 * @return object
		 */
		public static function beforeSend($funcion = false) {
			self::$plantilla['{{ beforeSend }}'] = trim($funcion);
			self::$constructor['beforeSend'] = '{{ beforeSend }}';
			return new static;
		}
		
		/**
		 * AjaxConstructor::construir()
		 * 
		 * Genera la construccion del script basico
		 * para la peticion ajax
		 * @return string
		 */
		public static function construir() {
			$jQuery = self::$jQuery;
			$json = self::jsonConstruir();
			self::$constructor = false;
			self::$plantilla = false;
			return <<<EOT
	{$jQuery}.ajax({$json});
EOT;
		}
		
		/**
		 * AjaxConstructor::jsonConstruir()
		 * 
		 * Genera la conversion a json la matriz de datos
		 * @return string
		 */
		private static function jsonConstruir() {
			$json = json_encode(self::$constructor, JSON_PRETTY_PRINT);
			if(is_array(self::$plantilla) == true):
				$llaves = array_keys(self::$plantilla);
				$json = str_replace(array_map(function($valor) {
					return implode('', array('"', $valor, '"'));
				}, $llaves), self::$plantilla, $json);
			endif;
			return $json;
		}
	}