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
	use \Sistema\Controlador\Cabecera;
	use \Sistema\Controlador\ImportarModelo;
	use \Sistema\Controlador\Validar;
	
	class Consola {
		
		protected $cabecera = false;
		protected $validar = false;
		protected $ruta = false;
		
		/**
		 * Consola::__construct()
		 * 
		 * Genera las variables correspondientes
		 * para el controlador
		 * 
		 * @return void
		 */
		function __construct() {
			$this->cabecera = new Cabecera();
			$this->validar = new Validar();
			$this->ruta = new Rutas(APP, ENV_ENTORNO);
		}
		
		/**
		 * Consola::importarModelo()
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