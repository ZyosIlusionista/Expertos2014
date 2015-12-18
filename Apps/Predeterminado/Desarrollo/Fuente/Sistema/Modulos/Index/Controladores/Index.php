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
	
	/**
	 * Namespace Controlador Modulos
	 * 
	 * Se genera el namespace para el modulo
	 * el cual se diferencia la carga de la misma
	 * @example namespace Controlador\Modulo\{nombre del modulo}
	 */
	namespace Controlador\Modulo\Index;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Neural\JQuery\ValidarForm;
	use \Neural\Sesion\SesionPHP;
	
	/**
	 * Controlador Index
	 * 
	 * El controlador es requerido extenderlo hacia la
	 * clase u objeto ubicado en el namespace \Mvc\Controlador.
	 * 
	 * El controlador debe ser generado en notacion UpperCamelCasse
	 * no debe contener simbolos como [\][_][-]
	 * 
	 * El nombre del archivo del controlador debe ser igual a la clase
	 * controlador
	 */
	class Index extends Controlador {
		
		private $sesionPHP = false;
		private $sesionNombre = false;
		
		/**
		 * Index::__construct()
		 * 
		 * Genera el proceso de las variables necesarias 
		 * para el procedimiento de login
		 * 
		 * @return void
		 */
		function __construct() {
			parent::__construct();
			$this->sesionNombre = hash('crc32b', APP);
			$this->sesionPHP = new SesionPHP(APP);
			
			if($this->sesionPHP->obtenerExistencia(APP) == true):
				$this->cabecera->redireccion($this->ruta->modulo('Central'));
				exit();
			endif;
		}
		
		/**
		 * Index::Index()
		 * 
		 * Genera la plantilla correspondiente para el
		 * login de la aplicacion
		 * 
		 * @return string
		 */
		public function Index() {
			$this->plantilla->parametro('script', $this->jQueryFormulario());
			echo $this->plantilla->mostrarPlantilla('Login.html');
		}
		
		/**
		 * Index::jQueryFormulario()
		 * 
		 * Genera la validacion del formulario en el login
		 * correspondiente en el metodo Index
		 * 
		 * @return string
		 */
		private function jQueryFormulario() {
			$validar = new ValidarForm(APP, false, true);
			$validar->usuario()->_requerido('Debe ingresar el usuario correspondiente');
			$validar->usuario()->_minCaracteres(5, 'Debe ingresar un usuario de minimo 5 caracteres');
			$validar->password()->_requerido('Debe ingresar una contraseña');
			$validar->password()->_minCaracteres(3, 'Debe ingresar una contraseña de minimo 8 caracteres');
			$validar->peticionAjax('login.init(formulario);');
			return $validar->mostrarValidacion('form');
		}
		
		/**
		 * Index::js()
		 * 
		 * Genera la funcion para la peticion ajax
		 * @param string $archivo
		 * @return string
		 */
		public function js($archivo = false) {
			$this->cabecera->header('js');
			echo $this->plantilla->mostrarPlantilla('Modulos', 'Index', 'js.js');
		}
		
		/**
		 * Index::autenticacion()
		 * 
		 * @return void
		 */
		public function autenticacion() {
			if($this->peticion->ajax() == true):
				$this->autenticacionValidacion();
			else:
				throw new Excepcion('No es posible procesar su petición', 1);
			endif;
		}
		
		/**
		 * Index::autenticacionValidacion()
		 * 
		 * Genera el proceso de validacion de formualario
		 * @return string
		 */
		private function autenticacionValidacion() {
			if($this->validarFormulario->validar('\Formularios\Login\Login') == true):
				$this->autenticacionProcesar();
			else:
				throw new Excepcion(implode("\n", $this->validarFormulario->mensajeError()), 2, APP, 'LoginMensajes');
			endif;
		}
		
		/**
		 * Index::autenticacionProcesar()
		 * 
		 * Genera la validacion de la existencia del usuario
		 * y obtiene el objeto correspondiente de la consulta
		 * 
		 * @return string
		 */
		private function autenticacionProcesar() {
			$consulta = $this->modelo->consultaUsuario($this->validarFormulario->datosFormulario());
			if(count($consulta) == 1):
				$this->usuarioEstado($consulta);
			else:
				throw new Excepcion('El usuario y/o contraseña incorrectos', 3, APP, 'LoginMensajes');
			endif;
		}
		
		/**
		 * Index::usuarioEstado()
		 * 
		 * Genera la validación del estado del usuario
		 * y se encuentra el objeto que implementa la
		 * consulta de datos
		 * 
		 * @param object $objeto
		 * @return string
		 */
		private function usuarioEstado($objeto = false) {
			if($objeto->getEstado()->getId() == 1):
				$this->usuarioPermisoEstado($objeto);
			else:
				throw new Excepcion('Se ha presentado un error con el proceso de inicio de sesión', 4, APP, 'LoginMensajes');
			endif;
		}
		
		/**
		 * Index::usuarioPermisoEstado()
		 * 
		 * Validacion del estado del permiso correspondiente
		 * @param object $objeto
		 * @return string
		 */
		private function usuarioPermisoEstado($objeto = false) {
			if($objeto->getPermiso()->getEstado()->getId() == 1):
				$this->usuarioProcesar($objeto);
			else:
				throw new Excepcion('Se ha presentado un error validar con el administrador del sistema', 5, APP, 'LoginMensajes');
			endif;
		}
		
		/**
		 * Index::usuarioProcesar()
		 * 
		 * Genera el proceso de inicio de sesion en el
		 * servidor correspondiente
		 * 
		 * @param object $objeto
		 * @return string
		 */
		private function usuarioProcesar($objeto = false) {
			$fecha = strtotime(ate("Y-m-d H:i:s"));
			$informacion = array(
				'info' => array(
					'nombre' => $objeto->getNombre(),
					'apellido' => $objeto->getApellido(),
					'correo' => $objeto->getCorreo(),
					'as400' => $objeto->getUsuarioRr(),
					'empresa' => $objeto->getEmpresa()->getNombre(),
					'cargo' => $objeto->getCargo()->getNombre()
				),
				'validacion' => array(
					'llave' => hash('snefru', implode('_', array($objeto->getNombre(), $objeto->getApellido(), $fecha))),
					'fecha' => date("Y-m-d"),
					'secuencia' => $fecha
				),
				'permisos' => $this->usuarioPermisosOrg($objeto->getPermiso()->getId())
			);
			$this->sesionPHP->asignar($this->sesionNombre, $informacion);
			
			$this->cabecera->header('json');
			echo json_encode(array('status' => (boolean) true, 'data' => $this->ruta->modulo('Central')));
		}
		
		/**
		 * Index::usuarioPermisosOrg()
		 * 
		 * Genera la organizacion correspondiente
		 * de los permisos relacionados
		 * 
		 * @param integer $permiso
		 * @return array
		 */
		private function usuarioPermisosOrg($permiso = false) {
			$object = $this->modelo->consultarPermisos($permiso);
			$lista = array();
			
			foreach ($object AS $info):
				if($info->getModulo()->getEstado()->getId() == 1):
					$lista[$info->getModulo()->getNombre()] = array(
						'lectura' => $info->getAcceso()->getLectura(),
						'escritura' => $info->getAcceso()->getEscritura(),
						'actualizar' => $info->getAcceso()->getActualizar(),
						'eliminar' => $info->getAcceso()->getEliminar()
					);
				endif;
			endforeach;
			return $lista;
		}
	}