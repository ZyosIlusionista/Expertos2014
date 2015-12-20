<?php
	
	namespace Formularios\Usuarios;
	use \Mvc\Consola;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Usuarios\usuarioExistencia"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class usuarioExistencia {
		
		/**
		 * validacion::usuario
		 * 
		 * @Columna "usuario"
		 * @Validacion {"requerido":"Es necesario ingresar el Usuario"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $usuario;

		/**
		 * validacion::getUsuario()
		 * 
		 * @return texto
		 */
		public function getUsuario() {
			return trim(mb_strtoupper($this->usuario));
		}

		/**
		 * validacion::setUsuario()
		 * 
		 * @param texto $Usuario
		 * @return void
		 */
		public function  setUsuario($usuario = false) {
			$this->usuario = $usuario;
		}

	}