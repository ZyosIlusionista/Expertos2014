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
	
	namespace Neural\BD;
	use \Neural\Excepcion;
	
	class Gab {
		
		private $entorno = false;
		private $conexion = false;
		private $tabla = false;
		private $paramArray = false;
		private $paramTipo = false;
		private $condArray = false;
		
		const PARAM_BOOL = \PDO::PARAM_BOOL;
		const PARAM_NULL = \PDO::PARAM_NULL;
		const PARAM_NUMERO = \PDO::PARAM_INT;
		const PARAM_TEXTO = \PDO::PARAM_STR;
		const PARAM_TEXTO_LARGO = \PDO::PARAM_LOB;
		
		/**
		 * Gab::__construct()
		 * 
		 * Asigna las variables necesarias para el proceso
		 * @param mixed $conexion
		 * @param string $tabla
		 * @return void
		 */
		function __construct($conexion = false, $tabla = false, $entorno = false) {
			$this->entorno = $this->inputEntorno($entorno);
			$this->conexion = $this->validarConexionInput($conexion);
			$this->tabla = $this->validarTablaInput($tabla);
		}
		
		/**
		 * GeneradorQuery::inputEntorno()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoSeleccion($entorno);
			else:
				return $this->inputEntornoValidar();
			endif;
		}
		
		/**
		 * GeneradorQuery::inputEntornoValidar()
		 * 
		 * Valida la existencoa del entorno correspondiente
		 * @return string
		 */
		private function inputEntornoValidar() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('No se ha seleccionado en un entorno para el proceso de GAB', 0);
			endif;
		}
		
		/**
		 * GeneradorQuery::inputEntornoSeleccion()
		 * 
		 * Genera la validacion de la seleccion del entorno
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no corresponde al requerido en el proceso del GAB', $entorno), 0);
			endif;
		}
		
		/**
		 * GeneradorQuery::validarConexionInput()
		 * 
		 * Genera la validacion y asignacion de la conexion 
		 * @param mixed $conexion
		 * @return object
		 */
		private function validarConexionInput($conexion = false) {
			if(is_string($conexion) == true):
				return $this->inputConexionExistencia($conexion);
			elseif(is_object($conexion) == true):
				return $conexion;
			else:
				throw new Excepcion('Debe ingresar una conexión para generar la consulta en el proceso GAB', 0);
			endif;
		}
		
		/**
		 * GeneradorQuery::inputConexionExistencia()
		 * 
		 * Genera la validacion de la existencia de la conexion a 
		 * la base de datos
		 * 
		 * @param string $conexion
		 * @return object
		 */
		private function inputConexionExistencia($conexion = false) {
			if(ConfigBaseDatos::conexionExistencia($conexion) == true):
				$connect = new Conexion($conexion, false, $this->entorno);
				return $connect->doctrineDBAL();
			else:
				throw new Excepcion(sprintf('La conexión: %s no existe en el archivo de configuración para el proceso de GAB', $conexion), 0);
			endif;
		}
		
		/**
		 * Gab::validarTablaInput()
		 * 
		 * Valida que se ingrese una tabla como texto
		 * @param string $tabla
		 * @return string
		 */
		private function validarTablaInput($tabla = false) {
			if(is_string($tabla) == true):
				return $tabla;
			else:
				throw new Excepcion('Debe ingresar la tabla donde se realizara el proceso en el Gab', 0);
			endif;
		}
		
		/**
		 * Gab::parametro()
		 * 
		 * Asigna el parametro ya sea para actualizar 
		 * o insertar en una tabla
		 * 
		 * @param string $columna
		 * @param integer / string $valor
		 * @param bool $tipo
		 * @return object
		 */
		public function parametro($columna = false, $valor = false, $tipo = false) {
			if(is_string($columna) == true):
				$this->paramArray[$columna] = $valor;
				if(is_string($tipo) == true):
					$this->paramTipo[] = $tipo;
				endif;
			else:
				throw new Excepcion('Ingrese la columna junto con su valor y tipo de dato en Gab', 0);
			endif;
			return $this;
		}
		
		/**
		 * Gab::parametros()
		 * 
		 * Asigna el parametro ya sea para actualizar 
		 * o insertar en una tabla
		 * 
		 * @param array $array
		 * @param array $tipos
		 * @return object
		 */
		public function parametros($array = false, $tipos = false) {
			if(is_array($array) == true):
				$this->paramArray = $array;
				if(is_array($tipos) == true):
					$this->paramTipo = $tipo;
				endif;
			else:
				throw new Excepcion('Ingrese el array columna => valor en parametros en la clase Gab', 0);
			endif;
			return $this;
		}
		
		/**
		 * Gab::condicion()
		 * 
		 * Genera la asignacion de la condicion 
		 * correspondiente
		 * 
		 * @param string $columna
		 * @param string $valor
		 * @return object
		 */
		public function condicion($columna = false, $valor = false) {
			if(is_string($columna) == true):
				$this->condArray[$columna] = $valor;
			else:
				throw new Excepcion('Debe ingresar la columna y valor para la condicion en el Gab', 0);
			endif;
			return $this;
		}
		
		/**
		 * Gab::condiciones()
		 * 
		 * Asigna las condiciones correspondientes
		 * ingresabndo un array de columna => valor
		 * 
		 * @param array $array
		 * @return object
		 */
		public function condiciones($array = false) {
			if(is_array($array) == true):
				$this->condArray = $array;
			else:
				throw new Excepcion('Debe ingresar una matriz de columna => valor para las condiciones en Gab', 0);
			endif;
			return $this;
		}
		
		/**
		 * Gab::eliminar()
		 * 
		 * Genera el proceso de eliminar el registro
		 * indicado
		 * 
		 * @return integer
		 */
		public function eliminar() {
			if(is_array($this->condArray) == true):
				return $this->conexion->delete($this->tabla, $this->condArray);
			else:
				throw new Excepcion('Debe ingresar las condiciones correspondientes para eliminar el registro en Gab', 0);
			endif;
		}
		
		/**
		 * Gab::actualizar()
		 * 
		 * genera el proceso de actualizar el
		 * registro indicado, retorna 
		 * valor 1: registro actualizado
		 * valor 0: registro no actualizado
		 * 
		 * @return integer
		 */
		public function actualizar() {
			if(is_array($this->paramArray) == true):
				return $this->actualizarCondicion();
			else:
				throw new Excepcion('Es necesario que ingrese los parametros a actualizar en Gab', 0);
			endif;
		}
		
		/**
		 * Gab::actualizarCondicion()
		 * 
		 * Genera la validacion de la existencia
		 * de condiciones
		 * 
		 * @return integer
		 */
		private function actualizarCondicion() {
			if(is_array($this->condArray) == true):
				return $this->actualizarTipos();
			else:
				throw new Excepcion('Es necesario que ingrese las condiciones para actualizar en Gab', 0);
			endif;
		}
		
		/**
		 * Gab::actualizarTipos()
		 * 
		 * genera el proceso de ingresar los tipos de datos
		 * 
		 * @return integer
		 */
		private function actualizarTipos() {
			if(is_array($this->paramTipo) == true):
				$resultado = $this->conexion->update($this->tabla, $this->paramArray, $this->condArray, $this->paramTipo);
			else:
				$resultado = $this->conexion->update($this->tabla, $this->paramArray, $this->condArray);
			endif;
			
			$this->condArray = false;
			$this->paramArray = false;
			$this->paramTipo = false;
			
			return $resultado;
		}
		
		/**
		 * Gab::insertar()
		 * 
		 * Genera el proceso de insertar los datos 
		 * correspondientes y retorna el id del
		 * registro insertado
		 * 
		 * @return integer
		 */
		public function insertar() {
			if(is_array($this->paramArray) == true):
				return $this->insertarTipos();
			else:
				throw new Excepcion('Es necesario que ingrese los parametros a guardar en Gab', 0);
			endif;
		}
		
		/**
		 * Gab::insertarTipos()
		 * 
		 * Genera el proceso de ingreso de los tipos
		 * de datos y retorna el id del ultimo insert
		 * 
		 * @return integer
		 */
		private function insertarTipos() {
			if(is_array($this->paramTipo) == true):
				$this->conexion->insert($this->tabla, $this->paramArray, $this->paramTipo);
			else:
				$this->conexion->insert($this->tabla, $this->paramArray);
			endif;
			
			$this->paramArray = false;
			$this->paramTipo = false;
			
			return $this->conexion->lastInsertId();
		}
	}