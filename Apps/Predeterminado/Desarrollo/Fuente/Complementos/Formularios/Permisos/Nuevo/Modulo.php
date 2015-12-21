<?php
	
	namespace Formularios\Permisos\Nuevo;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Permisos\Nuevo\Modulo"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class Modulo {
		
		/**
		 * validacion::nombre
		 * 
		 * @Columna "nombre"
		 * @Validacion {"requerido":"Es necesario ingresar el Nombre del Modulo"}
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
			return trim($this->nombre);
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