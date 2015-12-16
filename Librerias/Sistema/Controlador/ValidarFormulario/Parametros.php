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
	
	class Parametros {
		
		private $parametros = false;
		private $clase = array('formulario', 'metodo', 'tipo');
		private $validacion = array('columna', 'validacion', 'existencia', 'tipo');
		
		/**
		 * Parametros::__construct()
		 * 
		 * Genera las variables correspondientes
		 * @param string $namespace
		 * @return void
		 */
		function __construct($namespace = false) {
			$reflexion = new Reflexion($namespace);
			$this->parametros = $reflexion->obtener();
		}
		
		/**
		 * Parametros::obtener()
		 * 
		 * Retorna los campos del formulario para su validacion
		 * @return array
		 */
		public function obtener() {
			if(is_array($this->parametros) == true):
				$this->validacionClase();
				$this->validacionFormulario();
				return $this->parametros;
			else:
				throw new Excepcion('No hay parametros para procesar el la Validación del Formulario', 0);
			endif;
		}
		
		/**
		 * Parametros::validacionClase()
		 * 
		 * Genera la validacion de la existencia de los parametros
		 * requeridos para el procedimiento
		 * 
		 * @return void
		 */
		private function validacionClase() {
			foreach ($this->clase AS $valor):
				if(array_key_exists($valor, $this->parametros['clase']) == false):
					throw new Excepcion(sprintf('En el validador no se encuentra la propiedad [ %s ] para la validación del formulario', $valor), 0);
				endif;
			endforeach;
			unset($valor);
		}
		
		/**
		 * Parametros::validacionFormulario()
		 * 
		 * Genera la validacion de los campos correspondientes
		 * @return void
		 */
		private function validacionFormulario() {
			foreach ($this->parametros['formulario'] AS $campo => $param):
				$this->validacionFormularioParam($campo, $param);
			endforeach;
		}
		
		/**
		 * Parametros::validacionFormularioParam()
		 * 
		 * Genera la validacion de los campos correspondientes
		 * 
		 * @param string $campo
		 * @param array $param
		 * @return void
		 */
		private function validacionFormularioParam($campo = false, $param = false) {
			foreach ($this->validacion AS $valor):
				if(array_key_exists($valor, $param) == false):
					throw new Excepcion(sprintf('El campo: [ %s ] no existe el parametro: [ %s ] necesaria para la validación del formulario', $campo, $valor), 0);
				endif;
			endforeach;
			unset($campo, $param, $valor);
		}
	}