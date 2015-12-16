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
	use \Sistema\Utilidades\ConfigAcceso;
	
	class ImportarModelo {
		
		private $app = false; 
		private $controlador = false;
		private $entorno = false;
		private $modulo = false;
		private $tipo = false;
		private $directorio = false;
		
		/**
		 * ImportarModelo::__construct()
		 * 
		 * Genera las variables necesarias para 
		 * el proceso
		 * 
		 * @param string $app
		 * @param string $modulo
		 * @param string $controlador
		 * @return void
		 */
		function __construct($app = false, $modulo = false, $controlador = false, $entorno = false, $tipo = false) {
			$this->app = $this->validarApp($app);
			$this->modulo = $this->validarModulo($modulo);
			$this->controlador = $this->validarControlador($controlador);
			$this->directorio = ConfigAcceso::leer($app, 'fuente', 'directorio');
			$this->entorno = $entorno;
			$this->tipo = $tipo;
		}
		
		/**
		 * ImportarModelo::__call()
		 * 
		 * Genera la llamada a la funcion de validacion
		 * @param string $metodo
		 * @param array $parametro
		 * @return mixed
		 */
		function __call($metodo = false, $parametro = false) {
			return $this->seleccion($metodo, $parametro);
		}
		
		/**
		 * ImportarModelo::seleccion()
		 * 
		 * Determina si hay que retornar la informacion
		 * del modulo o del MVC
		 * 
		 * @param string $metodo
		 * @param array $parametro
		 * @return mixed
		 */
		private function seleccion($metodo = false, $parametro = false) {
			if(is_string($this->modulo) == true):
				return $this->modeloExistencia($metodo, $parametro);
			else:
				return $this->mvcExistencia($metodo, $parametro);
			endif;
		}
		
		/**
		 * ImportarModelo::modeloExistencia()
		 * 
		 * Valida la existencia del archivo modelo
		 * @param string $metodo
		 * @param array $parametro
		 * @return mixed
		 */
		private function modeloExistencia($metodo = false, $parametro = false) {
			$archivo = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $this->entorno, 'Fuente', 'Sistema', 'Modulos', $this->modulo, 'Modelos', $this->controlador.'.php'));
			if(file_exists($archivo) == true):
				$controlador = '\\Modelo\\Modulo\\'.$this->modulo.'\\'.$this->controlador;
				return $this->existenciaObjeto($controlador, $metodo, $parametro);
			else:
				throw new Excepcion(sprintf('El modelo: %s no existe en el modulo: %s para importar el modelo [ Tipo: %s ] [ Entorno: %s ]', $this->controlador, $this->modulo, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * ImportarModelo::mvcExistencia()
		 * 
		 * Valida la existencia del archivo modelo
		 * @param string $metodo
		 * @param array $parametro
		 * @return mixed
		 */
		private function mvcExistencia($metodo = false, $parametro = false) {
			$archivo = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $this->entorno, 'Fuente', 'Sistema', 'MVC', 'Modelos', $this->controlador.'.php'));
			if(file_exists($archivo) == true):
				$controlador = '\\Modelo\\MVC\\'.$this->controlador;
				return $this->existenciaObjeto($controlador, $metodo, $parametro);
			else:
				throw new Excepcion(sprintf('El modelo: %s no existe en el MVC para importar el modelo [ Tipo: %s ] [ Entorno: %s ]', $this->controlador, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		
		/**
		 * ImportarModelo::existenciaObjeto()
		 * 
		 * Genera la validacion de la existencia de la
		 * clase
		 * @param array $controlador
		 * @return mixed
		 */
		private function existenciaObjeto($controlador = false, $metodo = false, $parametros = false) {
			if(class_exists($controlador) == true):
				return $this->existenciaMetodo($controlador, $metodo, $parametros);
			else:
				throw new Excepcion(sprintf('La clase del modelo solicitado no existe para importar el modelo [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * ImportarModelo::existenciaMetodo()
		 * 
		 * Genera el proceso de validacion de la existencia del
		 * metodo solicitado
		 * 
		 * @param string $controlador
		 * @return mixed
		 */
		private function existenciaMetodo($controlador = false, $metodo = false, $parametros = false) {
			$metodos = array_flip(get_class_methods($controlador));
			if(array_key_exists($metodo, $metodos) == true):
				return $this->ejecutarObjeto($controlador, $metodo, $parametros);
			elseif(method_exists($controlador, $metodo) == true):
				throw new Excepcion(sprintf('El metodo: %s solicitado es privado o protegido para importar el modelo [ Tipo: %s ] [ Entorno: %s ]', $metodo, $this->tipo, $this->entorno), 0, $this->app);
			else:
				throw new Excepcion(sprintf('El metodo: %s solicitado no existe para importar el modelo [ Tipo: %s ] [ Entorno: %s ]', $metodo, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * ImportarModelo::ejecutarObjeto()
		 * 
		 * Genera el proceso de ejecutar la clase y el metodo 
		 * correspondiente
		 * 
		 * @param string $controlador
		 * @param string $metodo
		 * @param array $parametros
		 * @return mixed
		 */
		private function ejecutarObjeto($controlador = false, $metodo = false, $parametros = false) {
			$data = (is_array($parametros) == true) ? $parametros : array(false);
			return call_user_func_array(array(new $controlador, $metodo), $data);
		}
		
		/**
		 * ImportarModelo::validarApp()
		 * 
		 * Genera la validacion del nombre de la aplicacion
		 * @param string $app
		 * @return string
		 */
		private function validarApp($app = false) {
			if(is_string($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('El nombre de la aplicación no es valido [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * ImportarModelo::validarControlador()
		 * 
		 * Genera la validacion del nombre del controlador
		 * de la aplicacion
		 * 
		 * @param string $controlador
		 * @return string
		 */
		private function validarControlador($controlador = false) {
			if(is_string($controlador) == true):
				return $controlador;
			else:
				throw new Excepcion(sprintf('El nombre del controlador de la aplicación no es valido [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * ImportarModelo::validarModulo()
		 * 
		 * Genera la validacion del nombre del modulo
		 * de la aplicacion
		 * 
		 * @param string $modulo
		 * @return string
		 */
		private function validarModulo($modulo = false) {
			if(is_string($modulo) == true OR is_bool($modulo) == true):
				return $modulo;
			else:
				throw new Excepcion(sprintf('El nombre del modulo de la aplicación no es valido [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
	}