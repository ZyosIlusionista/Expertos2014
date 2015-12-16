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
	
	namespace Sistema\Utilidades;
	use \Mvc\Rutas;
	use \Sistema\Utilidades\LeerConfig;
	use \Sistema\Utilidades\Twig;
	
	class ExcepcionHandler {
		
		private $excepcion = false;
		private $entorno = 'Produccion';
		
		/**
		 * ExcepcionHandler::__construct()
		 * 
		 * Genera las variables necesarias para el
		 * proceso de manejo de excepciones
		 * 
		 * @param object $excepcion
		 * @return void
		 */
		function __construct($excepcion = false) {
			if(is_object($excepcion) == true):
				$this->excepcion = $excepcion;
			endif;
			
			$this->entorno = $this->inputEntorno();
			
			if(defined('ENV_TIPO') == false):
				define('ENV_TIPO', 'MVC');
			endif;
		}
		
		/**
		 * ExcepcionHandler::inputEntorno()
		 * 
		 * Genera la asignacion del entorno correspondiente
		 * 
		 * @return string
		 */
		private function inputEntorno() {
			return (defined('ENV_ENTORNO') == true) ? $this->inputEntornoSeleccion(ENV_ENTORNO) : 'Produccion';
		}
		
		/**
		 * ExcepcionHandler::inputEntornoSeleccion()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * en dado caso de que no se encuentre seleccionado 
		 * o definido alguno retornara Produccion
		 * 
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			return (array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true) ? $entorno : 'Produccion';
		}
		
		/**
		 * ExcepcionHandler::mostrarError()
		 * 
		 * Muestra la plantilla correspondiente con la
		 * excepcion
		 * 
		 * @return string
		 */
		public function mostrarError() {
			if(method_exists($this->excepcion, 'error') == true):
				return $this->excepcion->error();
			else:
				return $this->plantillaSistema();
			endif;
		}
		
		/**
		 * ExcepcionHandler::plantillaSistema()
		 * 
		 * Genera la plantilla por defecto
		 * @return string
		 */
		private function plantillaSistema() {
			$confg = new LeerConfig;
			$param = $confg->leer('Excepcion.json');
			$rutas = new Rutas($param['aplicacion'], $this->entorno);
			
			$plantilla = new Twig($param['aplicacion']);
			$plantilla->asignarRutas(implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Reservado', 'Plantillas')));
			$plantilla->parametroGlobal('RUTA_EXCEPCION', call_user_func(array($rutas, $param['ruta'])));
			$plantilla->parametroGlobal('RUTA_APP', $rutas->mvc());
			$plantilla->parametroGlobal('RUTA_PUBLICO', $rutas->publico());
			$plantilla->parametroGlobal('RUTA_PROTEGIDO', $rutas->protegido());
			$plantilla->parametroGlobal('RUTA_RESERVADO', $rutas->reservado());
			
			$plantilla->parametro('MENSAJE', $this->excepcion->getMessage());
			$plantilla->parametro('CODIGO', $this->excepcion->getCode());
			$plantilla->parametro('ARCHIVO', $this->excepcion->getFile());
			$plantilla->parametro('LINEA', $this->excepcion->getLine());
			$plantilla->parametro('TRACE_ARRAY', $this->excepcion->getTrace());
			$plantilla->parametro('TRACE_STRING', $this->excepcion->getTraceAsString());
			$plantilla->parametro('TRACE_ANTERIOR', $this->excepcion->getPrevious());
			
			$plantilla->filtro('print_r', function($data) {
				return print_r($data);
			});
			
			return $plantilla->mostrarPlantilla($param['plantilla'][$this->entorno]);
		}
	}