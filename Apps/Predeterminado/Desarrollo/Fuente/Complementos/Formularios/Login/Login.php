<?php
	
	namespace Formularios\Login;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Login\Login"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class Login {
		
		/**
		 * validacion::usuario
		 * 
		 * @Columna "usuario"
		 * @Validacion {"requerido":"Debe ingresar el Usuario"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $usuario;

		/**
		 * validacion::password
		 * 
		 * @Columna "password"
		 * @Validacion {"requerido":"Debe ingresar el password"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $password;

		/**
		 * validacion::getUsuario()
		 * 
		 * @return texto
		 */
		public function getUsuario() {
			return trim(mb_strtoupper($this->usuario));
		}

		/**
		 * validacion::getPassword()
		 * 
		 * @return texto
		 */
		public function getPassword() {
			return sha1(trim($this->password));
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

		/**
		 * validacion::setPassword()
		 * 
		 * @param texto $Password
		 * @return void
		 */
		public function  setPassword($password = false) {
			$this->password = $password;
		}

	}