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
	
	namespace Neural\Cache;
	
	interface InterfaceCache {
		
		/**
		 * InterfaceCache::agregar()
		 * 
		 * Agrega el valor asociado a la llave que se indica
		 * en dado caso que la llave exista retornara false
		 * en caso que se agrega a memoria true
		 * 
		 * @param string $identificador: identificador en memoria
		 * @param mixed $valor: valor en memoria
		 * @param integer $duracion: duracion en segundos en memoria
		 * @return bool
		 */
		public function agregar($identificador, $valor, $duracion);
		
		/**
		 * InterfaceCache::asignar()
		 *
		 * Genera la asignacion del identificador
		 * si no existe el identificador lo crea y guarda
		 * si existe el identificador actualiza el valor 
		 * correspondiente
		 * los valores retornados son true cuando se guarda
		 * false si se presenta un error en la escritura
		 * 
		 * @param string $identificador: identificador en memoria
		 * @param mixed $valor: valor en memoria
		 * @param integer $expiracion: duracion en segundos en memoria
		 * @return bool
		 */
		public function asignar($identificador, $valor, $expiracion);
		
		/**
		 * InterfaceCache::cerrarConexion()
		 * 
		 * Cierra la conexion con el servidor de Memcached
		 * retorna true finalizado correcto
		 * retorna false si se presenta un problemas o error
		 * 
		 * @return bool
		 */
		public function cerrarConexion();
		
		/**
		 * InterfaceCache::eliminar()
		 * 
		 * Genera el proceso de eliminar un identificador de memcache
		 * retorna valor true si se elimino, si no encuentra el
		 * identificador retornara error 
		 * 
		 * @param string $identificador: identificador del valor
		 * @param integer $espera: tiempo de espera en segundos
		 * @return bool
		 */
		public function eliminar($identificador, $espera);
		
		/**
		 * InterfaceCache::obtener()
		 * 
		 * obtiene el valor correspondiente cuando no
		 * se ha llegado al tiempo de expiracion, en este
		 * caso se retornara un valor false indicando que 
		 * ya no existe la informacion en cache
		 * 
		 * @param string $identificador
		 * @return mixed
		 */
		public function obtener($identificador);
		
		/**
		 * InterfaceCache::reemplazar()
		 * 
		 * Genera el proceso de reemplazar el valor del identificador
		 * correspondiente, cuando encuentra el identificador retornara
		 * un valor true en dado caso que no lo encuentre no genera el
		 * identificador y retorna un valor false
		 * 
		 * @param string $identificador
		 * @param mixed $valor
		 * @param integer $expiracion
		 * @return bool
		 */
		public function reemplazar($identificador, $valor, $expiracion);
	}