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
	
	namespace Sistema\Controlador\ValidarFormulario;
	use \Neural\Excepcion;
	
	class Reflexion {
		
		private $reflexion = false;
		private $propiedad = false;
		private $formato = false;
		
		/**
		 * Reflexion::__construct()
		 * 
		 * Genera las variables correspondientes
		 * 
		 * @param string $namespace
		 * @return void
		 */
		function __construct($namespace = false) {
			$this->propiedad = self::lectura($namespace);
		}
		
		/**
		 * Reflexion::lectura()
		 * 
		 * Genera la lectura de las propiedades del
		 * objeto correspondiente
		 * 
		 * @param string $namespace
		 * @return object
		 */
		private function lectura($namespace = false) {
			if(is_string($namespace) == true):
				$this->reflexion = new \ReflectionClass($namespace);
				return $this->reflexion->getProperties();
			else:
				throw new Excepcion('Debe especificar el namespace para proceso de validación de formularios', 0);
			endif;
		}
		
		/**
		 * Reflexion::obtener()
		 * 
		 * Retorna el proceso de listar los parametros del
		 * formulario y sus parametros correspondientes
		 * 
		 * @return array
		 */
		public function obtener() {
			if(is_array($this->propiedad) == true):
				return $this->obtenerPropiedad();
			else:
				throw new Excepcion('No hay propiedades para procesar en la validación de Formularios', 0);
			endif;
		}
		
		/**
		 * Reflexion::obtenerPropiedad()
		 * 
		 * Genera la lista correspondiente para el proceso
		 * de formato de parametros y campos
		 * 
		 * @return array
		 */
		private function obtenerPropiedad() {
			$lista['clase'] = ReflexionFormato::comentario('Clase', $this->reflexion->getDocComment());
			foreach ($this->propiedad AS $param):
				$lista['formulario'][$param->name] = ReflexionFormato::comentario($param->name, $this->reflexion->getProperty($param->name)->getDocComment());
			endforeach;
			return $lista;
		}
	}