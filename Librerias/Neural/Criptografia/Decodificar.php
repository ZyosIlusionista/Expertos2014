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
	
	class Decodificar {
		
		private $confg = false;
		public $clave = false;
		private $estado = false;
		private $resultado = false;
		
		/**
		 * Decodificar::__construct()
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
		 * Decodificar::constructorInputConfg()
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
				throw new Excepcion('Debe ingresar el nombre de la aplicación para la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::constructorInputConfgExistencia()
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
				throw new Excepcion('La aplicación ingresada no existe en el archivo de configuración para la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::constructorInputClave()
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
				throw new Excepcion('Debe ingresar una clave como numerico, alfabetico o alfanumerico en la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::procesar()
		 * 
		 * Genera el proceso de decodificacion de los
		 * datos ingresados
		 * 
		 * @param string $texto
		 * @return string
		 */
		public function procesar($texto = false) {
			if($this->estado == false):
				return $this->procesarString($texto);
			else:
				return $this->resultado;
			endif;
		}
		
		/**
		 * Decodificar::procesarString()
		 * 
		 * Determina si ya se genero un descifrado inicial
		 * y se evalua si se regresa el valor decodificado
		 * o se genera el proceso de decodificacion
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function procesarString($texto = false) {
			if(is_string($texto) == true):
				return $this->decodificar($texto);
			else:
				throw new Excepcion('Debe ingresar el texto correspondiente para ser procesado en la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::decodificar()
		 * 
		 * Genera el proceso de decodificacion correspondiente
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function decodificar($texto = false) {
			$dec = $this->formatoBase64($texto);
			$dec = $this->formatoBase64($dec);
			
			if($this->confg['compresion']['habilitado'] == true):
				$dec = $this->descompresion($dec);
			endif;
			
			$dec = $this->formatoJson($dec);
			$dec = $this->descifrado(MCRYPT_BLOWFISH, $this->confg['BLOWFISH'], $dec);
			
			if(is_bool($this->clave) == false):
				$dec = $this->formatoJson($dec);
				$clave = hash('ripemd160', $this->clave, $this->confg['hashBinario']);
				$dec = $this->descifrado(MCRYPT_BLOWFISH, $clave, $dec);
			endif;
			
			$dec = $this->formatoJson($dec);
			$dec = $this->descifrado(MCRYPT_BLOWFISH, $this->confg['BLOWFISH'], $dec);
			$dec = $this->formatoJson($dec);
			$dec = $this->descifrado(MCRYPT_RIJNDAEL_256, $this->confg['RIJNDAEL'], $dec);
			return $this->formatoJson($dec);
		}
		
		/**
		 * Decodificar::formatoBase64()
		 * 
		 * Genera el proceso de conversion de base64
		 * en caso de error se genera la excepcion correspondiente
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function formatoBase64($texto = false) {
			$formato = base64_decode($texto);
			if($formato == true):
				return $formato;
			else:
				throw new Excepcion('Se ha presentado un error en la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::descompresion()
		 * 
		 * Se genera la validacion de la existencia de la funcion
		 * de descompresion gzuncompress
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function descompresion($texto = false) {
			if(function_exists('gzuncompress') == true):
				return $this->descompresionProceso($texto);
			else:
				throw new Excepcion('No se encuentra la función gzuncompress instalada para el proceso de Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::descompresionProceso()
		 * 
		 * Genera el proceso de descompresion correspondiente
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function descompresionProceso($texto = false) {
			$descompresion = gzuncompress($texto);
			if($descompresion == true):
				return $descompresion;
			else:
				throw new Excepcion('Se ha presentado un error en la descompresión de la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::formatoJson()
		 * 
		 * Genera la validacion y el proceso de conversion 
		 * del string a array
		 * 
		 * @param string $texto
		 * @return string
		 */
		private function formatoJson($texto = false) {
			$formato = json_decode($texto, true);
			if(json_last_error() == JSON_ERROR_NONE):
				return $this->formatoJsonValidar($formato);
			else:
				throw new Excepcion('Se ha presentado un error en el formato del texto codificado en la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::formatoJsonValidar()
		 * 
		 * Genera la validacion si el array corresponde
		 * al formato establecido
		 * 
		 * @param array $array
		 * @return string
		 */
		private function formatoJsonValidar($array = false) {
			if(is_array($array) == true AND isset($array['val']) == true):
				return $array['txt'];
			else:
				throw new Excepcion('Se ha presentado un error en el formato del texto codificado en la Decodificación', 0);
			endif;
		}
		
		/**
		 * Decodificar::descifrado()
		 * 
		 * Genera el proceso de descifrado correspondiente
		 * 
		 * @param integer $codif
		 * @param string $llave
		 * @param string $texto
		 * @return string
		 */
		private function descifrado($codif = false, $llave = false, $texto = false) {
			$base64 = $this->formatoBase64($texto);
			$tamano = mcrypt_get_iv_size($codif, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($tamano, MCRYPT_RAND);
			return trim(mcrypt_decrypt($codif, $llave, $base64, MCRYPT_MODE_ECB, $iv));
		}
	}