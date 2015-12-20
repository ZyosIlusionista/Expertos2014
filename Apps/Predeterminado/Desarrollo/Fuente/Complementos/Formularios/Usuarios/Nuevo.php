<?php
	
	namespace Formularios\Usuarios;
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "Formularios\Usuarios\Nuevo"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class Nuevo {
		
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
		 * validacion::password
		 * 
		 * @Columna "password"
		 * @Validacion {"texto":"Es necesario ingresar el Usuario"}
		 * @Existencia false
		 * @Tipo "texto"
		 */
		private $password;

		/**
		 * validacion::nombre
		 * 
		 * @Columna "nombre"
		 * @Validacion {"requerido":"Es necesario ingresar el nombre del Usuario"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $nombre;

		/**
		 * validacion::apellido
		 * 
		 * @Columna "apellido"
		 * @Validacion {"requerido":"Es necesario ingresar los apellidos del Usuario"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $apellido;

		/**
		 * validacion::cedula
		 * 
		 * @Columna "cedula"
		 * @Validacion {"requerido":"Es necesario ingresar la cedula Usuario","numero":"Debe ingresar un valor numerico"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $cedula;

		/**
		 * validacion::as400
		 * 
		 * @Columna "usuarioRr"
		 * @Validacion {"requerido":"Es necesario ingresar el Usuario AS400"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $as400;

		/**
		 * validacion::correo
		 * 
		 * @Columna "correo"
		 * @Validacion {"requerido":"Es necesario ingresar el correo del Usuario","correo":"El formato del correo no es valido"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $correo;

		/**
		 * validacion::empresa
		 * 
		 * @Columna "empresa"
		 * @Validacion {"requerido":"Es necesario ingresar la empresa del Usuario","numero":"Debe seleccionar una empresa de la lista"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $empresa;

		/**
		 * validacion::cargo
		 * 
		 * @Columna "cargo"
		 * @Validacion {"requerido":"Es necesario ingresar el cargo del Usuario","numero":"Debe seleccionar un cargo de la lista"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $cargo;

		/**
		 * validacion::permiso
		 * 
		 * @Columna "permiso"
		 * @Validacion {"requerido":"Es necesario ingresar el permiso del Usuario","numero":"Debe seleccionar un permiso de la lista"}
		 * @Existencia true
		 * @Tipo "texto"
		 */
		private $permiso;

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
		 * validacion::getNombre()
		 * 
		 * @return texto
		 */
		public function getNombre() {
			return trim(mb_strtoupper($this->nombre));
		}

		/**
		 * validacion::getApellido()
		 * 
		 * @return texto
		 */
		public function getApellido() {
			return trim(mb_strtoupper($this->apellido));
		}

		/**
		 * validacion::getCedula()
		 * 
		 * @return texto
		 */
		public function getCedula() {
			return trim(mb_strtoupper($this->cedula));
		}

		/**
		 * validacion::getAs400()
		 * 
		 * @return texto
		 */
		public function getAs400() {
			return trim(mb_strtoupper($this->as400));
		}

		/**
		 * validacion::getCorreo()
		 * 
		 * @return texto
		 */
		public function getCorreo() {
			return trim(mb_strtoupper($this->correo));
		}

		/**
		 * validacion::getEmpresa()
		 * 
		 * @return texto
		 */
		public function getEmpresa() {
			return $this->empresa;
		}

		/**
		 * validacion::getCargo()
		 * 
		 * @return texto
		 */
		public function getCargo() {
			return $this->cargo;
		}

		/**
		 * validacion::getPermiso()
		 * 
		 * @return texto
		 */
		public function getPermiso() {
			return $this->permiso;
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
		 * validacion::setApellido()
		 * 
		 * @param texto $Apellido
		 * @return void
		 */
		public function  setApellido($apellido = false) {
			$this->apellido = $apellido;
		}

		/**
		 * validacion::setCedula()
		 * 
		 * @param texto $Cedula
		 * @return void
		 */
		public function  setCedula($cedula = false) {
			$this->password = $cedula;
			$this->cedula = $cedula;
		}

		/**
		 * validacion::setAs400()
		 * 
		 * @param texto $As400
		 * @return void
		 */
		public function  setAs400($as400 = false) {
			$this->as400 = $as400;
		}

		/**
		 * validacion::setCorreo()
		 * 
		 * @param texto $Correo
		 * @return void
		 */
		public function  setCorreo($correo = false) {
			$this->correo = $correo;
		}

		/**
		 * validacion::setEmpresa()
		 * 
		 * @param texto $Empresa
		 * @return void
		 */
		public function  setEmpresa($empresa = false) {
			$this->empresa = $empresa;
		}

		/**
		 * validacion::setCargo()
		 * 
		 * @param texto $Cargo
		 * @return void
		 */
		public function  setCargo($cargo = false) {
			$this->cargo = $cargo;
		}

		/**
		 * validacion::setPermiso()
		 * 
		 * @param texto $Permiso
		 * @return void
		 */
		public function  setPermiso($permiso = false) {
			$this->permiso = $permiso;
		}

	}