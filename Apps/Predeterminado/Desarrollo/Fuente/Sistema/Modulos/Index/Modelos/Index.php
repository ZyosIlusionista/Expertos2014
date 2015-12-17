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
	use \Neural\BD\Conexion;
	
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
		
		private $entidad = false;
		
		function __construct() {
			$conexion = new Conexion(APPBD, APP);
			$this->entidad = $conexion->doctrineORM();
		}
		
		/**
		 * Index::consultaUsuario()
		 * 
		 * Genera la consulta de los datos del usuario
		 * 
		 * @param array $array
		 * @return object
		 */
		public function consultaUsuario($array = false) {
			return $this->entidad->getRepository('\Entidades\Expertos\Usuarios')->findOneBy($array);
		}
	}