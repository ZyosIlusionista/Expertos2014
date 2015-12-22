<?php
	
	namespace Formularios\Permisos\Nuevo;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Permisos\Nuevo\permisoExistencia"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class PermisoExistencia {
		
		/**
		 * validacion::nombre
		 * 
		 * @Columna "nombre"
		 * @Validacion {"requerido":"Es necesario ingresar el Nombre del Permiso"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $nombre;

		/**
		 * validacion::getNombre()
		 * 
		 * @return texto
		 */
		public function getNombre() {
			return trim(mb_strtoupper($this->nombre));
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

	}