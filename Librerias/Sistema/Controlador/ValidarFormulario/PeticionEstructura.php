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
	
	class PeticionEstructura extends Validaciones {
		
		private $peticion = false;
		private $propiedad = false;
		private $validaciones = false;
		private $mensajes = false;
		
		/**
		 * PeticionEstructura::__construct()
		 * 
		 * Genera las variables necesarias para el proceso
		 * correspondiente
		 * 
		 * @param object $peticion
		 * @param array $propiedades
		 * @return void
		 */
		function __construct($peticion = false, $propiedades = false) {
			$this->peticion = $peticion;
			$this->propiedad = $propiedades;
			$this->validaciones = array_flip(array('ip', 'ipv4', 'ipv6', 'correo', 'finito', 'float', 'numero', 'texto', 'requerido'));
		}
		
		/**
		 * PeticionEstructura::mensajes()
		 * 
		 * Retorna los mensajes de error generados en el
		 * proceso de validacion
		 * 
		 * @return array
		 */
		public function mensajes() {
			return $this->mensajes;
		}
		
		/**
		 * PeticionEstructura::procesar()
		 * 
		 * Ejecuta el proceso correspondiente
		 * 
		 * @return bool
		 */
		public function procesar() {
			$this->formulario();
			return (is_array($this->mensajes) == true) ? (boolean) false : (boolean) true;
		}
		
		/**
		 * PeticionEstructura::formulario()
		 * 
		 * Inicial el proceso correspondiente
		 * de validacion
		 * 
		 * @return void
		 */
		private function formulario() {
			foreach ($this->propiedad['formulario'] AS $campo => $param):
				$this->formularioExistencia($campo, $param);
			endforeach;
		}
		
		/**
		 * PeticionEstructura::formularioExistencia()
		 * 
		 * Valida si hay que generar la validación de existencia
		 * del campos en los datos enviados
		 * 
		 * @param string $campo
		 * @param array $param
		 * @return void
		 */
		private function formularioExistencia($campo = false, $param = false) {
			if($param['existencia'] == true):
				$this->formularioExistenciaVal($campo, $param);
			endif;
			$this->formularioCampoExistencia($campo, $param);
		}
		
		/**
		 * PeticionEstructura::formularioExistenciaVal()
		 * 
		 * Genera la validacion de la existencia del campo
		 * dentro de la matriz de datos enviados por el formulario
		 * 
		 * @param bool $campo
		 * @return void
		 */
		private function formularioExistenciaVal($campo = false) {
			if(array_key_exists($campo, $this->peticion) == false):
				throw new Excepcion(sprintf('El campo: [ %s ] no se encuentra en los datos enviados por el formulario', $campo), 0);
			endif;
		}
		
		/**
		 * PeticionEstructura::formularioCampoExistencia()
		 * 
		 * Genera la validacion de la existencia del campo
		 * dentro de la matriz de datos enviados por el formulario
		 * 
		 * @param string $campo
		 * @param array $param
		 * @return void
		 */
		private function formularioCampoExistencia($campo = false, $param = false) {
			if(array_key_exists($campo, $this->peticion) == true):
				$this->formularioValidacion($campo, $param);
			endif;
		}
		
		/**
		 * PeticionEstructura::formularioValidacion()
		 * 
		 * Genera la validación de la existencia de validaciones en el
		 * campo del formulario
		 * 
		 * @param string $campo
		 * @param array $param
		 * @return void
		 */
		private function formularioValidacion($campo = false, $param = false) {
			if(is_array($param['validacion']) == true):
				$this->formularioValidar($campo, $param['validacion']);
			else:
				throw new Excepcion(sprintf('No hay validaciones para el campo: [ %s ] en el proceso de validación de formularios', $campo), 0);
			endif;
		}
		
		/**
		 * PeticionEstructura::formularioValidar()
		 * 
		 * Se recorre la matriz de validaciones para el proceso
		 * base de validacion
		 * 
		 * @param string $campo
		 * @param array $validacion
		 * @return void
		 */
		private function formularioValidar($campo = false, $validacion = false) {
			foreach ($validacion AS $metodo => $mensaje):
				$this->formularioMetodoExistencia($campo, $metodo, $mensaje);
			endforeach;
		}
		
		/**
		 * PeticionEstructura::formularioMetodoExistencia()
		 * 
		 * Genera la validación de la funcion de validacion
		 * requerida
		 * 
		 * @param string $campo
		 * @param string $metodo
		 * @param string $mensaje
		 * @return void
		 */
		private function formularioMetodoExistencia($campo = false, $metodo = false, $mensaje = false) {
			if(array_key_exists($metodo, $this->validaciones) == true):
				$this->formularioMetodoTipo($campo, $metodo, $mensaje);
			else:
				throw new Excepcion(sprintf('La validación: [ %s ] no existe en el proceso de validación del campo: [ %s ] en la validación de formularios', $metodo, $campo), 0);
			endif;
		}
		
		/**
		 * PeticionEstructura::formularioMetodoTipo()
		 * 
		 * Genera la validacion del campo correspondiente
		 * 
		 * @param string $campo
		 * @param string $metodo
		 * @param string $mensaje
		 * @return void
		 */
		private function formularioMetodoTipo($campo = false, $metodo = false, $mensaje = false) {
			if(is_array($this->peticion[$campo]) == true):
				$this->formularioMetodoArray($campo, $metodo, $mensaje);
			else:
				$this->formularioMetodoString($campo, $metodo, $mensaje);
			endif;
		}
		
		/**
		 * PeticionEstructura::formularioMetodoArray()
		 * 
		 * Genera la validacion seleccionada
		 * 
		 * @param strig $campo
		 * @param strig $metodo
		 * @param strig $mensaje
		 * @return void
		 */
		private function formularioMetodoArray($campo = false, $metodo = false, $mensaje = false) {
			foreach ($this->peticion[$campo] AS $llave => $valor):
				if(call_user_func_array(array($this, $metodo), array($valor)) == false):
					$this->mensajes[] = (is_string($mensaje) == true) ? $mensaje : sprintf('Se ha presentado un error en la validación: [ %s ] en el campo [ %s ] en el registro [ %d ]', $metodo, $campo, $llave);
				endif;
			endforeach;
		}
		
		/**
		 * PeticionEstructura::formularioMetodoString()
		 * 
		 * Genera la validacion seleccionada
		 * 
		 * @param string $campo
		 * @param string $metodo
		 * @param string $mensaje
		 * @return void
		 */
		private function formularioMetodoString($campo = false, $metodo = false, $mensaje = false) {
			if(call_user_func_array(array($this, $metodo), array($this->peticion[$campo])) == false):
				$this->mensajes[] = (is_string($mensaje) == true) ? $mensaje : sprintf('Se ha presentado un error en la validación: [ %s ] en el campo [ %s ]', $metodo, $campo);
			endif;
		}
	}