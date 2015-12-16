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
	
	class Validaciones extends \Sistema\Controlador\Validar {
		
		/**
		 * Validaciones::requerido()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function requerido($valor = false) {
			return (empty($valor) == false) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Validaciones::texto()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function texto($valor = false) {
			return (is_string($valor) == true) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Validaciones::numero()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function numero($valor = false) {
			return (is_numeric($valor) == true) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Validaciones::float()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function float($valor = false) {
			return (is_float($valor) == true) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Validaciones::finito()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function finito($valor = false) {
			return (is_finite($valor) == true) ? (boolean) true : (boolean) false;
		}
		
		/**
		 * Validaciones::correo()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function correo($valor = false) {
			return $this->email($valor);
		}
		
		/**
		 * Validaciones::ip()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function ip($valor = false) {
			return $this->ip($valor);
		}
		
		/**
		 * Validaciones::ipv4()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function ipv4($valor = false) {
			return $this->ipv4($valor);
		}
		
		/**
		 * Validaciones::ipv6()
		 * 
		 * Genera la validacion correspondiente
		 * @param string $valor
		 * @return bool
		 */
		public function ipv6($valor = false) {
			return $this->ipv6($valor);
		}
		
		/**
		 * Validaciones::ninguno()
		 * 
		 * Genera el proceso de no validacion
		 * en un campo especifico
		 * @param bool $valor
		 * @return
		 */
		public function ninguno($valor = false) {
			return (boolean) true;
		}
	}