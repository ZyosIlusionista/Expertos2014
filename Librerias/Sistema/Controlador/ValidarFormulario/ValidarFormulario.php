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
	
	namespace Sistema\Controlador\ValidarFormulario;
	use \Neural\Excepcion;
	
	class ValidarFormulario {
		
		private $estado = false;
		private $namespace = false;
		private $peticion = false;
		private $propiedad = false;
		private $mensajes = false;
		private $conversion = false;
		
		/**
		 * ValidarFormulario::__construct()
		 * 
		 * Genera las variables requeridas
		 * @param object $peticion
		 * @return void
		 */
		function __construct($peticion = false) {
			if(is_object($peticion) == true):
				$this->peticion = $peticion;
			else:
				throw new Excepcion('Se requiere el objeto de peticiones para el proceso de validación de formularios', 0);
			endif;
			$this->conversion = array('booleano' => 'boolean', 'entero' => 'integer', 'flotante' => 'float', 'texto' => 'string', 'nulo' => 'null');
		}
		
		/**
		 * ValidarFormulario::mensajeError()
		 * 
		 * Retorna los mensajes de error generados por
		 * la validacion
		 * 
		 * @return array
		 */
		public function mensajeError() {
			if($this->estado == true):
				return $this->mensajes;
			else:
				throw new Excepcion('No se ha generado una validación de formulario para obtener los errores', 0);
			endif;
		}
		
		/**
		 * ValidarFormulario::validar()
		 * 
		 * Genera el proceso de validacion del formulario
		 * y retorna el valor booleano indicado el
		 * estatus de la validacion
		 * 
		 * @param string $namespace
		 * @return bool
		 */
		public function validar($namespace = false) {
			if(is_string($namespace) == true):
				return $this->proceso($namespace);
			else:
				throw new Excepcion('Es requerido indicar el namespace del formulario correspondiente para el proceso de validación', 0);
			endif;
		}
		
		/**
		 * ValidarFormulario::proceso()
		 * 
		 * Genera el proceso correspondiente
		 * 
		 * @param string $namespace
		 * @return void
		 */
		private function proceso($namespace = false) {
			$this->namespace = new $namespace;
			
			$propiedad = new Parametros($namespace);
			$this->propiedad = $propiedad->obtener();
			
			if(call_user_func(array($this->peticion, $this->propiedad['clase']['metodo'].'Existencia')) == true):
				return $this->resultado();
			else:
				throw new Excepcion(sprintf('No hay una petición: [ %s ] para generar el proceso de validación de formulario', mb_strtoupper($this->propiedad['clase']['metodo'])), 0);
			endif;
		}
		
		/**
		 * ValidarFormulario::resultado()
		 * 
		 * Retorna el resultado correspondiente
		 * @return bool
		 */
		private function resultado() {
			$peticion = new PeticionEstructura(call_user_func(array($this->peticion, $this->propiedad['clase']['metodo'])), $this->propiedad);
			$resultado = $peticion->procesar();
			
			$this->mensajes = $peticion->mensajes();
			$this->estado = true;
			$this->setClase();
			return $resultado;
		}
		
		/**
		 * ValidarFormulario::setClase()
		 * 
		 * e asigna los valores a la 
		 * clase correspondiente para obtener
		 * el formato y validaciones adicionales
		 * 
		 * @return void
		 */
		private function setClase() {
			$data = call_user_func(array($this->peticion, $this->propiedad['clase']['metodo']));
			foreach ($this->propiedad['formulario'] AS $campo => $param):
				if(array_key_exists($campo, $data) == true):
					call_user_func_array(array($this->namespace, 'set'.ucfirst(mb_strtolower($campo))), array($this->setClaseSeleccion($data[$campo], $param['tipo'])));
				else:
					throw new Excepcion(sprintf('No existe el campo: %s en el proceso del formulario', $campo), 0);
				endif;
			endforeach;
		}
		
		/**
		 * ValidarFormulario::setClaseSeleccion()
		 * 
		 * Genera el formato correspondiente
		 * @param mixed $info
		 * @param string $conversion
		 * @return mixed
		 */
		private function setClaseSeleccion($info = false, $conversion = false) {
			$seleccion = (array_key_exists($conversion, $this->conversion) == true) ? $this->conversion[$conversion] : 'string';
			if(is_array($info) == true):
				foreach ($info AS $llave => $valor):
					settype($valor, $seleccion);
					$lista[$llave] = $valor;
				endforeach;
				return $lista;
			else:
				settype($info, $seleccion);
				return $info;
			endif;
		}
		
		/**
		 * ValidarFormulario::datosFormulario()
		 * 
		 * Retorna la matriz de datos con el formato indicado
		 * en el objeto de validacion
		 * 
		 * @return array
		 */
		public function datosFormulario() {
			if($this->estado == true):
				return $this->datosFormClase();
			else:
				throw new Excepcion('No se ha generado el proceso de validación del formulario para retonar los datos correspondientes', 0);
			endif;
		}
		
		/**
		 * ValidarFormulario::datosFormClase()
		 * 
		 * Se valida la informacion sobre el tipo de datos
		 * a retornar
		 * 
		 * @return array
		 */
		private function datosFormClase() {
			if($this->propiedad['clase']['tipo'] == 'multidimensional'):
				return $this->formatoMultidimensional();
			else:
				return $this->formatoUnidimensional();
			endif;
		}
		
		/**
		 * ValidarFormulario::formatoMultidimensional()
		 * 
		 * Genera el formato correspondiente
		 * @return array
		 */
		private function formatoMultidimensional() {
			foreach ($this->propiedad['formulario'] AS $campo => $param):
				$data = call_user_func(array($this->namespace, 'get'.ucfirst(mb_strtolower($campo))));
				if(is_array($data) == true):
					foreach ($data AS $llave => $valor):
						$lista[$llave][$param['columna']] = $valor;
					endforeach;
				else:
					$lista[$param['columna']] = $data;
				endif;
			endforeach;
			unset($campo, $param, $data, $llave, $valor);
			return $lista;
		}
		
		/**
		 * ValidarFormulario::formatoUnidimensional()
		 * 
		 * Se genera el formato para un array unidimensional
		 * 
		 * @return array
		 */
		private function formatoUnidimensional() {
			foreach ($this->propiedad['formulario'] AS $campo => $param):
				$lista[$param['columna']] = call_user_func(array($this->namespace, 'get'.ucfirst(mb_strtolower($campo))));
			endforeach;
			unset($campo, $param);
			return $lista;
		}
	}