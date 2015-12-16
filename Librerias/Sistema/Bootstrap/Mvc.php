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
	
	namespace Sistema\Bootstrap;
	use \Neural\Excepcion;
	
	class Mvc {
		
		private $app = false;
		private $clase = false;
		private $directorio = false;
		private $entorno = false;
		private $modReWrite = false;
		
		/**
		 * Mvc::__construct()
		 * 
		 * Asigna las variables necesarias para el 
		 * procesamiento del controlador MVC
		 * 
		 * @param string $directorio
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $directorio = false, $entorno = false, $modReWrite = false) {
			$this->app = $app;
			$this->directorio = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $entorno, 'Fuente', 'Sistema', 'MVC', 'Controladores'));
			$this->entorno = $entorno;
			$this->modReWrite = $modReWrite;
			if(defined('ENV_TIPO') == false):
				define('ENV_TIPO', 'MVC');
			endif;
		}
		
		/**
		 * Mvc::ejecutar()
		 * 
		 * Genera la ejecucion del proceso de validacion
		 * del controlador correspondiente
		 * @return void
		 */
		public function ejecutar() {
			$this->archivoSeleccion();
		}
		
		/**
		 * Mvc::archivoSeleccion()
		 * 
		 * Genera la validacion si debe generar la carga
		 * del archivo controlador MVC o del controlador
		 * de ayuda que genera la lectura por stream de
		 * archivos publicos
		 * 
		 * @return void
		 */
		private function archivoSeleccion() {
			if($this->modReWrite['controlador'] == 'contPublico'):
				$this->clase = '\\Sistema\\Utilidades\\contPublico';
				$this->archivoExistencia(ROOT_SISTEMA.DIRECTORY_SEPARATOR.'Utilidades'.DIRECTORY_SEPARATOR.'contPublico.php');
			else:
				$this->clase = '\\Controlador\\MVC\\'.$this->modReWrite['controlador'];
				$this->archivoExistencia($this->directorio.DIRECTORY_SEPARATOR.$this->modReWrite['controlador'].'.php');
			endif;
		}
		
		/**
		 * Mvc::archivoExistencia()
		 * 
		 * Genera la validacion de la existencia del archivo 
		 * del controlador dentro del mvc
		 * 
		 * @return void
		 */
		private function archivoExistencia($archivo = false) {
			if(file_exists($archivo) == true):
				$this->archivoLectura($archivo);
			else:
				throw new Excepcion(sprintf('El controlador: %s no existe en el MVC en el entorno: %s', $this->modReWrite['controlador'], $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Mvc::archivoLectura()
		 * 
		 * Genera la validacion de la lectura del
		 * archivo controlador del mvc
		 * 
		 * @return void
		 */
		private function archivoLectura($archivo = false) {
			if(is_readable($archivo) == true):
				$this->controladorExistencia();
			else:
				throw new Excepcion(sprintf('El controlador: %s no es posible leerlo en el MVC en el entorno: %s', $this->modReWrite['controlador'], $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Mvc::controladorExistencia()
		 * 
		 * Genera la validacion si dentro del archivo
		 * del controlador se encuentra la clase solicitada
		 * 
		 * @return void
		 */
		private function controladorExistencia() {
			if(class_exists($this->clase) == true):
				$this->controladorExistenciaMetodo();
			else:
				throw new Excepcion(sprintf('El controlador: %s no contiene la clase solicitada en el MVC en el entorno: %s', $this->modReWrite['controlador'], $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Mvc::controladorExistenciaMetodo()
		 * 
		 * Genera la validacion de la existencia del
		 * metodo solicitado
		 * 
		 * @return void
		 */
		private function controladorExistenciaMetodo() {
			$metodos = array_flip(get_class_methods($this->clase));
			if(array_key_exists($this->modReWrite['metodo'], $metodos) == true):
				$this->objetoEjecutar($this->clase, $this->modReWrite['metodo']);
			elseif(method_exists($this->clase, $this->modReWrite['metodo']) == true):
				throw new Excepcion(sprintf('El metodo: %s del controlador: %s solicitado es privado o protegido en el MVC en el entorno: %s', $this->modReWrite['metodo'], $this->modReWrite['controlador'], $this->entorno), 0, $this->app);
			else:
				throw new Excepcion(sprintf('El metodo: %s del controlador: %s solicitado no existe en el MVC en el entorno: %s', $this->modReWrite['metodo'], $this->modReWrite['controlador'], $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Mvc::objetoEjecutar()
		 * 
		 * Genera el proceso de ejecucion del controlador
		 * correspondiente
		 * 
		 * @param string $controlador
		 * @param string $metodo
		 * @return raw
		 */
		private function objetoEjecutar($controlador = false, $metodo = false) {
			$parametros = (is_array($this->modReWrite['parametro']) == true) ? $this->modReWrite['parametro'] : array(false);
			call_user_func_array(array(new $controlador, $metodo), $parametros);
		}
	}