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
	use \Neural\Excepcion;
	
	class AdicionalTwig {
		
		public $confg = false;
		public $entorno = false;
		public $temporal = false;
		public $sistema = false;
		
		/**
		 * AdicionalTwig::__construct()
		 * 
		 * Genera la informacion requerida
		 * 
		 * @param string $app
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $entorno = false) {
			$this->confg = $this->inputApp($app);
			$this->entorno = $this->inputEntorno($entorno);
			$directorio = ConfigAcceso::leer($app, 'fuente', 'directorio');
			$this->sistema = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $this->entorno, 'Fuente', 'Sistema'));
			$this->temporal = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $this->entorno, 'Fuente', 'Complementos', 'Temporal'));
			
		}
		
		/**
		 * AdicionalTwig::inputApp()
		 * 
		 * Genera la validacion de la aplicacion
		 * @param string $app
		 * @return array
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Debe ingresar la aplicación donde se obtendran los datos para la Plantilla Twig', 0);
			endif;
		}
		
		/**
		 * AdicionalTwig::inputAppExistencia()
		 * 
		 * Genera el proceso de validacion de la existencia
		 * de la aplicacion correspondiente
		 * 
		 * @param string $app
		 * @return array
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return ConfigAcceso::leer($app, 'twig');
			else:
				throw new Excepcion('La aplicación indicada no existe en el archivo de configuración para el proceso de la Plantilla Twig', 0);
			endif;
		}
		
		/**
		 * AdicionalTwig::inputEntorno()
		 * 
		 * Valida la asignacion del entorno correspondiente
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoTxt($entorno);
			else:
				return $this->inputEntornoAuto();
			endif;
		}
		
		/**
		 * AdicionalTwig::inputEntornoAuto()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * @return string
		 */
		private function inputEntornoAuto() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoTxt(ENV_ENTORNO);
			else:
				throw new Excepcion('Debe ingresar el entorno requerido para el proceso de la plantilla Twig');
			endif;
		}
		
		/**
		 * AdicionalTwig::inputEntornoTxt()
		 * 
		 * Genera la validacion del entorno ingresado sea correcto
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoTxt($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno ingresado: %s, no corresponde a los admitidos [ Desarrollo - Produccion ] en la plantilla Twig', $entorno), 0);
			endif;
		}
		
		/**
		 * AdicionalTwig::rutasPlantilla()
		 * 
		 * Genera un listado de rutas basico
		 * @return array
		 */
		public function rutasPlantilla() {
			$lista[] = implode(DIRECTORY_SEPARATOR, array($this->sistema, 'Plantillas'));
			if(defined('ENV_TIPO') == true):
				$lista[] = (ENV_TIPO == 'Modulo') ? implode(DIRECTORY_SEPARATOR, array($this->sistema, 'Modulos', ModReWrite::leer('modulo', 'modulo'), 'Vistas')) : implode(DIRECTORY_SEPARATOR, array($this->sistema, 'MVC', 'Vistas'));
			endif;
			return $lista;
		}
	}