<?php
	
	namespace Formularios\Permisos\Nuevo;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Permisos\Nuevo\Acceso"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class Acceso {
		
		/**
		 * validacion::nombre
		 * 
		 * @Columna "Nombre"
		 * @Validacion {"requerido":"Es necesario ingresar el Nombre del Acceso", "texto" : "Es necesario que sea texto o alfanumerico"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $nombre;

		/**
		 * validacion::lectura
		 * 
		 * @Columna "Lectura"
		 * @Validacion {"requerido":"Es necesario Seleccionar un Permiso de Lectura"}
		 * @Existencia true
		 * @Tipo "entero"
		 */
		private $lectura;

		/**
		 * validacion::escritura
		 * 
		 * @Columna "Escritura"
		 * @Validacion {"requerido":"Es necesario Seleccionar un Permiso de Escritura"}
		 * @Existencia true
		 * @Tipo "entero"
		 */
		private $escritura;

		/**
		 * validacion::actualizar
		 * 
		 * @Columna "Actualizar"
		 * @Validacion {"requerido":"Es necesario Seleccionar un Permiso de Actualizar"}
		 * @Existencia true
		 * @Tipo "entero"
		 */
		private $actualizar;

		/**
		 * validacion::eliminar
		 * 
		 * @Columna "Eliminar"
		 * @Validacion {"requerido":"Es necesario Seleccionar un Permiso de Eliminar"}
		 * @Existencia true
		 * @Tipo "entero"
		 */
		private $eliminar;

		/**
		 * validacion::getNombre()
		 * 
		 * @return texto
		 */
		public function getNombre() {
			return trim(mb_strtoupper($this->nombre));
		}

		/**
		 * validacion::getLectura()
		 * 
		 * @return entero
		 */
		public function getLectura() {
			return ($this->lectura == 1) ? 1 : 0;
		}

		/**
		 * validacion::getEscritura()
		 * 
		 * @return entero
		 */
		public function getEscritura() {
			return ($this->escritura == 1) ? 1 : 0;
		}

		/**
		 * validacion::getActualizar()
		 * 
		 * @return entero
		 */
		public function getActualizar() {
			return ($this->actualizar == 1) ? 1 : 0;
		}

		/**
		 * validacion::getEliminar()
		 * 
		 * @return entero
		 */
		public function getEliminar() {
			return ($this->eliminar == 1) ? 1 : 0;
		}

		/**
		 * validacion::setNombre()
		 * 
		 * @param texto $Nombre
		 * @return void
		 */
		public function  setNombre($nombre = false) {
			$this->nombre = $nombre;
		}

		/**
		 * validacion::setLectura()
		 * 
		 * @param entero $Lectura
		 * @return void
		 */
		public function  setLectura($lectura = false) {
			$this->lectura = $lectura;
		}

		/**
		 * validacion::setEscritura()
		 * 
		 * @param entero $Escritura
		 * @return void
		 */
		public function  setEscritura($escritura = false) {
			$this->escritura = $escritura;
		}

		/**
		 * validacion::setActualizar()
		 * 
		 * @param entero $Actualizar
		 * @return void
		 */
		public function  setActualizar($actualizar = false) {
			$this->actualizar = $actualizar;
		}

		/**
		 * validacion::setEliminar()
		 * 
		 * @param entero $Eliminar
		 * @return void
		 */
		public function  setEliminar($eliminar = false) {
			$this->eliminar = $eliminar;
		}

	}