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
	
	class ConfigExcepcion {
		
		/**
		 * ConfigExcepcion::leer()
		 * 
		 * Genera la lectura del archivo de configuracion
		 * de modulos para su procesamiento
		 * 
		 * @param string $directorio
		 * @param string $entorno
		 * @return array
		 */
		public static function leer($directorio = false, $entorno = false) {
			if(is_string($directorio) == true AND is_string($entorno) == true):
				return self::existencia($directorio, $entorno);
			else:
				throw new Excepcion('Debe ingresar el directorio y el entorno para la lectura de los modulos correspondientes.', 0);
			endif;
		}
		
		/**
		 * ConfigExcepcion::existencia()
		 *
		 * Genera la validacion de la existencia del archivo de configuracion 
		 * @param string $directorio
		 * @param string $entorno
		 * @return array
		 */
		private static function existencia($directorio = false, $entorno = false) {
			$file = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $entorno, 'Fuente', 'Configuracion', 'Excepciones.json'));
			if(file_exists($file) == true):
				return self::lectura($file, $entorno);
			else:
				throw new Excepcion(sprintf('El archivo de configuración: %s no existe dentro de la aplicación en entorno: %s', 'Excepciones.json', $entorno), 0);
			endif;
		}
		
		/**
		 * ConfigExcepcion::lectura()
		 *
		 * Genera la validacion de la lectura del archivo 
		 * @param string $archivo
		 * @param string $entorno
		 * @return array
		 */
		private static function lectura($archivo = false, $entorno = false) {
			if(is_readable($archivo) == true):
				return self::obtener($archivo, $entorno);
			else:
				throw new Excepcion(sprintf('El archivo de configuración: %s no es posible leerlo en entorno: %s', 'Excepciones.json', $entorno), 0);
			endif;
		}
		
		/**
		 * ConfigExcepcion::obtener()
		 * 
		 * Obtiene los datos correspondientes
		 * @param string $archivo
		 * @param string $entorno
		 * @return array
		 */
		private static function obtener($archivo = false, $entorno = false) {
			$contenido = json_decode(file_get_contents($archivo), true);
			switch(json_last_error()):
				case JSON_ERROR_NONE: return $contenido;
					break;
				case JSON_ERROR_DEPTH: throw new Excepcion(sprintf('Excedido tamaño máximo de la pila del archivo de %s en %s', 'Excepciones.json', $entorno), 0);
					break;
				case JSON_ERROR_STATE_MISMATCH: throw new Excepcion(sprintf('Desbordamiento de buffer o los modos no coinciden del archivo de configuración: %s en %s', 'Excepciones.json', $entorno), 0);
					break;
				case JSON_ERROR_CTRL_CHAR: throw new Excepcion(sprintf('Encontrado carácter de control no esperado del archivo de configuración: %s en %s', 'Excepciones.json', $entorno), 0);
					break;
				case JSON_ERROR_SYNTAX: throw new Excepcion(sprintf('Error de sintaxis, JSON mal formado del archivo de configuración: %s en %s', 'Excepciones.json', $entorno), 0);
					break;
				case JSON_ERROR_UTF8: throw new Excepcion(sprintf('Caracteres UTF-8 malformados, posiblemente están mal codificados del archivo de configuración: %s en %s', 'Excepciones.json', $entorno), 0);
					break;
				default : throw new Excepcion(sprintf('Error desconocido del archivo de configuración: %s en %s', 'Execpciones.json', $entorno), 0);
					break;
			endswitch;
		}
	}