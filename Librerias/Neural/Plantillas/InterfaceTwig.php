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
	
	namespace Neural\Plantillas;
	
	interface InterfaceTwig {
		
		/**
		 * InterfaceTwig::parametro()
		 * 
		 * Genera un parametro o variable para ser ejecutado
		 * dentro del motor de plantillas twig
		 * 
		 * @param string $nombre: nombre del parametro
		 * @param mixed $valor: el valor puede ser bool, array, int, str
		 * @return object
		 */
		public function parametro($nombre, $valor);
		
		/**
		 * InterfaceTwig::parametroGlobal()
		 * 
		 * Genera un parametro o variable para ser ejecutado
		 * dentro del motor de plantillas twig
		 * 
		 * @param string $nombre: nombre del parametro
		 * @param mixed $valor: el valor puede ser bool, array, int, str
		 * @return object
		 */
		public function parametroGlobal($nombre, $valor);
		
		/**
		 * InterfaceTwig::filtro()
		 * 
		 * Asigna un filtro para ser utilizado dentro
		 * del motor de plantilla twig
		 * 
		 * @param string $nombre: nombre del filtro
		 * @param object $funcion: funcion anonima
		 * @return object
		 */
		public function filtro($nombre, $funcion);
		
		/**
		 * InterfaceTwig::funcion()
		 * 
		 * Asigna una funcion dentro del motor de plantillas
		 * twig
		 * 
		 * @param string $nombre: nombre de la funcion
		 * @param object $funcion: funcion anonima
		 * @return object
		 */
		public function funcion($nombre, $funcion);
		
		/**
		 * InterfaceTwig::mostrarPlantilla()
		 * 
		 * Muestra la plantilla correspondiente
		 * @return string
		 */
		public function mostrarPlantilla();
	}