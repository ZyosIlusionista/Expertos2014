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
	
	namespace Neural\Criptografia;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	
	class Codificar {
		
		private $confg = false;
		public $clave = false;
		
		/**
		 * Codificar::__construct()
		 * 
		 * Genera las variables correspondientes para
		 * el proceso de codificacion
		 * 
		 * @param string $app: nombre de la aplicacion
		 * @param string $clave: clave de la codificacion
		 * @return void
		 */
		function __construct($app = false, $clave = false) {
			$this->confg = $this->constructorInputConfg($app);
			$this->clave = $this->constructorInputClave($clave);
		}
		
		/**
		 * Codificar::constructorInputConfg()
		 * 
		 * Determina si se ha ingresado el nombre de la aplicacion
		 * para tomar los datos correspondientes de la configuracion
		 * 
		 * @param string $app: nombre de la aplicacion
		 * @return array
		 */
		private function constructorInputConfg($app = false) {
			if(is_string($app) == true):
				return $this->constructorInputConfgExistencia($app);
			else:
				throw new Excepcion('Debe ingresar el nombre de la aplicación para la Codificación', 0);
			endif;
		}
		
		/**
		 * Codificar::constructorInputConfgExistencia()
		 * 
		 * Genera la validacion de la existencia de la aplicacion
		 * y retorna la configuracion para el proceso
		 * 
		 * @param string $app
		 * @return array
		 */
		private function constructorInputConfgExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return ConfigAcceso::leer($app, 'criptografia');
			else:
				throw new Excepcion('La aplicación ingresada no existe en el archivo de configuración para la Codificación', 0);
			endif;
		}
		
		/**
		 * Codificar::constructorInputClave()
		 * 
		 * Valida el proceso de la clave correspondiente
		 * 
		 * @param string $clave
		 * @return mixed
		 */
		private function constructorInputClave($clave = false) {
			if(is_object($clave) == false AND is_array($clave) == false):
				return $clave;
			else:
				throw new Excepcion('Debe ingresar una clave como numerico, alfabetico o alfanumerico en la Codificación', 0);
			endif;
		}
		
		/**
		 * Codificar::procesar()
		 * 
		 * Genera el proceso de codificacion 
		 * @param bool $texto
		 * @return void
		 */
		public function procesar($texto = false) {
			if(is_object($texto) == false AND is_bool($texto) == false):
				return $this->codificar($texto);
			else:
				throw new Excepcion('Ingrese el texto corespondiente a Codificar', 0);
			endif;
		}
		
		/**
		 * Codificar::codificar()
		 * 
		 * Genera los pasos basicos de codificacion
		 * de los datos solicitados
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function codificar($texto = false) {
			$cod = $this->formatoJson($texto);
			$cod = $this->cifrado(MCRYPT_RIJNDAEL_256, $this->confg['RIJNDAEL'], $cod);
			$cod = $this->formatoJson($cod);
			$cod = $this->cifrado(MCRYPT_BLOWFISH, $this->confg['BLOWFISH'], $cod);
			$cod = $this->formatoJson($cod);
			
			if(is_bool($this->clave) == false):
				$clave = hash('ripemd160', $this->clave, $this->confg['hashBinario']);
				$cod = $this->cifrado(MCRYPT_BLOWFISH, $clave, $cod);
				$cod = $this->formatoJson($cod);
			endif;
			
			$cod = $this->cifrado(MCRYPT_BLOWFISH, $this->confg['BLOWFISH'], $cod);
			$cod = $this->formatoJson($cod);
			
			if($this->confg['compresion']['habilitado'] == true):
				$cod = $this->compresion($cod);
			endif;
			
			return base64_encode(base64_encode($cod));
		}
		
		/**
		 * Codificar::formatoJson()
		 * 
		 * Genera el formato correspondiente json
		 * para ser codificado
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function formatoJson($texto = false) {
			return json_encode(array('val' => true, 'txt' => $texto));
		}
		
		/**
		 * Codificar::cifrado()
		 * 
		 * Genera el proceso de cifrado correspondiente
		 * 
		 * @param integer $codif
		 * @param string $llave
		 * @param string $texto
		 * @return string
		 */
		private function cifrado($codif = false, $llave = false, $texto = false) {
			$tamano = mcrypt_get_iv_size($codif, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($tamano, MCRYPT_RAND);
			$crypt = mcrypt_encrypt($codif, $llave, $texto, MCRYPT_MODE_ECB, $iv);
			return base64_encode($crypt);
		}
		
		/**
		 * Codificar::compresion()
		 * 
		 * Genera la validacion de la existencia de la
		 * funcion gzcompress 
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function compresion($texto = false) {
			if(function_exists('gzcompress') == true):
				return $this->compresionProceso($texto);
			else:
				throw new Excepcion('No se encuentra la función gzcompress instalada para el proceso de Codificación', 0);
			endif;
		}
		
		/**
		 * Codificar::compresionProceso()
		 * 
		 * Genera el proceso de compresion y valida si
		 * se ha presentado algun tipo de error en la compresion
		 * correspondiente
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function compresionProceso($texto = false) {
			$compresion = gzcompress($texto, $this->confg['compresion']['nivelCompresion']);
			if($compresion == true):
				return $compresion;
			else:
				throw new Excepcion('Se ha presentado un error en la compresión de la Codificación', 0);
			endif;
		}
	}