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
	
	class LeerConfig implements LeerConfigInterface {
		
		/**
		 * LeerConfig::leer()
		 * 
		 * genera el proceso de lectura del archivo de 
		 * configuracion
		 * 
		 * @param string $archivo
		 * @return array
		 */
		public function leer($archivo = false) {
			if(is_string($archivo) == true):
				return $this->existencia($archivo);
			else:
				throw new Excepcion('Debe ingresar el nombre del archivo de configuración a leer', 0);
			endif;
		}
		
		/**
		 * LeerConfig::existencia()
		 * 
		 * Genera la validacion de la existencia
		 * del archivo de configuracion
		 * 
		 * @param string $archivo
		 * @return array
		 */
		private function existencia($archivo = false) {
			$file = implode(DIRECTORY_SEPARATOR, array(ROOT_CONFIG, $archivo));
			if(file_exists($file) == true):
				return $this->lectura($file, $archivo);
			else:
				throw new Excepcion(sprintf('El archivo de configuración: %s no existe.', $archivo), 0);
			endif;
		}
		
		/**
		 * LeerConfig::lectura()
		 * 
		 * Genera la validacion de si es posible leer el
		 * archivo de configuracion ingresado
		 * 
		 * @param string $archivo
		 * @param string $nombre
		 * @return array
		 */
		private function lectura($archivo = false, $nombre = false) {
			if(is_readable($archivo) == true):
				return $this->obtener($archivo, $nombre);
			else:
				throw new Excepcion(sprintf('El archivo de configuración: %s no es posible leerlo.', $nombre), 0);
			endif;
		}
		
		/**
		 * LeerConfig::obtener()
		 * 
		 * Obtiene el contenido del archivo de configuracion
		 * correspondiente
		 * 
		 * @param string $archivo
		 * @param string $nombre
		 * @return array
		 */
		private function obtener($archivo = false, $nombre = false) {
			$contenido = json_decode(file_get_contents($archivo), true);
			switch(json_last_error()):
				case JSON_ERROR_NONE: return $contenido;
					break;
				case JSON_ERROR_DEPTH: throw new Excepcion('Excedido tamaño máximo de la pila del archivo de configuración', 0);
					break;
				case JSON_ERROR_STATE_MISMATCH: throw new Excepcion('Desbordamiento de buffer o los modos no coinciden del archivo de configuración', 0);
					break;
				case JSON_ERROR_CTRL_CHAR: throw new Excepcion('Encontrado carácter de control no esperado del archivo de configuración', 0);
					break;
				case JSON_ERROR_SYNTAX: throw new Excepcion('Error de sintaxis, JSON mal formado del archivo de configuración', 0);
					break;
				case JSON_ERROR_UTF8: throw new Excepcion('Caracteres UTF-8 malformados, posiblemente están mal codificados del archivo de configuración', 0);
					break;
				default : throw new Excepcion('Error desconocido del archivo de configuración', 0);
					break;
			endswitch;
		}
	}