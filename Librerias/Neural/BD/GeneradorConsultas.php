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
	use \Doctrine\DBAL\Query\QueryBuilder;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigBaseDatos;
	
	class GeneradorConsultas {
		
		private $entorno = false;
		private $conexion = false;
		private $columna = false;
		private $tabla = false;
		private $tablaAlias = false;
		private $joins = false;
		private $condiciones = false;
		private $condNombre = false;
		private $condPosicion = false;
		private $groupBy = false;
		private $havings = false;
		private $havingPosicion = false;
		private $havingNombre = false;
		private $orderBy = false;
		private $limitInput = false;
		private $offSetInput = false;
		private $constructor = false;
				
		/**
		 * GeneradorConsultas::__construct()
		 * 
		 * Asigna la conexion correspondiente
		 * @param string $conexion
		 * @return void
		 */
		function __construct($conexion = false, $entorno = false) {
			$this->entorno = $this->inputEntorno($entorno);
			$this->conexion = $this->validarConexionInput($conexion)->createQueryBuilder();
		}
		
		/**
		 * GeneradorConsultas::inputEntorno()
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
		 * GeneradorConsultas::inputEntornoValidar()
		 * 
		 * Valida la existencoa del entorno correspondiente
		 * @return string
		 */
		private function inputEntornoValidar() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('No se ha seleccionado en un entorno para el proceso de GeneradorConsultas', 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::inputEntornoSeleccion()
		 * 
		 * Genera la validacion de la seleccion del entorno
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no corresponde al requerido en el proceso del GeneradorConsultas', $entorno), 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::validarConexionInput()
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
				throw new Excepcion('Debe ingresar una conexión para generar la consulta', 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::inputConexionExistencia()
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
				throw new Excepcion(sprintf('La conexión: %s no existe en el archivo de configuración para el proceso de GeneradorConsultas', $conexion), 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::columnas()
		 * 
		 * Asigna las columnas que se visualizaran
		 * @return object
		 */
		public function columnas() {
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				$this->columna = $parametros;
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::tabla()
		 * 
		 * Asigna la tabla correspondiente junto con su
		 * alias
		 * 
		 * @param string $tabla
		 * @param string $alias
		 * @return object
		 */
		public function tabla($tabla = false, $alias = false) {
			if(is_string($tabla) == true AND is_string($alias) == true):
				$this->tabla = $tabla;
				$this->tablaAlias = $alias;
			else:
				throw new Excepcion('Debe ingresar el nombre de la tabla y el alias correspondiente en el generador de consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::join()
		 * 
		 * Asigna el join correspondiente
		 * @param string $tabla
		 * @param string $alias
		 * @param string $condicion
		 * @return object
		 */
		public function join($tabla = false, $alias = false, $condicion = false) {
			if(is_string($tabla) == true AND is_string($alias) == true AND is_string($condicion) == true):
				$this->joins[] = array('tipo' => 'join', 'tabla' => $tabla, 'alias' => $alias, 'condicion' => $condicion);
			else:
				throw new Excepcion('Debe ingresar los datos necesarios para el Join del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::innerJoin()
		 * 
		 * Asigna el join correspondiente
		 * @param string $tabla
		 * @param string $alias
		 * @param string $condicion
		 * @return object
		 */
		public function innerJoin($tabla = false, $alias = false, $condicion = false) {
			if(is_string($tabla) == true AND is_string($alias) == true AND is_string($condicion) == true):
				$this->joins[] = array('tipo' => 'innerJoin', 'tabla' => $tabla, 'alias' => $alias, 'condicion' => $condicion);
			else:
				throw new Excepcion('Debe ingresar los datos necesarios para el Join del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::leftJoin()
		 * 
		 * Asigna el join correspondiente
		 * @param string $tabla
		 * @param string $alias
		 * @param string $condicion
		 * @return object
		 */
		public function leftJoin($tabla = false, $alias = false, $condicion = false) {
			if(is_string($tabla) == true AND is_string($alias) == true AND is_string($condicion) == true):
				$this->joins[] = array('tipo' => 'leftJoin', 'tabla' => $tabla, 'alias' => $alias, 'condicion' => $condicion);
			else:
				throw new Excepcion('Debe ingresar los datos necesarios para el Join del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::rightJoin()
		 * 
		 * Asigna el join correspondiente
		 * @param string $tabla
		 * @param string $alias
		 * @param string $condicion
		 * @return object
		 */
		public function rightJoin($tabla = false, $alias = false, $condicion = false) {
			if(is_string($tabla) == true AND is_string($alias) == true AND is_string($condicion) == true):
				$this->joins[] = array('tipo' => 'rightJoin', 'tabla' => $tabla, 'alias' => $alias, 'condicion' => $condicion);
			else:
				throw new Excepcion('Debe ingresar los datos necesarios para el Join del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::condicion()
		 * 
		 * Genera la condicion inicial de la consulta
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function condicion($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				if(is_array($this->condiciones) == false):
					$this->condiciones[] = array('tipo' => 'where', 'plantilla' => $condicion);
					$this->condicionParametros($parametros);
				else:
					throw new Excepcion('Ya se ha ingresado una condición previa, seleccione [ and - or ] condición para el Generador de Consultas', 0);
				endif;
			else:
				throw new Excepcion('Ingrese la condición y los valores para las condiciones del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::andCondicion()
		 * 
		 * Genera la condicion inicial de la consulta
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function andCondicion($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				$this->condiciones[] = (is_array($this->condiciones) == true) ? array('tipo' => 'andWhere', 'plantilla' => $condicion) : array('tipo' => 'where', 'plantilla' => $condicion);
				$this->condicionParametros($parametros);
			else:
				throw new Excepcion('Ingrese la condición y los valores para las condiciones del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::orCondicion()
		 * 
		 * Genera la condicion inicial de la consulta
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function orCondicion($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				$this->condiciones[] = (is_array($this->condiciones) == true) ? array('tipo' => 'orWhere', 'plantilla' => $condicion) : array('tipo' => 'where', 'plantilla' => $condicion);
				$this->condicionParametros($parametros);
			else:
				throw new Excepcion('Ingrese la condición y los valores para las condiciones del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::condicionParametros()
		 * 
		 * Genera la validacion para la asignacion del
		 * valor ingresado en las condiciones establecidas
		 * 
		 * @param array $array
		 * @return object
		 */
		private function condicionParametros($array = false) {
			if(is_array($array) == true AND count($array) >= 1):
				foreach ($array AS $valor):
					if(is_array($valor) == true):
						$this->condicionParametrosArray($valor);
					else:
						$this->condPosicion[] = $valor;
					endif;
				endforeach;
			else:
				throw new Excepcion('Ingrese los valores para las condiciones del Generador de Consultas', 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::condicionParametrosArray()
		 * 
		 * Asigna los valores correspondientes para la condicion
		 * @param array $array
		 * @return void
		 */
		private function condicionParametrosArray($array = false) {
			$posicion = key($array);
			if(is_array($this->condNombre) == true):
				if(array_key_exists($posicion, $this->condNombre) == false):
					$this->condNombre[$posicion] = $array[$posicion];
				else:
					throw new Excepcion(sprintf('El parametro: %s ya se encuentra asignado en las condiciones del Generador de Consultas', $posicion), 0);
				endif;
			else:
				$this->condNombre[$posicion] = $array[$posicion];
			endif;
		}
		
		/**
		 * GeneradorConsultas::agrupar()
		 * 
		 * Genera el proceso de agrupar por las columnas
		 * indicadas
		 * @return void
		 */
		public function agrupar($columna = false) {
			if(is_string($columna) == true):
				$this->groupBy[] = (is_array($this->groupBy) == true) ? array('tipo' => 'addGroupBy', 'columna' => $columna) : array('tipo' => 'groupBy', 'columna' => $columna);
			else:
				throw new Excepcion('Debe ingresar la columna a agrupar en el Generador de Consultas', 0);
			endif;
			
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				$this->groupBy[] = '';
			else:
				throw new Excepcion('Debe ingresar la(s) columnas(s) a agrupar en el Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::having()
		 * 
		 * Genera el proceso del having correspondiente
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function having($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				if(is_array($this->havings) == false):
					$this->havings[] = array('tipo' => 'having', 'plantilla' => $condicion);
					$this->condicionParametros($parametros);
				else:
					throw new Excepcion('Ya se ha ingresado un having previo, seleccione [ and - or ] having para el Generador de Consultas', 0);
				endif;
			else:
				throw new Excepcion('Ingrese la condición y los valores para el having del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::andHaving()
		 * 
		 * Genera el proceso del having correspondiente
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function andHaving($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				$this->havings[] = (is_array($this->havings) == true) ? array('tipo' => 'andHaving', 'plantilla' => $condicion) : array('tipo' => 'having', 'plantilla' => $condicion);
				$this->condicionParametros($parametros);
			else:
				throw new Excepcion('Ingrese la condición y los valores para el andHaving del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::orHaving()
		 * 
		 * Genera el proceso del having correspondiente
		 * @param string $condicion
		 * @param string $valores
		 * @return object
		 */
		public function orHaving($condicion = false, $valores = false) {
			$parametros = func_get_args();
			if(is_string($condicion) == true AND $valores == true):
				unset($parametros[0]);
				$this->havings[] = (is_array($this->havings) == true) ? array('tipo' => 'orHaving', 'plantilla' => $condicion) : array('tipo' => 'having', 'plantilla' => $condicion);
				$this->condicionParametros($parametros);
			else:
				throw new Excepcion('Ingrese la condición y los valores para el orHaving del Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::ordenar()
		 * 
		 * Genera el proceso de ordenar la consulta
		 * @param string $columna
		 * @param styring $orden : ASC - DESC
		 * @return object
		 */
		public function ordenar($columna = false, $orden = null) {
			$parametros = func_get_args();
			if(is_string($columna) == true):
				$this->orderBy[] = (is_array($this->orderBy) == true) ? array('tipo' => 'addGroupBy', 'parametros' => $parametros) : array('tipo' => 'groupBy', 'parametros' => $parametros);
			else:
				throw new Excepcion('Debe ingresar la columna para ordenar en el Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::limitar()
		 * 
		 * Genera el proceso de limitar los datos
		 * @param integer $limit: cantidad de registros a observar
		 * @param integer $offSet: punto cantidad de registros a observar
		 * @return object
		 */
		public function limitar($limit = false, $offSet = false) {
			if(is_numeric($limit) == true AND is_numeric($offSet) == true):
				$this->limitInput = $limit;
				$this->offSetInput = $offSet;
			else:
				throw new Excepcion('Debe ingresar tanto el limite como el offSet para limitar en el Generador de Consultas', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorConsultas::obtener()
		 * 
		 * Retorna el primer valor de la consulta
		 * PDO::fetch()
		 * 
		 * @return array
		 */
		public function obtener() {
			if($this->constructor == false):
				$this->procesar();
				$this->constructor = true;
			endif;
			return $this->conexion->execute()->fetch();
		}
		
		/**
		 * GeneradorConsultas::obtenerTodo()
		 * 
		 * Retorna todos los valores de la consulta
		 * PDO::fetchAll()
		 * 
		 * @return array
		 */
		public function obtenerTodo() {
			if($this->constructor == false):
				$this->procesar();
				$this->constructor = true;
			endif;
			return $this->conexion->execute()->fetchAll();
		}
		
		/**
		 * GeneradorConsultas::obtenerSQL()
		 * 
		 * Retorna el constructor de SQL
		 * @param bool $raw
		 * @return string
		 */
		public function obtenerSQL($raw = false) {
			$this->procesar();
			if($raw == true):
				return $this->conexion->getSQL();
			else:
				if(is_array($this->condPosicion) == true):
					$sql = $this->conexion->getSQL();
					return vsprintf(str_replace('?', '\'%s\'', $sql), $this->condPosicion);
				else:
					return $this->conexion->getSQL();
				endif;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesar()
		 * 
		 * Genera la construccion del objeto 
		 * de la consulta correspondiente
		 * @return void
		 */
		private function procesar() {
			$this->procesarSelect();
			$this->procesarTabla();
			$this->procesarJoins();
			$this->procesarCondiciones();
			$this->procesarGroupBy();
			$this->procesarHaving();
			$this->procesarOrderBy();
			$this->procesarLimit();
			$this->procesarCondicionesPosicion();
			$this->procesarCondicionesNombre();
		}
		
		/**
		 * GeneradorConsultas::procesarSelect()
		 * 
		 * Genera el proceso del select correspondientes
		 * @return void
		 */
		private function procesarSelect() {
			$columnas = (is_bool($this->columna) == true) ? null : implode(', ', $this->columna);
			$this->conexion->select($columnas);
		}
		
		/**
		 * GeneradorConsultas::procesarTabla()
		 * 
		 * Genera el proceso del form correspondientes
		 * @return void
		 */
		private function procesarTabla() {
			if(is_bool($this->tabla) == false):
				$this->conexion->from($this->tabla, $this->tablaAlias);
			else:
				throw new Excepcion('Debe ingresar la tabla correspondiente para el Generador de Consultas', 0);
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarJoins()
		 * 
		 * Genera el proceso del join correspondientes
		 * @return void
		 */
		private function procesarJoins() {
			if(is_array($this->joins) == true):
				foreach ($this->joins AS $array):
					$this->conexion->{$array['tipo']}($this->tablaAlias, $array['tabla'], $array['alias'], $array['condicion']);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarCondiciones()
		 * 
		 * Genera el proceso de las condiciones correspondientes
		 * @return void
		 */
		private function procesarCondiciones() {
			if(is_array($this->condiciones) == true):
				foreach ($this->condiciones AS $array):
					$this->conexion->{$array['tipo']}($array['plantilla']);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarCondicionesPosicion()
		 * 
		 * Asigna los valores a la posicion
		 * @return void
		 */
		private function procesarCondicionesPosicion() {
			if(is_array($this->condPosicion) == true):
				foreach ($this->condPosicion AS $posicion => $valor):
					$this->conexion->setParameter($posicion, $valor);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarCondicionesNombre()
		 * 
		 * Asigna los valores a la posicion
		 * @return void
		 */
		private function procesarCondicionesNombre() {
			if(is_array($this->condNombre) == true):
				foreach ($this->condNombre AS $posicion => $valor):
					$this->conexion->setParameter($posicion, $valor);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarGroupBy()
		 * 
		 * Genera el groupby correspondiente
		 * @return void
		 */
		private function procesarGroupBy() {
			if(is_array($this->groupBy) == true):
				foreach ($this->groupBy AS $array):
					$this->conexion->{$array['tipo']}($array['columna']);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarHaving()
		 *
		 * Genera el having correspondiente 
		 * @return void
		 */
		private function procesarHaving() {
			if(is_array($this->havings) == true):
				foreach ($this->havings AS $array):
					$this->conexion->{$array['tipo']}($array['plantilla']);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarOrderBy()
		 *
		 * Genera el orderBy correspondiente 
		 * @return void
		 */
		private function procesarOrderBy() {
			if(is_array($this->orderBy) == true):
				foreach ($this->orderBy AS $array):
					$this->conexion->{$array['tipo']}($array['parametros']);
				endforeach;
			endif;
		}
		
		/**
		 * GeneradorConsultas::procesarLimit()
		 * 
		 * Genera el proceso del limite correspondiente
		 * @return void
		 */
		private function procesarLimit() {
			if(is_bool($this->limitInput) == false):
				$this->conexion->setMaxResults($this->limitInput);
				$this->conexion->setFirstResult($this->offSetInput);
			endif;
		}
	}