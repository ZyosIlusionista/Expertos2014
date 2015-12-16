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
	
	class Modelo {
		
		private $app = false;
		private $entorno = false;
		private $tipo = false;
		private $modReWrite = false;
		private $directorio = false;
		private $clase = false;
		
		/**
		 * Modelo::__construct()
		 * 
		 * Genera los parametros necesarios para el procedimiento
		 * @param string $app
		 * @param string $entorno
		 * @param string $tipo
		 * @param array $modRewrite
		 * @return void
		 */
		function __construct($app = false, $entorno = false, $tipo = false, $modRewrite = false) {
			$this->app = $app;
			$this->entorno = $entorno;
			$this->tipo = $tipo;
			$this->modReWrite = $modRewrite;
			$this->directorio = ConfigAcceso::leer($app, 'fuente', 'directorio');
		}
		
		/**
		 * Modelo::__call()
		 * 
		 * Genera el proceso de la llamada del metodo correspondiente
		 * @param string $metodo
		 * @param array $parametros
		 * @return mixed
		 */
		function __call($metodo = false, $parametros = false) {
			if(is_string($metodo) == true):
				return $this->seleccionTipo($metodo, $parametros);
			else:
				throw new Excepcion(sprintf('Debe ingresar el metodo a ejecutar del modelo [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::seleccionTipo()
		 * 
		 * Genera la seleccion del tipo de procedimiento
		 * a cargar
		 * 
		 * @param string $metodo
		 * @param string $parametros
		 * @return mixed
		 */
		private function seleccionTipo($metodo = false, $parametros = false) {
			if($this->tipo == 'Modulo'):
				$this->clase = '\\Modelo\\Modulo\\'.$this->modReWrite['modulo']['modulo'].'\\'.$this->modReWrite['modulo']['controlador'];
				return $this->moduloExistencia($metodo, $parametros);
			elseif($this->tipo == 'MVC'):
				$this->clase = '\\Modelo\\MVC\\'.$this->modReWrite['mvc']['controlador'];
				return $this->mvcExistencia($metodo, $parametros);
			else:
				throw new Excepcion(sprintf('Error desconocido [ Tipo: %s ] [ Entorno: %s ]', $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::moduloExistencia()
		 * 
		 * Genera el procedimiento de validacion de la
		 * existencia del modelo en el modulo
		 * 
		 * @param string $metodo
		 * @param array $parametros
		 * @return
		 */
		private function moduloExistencia($metodo = false, $parametros = false) {
			$archivo = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $this->entorno, 'Fuente', 'Sistema', 'Modulos', $this->modReWrite['modulo']['modulo'], 'Modelos', $this->modReWrite['modulo']['controlador'].'.php'));
			if(file_exists($archivo) == true):
				return $this->existenciaObjeto($this->clase, $metodo, $parametros);
			else:
				throw new Excepcion(sprintf('El modelo: [ %s ] solicitado no existe [ Tipo: %s ] [ Entorno: %s ]', $this->clase, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::mvcExistencia()
		 * 
		 * Genera la validacion de la existencia del
		 * archivo en los modelos del MVC
		 * 
		 * @param string $metodo
		 * @param array $parametros
		 * @return mixed
		 */
		private function mvcExistencia($metodo = false, $parametros = false) {
			$archivo = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $this->entorno, 'Fuente', 'Sistema', 'MVC', 'Modelos', $this->modReWrite['mvc']['controlador'].'.php'));
			if(file_exists($archivo) == true):
				return $this->existenciaObjeto($this->clase, $metodo, $parametros);
			else:
				throw new Excepcion(sprintf('El modelo: [ %s ] solicitado no existe [ Tipo: %s ] [ Entorno: %s ]', $this->clase, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::existenciaObjeto()
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
				throw new Excepcion(sprintf('La clase: [ %s ] del modelo solicitado no existe [ Tipo: %s ] [ Entorno: %s ]', $controlador, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::existenciaMetodo()
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
				throw new Excepcion(sprintf('El Metodo: [ %s ] del modelo solicitado es privado o protegido [ Tipo: %s ] [ Entorno: %s ]', $metodo, $this->tipo, $this->entorno), 0, $this->app);
			else:
				throw new Excepcion(sprintf('El Metodo: [ %s ] del modelo solicitado no existe [ Tipo: %s ] [ Entorno: %s ]', $metodo, $this->tipo, $this->entorno), 0, $this->app);
			endif;
		}
		
		/**
		 * Modelo::ejecutarObjeto()
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
	}