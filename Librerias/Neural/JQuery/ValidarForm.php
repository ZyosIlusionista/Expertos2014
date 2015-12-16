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
	
	namespace Neural\JQuery;
	use \Mvc\Rutas;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	
	class ValidarForm {
		
		private $app = false;
		private $campo = false;
		private $constructor = false;
		private $plantilla = false;
		private $rutas = false;
		private $jQueryScript = false;
		private $validate = false;
		public $jQuery = 'jQuery';
		
		/**
		 * ValidarForm::__construct()
		 * 
		 * Genera las variables correspondiente
		 * @param bool $app
		 * @return void
		 */
		function __construct($app = false, $jQuery = false, $validate = false) {
			$this->app = $this->inputApp($app);
			$this->jQueryScript = $jQuery;
			$this->validate = $validate;
			$this->rutas = new Rutas(APP);
		}
		
		/**
		 * ValidarForm::inputApp()
		 * 
		 * Genera la validacion de ingreso de la
		 * aplicacion
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Debe ingresar una aplicación en el proceso de ValidarForm', 0);
			endif;
		}
		
		/**
		 * ValidarForm::inputAppExistencia()
		 * 
		 * Valida la existencia de la aplicacion correspondiente
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s, no existe en el archivo de configuración para el proceso de ValidarForm', $app), 0);
			endif;
		}
		
		/**
		 * ValidarForm::__call()
		 * 
		 * Genera el proceso de asignacion del nombre
		 * del campo
		 * 
		 * @param string $campo
		 * @param string $parametros
		 * @return object
		 */
		public function __call($campo, $parametros) {
			$this->campo = (count($parametros) >= 1) ? $campo.'['.implode('][', $parametros).']' : $campo;
			return $this;
		}
		
		/**
		 * ValidarForm::_requerido()
		 * 
		 * Genera la validacion para que sea requerido
		 * el campo indicado
		 * 
		 * @param string $mensaje
		 * @return object
		 */
		public function _requerido($mensaje = false, $function = false) {
			$id = uniqid();
			if(is_string($function) == true):
				$this->plantilla[$id] = $function;
				$this->constructor['rules'][$this->campo]['required'] = $id;
			else:
				$this->constructor['rules'][$this->campo]['required'] = true;
			endif;
			
			$this->constructor['messages'][$this->campo]['required'] = (is_string($mensaje) == true) ? $mensaje : 'Campo Requerido';
			return $this;
		}
		
		/**
		 * ValidarForm::_remoto()
		 * 
		 * Genera la peticion ajax para la consulta
		 * correspondiente del campo
		 * 
		 * @param string $url: direccion de la peticion
		 * @param string $metodo: POST o GET
		 * @param string $mensaje
		 * @return object
		 */
		public function _remoto($url = false, $metodo = false, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['remote'] = array(
				'url' => $url, 
				'type' => $metodo,
				'async' => false
			);
			$this->constructor['messages'][$this->campo]['remote'] = (is_string($mensaje) == true) ? $mensaje : 'No se encuentra la información solicitada';
			return $this;
		}
		
		/**
		 * ValidarForm::_minCaracteres()
		 * 
		 * Asigna la cantidad minima de caracteres
		 * @param integer $cantidad
		 * @param string $mensaje
		 * @return object
		 */
		public function _minCaracteres($cantidad = 1, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['minlength'] = $cantidad;
			$this->constructor['messages'][$this->campo]['minlength'] = (is_string($mensaje) == true) ? $mensaje : sprintf('La cantidad mínima de caracteres: %d', $cantidad);
			return $this;
		}
		
		/**
		 * ValidarForm::_maxCaracteres()
		 *
		 * Asigna la cantidad maxima de caracteres 
		 * @param integer $cantidad
		 * @param string $mensaje
		 * @return object
		 */
		public function _maxCaracteres($cantidad = 1, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['maxlength'] = $cantidad;
			$this->constructor['messages'][$this->campo]['maxlength'] = (is_string($mensaje) == true) ? $mensaje : sprintf('La cantidad máxima de caracteres: %d', $cantidad);
			return $this;
		}
		
		/**
		 * ValidarForm::_rangoCaracteres()
		 * 
		 * Asigna el rango de caracteres admitido
		 * @param integer $inicio
		 * @param integer $fin
		 * @param string $mensaje
		 * @return object
		 */
		public function _rangoCaracteres($inicio = 1, $fin = 8, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['rangelength'] = array($inicio, $fin);
			$this->constructor['messages'][$this->campo]['rangelength'] = (is_string($mensaje) == true) ? $mensaje : sprintf('La longitud del campo es del rango: %d a %d caracteres', $inicio, $fin);
			return $this;
		}
		
		/**
		 * ValidarForm::_minCantidad()
		 * 
		 * Asigna la cantidad (numerica) minima que debe 
		 * tener el campo
		 * 
		 * @param integer $cantidad
		 * @param string $mensaje
		 * @return object
		 */
		public function _minCantidad($cantidad = 1, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['min'] = $cantidad;
			$this->constructor['messages'][$this->campo]['min'] = (is_string($mensaje) == true) ? $mensaje : sprintf('La cantidad mínima de caracteres es: %d', $cantidad);
			return $this;
		}
		
		/**
		 * ValidarForm::_maxCantidad()
		 * 
		 * Asigna la cantidad (numerica) maxima que debe 
		 * tener el campo
		 * 
		 * @param integer $cantidad
		 * @param string $mensaje
		 * @return object
		 */
		public function _maxCantidad($cantidad = 1, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['max'] = $cantidad;
			$this->constructor['messages'][$this->campo]['max'] = (is_string($mensaje) == true) ? $mensaje : sprintf('La cantidad máxima de caracteres es: %d', $cantidad);
			return $this;
		}
		
		/**
		 * ValidarForm::_rangoCantidad()
		 * 
		 * Asigna el rango correspondiente para ser validado
		 * el cual es un rango numerico
		 * 
		 * @param integer $inicio
		 * @param integer $fin
		 * @param string $mensaje
		 * @return object
		 */
		public function _rangoCantidad($inicio = 1, $fin = 8, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['range'] = array($inicio, $fin);
			$this->constructor['messages'][$this->campo]['range'] = (is_string($mensaje) == true) ? $mensaje : sprintf('El valor del campo es del rango: %d a %d (numerico)', $inicio, $fin);
			return $this;
		}
		
		/**
		 * ValidarForm::_correo()
		 * 
		 * Valida el formato de correo electronico
		 * @param string $mensaje
		 * @return object
		 */
		public function _correo($mensaje = false) {
			$this->constructor['rules'][$this->campo]['email'] = true;
			$this->constructor['messages'][$this->campo]['email'] = (is_string($mensaje) == true) ? $mensaje : 'El formato del correo no es correcto';
			return $this;
		}
		
		/**
		 * ValidarForm::_url()
		 * 
		 * Genera la validacion de la url
		 * @param string $mensaje
		 * @return object
		 */
		public function _url($mensaje = false) {
			$this->constructor['rules'][$this->campo]['url'] = true;
			$this->constructor['messages'][$this->campo]['url'] = (is_string($mensaje) == true) ? $mensaje : 'El formato de la URL no es correcto';
			return $this;
		}
		
		/**
		 * ValidarForm::_fecha()
		 * 
		 * Valida la fecha 
		 * @param bool $mensaje
		 * @return object
		 */
		public function _fecha($mensaje = false) {
			$this->constructor['rules'][$this->campo]['date'] = true;
			$this->constructor['messages'][$this->campo]['date'] = (is_string($mensaje) == true) ? $mensaje : 'El formato de fecha debe ser: Año-Mes-Dia';
			return $this;
		}
		
		/**
		 * ValidarForm::_fechaISO()
		 * 
		 * Valida la fecha 
		 * @param bool $mensaje
		 * @return object
		 */
		public function _fechaISO($mensaje = false) {
			$this->constructor['rules'][$this->campo]['dateISO'] = true;
			$this->constructor['messages'][$this->campo]['dateISO'] = (is_string($mensaje) == true) ? $mensaje : 'El formato de fecha ISO debe ser: Año-Mes-Dia';
			return $this;
		}
		
		/**
		 * ValidarForm::_numero()
		 * 
		 * Valida que sea numerico el campo
		 * @param string $mensaje
		 * @return object
		 */
		public function _numero($mensaje = false) {
			$this->constructor['rules'][$this->campo]['number'] = true;
			$this->constructor['messages'][$this->campo]['number'] = (is_string($mensaje) == true) ? $mensaje : 'El campo debe tener unicamente datos númericos';
			return $this;
		}
		
		/**
		 * ValidarForm::_digitos()
		 *
		 * Valida que sea digitos los que sean ingresados 
		 * @param string $mensaje
		 * @return object
		 */
		public function _digitos($mensaje = false) {
			$this->constructor['rules'][$this->campo]['digits'] = true;
			$this->constructor['messages'][$this->campo]['digits'] = (is_string($mensaje) == true) ? $mensaje : 'Solo es permitido dígitos';
			return $this;
		}
		
		/**
		 * ValidarForm::_campoIgual()
		 * 
		 * Valida que un campo sea igual a otro
		 * @param string $idCampo
		 * @param string $mensaje
		 * @return object
		 */
		public function _campoIgual($idCampo = false, $mensaje = false) {
			$this->constructor['rules'][$this->campo]['equalTo'] = '#'.$idCampo;
			$this->constructor['messages'][$this->campo]['equalTo'] = (is_string($mensaje) == true) ? $mensaje : 'Los campos no son iguales';
			return $this;
		}
		
		/**
		 * ValidarForm::_archivoMimeType()
		 * 
		 * Solo acepta los archivos con los mimetypes
		 * indicados
		 * 
		 * @return object
		 */
		public function _archivoMimeType() {
			$parametros = func_get_args();
			$this->constructor['rules'][$this->campo]['accept'] = implode(', ', $parametros);
			$this->constructor['messages'][$this->campo]['accept'] = 'Solo son aceptados los archivos del tipo especificado';
			return $this;
		}
		
		/**
		 * ValidarForm::_archivoExtension()
		 * 
		 * Solo acepta los archivos con las extensiones
		 * indicadas
		 * 
		 * @return object
		 */
		public function _archivoExtension() {
			$parametros = func_get_args();
			$this->constructor['rules'][$this->campo]['extension'] = implode('|', $parametros);
			$this->constructor['messages'][$this->campo]['extension'] = 'Solo son aceptados los archivos con la extensión especificada';
			return $this;
		}
		
		/**
		 * ValidarForm::peticionAjax()
		 * 
		 * Genera una peticion adicional para 
		 * procedimientos ajax
		 *  
		 * @param string $funcion
		 * @return object
		 */
		public function peticionAjax($funcion = false) {
			$this->constructor['submitHandler'] = '{{ submitHandler }}';
			$script = <<<EOT
	function(formulario) {
		{$funcion}
	}
EOT;
			$this->plantilla['{{ submitHandler }}'] = trim($script);
			return $this;
		}
		
		/**
		 * ValidarForm::mostrarValidacion()
		 * 
		 * Genera el proceso 
		 * @param string $idFormulario
		 * @return string
		 */
		public function mostrarValidacion($idFormulario = false) {
			if(is_string($idFormulario) == true OR is_numeric($idFormulario) == true):
				return $this->construirScript($idFormulario);
			else:
				throw new Excepcion('Debe ingresar el ID del formulario en el proceso de ValidarForm', 0);
			endif;
		}
		
		/**
		 * ValidarForm::construirJquery()
		 * 
		 * Incluye la libreria de jquery
		 * @return string
		 */
		private function construirJquery() {
			if($this->jQueryScript == true):
				return <<<EOT
<script type="text/javascript" src="{$this->rutas->reservado()}/js_validacion/jquery-1.11.3.min.js"></script>
EOT;
			endif;
		}
		
		/**
		 * ValidarForm::construirValidate()
		 * 
		 * Incluye la libreria validate
		 * @return string
		 */
		private function construirValidate() {
			if($this->validate == true):
				return <<<EOT
<script type="text/javascript" src="{$this->rutas->reservado()}/js_validacion/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{$this->rutas->reservado()}/js_validacion/additional-methods.min.js"></script>
EOT;
			endif;
		}
		
		/**
		 * ValidarForm::construirScript()
		 * 
		 * Genera el proceso de construccion del
		 * script correspondiente de la validacion
		 * 
		 * @param string $idForm
		 * @return string
		 */
		private function construirScript($idForm = false) {
			return <<<EOT
	{$this->construirJquery()}
	{$this->construirValidate()}
	
	<script type="text/javascript">
		{$this->jQuery}(document).ready(function() {
			{$this->jQuery}("#{$idForm}").validate({$this->construirPlantillaDatos()});
		});
	</script>	
EOT;
		}
		
		/**
		 * ValidarForm::construirPlantillaDatos()
		 * 
		 * Genera el array de datos de la plantilla correspondiente
		 * @return string
		 */
		private function construirPlantillaDatos() {
			$json = json_encode($this->constructor, JSON_PRETTY_PRINT);
			return (is_array($this->plantilla) == true) ? $this->construirPlantillaArray($json) : $json;
		}
		
		/**
		 * ValidarForm::construirPlantillaArray()
		 * 
		 * Genera el reemplazo de datos para completar
		 * el proceso de plantilla
		 * 
		 * @param string $json
		 * @return string
		 */
		private function construirPlantillaArray($json = false) {
			$llaves = array_keys($this->plantilla);
			return str_replace(array_map(function($valor) {
				return implode('', array('"', $valor, '"'));
			}, $llaves), $this->plantilla, $json);
		}
	}