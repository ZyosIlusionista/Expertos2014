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
	
	namespace Sistema\Utilidades;
	use \Neural\Excepcion;
	
	class ConfigCache {
		
		private static $config = false;
		
		/**
		 * ConfigCache::driverExistencia()
		 * 
		 * Genera la validacion de la existencia de la 
		 * configuracion correspondiente
		 * 
		 * @param string $driver
		 * @return bool
		 */
		public static function driverExistencia($driver = false) {
			if(is_string($driver) == true):
				self::lecturaPrevia();
				return (array_key_exists($driver, self::$config['driver']) == true) ? (boolean) true : (boolean) false;
			else:
				throw new Excepcion('Debe indicar el driver a validar en el proceso de driverExistencia en la Configuración de Cache', 0);
			endif;
		}
		
		/**
		 * ConfigCache::driverEstado()
		 * 
		 * Valida el estado del driver correspondiente
		 * si esta activo o inactivo
		 * 
		 * @param string $driver
		 * @return bool
		 */
		public static function driverEstado($driver = false) {
			if(is_string($driver) == true):
				self::lecturaPrevia();
				return (array_key_exists($driver, self::$config['configuracion']) == true) ? (boolean) self::$config['configuracion'][$driver] : (boolean) false;
			else:
				throw new Excepcion('Debe indicar el driver a validar en el proceso de driverExistencia en la Configuración de Cache', 0);
			endif;
		}
		
		/**
		 * ConfigCache::leer()
		 * 
		 * Genera la lectura del archivo de configuracion 
		 * correspondiente a cache
		 * 
		 * @return array
		 */
		public static function leer() {
			$parametros = func_get_args();
			self::lecturaPrevia();
			if(count($parametros) >= 1):
				return self::seleccion($parametros);
			else:
				return self::$config;
			endif;
		}
		
		/**
		 * ConfigCache::seleccion()
		 * 
		 * genera la busqueda correspondiente dentro de
		 * la matriz de configuracion los apartados
		 * correspondientes ingresados
		 * 
		 * @param array $parametros
		 * @return array
		 */
		private static function seleccion($parametros = false) {
			$base = self::$config;
			foreach ($parametros AS $param):
				if(is_array($base) == true):
					if(array_key_exists($param, $base) == true):
						$base = $base[$param];
					else:
						throw new Excepcion(sprintf('El apartado %s no existe en la configuración de Cache', $param), 0);
						break;
					endif;
				else:
					throw new Excepcion(sprintf('El apartado %s no existe en la configuración de Cache', $param), 0);
					break;
				endif;
			endforeach;
			unset($parametros, $param);
			return $base;
		}
		
		/**
		 * ConfigCache::lecturaPrevia()
		 * 
		 * @return
		 */
		private function lecturaPrevia() {
			if(is_array(self::$config) == false):
				$lectura = new LeerConfig;
				self::$config = $lectura->leer('Cache.json');
			endif;
		}
	}