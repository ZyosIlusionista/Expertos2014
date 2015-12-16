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
	
	namespace Mvc;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	
	class Rutas {
		
		private $app = false;
		private $entorno = false;
		private $protocolo = false;
		private $envApache = false;
		
		/**
		 * Rutas::__construct()
		 * 
		 * Genera las variables necesarias para
		 * el proceso correspondiente
		 * 
		 * @param string $app
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $entorno = false) {
			$this->app = $this->inputApp($app);
			$this->entorno = $this->inputEntorno($entorno);
			$this->protocolo = (isset($_SERVER['HTTPS']) == true) ? 'https' : 'http';
			$this->envApache = $this->apache();
		}
		
		/**
		 * Rutas::apache()
		 * 
		 * Genera la validacion de modo apache
		 * @return bool
		 */
		private function apache() {
			if(defined('ENV_APACHE') == true):
				return (is_bool(ENV_APACHE) == true AND ENV_APACHE == true) ? true : false;
			else:
				return false;
			endif;
		}
		
		/**
		 * Rutas::inputEntorno()
		 * 
		 * Genera la validacion y la asignacion del entorno
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoSeleccion($entorno);
			else:
				return $this->inputEntornoExistencia();
			endif;
		}
		
		/**
		 * Rutas::inputEntornoExistencia()
		 * 
		 * Genera la validacion si esta asignado un
		 * entorno correspondientes
		 * 
		 * @return string
		 */
		private function inputEntornoExistencia() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('Debe asignar un entorno para la construcción del proceso de Rutas', 0);
			endif;
		}
		
		/**
		 * Rutas::inputEntornoSeleccion()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s, no es valido para el manejo de las Rutas', $entorno), 0);
			endif;
		}
		
		/**
		 * Rutas::inputApp()
		 * 
		 * Valida si se esta ingresando la aplicacion
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Debe ingresar la aplicación para el proceso de Rutas', 0);
			endif;
		}
		
		/**
		 * Rutas::inputAppExistencia()
		 * 
		 * Valida la existencia de la aplicacion
		 * en el archivo de configuracion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s, no existe en el archivo de configuración para el proceso de Rutas', $app), 0);
			endif;
		}
		
		/**
		 * Rutas::host()
		 * 
		 * Genera la URL base
		 * @return string
		 */
		private function host() {
			if($this->envApache == true):
				return $this->protocolo.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
			else:
				return $this->protocolo.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
			endif;
		}
		
		/**
		 * Rutas::hostSrc()
		 * 
		 * Genera la URL para Src
		 * @return string
		 */
		private function hostSrc() {
			return $this->protocolo.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/Src';
		}
		
		/**
		 * Rutas::mvc()
		 * 
		 * Genera la ruta mvc
		 * @param string $controlador
		 * @param string $metodo
		 * @param string $parametros
		 * @return string
		 */
		public function mvc($controlador = false, $metodo = false, $parametros = false) {
			$param = array_merge(array($this->host(), $this->app), func_get_args());
			return rtrim(implode('/', $param), '/');
		}
		
		/**
		 * Rutas::modulo()
		 * 
		 * Genera la ruta al modulo
		 * @param string $controlador
		 * @param string $metodo
		 * @param string $parametros
		 * @return string
		 */
		public function modulo($modulo = false, $controlador = false, $metodo = false, $parametros = false) {
			$param = array_merge(array($this->host(), $this->app), func_get_args());
			return rtrim(implode('/', $param), '/');
		}
		
		/**
		 * Rutas::publico()
		 * 
		 * Genera la ruta correspondiente
		 * para los archivos publicos
		 * 
		 * @return string
		 */
		public function publico() {
			$param = array_merge(array($this->hostSrc(), \hash('adler32', $this->app), \hash('adler32', $this->entorno)), func_get_args());
			return rtrim(implode('/', $param), '/');
		}
		
		/**
		 * Rutas::protegido()
		 * 
		 * Genera la ruta al controlador contPublico
		 * @return string
		 */
		public function protegido() {
			$param = array_merge(array($this->host(), $this->app, 'contPublico', 'publico'), func_get_args());
			return rtrim(implode('/', $param), '/');
		}
		
		/**
		 * Rutas::reservado()
		 * 
		 * Genera la ruta correspondiente para el
		 * contPublico reservado por neuralPHP
		 * 
		 * @return string
		 */
		public function reservado() {
			$param = array_merge(array($this->host(), $this->app, 'contPublico', 'reservado'), func_get_args());
			return rtrim(implode('/', $param), '/');
		}
	}