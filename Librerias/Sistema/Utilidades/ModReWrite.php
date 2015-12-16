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
	
	class ModReWrite {
		
		private static $config = false;
		
		/**
		 * ModReWrite::leer()
		 * 
		 * Genera el proceso de lectura del url
		 * @return array
		 */
		public static function leer() {
			$parametros = func_get_args();
			if(is_array($parametros) == true AND count($parametros) >= 1):
				return self::seleccionParametros($parametros);
			else:
				return self::seleccion();
			endif;
		}
		
		/**
		 * ModReWrite::seleccionParametros()
		 * 
		 * Genera el retorno de los valores solicitados
		 * @param array $parametros
		 * @return mixed
		 */
		private static function seleccionParametros($parametros = false) {
			$base = self::seleccion();
			foreach ($parametros AS $param):
				if(array_key_exists($param, $base) == true):
					$base = $base[$param];
				else:
					throw new Excepcion(sprintf('El apartado %s no existe en el Mod_ReWrite', $param), 0);
					break;
				endif;
			endforeach;
			unset($parametros, $param);
			return $base;
		}
		
		/**
		 * ModReWrite::seleccion()
		 * 
		 * Genera el formato correspondiente
		 * @return
		 */
		private function seleccion() {
			if(is_array(self::$config) == true):
				return self::$config;
			else:
				self::$config = self::infoFormato(self::seleccionModreWrite());
				return self::$config;
			endif;
		}
		
		/**
		 * ModReWrite::infoFormato()
		 * 
		 * Retorna la matriz de datos formateados
		 * @param array $info
		 * @return array
		 */
		private static function infoFormato($info = false) {
			return array(
				'app' => (isset($info[0]) == true) ? $info[0] : self::infoFormatoApp(), 
				'mvc' => self::infoFormatoMVC($info), 
				'modulo' => self::infoFormatoModulo($info)
			);
		}
		
		/**
		 * ModReWrite::infoFormatoApp()
		 *
		 * Genera la validacion de la aplicacion 
		 * predeterminada 
		 *  
		 * @return string
		 */
		private static function infoFormatoApp() {
			return (defined('PREDETERMINADO') == true) ? PREDETERMINADO : 'Predeterminado';
		}
		
		/**
		 * ModReWrite::infoFormatoModulo()
		 * 
		 * Retorna la matriz organizada de datos
		 * del modulo del url
		 * 
		 * @param array $info
		 * @return array
		 */
		private static function infoFormatoModulo($info = false) {
			return array(
				'modulo' => (isset($info[1]) == true) ? $info[1] : 'Index',
				'controlador' => (isset($info[2]) == true) ? $info[2] : 'Index', 
				'metodo' => (isset($info[3]) == true) ? $info[3] : 'Index',
				'parametro' => (isset($info[4]) == true) ? self::infoFormatoParam($info, 4) : (boolean) false
			);
		}
		
		/**
		 * ModReWrite::infoFormatoMVC()
		 * 
		 * Retorna la matriz organizada de datos
		 * del mvc del url
		 * 
		 * @param array $info
		 * @return array
		 */
		private static function infoFormatoMVC($info = false) {
			return array(
				'controlador' => (isset($info[1]) == true) ? $info[1] : 'Index', 
				'metodo' => (isset($info[2]) == true) ? $info[2] : 'Index',
				'parametro' => (isset($info[3]) == true) ? self::infoFormatoParam($info, 3) : (boolean) false
			);
		}
		
		/**
		 * ModReWrite::infoFormatoParam()
		 * 
		 * genera el formato de los parametros
		 * ingresados por la url
		 * 
		 * @param array $info
		 * @param integer $indicador
		 * @return array / bool
		 */
		private static function infoFormatoParam($info = false, $indicador = 3) {
			$cantidad = count($info);
			for ($i = $indicador; $i < $cantidad; $i++):
				$lista[] = $info[$i];
			endfor;
			return $lista;
		}
		
		/**
		 * ModReWrite::seleccionModreWrite()
		 * 
		 * genera la validacion de el sistema
		 * de modrewrite de apache activo
		 * 
		 * @return array
		 */
		private static function seleccionModreWrite() {
			if(defined('MOD_REWRITE') == true):
				return (MOD_REWRITE == true) ? self::get_path_info() : self::server_path_info();
			else:
				return self::server_path_info();
			endif;
		}
		
		/**
		 * ModReWrite::get_path_info()
		 * 
		 * Genera la lectura del url sin formato
		 * @return array
		 */
		private static function get_path_info() {
			if(isset($_GET['PATH_INFO']) == true):
				return self::formatoPathInfo($_GET['PATH_INFO']);
			else:
				return self::seleccionDefault();
			endif;
		}
		
		/**
		 * ModReWrite::server_path_info()
		 * 
		 * Genera la lectura del url sin formato
		 * @return array
		 */
		private static function server_path_info() {
			if(isset($_SERVER['PATH_INFO']) == true):
				return self::formatoPathInfo($_SERVER['PATH_INFO']);
			else:
				return self::seleccionDefault();
			endif;
		}
		
		/**
		 * ModReWrite::formatoPathInfo()
		 * 
		 * Genera el proceso de eliminar espacios 
		 * y / adicionales que puedan existir
		 * 
		 * @param string $path_info
		 * @return array
		 */
		private static function formatoPathInfo($path_info = false) {
			$formato = ltrim(rtrim(trim($path_info), '/'), '/');
			return self::formatoMatrizPathInfo($formato);
		}
		
		/**
		 * ModReWrite::formatoMatrizPathInfo()
		 * 
		 * Genera el formato para convertir la
		 * url en array
		 * 
		 * @param string $path_info
		 * @return array
		 */
		private static function formatoMatrizPathInfo($path_info = false) {
			$formato = explode('/', $path_info);
			return (isset($formato[0]) == true AND empty($formato[0]) == false) ? $formato : self::seleccionDefault();
		}
		
		/**
		 * ModReWrite::seleccionDefault()
		 * 
		 * Genera la validacion de la existencia de 
		 * una aplicacion predeterminada
		 * 
		 * @return array
		 */
		private static function seleccionDefault() {
			return (defined('PREDETERMINADO') == true) ? array(PREDETERMINADO) : array('Predeterminado');
		}
	}