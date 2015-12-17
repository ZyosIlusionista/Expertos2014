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
	
	/**
	 * Namespace Modelo Modulos
	 * 
	 * Se genera el namespace para el modulo
	 * el cual se diferencia la carga de la misma
	 * @example namespace Modelo\Modulo\{nombre del modulo}
	 */
	namespace Modelo\Modulo\Index;
	use \Neural\Excepcion;
	
	/**
	 * Modelo Index
	 * 
	 * El Modelo debe ser generado en notacion UpperCamelCasse
	 * no debe contener simbolos como [\][_][-]
	 * 
	 * El nombre del archivo del Modelo y de la clase del modelo 
	 * debe ser igual a la clase controlador
	 */
	class Index {
		
		/**
		 * Index::consultaSQL()
		 * 
		 * Los metodos pueden ser invocados por el controlador
		 * sin importar su nombre pero solo con la visualizacion
		 * publica que se requiere
		 * 
		 * @return void
		 */
		public function consultaSQL() {
			return 'Se carga el Modulo Index, controlador Index y Metodo Index';
		}
	}