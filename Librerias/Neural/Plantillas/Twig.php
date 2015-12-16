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
	
	namespace Neural\Plantillas;
	use \Mvc\Rutas;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\AdicionalTwig;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ModReWrite;
	
	class Twig implements InterfaceTwig {
		
		private $app = false;
		private $confg = false;
		private $directorio = false;
		private $entorno = false;
		private $rutas = false;
		private $temporal = false;
		
		private $twigParam = array();
		private $twigParamGlobal = false;
		private $twigFiltro = false;
		private $twigFuncion = false;
		
		/**
		 * Twig::__construct()
		 * 
		 * Genera las variables necesarias para el proceso
		 * @param string $app
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $entorno = false) {
			$info = new AdicionalTwig($app, $entorno);
			$this->confg= $info->confg;
			$this->entorno = $info->entorno;
			$this->app = $app;
			$this->rutas = $info->rutasPlantilla();
			$this->temporal = $info->temporal;
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
		public function filtro($nombre = false, $funcion = false) {
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
				return $this->validarExistencia(implode(DIRECTORY_SEPARATOR, $parametros));
			else:
				throw new Excepcion('Debe ingresar la plantilla correspondiente para mostrar en el proceso de la Plantilla Twig', 0);
			endif;
		}
		
		/**
		 * Twig::validarExistencia()
		 * 
		 * Genera la validacion de la existencia de
		 * la plantilla correspondiente
		 * 
		 * @param string $plantilla
		 * @return string
		 */
		private function validarExistencia($plantilla = false) {
			if($this->existenciaPlantilla($plantilla) == true):
				return $this->twigPlantilla($plantilla);
			else:
				throw new Excepcion(sprintf('La plantilla: %s, no existe en los directorios de vistas registrados en el proceso de la Plantilla Twig', $plantilla), 0);
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
		 * Twig::existenciaPlantilla()
		 * 
		 * Valida si existe la plantilla indicada
		 * @param string $plantilla
		 * @return bool
		 */
		private function existenciaPlantilla($plantilla = false) {
			foreach ($this->rutas as $base):
				if(file_exists(implode(DIRECTORY_SEPARATOR, array($base, $plantilla))) == true):
					$lista[] = true;
				endif;
			endforeach;
			return (isset($lista) == true) ? true : false;
		}
	}