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
	
	namespace Sistema\Controlador;
	use \Neural\Excepcion;
	
	class Validar {
		
		/**
		 * Validar::email()
		 * 
		 * Genera el proceso de validacion de
		 * email, esta retorna true si es correcto
		 * o false si no corresponde como un correo
		 * 
		 * @param string $correo
		 * @return bool
		 */
		public function email($correo = false) {
			$validacion = filter_var($correo, FILTER_VALIDATE_EMAIL);
			return (is_string($validacion) == true) ? (boolean) true : false;
		}
		
		/**
		 * Validar::url()
		 * 
		 * Genera el proceso de validacion de la
		 * url el cual solo valida el formato 
		 * mas no valida su existencia, solo su formato
		 * 
		 * @param string $url
		 * @example http://ejemplo.com
		 * @return bool
		 */
		public function url($url = false) {
			$validacion = filter_var($url, FILTER_VALIDATE_URL);
			return (is_string($validacion) == true) ? (boolean) true : false;
		}
		
		/**
		 * Validar::ip()
		 * 
		 * Genera el proceso de validacion si es
		 * una IP valida en los rangos correctos
		 *  
		 * @param string $ip
		 * @return bool
		 */
		public function ip($ip = false) {
			$validacion = filter_var($ip, FILTER_VALIDATE_IP);
			return (is_string($validacion) == true) ? (boolean) true : false;
		}
		
		/**
		 * Validar::ipv4()
		 * 
		 * Genera el proceso de validacion si es
		 * una IP valida en los rangos correctos
		 * y si es una IP en formato IPV4
		 *  
		 * @param string $ip
		 * @return bool
		 */
		public function ipv4($ip = false) {
			$validacion = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
			return (is_string($validacion) == true) ? (boolean) true : false;
		}
		
		/**
		 * Validar::ipv6()
		 * 
		 * Genera el proceso de validacion si es
		 * una IP valida en los rangos correctos
		 * y si es una IP en formato IPV6
		 *  
		 * @param string $ip
		 * @return bool
		 */
		public function ipv6($ip = false) {
			$validacion = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
			return (is_string($validacion) == true) ? (boolean) true : false;
		}
		
		/**
		 * Validar::rangoEntero()
		 * 
		 * Genera el proceso de validacion de 
		 * del valor entero este sobre el rango
		 * indicado
		 * 
		 * @param integer $numero
		 * @param integer $inicial
		 * @param integer $final
		 * @return bool
		 */
		public function rangoEntero($numero = false, $inicial = false, $final = false) {
			$validacion = filter_var($numero, FILTER_VALIDATE_INT, array('options' => array('min_range' => $inicial, 'max_range' => $final)));
			return (is_numeric($validacion) == true) ? (boolean) true : false;
		}
	}