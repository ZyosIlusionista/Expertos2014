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
	
	class Dec {
		
		private $app = false;
		private $blowfish = false;
		private $compresion = false;
		public $clave = false;
		private $hashBinario = false;
		private $rijndael = false;
		
		private $resultado = null;
		
		/**
		 * Cod::__construct()
		 * 
		 * Genera las variables correspondientes para el proceso
		 * de codificacion
		 * 
		 * @param string $app: nombre de la aplicacion
		 * @param string $clave: (opcional) clave de codificacion
		 * @return void
		 */
		function __construct($app = false, $clave = false) {
			$this->app = $this->inputApp($app);
			$this->clave = $this->inputClave($clave);
			$this->hashBinario = ConfigAcceso::leer($app, 'criptografia', 'hashBinario');
			$this->compresion = ConfigAcceso::leer($app, 'criptografia', 'compresion');
			$this->rijndael = ConfigAcceso::leer($app, 'criptografia', 'RIJNDAEL');
			$this->blowfish = ConfigAcceso::leer($app, 'criptografia', 'BLOWFISH');
		}
		
		/**
		 * Cod::inputClave()
		 * 
		 * Genera el proceso de la asignacion de la clave correspondiente
		 * @param string $clave
		 * @return string
		 */
		private function inputClave($clave = false) {
			if(is_string($clave) == true OR is_numeric($clave) == true OR is_bool($clave) == true):
				return $clave;
			else:
				throw new Excepcion('La clave indicada no es de un formato valido para el proceso de Decodificación', 0);
			endif;
		}
		
		/**
		 * Cod::inputApp()
		 * 
		 * Genera la validacion del ingreso de la aplicacion
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Es necesaria la aplicación configurada para el proceso de Decodificación', 0);
			endif;
		}
		
		/**
		 * Cod::inputAppExistencia()
		 * 
		 * Genera la validacion de la existencia de la aplicacion
		 * para el proceso de codificacion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s no se encuentra registrada en el archivo de configuración para el procesd de Decodificación', $app), 0);
			endif;
		}
		
		/**
		 * Dec::validar()
		 * 
		 * Genera la validacion si se presenta un error
		 * en el proceso de codificacion
		 * 
		 * @param string $texto
		 * @return bool
		 */
		public function validar($texto = false) {
			if(is_null($this->resultado) == true):
				return $this->validarProcesar($texto);
			else:
				return false;
			endif;
		}
		
		private function validarProcesar($texto = false) {
			
			$decodificar = $this->formatoJson($texto);
			return $decodificar;
		}
		
		private function formatoBase64($texto = false) {
			$formato = base64_decode($texto);
			if($formato == true):
			
			else:
			
			endif;
		}
		
		/**
		 * Dec::formatoJson()
		 * 
		 * Genera la conversion de formato json
		 * 
		 * @param string $texto
		 * @return array
		 */
		private function formatoJson($texto = false) {
			$formato = json_decode($texto, true);
			if(json_last_error() == JSON_ERROR_NONE):
				return array('validar' => (boolean) true, 'contenido' => $formato);
			else:
				return array('validar' => (boolean) false, 'contenido' => (boolean) false);
			endif;
		}
		
	}