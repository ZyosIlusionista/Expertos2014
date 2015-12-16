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
	
	/**
	 * Namespace Controlador Modulos
	 * 
	 * Se genera el namespace para el modulo
	 * el cual se diferencia la carga de la misma
	 * @example namespace Controlador\Modulo\{nombre del modulo}
	 */
	namespace Controlador\Modulo\Index;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Neural\JQuery\ValidarForm;
	
	/**
	 * Controlador Index
	 * 
	 * El controlador es requerido extenderlo hacia la
	 * clase u objeto ubicado en el namespace \Mvc\Controlador.
	 * 
	 * El controlador debe ser generado en notacion UpperCamelCasse
	 * no debe contener simbolos como [\][_][-]
	 * 
	 * El nombre del archivo del controlador debe ser igual a la clase
	 * controlador
	 */
	class Index extends Controlador {
		
		/**
		 * Index::Index()
		 * 
		 * Genera la plantilla correspondiente para el
		 * login de la aplicacion
		 * 
		 * @return string
		 */
		public function Index() {
			$this->plantilla->parametro('script', $this->jQueryFormulario());
			echo $this->plantilla->mostrarPlantilla('Login.html');
		}
		
		/**
		 * Index::jQueryFormulario()
		 * 
		 * Genera la validacion del formulario en el login
		 * correspondiente en el metodo Index
		 * 
		 * @return string
		 */
		private function jQueryFormulario() {
			$validar = new ValidarForm(APP, false, true);
			$validar->usuario()->_requerido('Debe ingresar el usuario correspondiente');
			$validar->usuario()->_minCaracteres(5, 'Debe ingresar un usuario de minimo 5 caracteres');
			$validar->password()->_requerido('Debe ingresar una contraseña');
			$validar->password()->_minCaracteres(3, 'Debe ingresar una contraseña de minimo 8 caracteres');
			$validar->peticionAjax('login.init(formulario);');
			return $validar->mostrarValidacion('form');
		}
		
		/**
		 * Index::js()
		 * 
		 * Genera la funcion para la peticion ajax
		 * @param string $archivo
		 * @return string
		 */
		public function js($archivo = false) {
			$this->cabecera->header('js');
			echo $this->plantilla->mostrarPlantilla('Modulos', 'Index', 'js.js');
		}
		
		/**
		 * Index::autenticacion()
		 * 
		 * @return void
		 */
		public function autenticacion() {
			if($this->peticion->ajax() == true):
				$this->autenticacionValidacion();
			else:
				throw new Excepcion('No es posible procesar su petición', 1);
			endif;
		}
		
		/**
		 * Index::autenticacionValidacion()
		 * 
		 * Genera el proceso de validacion de formualario
		 * @return string
		 */
		private function autenticacionValidacion() {
			if($this->validarFormulario->validar('\Formularios\Login\Login') == true):
				throw new Excepcion(json_encode($this->validarFormulario->mensajeError()), 2, APP, 'LoginMensajes');
			else:
				throw new Excepcion(json_encode($this->validarFormulario->mensajeError()), 2, APP, 'LoginMensajes');
			endif;
		}
	}