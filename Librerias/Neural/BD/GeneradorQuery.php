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
	
	class GeneradorQuery {
		
		private $entorno = false;
		private $conexion = false;
		private $condiciones = false;
		
		/**
		 * GeneradorQuery::__construct()
		 * 
		 * Genera la asignacion de la conexion a la base
		 * de datos
		 * 
		 * @param string $conexion
		 * @return void
		 */
		function __construct($conexion = false, $entorno = false) {
			$this->entorno = $this->inputEntorno($entorno);
			$this->conexion = $this->validarConexionInput($conexion)->createQueryBuilder();
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
				throw new Excepcion('No se ha seleccionado en un entorno para el proceso de GeneradorQuery', 0);
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
				throw new Excepcion(sprintf('El entorno: %s no corresponde al requerido en el proceso del GeneradorQuery', $entorno), 0);
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
				throw new Excepcion('Debe ingresar una conexión para generar la consulta en el proceso GeneradorQuery', 0);
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
				throw new Excepcion(sprintf('La conexión: %s no existe en el archivo de configuración para el proceso de GeneradorQuery', $conexion), 0);
			endif;
		}
		
		/**
		 * GeneradorQuery::select()
		 * 
		 * Agrega las columnas correspondientes
		 * @return object
		 */
		public function select() {
			$parametros = func_get_args();
			if(count($parametros) >= 1):
				$this->conexion->select(implode(', ', $parametros));
			else:
				throw new Excepcion('Debe ingresar las columnas en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::from()
		 * 
		 * Genera la asignacion de la tabla de la consulta
		 * @param string $tabla
		 * @param string $alias
		 * @return object
		 */
		public function from($tabla = false, $alias = null) {
			if(is_string($tabla) == true):
				$this->conexion->from($tabla, $alias);
			else:
				throw new Excepcion('Debe ingresar la tabla en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::where()
		 * 
		 * genera la condicion correspondiente
		 * @param string $condicion
		 * @param mixed $valor
		 * @return object
		 */
		public function where($condicion = false, $valor = false) {
			if(is_string($condicion) == true):
				$this->conexion->where($condicion);
				$this->agregarCondicion($valor);
			else:
				throw new Excepcion('Debe ingresar la condición en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::andWhere()
		 * 
		 * genera la condicion AND correspondiente
		 * @param string $condicion
		 * @param mixed $valor
		 * @return object
		 */
		public function andWhere($condicion = false, $valor = false) {
			if(is_string($condicion) == true):
				$this->conexion->andWhere($condicion);
				$this->agregarCondicion($valor);
			else:
				throw new Excepcion('Debe ingresar la condición [ AND ] en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::orWhere()
		 * 
		 * genera la condicion OR correspondiente
		 * @param string $condicion
		 * @param mixed $valor
		 * @return object
		 */
		public function orWhere($condicion = false, $valor = false) {
			if(is_string($condicion) == true):
				$this->conexion->orWhere($condicion);
				$this->agregarCondicion($valor);
			else:
				throw new Excepcion('Debe ingresar la condición [ OR ] en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::groupBy()
		 * 
		 * Genera la agrupacion correspondiente
		 * @param string $columna
		 * @return object
		 */
		public function groupBy($columna = false) {
			if(is_string($columna) == true):
				$this->conexion->groupBy($columna);
			else:
				throw new Excepcion('Debe ingresar la columna a agrupar en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::having()
		 * 
		 * Genera la condicion having correspondiente
		 * @param string $condicion
		 * @param string $valor
		 * @return object
		 */
		public function having($condicion = false, $valor = false) {
			if(is_string($condicion) == true):
				$this->conexion->having($condicion);
				$this->agregarCondicion($valor);
			else:
				throw new Excepcion('Debe ingresar la condición [ AND having ] en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::andHaving()
		 * 
		 * Genera la condicion having correspondiente
		 * @param string $condicion
		 * @param string $valor
		 * @return object
		 */
		public function andHaving($condicion = false, $valor = false) {
			if(is_string($condicion) == true):
				$this->conexion->andHaving($condicion);
				$this->agregarCondicion($valor);
			else:
				throw new Excepcion('Debe ingresar la condición [ OR having ] en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::innerJoin()
		 * 
		 * Genera el proceso de la relacion correspondiente
		 * @param bool $aliasRelacion: alias primaria tabla de la relacion
		 * @param bool $tablaJoin: tabla de la relacion
		 * @param mixed $aliasTablaJoin alias de la tabla de la relacion
		 * @param mixed $condicion
		 * @return object
		 */
		public function innerJoin($aliasRelacion = false, $tablaJoin = false, $aliasTablaJoin = false, $condicion = null) {
			if(is_string($aliasRelacion) AND is_string($tablaJoin) AND is_string($aliasTablaJoin) AND is_string($condicion)):
				$this->conexion->innerJoin($aliasRelacion, $tablaJoin, $aliasTablaJoin, $condicion);
			else:
				throw new Excepcion('Debe ingresar toda la información requerida del innerJoin en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::leftJoin()
		 * 
		 * Genera el proceso de la relacion correspondiente
		 * @param bool $aliasRelacion: alias primaria tabla de la relacion
		 * @param bool $tablaJoin: tabla de la relacion
		 * @param mixed $aliasTablaJoin alias de la tabla de la relacion
		 * @param mixed $condicion
		 * @return object
		 */
		public function leftJoin($aliasRelacion = false, $tablaJoin = false, $aliasTablaJoin = false, $condicion = null) {
			if(is_string($aliasRelacion) AND is_string($tablaJoin) AND is_string($aliasTablaJoin) AND is_string($condicion)):
				$this->conexion->leftJoin($aliasRelacion, $tablaJoin, $aliasTablaJoin, $condicion);
			else:
				throw new Excepcion('Debe ingresar toda la información requerida del LeftJoin en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::join()
		 * 
		 * Genera el proceso de la relacion correspondiente
		 * @param bool $aliasRelacion: alias primaria tabla de la relacion
		 * @param bool $tablaJoin: tabla de la relacion
		 * @param mixed $aliasTablaJoin alias de la tabla de la relacion
		 * @param mixed $condicion
		 * @return object
		 */
		public function join($aliasRelacion = false, $tablaJoin = false, $aliasTablaJoin = false, $condicion = null) {
			if(is_string($aliasRelacion) AND is_string($tablaJoin) AND is_string($aliasTablaJoin) AND is_string($condicion)):
				$this->conexion->join($aliasRelacion, $tablaJoin, $aliasTablaJoin, $condicion);
			else:
				throw new Excepcion('Debe ingresar toda la información requerida del Join en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::rightJoin()
		 * 
		 * Genera el proceso de la relacion correspondiente
		 * @param bool $aliasRelacion: alias primaria tabla de la relacion
		 * @param bool $tablaJoin: tabla de la relacion
		 * @param mixed $aliasTablaJoin alias de la tabla de la relacion
		 * @param mixed $condicion
		 * @return object
		 */
		public function rightJoin($aliasRelacion = false, $tablaJoin = false, $aliasTablaJoin = false, $condicion = null) {
			if(is_string($aliasRelacion) AND is_string($tablaJoin) AND is_string($aliasTablaJoin) AND is_string($condicion)):
				$this->conexion->rightJoin($aliasRelacion, $tablaJoin, $aliasTablaJoin, $condicion);
			else:
				throw new Excepcion('Debe ingresar toda la información requerida del RightJoin en el Generador de Query', 0);
			endif;
			return $this;
		}
		
		/**
		 * GeneradorQuery::orderBy()
		 * 
		 * Genera el proceso de ordenar el query correspondiente
		 * @param string $columna
		 * @param string $orden
		 * @return object
		 */
		public function orderBy($columna = false, $orden = null) {
			if(is_string($columna) == true):
				$this->conexion->orderBy($columna, $orden);
			else:
				throw new Excepcion('Debe ingresar la columna correspondiente en el OrderBy del Generador de Query', 0);
			endif;
			return $this;
		}
		
		
		/**
		 * GeneradorQuery::limitOffset()
		 * 
		 * Genera el limite correspondiente
		 * @param bool $registro: registro a visualizar
		 * @param bool $cantidad: cantidad de registros por
		 * pagina
		 * @return
		 */
		public function limitOffset($limit = false, $offSet = false) {
			if(is_numeric($limit) == true AND is_numeric($offSet) == true):
				$this->conexion->setFirstResult($offSet);
				$this->conexion->setMaxResults($limit);
			else:
				throw new Excepcion('Es necesario ingresar el valor numerico para el registro y la cantidad del Generador de Query', 0);
			endif;
		}
		
		/**
		 * GeneradorQuery::obtenerSQL()
		 * 
		 * Retorna el sql correspondiente que se ejecutara
		 * @return string
		 */
		public function obtenerSQL() {
			if(is_array($this->condiciones) == true):
				$sql = $this->conexion->getSQL();
				return vsprintf(str_replace('?', '\'%s\'', $sql), $this->condiciones);
			else:
				return $this->conexion->getSQL();
			endif;
		}
		
		/**
		 * GeneradorQuery::fetch()
		 * 
		 * Retorna el primer registro de la consulta
		 * @return array
		 */
		public function fetch() {
			$this->condicionesProcesar();
			return $this->conexion->execute()->fetch();
		}
		
		/**
		 * GeneradorQuery::fetchAll()
		 * 
		 * retorna todos los registros de la consulta
		 * @return array
		 */
		public function fetchAll() {
			$this->condicionesProcesar();
			return $this->conexion->execute()->fetchAll();
		}
		
		/**
		 * GeneradorQuery::agregarCondicion()
		 * 
		 * Asigna los valores para la sustitucion
		 * @param mixed $valor
		 * @return void
		 */
		private function agregarCondicion($valor = false) {
			if(is_array($valor) == true):
				foreach ($valor AS $posicion => $data):
					if(is_string($posicion) == true):
						$this->agregarCondicionPosicion($posicion, $data);
					else:
						$this->condiciones[] = addslashes($data);
					endif;
				endforeach;
			else:
				$this->condiciones[] = addslashes($valor);
			endif;
		}
		
		/**
		 * GeneradorQuery::agregarCondicionPosicion()
		 * 
		 * Asigna el nombre de la posicion correspondiente
		 * @param array $array
		 * @return void
		 */
		private function agregarCondicionPosicion($posicion = false, $valor = false) {
			$this->condiciones[$posicion] = $valor;
		}
		
		/**
		 * GeneradorQuery::condicionesProcesar()
		 * 
		 * Agrega los parametros correspondientes segun
		 * la posicion correspondiente
		 * 
		 * @return void
		 */
		private function condicionesProcesar() {
			if(is_array($this->condiciones) == true):
				foreach($this->condiciones AS $posicion => $valor):
					$this->conexion->setParameter($posicion, $valor);
				endforeach;
			endif;
		}
	}