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
	use \Doctrine\ORM\Proxy\Autoloader;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigModulos;
	use \Sistema\Utilidades\ModReWrite;
	
	class Boot extends Estructura {
		 
		 private $confg = false;
		 private $confgModulo = false;
		 private $modReWrite = false;
		 
		 /**
		  * Boot::__construct()
		  * 
		  * Genera las variables necesarias para el proceso
		  * @param string $app
		  * @param string $directorio
		  * @return void
		  */
		 function __construct($app = false, $directorio = false) {
		 	$this->modReWrite = ModReWrite::leer();
		 }
		 
		 /**
		  * Boot::ejecutar()
		  * 
		  * Ejecuta el proceso de ejecucion
		  * @return void
		  */
		 public function ejecutar() {
		 	$this->appExistencia();
		 }
		 
		 /**
		  * Boot::appExistencia()
		  * 
		  * Genera la validacion si la aplicacion existe
		  * en el archivo de configuracion de accesos
		  * 
		  * @return void
		  */
		 private function appExistencia() {
		 	if(ConfigAcceso::appExistencia($this->modReWrite['app']) == true):
		 		$this->appSeleccionEntorno();
		 	else:
		 		throw new Excepcion(sprintf('La aplicación: %s no existe en el archivo de configuración.', $this->modReWrite['app']), 0);
		 	endif;
		 }
		 
		 /**
		  * Boot::appSeleccionEntorno()
		  * 
		  * Genera la validacion inicial del entorno a definir
		  * @return void
		  */
		 private function appSeleccionEntorno() {
		 	$this->confg = ConfigAcceso::leer($this->modReWrite['app']);
		 	if($this->confg['localhost']['habilitado'] == true):
		 		if(array_key_exists($_SERVER['REMOTE_ADDR'], array_flip($this->confg['localhost']['ip'])) == true):
		 			define('ENV_ENTORNO', 'Desarrollo');
		 		else:
		 			define('ENV_ENTORNO', 'Produccion');
		 		endif;
		 	else:
		 		define('ENV_ENTORNO', 'Produccion');
		 	endif;
		 	$this->directorioExistencia();
		 }
		 
		 /**
		  * Boot::directorioExistencia()
		  * 
		  * Genera la validacion si el directorio de 
		  * la aplicacion existe
		  * 
		  * @return void
		  */
		 private function directorioExistencia() {
		 	if(is_dir(ROOT_APPS.DIRECTORY_SEPARATOR.$this->confg['fuente']['directorio']) == true):
		 		$this->directorioLectura();
		 	else:
		 		throw new Excepcion(sprintf('El directorio de la aplicación: %s no existe', $this->modReWrite['app']), 0);
		 	endif;
		 }
		 
		 /**
		  * Boot::directorioLectura()
		  * 
		  * Genera la validacion de la lectura del directorio
		  * de la aplicacion
		  * 
		  * @return void
		  */
		 private function directorioLectura() {
		 	if(is_readable(ROOT_APPS.DIRECTORY_SEPARATOR.$this->confg['fuente']['directorio']) == true):
		 		$this->estructuraValidacion();
		 	else:
		 		throw new Excepcion(sprintf('El directorio de la aplicación: %s no es posible leerlo', $this->modReWrite['app']), 0);
		 	endif;
		 }
		 
		 /**
		  * Boot::estructuraValidacion()
		  * 
		  * Genera el proceso de validacion de la estructura 
		  * de toda la aplicacion
		  * 
		  * @return void
		  */
		 private function estructuraValidacion() {
		 	if($this->confg['sistema']['estructura'][ENV_ENTORNO] == true):
		 		parent::ejecutarEstructura($this->confg['fuente']['directorio']);
		 	endif;
		 	$this->adicionalesIncluir();
		 	$this->moduloValidacion();
		 }
		 
		 /**
		  * Boot::adicionalesIncluir()
		  *
		  * Genera la carga de procesos adicionales
		  * necesarios dentro de la aplicacion
		  *  
		  * @return void
		  */
		 private function adicionalesIncluir() {
		 	date_default_timezone_set($this->confg['sistema']['tiempo']['zona']);
			require implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Configuracion', 'Parametros.php'));
			
			$consola = new \AutoCargador('Consola', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos')));
			$consola->registrar();
			
			$entidades = new \AutoCargador('Entidades', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos', 'ORM')));
		 	$entidades->registrar();
		 	
		 	$formulario = new \AutoCargador('Formularios', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos')));
		 	$formulario->registrar();
		 	
			$interface = new \AutoCargador('Interfaces', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos')));
		 	$interface->registrar();
		 	
		 	Autoloader::register(implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos', 'ORM', 'Proxy')), 'Proxy');
		 	
			$utilidades = new \AutoCargador('Utilidades', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Complementos')));
		 	$utilidades->registrar();
			
			$controladorMVC = new \AutoCargador('Controlador', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Sistema')));
		 	$controladorMVC->registrarControlador();
		 	
			$modeloMVC = new \AutoCargador('Modelo', implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->confg['fuente']['directorio'], ENV_ENTORNO, 'Fuente', 'Sistema')));
		 	$modeloMVC->registrarModelo();
		 }
		 
		 /**
		  * Boot::moduloValidacion()
		  * 
		  * Genera la validacion de modulos activos
		  * @return void
		  */
		 private function moduloValidacion() {
		 	$this->confgModulo = ConfigModulos::leer($this->confg['fuente']['directorio'], ENV_ENTORNO);
		 	if($this->confgModulo['configuracion']['habilitado'] == true):
		 		$this->moduloSeleccion();
		 	else:
		 		$this->cargaMVC();
		 	endif;
		 }
		 
		 /**
		  * Boot::moduloSeleccion()
		  * 
		  * Genera la validacion si existe registrado el modulo
		  * @return void
		  */
		 private function moduloSeleccion() {
		 	if(array_key_exists($this->modReWrite['modulo']['modulo'], $this->confgModulo['modulos']) == true):
		 		$this->moduloActivo();
		 	else:
		 		$this->cargaMVC();
		 	endif;
		 }
		 
		 /**
		  * Boot::moduloActivo()
		  * 
		  * Genera la validacion si el modulo se encuentra activo
		  * @return void
		  */
		 private function moduloActivo() {
		 	if($this->confgModulo['modulos'][$this->modReWrite['modulo']['modulo']]['habilitado'] == true):
		 		$this->cargaModulo();
		 	else:
		 		$this->cargaMVC();
		 	endif;
		 }
		 
		 /**
		  * Boot::cargaModulo()
		  * 
		  * Genera el proceso de carga del modulo correspondiente
		  * @return void
		  */
		 private function cargaModulo() {
			$modulo = new Modulo($this->modReWrite['app'], $this->confg['fuente']['directorio'], ENV_ENTORNO, $this->modReWrite['modulo'], $this->confgModulo['modulos'][$this->modReWrite['modulo']['modulo']]);
		 	$modulo->ejecutar();
		 }
		 
		 /**
		  * Boot::cargaMVC()
		  * 
		  * Genera el proceso de carga del MVC correspondiente
		  * @return void
		  */
		 private function cargaMVC() {
		 	$mvc = new Mvc($this->modReWrite['app'], $this->confg['fuente']['directorio'], ENV_ENTORNO, $this->modReWrite['mvc']);
		 	$mvc->ejecutar();
		 }
	}