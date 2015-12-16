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
	use \Neural\Plantillas\Twig;
	use \Sistema\Controlador\Cabecera;
	use \Sistema\Controlador\ImportarModelo;
	use \Sistema\Controlador\Modelo;
	use \Sistema\Controlador\Peticion;
	use \Sistema\Controlador\Validar;
	use \Sistema\Controlador\ValidarFormulario\ValidarFormulario;
	use \Sistema\Utilidades\ModReWrite;
	
	class Controlador {
		
		protected $cabecera = false;
		protected $modelo = false;
		protected $peticion = false;
		protected $url = false;
		protected $validar = false;
		protected $validarFormulario = false;
		protected $ruta = false;
		protected $plantilla = false;
		
		/**
		 * Controlador::__construct()
		 * 
		 * Genera las variables correspondientes
		 * para el controlador
		 * 
		 * @return void
		 */
		function __construct() {
			$this->cabecera = new Cabecera();
			$this->peticion = new Peticion();
			$this->url = ModReWrite::leer();
			$this->validar = new Validar();
			$this->modelo = new Modelo(APP, ENV_ENTORNO, ENV_TIPO, $this->url);
			$this->ruta = new Rutas(APP, ENV_ENTORNO);
			$this->validarFormulario = new ValidarFormulario($this->peticion);
			$this->plantilla = new Twig(APP, ENV_ENTORNO);
		}
		
		/**
		 * Controlador::importarModelo()
		 * 
		 * Genera el proceso para importar modelos
		 * los cuales se encuentren en otros modulos
		 * o directamente en MVC
		 * 
		 * @param string $modulo
		 * @param string $modelo
		 * @return object
		 */
		protected function importarModelo($modulo = false, $modelo = false) {
			return new ImportarModelo(APP, $modulo, $modelo, ENV_ENTORNO, ENV_TIPO);
		}
	}