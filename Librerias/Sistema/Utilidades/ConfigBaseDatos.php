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
	
	class ConfigBaseDatos {
		
		private static $config = false;
		
		/**
		 * ConfigBaseDatos::leer()
		 * 
		 * Retorna los valores de configuracion solicitados
		 * @return array
		 */
		public static function leer() {
			$parametros = func_get_args();
			return self::lecturaPrevia($parametros);
		}
		
		/**
		 * ConfigBaseDatos::appExistencia()
		 * 
		 * Valida si la aplicacion indicada existe
		 * en el archivo de configuracion
		 * 
		 * @param string $app
		 * @return bool
		 */
		public static function conexionExistencia($conexion = false) {
			if(is_array(self::$config) == true):
				return self::appExistenciaValidar($conexion);
			else:
				$lectura = new LeerConfig;
				self::$config = $lectura->leer('BaseDatos.json');
				return self::appExistenciaValidar($conexion);
			endif;
		}
		
		/**
		 * ConfigBaseDatos::appExistenciaValidar()
		 * 
		 * genera el proceso de validacion de existencia
		 * de la aplicacion indicada
		 * 
		 * @param string $app
		 * @return bool
		 */
		private static function appExistenciaValidar($app = false) {
			if(is_bool($app) == false):
				return (array_key_exists($app, self::$config) == true) ? (boolean) true : (boolean) false;
			else:
				return (boolean) false;;
			endif;
		}
		
		/**
		 * ConfigBaseDatos::lecturaPrevia()
		 * 
		 * Genera la validacion si ya se ha generado 
		 * la consulta de la configuracion solicitada
		 * 
		 * @param array $parametros
		 * @return array
		 */
		private static function lecturaPrevia($parametros = false) {
			if(is_array(self::$config) == true):
				return self::opcion($parametros);
			else:
				$lectura = new LeerConfig;
				self::$config = $lectura->leer('BaseDatos.json');
				return self::opcion($parametros);
			endif;
		}
		
		/**
		 * ConfigBaseDatos::opcion()
		 * 
		 * Determina si se han pasado parametros
		 * y retorna el valor correspondiente
		 * 
		 * @param array $parametros
		 * @return array
		 */
		private static function opcion($parametros = false) {
			if(is_array($parametros) == true AND count($parametros) >= 1):
				return self::seleccion($parametros);
			else:
				return self::$config;
			endif;
		}
		
		/**
		 * ConfigBaseDatos::seleccion()
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
				if(array_key_exists($param, $base) == true):
					$base = $base[$param];
				else:
					throw new Excepcion(sprintf('El apartado %s no existe en la configuración de conexion de base de datos', $param), 0);
					break;
				endif;
			endforeach;
			unset($parametros, $param);
			return $base;
		}
	}