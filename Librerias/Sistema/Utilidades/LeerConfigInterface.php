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
	
	interface LeerConfigInterface {
		
		/**
		 * LeerConfigInterface::leer()
		 * 
		 * genera el proceso de lectura del archivo de 
		 * configuracion
		 * 
		 * @param string $archivo
		 * @return array
		 */
		public function leer($archivo = false);
		
		/**
		 * LeerConfigInterface::existencia()
		 * 
		 * Genera la validacion de la existencia
		 * del archivo de configuracion
		 * 
		 * @param string $archivo
		 * @return array
		 */
		//public function existencia($archivo = false);
		
		/**
		 * LeerConfigInterface::lectura()
		 * 
		 * Genera la validacion de si es posible leer el
		 * archivo de configuracion ingresado
		 * 
		 * @param string $archivo
		 * @param string $nombre
		 * @return array
		 */
		//public function lectura($archivo = false, $nombre = false);
		
		/**
		 * LeerConfigInterface::obtener()
		 * 
		 * Obtiene el contenido del archivo de configuracion
		 * correspondiente
		 * 
		 * @param string $archivo
		 * @param string $nombre
		 * @return array
		 */
		//public function obtener($archivo = false, $nombre = false);
	}