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
	
	namespace Neural;
	use \Exception;
	use \Mvc\Rutas;
	use \Neural\Plantillas\Twig AS TwigApp;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigExcepcion;
	use \Sistema\Utilidades\Twig AS TwigExterno;
	
	class Excepcion extends \Exception {
		
		private $app = false, $asignado = false, $confg = false, $entorno = false;
		
		/**
		 * Excepcion::__construct()
		 * 
		 * Genera las variables correspondientes
		 * para el proceso de la excepcion
		 * 
		 * @param string $mensaje
		 * @param integer $codigo
		 * @param string $app
		 * @param string $asignado
		 * @return void
		 */
		function __construct($mensaje = false, $codigo = false, $app = false, $asignado = false) {
			parent::__construct($mensaje, $codigo, null);
			$this->app = $app;
			$this->asignado = $asignado;
			$this->entorno = $this->inputEntorno();
			
			if(defined('ENV_TIPO') == false):
				define('ENV_TIPO', 'MVC');
			endif;
		}
		
		/**
		 * Excepcion::inputEntorno()
		 * 
		 * Genera la asignacion del entorno correspondiente
		 * 
		 * @return string
		 */
		private function inputEntorno() {
			return (defined('ENV_ENTORNO') == true) ? $this->inputEntornoSeleccion(ENV_ENTORNO) : 'Produccion';
		}
		
		/**
		 * Excepcion::inputEntornoSeleccion()
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
		 * Excepcion::error()
		 * 
		 * Genera la plantilla correspondiente
		 * de la excepcion
		 * 
		 * @return string
		 */
		public function error() {
			return $this->appProcesoSeleccion();
		}
		
		/**
		 * Excepcion::appProcesoSeleccion()
		 * 
		 * Valida si se ha asignado una aplicacion para cargar
		 * @return
		 */
		private function appProcesoSeleccion() {
			if(is_string($this->app) == true):
				return $this->appExistencia();
			else:
				return $this->plantillaSistema();
			endif;
		}
		
		/**
		 * Excepcion::appExistencia()
		 * 
		 * Genera la validacion de la existencia de la aplicacion
		 * en dado caso que no exista la aplicacion se cargara la
		 * plantilla por defecto
		 * 
		 * @return string
		 */
		private function appExistencia() {
			if(ConfigAcceso::appExistencia($this->app) == true):
				$this->confg = ConfigExcepcion::leer(ConfigAcceso::leer($this->app, 'fuente', 'directorio'), $this->entorno);
				return $this->asignadoSeleccion();
			else:
				return $this->plantillaSistema();
			endif;
		}
		
		/**
		 * Excepcion::asignadoSeleccion()
		 * 
		 * Genera la validacion de la existencia
		 * del ingreso de una plantilla asignada
		 * 
		 * @return string
		 */
		private function asignadoSeleccion() {
			if(is_bool($this->asignado) == false):
				return $this->asignadoSeleccionExistencia();
			else:
				return $this->asignadoExistencia($this->confg['predeterminado']);
			endif;
		}
		
		/**
		 * Excepcion::asignadoSeleccionExistencia()
		 * 
		 * Genera el proceso de validacion de la existencia
		 * de la plantilla asignada
		 * 
		 * @return string
		 */
		private function asignadoSeleccionExistencia() {
			if(array_key_exists($this->asignado, $this->confg['asignado'])== true):
				return $this->asignadoExistencia($this->confg['asignado'][$this->asignado]);
			else:
				return $this->asignadoExistencia($this->confg['predeterminado']);
			endif;
		}
		
		/**
		 * Excepcion::asignadoExistencia()
		 * 
		 * Valida la existencia de la plantilla correspondiente
		 * en dado caso que no exista la plantilla se generara la
		 * plantilla definida por el sistema de manera autonoma
		 * 
		 * @param array $array
		 * @return string
		 */
		private function asignadoExistencia($array = false) {
			$archivo = implode(DIRECTORY_SEPARATOR, array_merge(array(ROOT_APPS, ConfigAcceso::leer($this->app, 'fuente', 'directorio'), $this->entorno, 'Fuente', 'Sistema', 'Plantillas'), $array));
			if(file_exists($archivo) == true):
				return $this->plantillaApp($array);
			else:
				return $this->plantillaSistema();
			endif;
		}
		
		/**
		 * Excepcion::plantillaApp()
		 * 
		 * @param bool $array
		 * @return
		 */
		private function plantillaApp($array = false) {
			$rutas = new Rutas($this->app, $this->entorno);
			
			$plantilla = new TwigApp($this->app, $this->entorno);
			$plantilla->parametroGlobal('RUTA_APP', $rutas->mvc());
			$plantilla->parametroGlobal('RUTA_PUBLICO', $rutas->publico());
			$plantilla->parametroGlobal('RUTA_PROTEGIDO', $rutas->protegido());
			$plantilla->parametroGlobal('RUTA_RESERVADO', $rutas->reservado());
			
			$plantilla->parametro('MENSAJE', $this->getMessage());
			$plantilla->parametro('CODIGO', $this->getCode());
			$plantilla->parametro('ARCHIVO', $this->getFile());
			$plantilla->parametro('LINEA', $this->getLine());
			$plantilla->parametro('TRACE_ARRAY', $this->getTrace());
			$plantilla->parametro('TRACE_STRING', $this->getTraceAsString());
			$plantilla->parametro('TRACE_ANTERIOR', $this->getPrevious());
			
			$plantilla->filtro('print_r', function($data) {
				return print_r($data);
			});
			
			return $plantilla->mostrarPlantilla(implode(DIRECTORY_SEPARATOR, $array));
		}
		
		/**
		 * Excepcion::plantillaSistema()
		 * 
		 * Genera la ejecucion de la plantilla correspondiente
		 * @return string
		 */
		private function plantillaSistema() {
			$confg = new \Sistema\Utilidades\LeerConfig;
			$param = $confg->leer('Excepcion.json');
			$rutas = new Rutas($param['aplicacion'], $this->entorno);
			
			$plantilla = new TwigExterno($param['aplicacion'], $this->entorno);
			$plantilla->asignarRutas(implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Reservado', 'Plantillas')));
			$plantilla->parametroGlobal('RUTA_EXCEPCION', call_user_func(array($rutas, $param['ruta'])));
			$plantilla->parametroGlobal('RUTA_APP', $rutas->mvc());
			$plantilla->parametroGlobal('RUTA_PUBLICO', $rutas->publico());
			$plantilla->parametroGlobal('RUTA_PROTEGIDO', $rutas->protegido());
			$plantilla->parametroGlobal('RUTA_RESERVADO', $rutas->reservado());
			
			$plantilla->parametro('MENSAJE', $this->getMessage());
			$plantilla->parametro('CODIGO', $this->getCode());
			$plantilla->parametro('ARCHIVO', $this->getFile());
			$plantilla->parametro('LINEA', $this->getLine());
			$plantilla->parametro('TRACE_ARRAY', $this->getTrace());
			$plantilla->parametro('TRACE_STRING', $this->getTraceAsString());
			$plantilla->parametro('TRACE_ANTERIOR', $this->getPrevious());
			
			$plantilla->filtro('print_r', function($data) {
				return print_r($data);
			});
			
			return $plantilla->mostrarPlantilla($param['plantilla'][$this->entorno]);
		}
	}
