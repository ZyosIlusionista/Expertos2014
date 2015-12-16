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
	use \Neural\Excepcion;
	
	class Twig implements \Neural\Plantillas\InterfaceTwig {
		
		private $app = false;
		private $rutas = false;
		private $entorno = false;
		private $confg = false;
		
		private $twigParam = array();
		private $twigParamGlobal = false;
		private $twigFiltro = false;
		private $twigFuncion = false;
		
		/**
		 * Twig::__construct()
		 * 
		 * Genera las variables necesarias para el proceso
		 * de plantillas
		 * 
		 * @param string $app
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $entorno = false) {
			$this->app = $this->inputApp($app);
			$this->entorno = $this->inputEntorno($entorno);
			$this->confg = ConfigAcceso::leer($app, 'twig');
		}
		
		/**
		 * Twig::inputApp()
		 * 
		 * Genera el proceso de validacion de la aplicacion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Es necesario ingresar una aplicación para el proceso solicitado de Twig', 0);
			endif;
		}
		
		/**
		 * Twig::inputAppExistencia()
		 * 
		 * Valida la existencia de la aplicacion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s no existe en el archivo de configuración para el proceso de Twig', $app), 0);
			endif;
		}
		
		/**
		 * Twig::inputEntorno()
		 * 
		 * Asigna el entorno correspondiente de acceso
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoSeleccion($entorno);
			else:
				return $this->inputEntornoExistencia();
			endif;
			return $this;
		}
		
		/**
		 * Twig::inputEntornoExistencia()
		 * 
		 * Genera la validacion del entorno desde la asignacion
		 * automatica por la aplicaicon
		 * 
		 * @return string
		 */
		private function inputEntornoExistencia() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('No se ha asignado un entorno para el proceso de Twig', 0);
			endif;
		}
		
		/**
		 * Twig::inputEntornoSeleccion()
		 * 
		 * Genera la validacion del entorno indicado
		 * @param string $entorno
		 * @return object
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion')) )== true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no es valido para el proceso de la plantilla Twig', $entorno), 0);
			endif;
		}
		
		/**
		 * Twig::asignarRutas()
		 * 
		 * Genera la asignacion correspondiente
		 * de las ruta donde se encuentran las
		 * plantillas
		 * 
		 * @param string $ruta
		 * @return object
		 */
		public function asignarRutas($ruta = false) {
			if(is_string($ruta) == true):
				$this->asignarRutasExistencia($ruta);
			else:
				throw new Excepcion('Debe ingresar la ruta correspondiente para el proceso de la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::asignarRutasExistencia()
		 * 
		 * Genera la validacion de la existencia del
		 * directorio
		 * 
		 * @param string $ruta
		 * @return object
		 */
		private function asignarRutasExistencia($ruta = false) {
			if(is_dir($ruta) == true):
				$this->rutas[] = $ruta;
			else:
				throw new Excepcion('El directorio de plantillas indicado no existe para el proceso de plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::parametro()
		 * 
		 * Genera un parametro o variable para ser ejecutado
		 * dentro del motor de plantillas twig
		 * 
		 * @param string $nombre: nombre del parametro
		 * @param mixed $valor: el valor puede ser bool, array, int, str
		 * @return object
		 */
		public function parametro($nombre = false, $valor = false) {
			if(is_string($nombre) == true):
				$this->twigParam[$nombre] = $valor;
			else:
				throw new Excepcion('Debe ingresar el nombre del parametro como texto para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::parametroGlobal()
		 * 
		 * Genera un parametro o variable para ser ejecutado
		 * dentro del motor de plantillas twig
		 * 
		 * @param string $nombre: nombre del parametro
		 * @param mixed $valor: el valor puede ser bool, array, int, str
		 * @return object
		 */
		public function parametroGlobal($nombre = false, $valor = false) {
			if(is_string($nombre) == true):
				$this->twigParamGlobal[$nombre] = $valor;
			else:
				throw new Excepcion('Debe ingresar el nombre del parametro global como texto para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::filtro()
		 * 
		 * Asigna un filtro para ser utilizado dentro
		 * del motor de plantilla twig
		 * 
		 * @param string $nombre: nombre del filtro
		 * @param object $funcion: funcion anonima
		 * @return object
		 */
		public function filtro($nombre, $funcion) {
			if(is_string($nombre) == true):
				$this->filtroFuncion($nombre, $funcion);
			else:
				throw new Excepcion('Debe ingresar el nombre del filtro como texto para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::filtroFuncion()
		 * 
		 * Genera la validacion que se ingrese un objeto
		 * para el filtro
		 * 
		 * @param string $nombre
		 * @param object $funcion
		 * @return object
		 */
		private function filtroFuncion($nombre = false, $funcion = false) {
			if(is_object($funcion) == true):
				$this->twigFiltro[$nombre] = $funcion;
			else:
				throw new Excepcion('Debe ingresar la función anonima para el filtro correspondiente para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::funcion()
		 * 
		 * Asigna una funcion dentro del motor de plantillas
		 * twig
		 * 
		 * @param string $nombre: nombre de la funcion
		 * @param object $funcion: funcion anonima
		 * @return object
		 */
		public function funcion($nombre = false, $funcion = false) {
			if(is_string($nombre) == true):
				$this->funcionObjeto($nombre, $funcion);
			else:
				throw new Excepcion('Debe ingresar el nombre de la función como texto para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::funcionObjeto()
		 * 
		 * Genera la validacion del ingreso de la funcion anonima
		 * @param string $nombre
		 * @param object $funcion
		 * @return object
		 */
		private function funcionObjeto($nombre = false, $funcion = false) {
			if(is_object($funcion) == true):
				$this->twigFuncion[$nombre] = $funcion;
			else:
				throw new Excepcion('Debe ingresar la función anonima para la función correspondiente para la plantilla Twig', 0);
			endif;
			return $this;
		}
		
		/**
		 * Twig::mostrarPlantilla()
		 * 
		 * Muestra la plantilla correspondiente
		 * @return string
		 */
		public function mostrarPlantilla() {
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				return $this->existenciaApp(implode(DIRECTORY_SEPARATOR, $parametros));
			else:
				throw new Excepcion('Es necesario indicar la plantilla a utilizar en el proceso de plantilla Twig', 0);
			endif;
		}
		
		/**
		 * Twig::existenciaApp()
		 * 
		 * Genera la validacion del ingreso de la aplicacion
		 * @param string $plantilla
		 * @return string
		 */
		private function existenciaApp($plantilla = false) {
			if(is_string($this->app) == true):
				return $this->existenciaAppEx($plantilla);
			else:
				throw new Excepcion('Es necesario asignar una aplicación para el proceso de plantilla Twig', 0);
			endif;
		}
		
		/**
		 * Twig::existenciaAppEx()
		 * 
		 * Valida la existencia de la aplicacion correspondiente
		 * @param string $plantilla
		 * @return string
		 */
		private function existenciaAppEx($plantilla = false) {
			if(ConfigAcceso::appExistencia($this->app) == true):
				return $this->existenciaRutas($plantilla);
			else:
				throw new Excepcion('La aplicación no existe registrado en el archivo de configuración para el proceso de plantilla Twig', 0);
			endif;
		}
		
		/**
		 * Twig::existenciaRutas()
		 * 
		 * Genera la validacion de la asignacion de rutas
		 * @param string $plantilla
		 * @return string
		 */
		private function existenciaRutas($plantilla = false) {
			if(is_array($this->rutas) == true):
				return $this->existenciaPlantilla($plantilla);
			else:
				throw new Excepcion('Es necesario ingresar la ruta donde se tomaran las plantillas en el proceso de Twig', 0);
			endif;
		}
		
		/**
		 * Twig::existenciaPlantilla()
		 * 
		 * Genera la validacion de la existencoa de la
		 * plantilla indicada
		 * 
		 * @param string $plantilla
		 * @return string
		 */
		private function existenciaPlantilla($plantilla = false) {
			if($this->existenciaUtilidad($plantilla) == true):
				return $this->twigPlantilla($plantilla);
			else:
				throw new Excepcion(sprintf('La plantilla: %s no existe dentro de los directorios de plantillas en el proceso de Twig', $plantilla), 0);
			endif;
		}
		
		/**
		 * Twig::twigPlantilla()
		 * 
		 * Genera el proceso de mostrar y cargar la plantilla
		 * correspondiente
		 * 
		 * @param string $plantilla
		 * @return string
		 */
		private function twigPlantilla($plantilla = false) {
			$cargador = new \Twig_Loader_Filesystem($this->rutas);
			$twig = new \Twig_Environment($cargador, $this->existenciaEnvironment());
			$twig->addExtension(new \Twig_Extension_StringLoader());
			
			$twig->addGlobal('ENV_ENTORNO', $this->entorno);
			$twig->addGlobal('URL', ModReWrite::leer());
			
			$rutas = new Rutas($this->app, $this->entorno);
			$twig->addGlobal('RUTA_APP', $rutas->mvc());
			$twig->addGlobal('RUTA_PUBLICO', $rutas->publico());
			$twig->addGlobal('RUTA_PROTEGIDO', $rutas->protegido());
			$twig->addGlobal('RUTA_RESERVADO', $rutas->reservado());
			
			if(is_array($this->twigParamGlobal) == true):
				$this->parametrosGlobales($twig);
			endif;
			
			if(is_array($this->twigFiltro) == true):
				$this->filtros($twig);
			endif;
			
			if(is_array($this->twigFuncion) == true):
				$this->funciones($twig);
			endif;
			
			return $twig->render($plantilla, $this->twigParam);
		}
		
		/**
		 * Twig::funciones()
		 * 
		 * Adicional las funciones correspondientes
		 * @param object $objeto
		 * @return void
		 */
		private function funciones($objeto = false) {
			foreach ($this->twigFuncion as $nombre => $funcion):
				$objeto->addFunction(new \Twig_SimpleFunction($nombre, $funcion));
			endforeach;
		}
		
		/**
		 * Twig::filtros()
		 * 
		 * Adiciona los filtros correspondientes
		 * @param object $objeto
		 * @return void
		 */
		private function filtros($objeto = false) {
			foreach ($this->twigFiltro as $nombre => $funcion):
				$objeto->addFilter(new \Twig_SimpleFilter($nombre, $funcion));
			endforeach;
		}
		
		/**
		 * Twig::parametrosGlobales()
		 * 
		 * Adiciona los parametros globales
		 * @param object $objeto
		 * @return void
		 */
		private function parametrosGlobales($objeto = false) {
			foreach ($this->twigParamGlobal as $nombre => $valor):
				$objeto->addGlobal($nombre, $valor);
			endforeach;
		}
		
		/**
		 * Twig::existenciaEnvironment()
		 * 
		 * Retorna la configuracion del ambiente para el
		 * motor de plantillas
		 * 
		 * @return array
		 */
		private function existenciaEnvironment() {
			$confg['charset'] = $this->confg['codificacion'];
			$confg['debug'] = $this->confg['debug'][$this->entorno];
			$confg['cache'] = ($this->confg['cache'][$this->entorno] == true) ? $this->temporal : (boolean) false;
			return $confg;
		}
		
		/**
		 * Twig::existenciaUtilidad()
		 * 
		 * Valida la existencia de la plantilla indicada
		 * @param string $plantilla
		 * @return bool
		 */
		private function existenciaUtilidad($plantilla = false) {
			foreach ($this->rutas AS $ruta):
				if(file_exists(implode(DIRECTORY_SEPARATOR, array($ruta, $plantilla))) == true):
					$lista[] = true;
				endif;
			endforeach;
			return (isset($lista) == true) ? (boolean) true : (boolean) false;
		}
	}