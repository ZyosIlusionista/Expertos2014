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
	
	namespace Neural\Cache;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigCache;
	
	class Memcached implements InterfaceCache {
		
		private $app = false;
		private $duracion = false;
		private $entorno = false;
		public $objeto = false;
		private $servidor = false;
		
		/**
		 * Memcached::__construct()
		 * 
		 * Genera las variables requeridas para
		 * el proceso
		 * 
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $entorno = false) {
			$this->app = $this->inputApp($app);
			$this->entorno = $this->inputEntorno($entorno);
			$this->objeto = $this->inputMemcached();
		}
		
		/**
		 * Memcached::inpurApp()
		 * 
		 * Genera el proceso de asignacion de app
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				throw new Excepcion('Debe indicar la aplicación para el proceso de Cache Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::inputAppExistencia()
		 * 
		 * Genera el proceso de existencia de la aplicacion
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return $app;
			else:
				throw new Excepcion(sprintf('La aplicación: %s no existe en el archivo de configuración de accesos para el proceso de Memcached', $app), 0);
			endif;
		}
		
		/**
		 * Memcached::inputMemcached()
		 * 
		 * Genera la validacion y el estado del objeto
		 * @return object
		 */
		private function inputMemcached() {
			if(class_exists('Memcached') == true):
				return $this->inputMemcachedExistencia();
			else:
				throw new Excepcion(sprintf('La clase %s no se encuentra disponible en el servidor para el proceso de Memcached', 'Memcached'), 0);
			endif;
		}
		
		/**
		 * Memcached::inputMemcachedExistencia()
		 * 
		 * Genera la validacion de la existencia de 
		 * los parametros de configuracion en el archivo
		 * de configuracion de cache
		 * 
		 * @return objec
		 */
		private function inputMemcachedExistencia() {
			if(ConfigCache::driverExistencia('memcached') == true):
				return $this->inputMemcachedActivo();
			else:
				throw new Excepcion(sprintf('%s no se encuentra registrador en el archivo de configuración de Cache para el proceso de Memcached', 'Memcached'), 0);
			endif;
		}
		
		/**
		 * Memcached::inputMemcachedActivo()
		 * 
		 * Genera la validacion si esta activo en
		 * la configuracion de cache el driver correspondiente
		 * 
		 * @return object
		 */
		private function inputMemcachedActivo() {
			if(ConfigCache::driverEstado('memcached') == true):
				return $this->inputConfiguracion();
			else:
				throw new Excepcion(sprintf('%s no se encuentra activo en el archivo de configuración de Cache en el proceso de Memcached', 'Memcached'), 0);
			endif;
		}
		
		/**
		 * Memcached::inputConfiguracion()
		 * 
		 * Genera el objeto de manejo de cache
		 * 
		 * @return object
		 */
		private function inputConfiguracion() {
			$confg = ConfigCache::leer('driver', 'memcached');
			$this->duracion = $confg['expiracion'];
			$this->servidor = $confg['nombreServidor'];
			$OPT_BINARY_PROTOCOL = (is_bool($confg['usuario']) == false AND is_bool($confg['clave']) == false) ? (boolean) true : (boolean) false;
			
			$memcached = new \Memcached($this->app);
			$memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, $OPT_BINARY_PROTOCOL);
			$memcached->addServer($confg['servidor'], $confg['puerto']);
			
			if(is_bool($confg['usuario']) == false AND is_bool($confg['clave']) == false):
				$memcached->setSaslAuthData($confg['usuario'], $confg['clave']);
			endif;
			
			return $memcached;
		}
		
		/**
		 * Memcached::inputEntorno()
		 * 
		 * Genera la validacion del entorno correspondiente
		 * 
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntorno($entorno = false) {
			if(is_string($entorno) == true):
				return $this->inputEntornoSeleccion($entorno);
			else:
				return $this->inputEntornoValidacion();
			endif;
		}
		
		/**
		 * Memcached::inputEntornoValidacion()
		 * 
		 * Genera la validacion de la cache generada
		 * automaticamente por el sistema
		 * 
		 * @return string
		 */
		private function inputEntornoValidacion() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('Debe indicar el entorno correspondiente para el proceso de Cache Memcache', 0);
			endif;
		}
		
		/**
		 * Memcached::inputEntornoSeleccion()
		 * 
		 * Genera la validacion de la seleccion
		 * del entorno correspondiente para el
		 * entorno
		 * 
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no es valido para el proceso de Cache Memcache'), 0);
			endif;
		}
		
		/**
		 * Memcached::agregar()
		 * 
		 * Agrega el valor asociado a la llave que se indica
		 * en dado caso que la llave exista retornara false
		 * en caso que se agrega a memoria true
		 * 
		 * @param string $identificador: identificador en memoria
		 * @param mixed $valor: valor en memoria
		 * @param integer $expiracion: duracion en segundos en memoria
		 * @return bool
		 */
		public function agregar($identificador = false, $valor = false, $expiracion = false) {
			if(is_string($identificador) == true OR is_numeric($identificador) == true):
				return $this->objeto->add($identificador, $valor, $this->inputDuracion($expiracion));
			else:
				throw new Excepcion('Debe ingresar el identificador correspondiente para agregar en el proceso de Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::asignar()
		 *
		 * Genera la asignacion del identificador
		 * si no existe el identificador lo crea y guarda
		 * si existe el identificador actualiza el valor 
		 * correspondiente
		 * los valores retornados son true cuando se guarda
		 * false si se presenta un error en la escritura
		 * 
		 * @param string $identificador: identificador en memoria
		 * @param mixed $valor: valor en memoria
		 * @param integer $expiracion: duracion en segundos en memoria
		 * @return bool
		 */
		public function asignar($identificador = false, $valor = false, $expiracion = false) {
			if(is_string($identificador) == true OR is_numeric($identificador) == true):
				return $this->objeto->set($identificador, $valor, $this->inputDuracion($expiracion));
			else:
				throw new Excepcion('Debe ingresar el identificador correspondiente para asignar en el proceso de Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::reemplazar()
		 * 
		 * Genera el proceso de reemplazar el valor del identificador
		 * correspondiente, cuando encuentra el identificador retornara
		 * un valor true en dado caso que no lo encuentre no genera el
		 * identificador y retorna un valor false
		 * 
		 * @param string $identificador
		 * @param mixed $valor
		 * @param integer $expiracion
		 * @return bool
		 */
		public function reemplazar($identificador = false, $valor = false, $expiracion = false) {
			if(is_string($identificador) == true OR is_numeric($identificador) == true):
				return $this->objeto->replace($identificador, $valor, $this->inputDuracion($expiracion));
			else:
				throw new Excepcion('Debe ingresar el identificador correspondiente para reemplazar en el proceso de Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::obtener()
		 * 
		 * obtiene el valor correspondiente cuando no
		 * se ha llegado al tiempo de expiracion, en este
		 * caso se retornara un valor false indicando que 
		 * ya no existe la informacion en cache
		 * 
		 * @param string $identificador
		 * @return mixed
		 */
		public function obtener($identificador = false) {
			if(is_string($identificador) == true OR is_numeric($identificador) == true):
				$this->objeto->getByKey($this->servidor, $identificador);
			else:
				throw new Excepcion('Debe ingresar el identificador correspondiente para obtener en el proceso de Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::eliminar()
		 * 
		 * Genera el proceso de eliminar un identificador de memcache
		 * retorna valor true si se elimino, si no encuentra el
		 * identificador retornara error 
		 * 
		 * @param string $identificador: identificador del valor
		 * @param integer $espera: tiempo de espera en segundos
		 * @return bool
		 */
		public function eliminar($identificador = false, $espera = 0) {
			if(is_string($identificador) == true OR is_numeric($identificador) == true):
				$this->objeto->deleteByKey($this->servidor, $identificador, $espera);
			else:
				throw new Excepcion('Debe ingresar el identificador correspondiente para eliminar en el proceso de Memcached', 0);
			endif;
		}
		
		/**
		 * Memcached::inputDuracion()
		 * 
		 * Genera la validacion de la expiracion de los datos
		 * en la memoria cache
		 * 
		 * @param integer $duracion
		 * @return integer
		 */
		private function inputDuracion($duracion = false) {
			return (is_bool($duracion) == true) ? $this->duracion : $duracion;
		}
		
		/**
		 * Memcached::errorCodigo()
		 * 
		 * Retorna el valor si se presenta un error
		 * retornara un valor booleano false en caso
		 * que no hay error
		 * 
		 * @return integer
		 */
		public function errorCodigo() {
			return ($this->objeto->getResultCode() == \Memcached::RES_SUCCESS) ? (boolean) false : $this->objeto->getResultCode();
		}
		
		/**
		 * Memcached::errorDescripcion()
		 * 
		 * Retorna el error que se genero en el 
		 * proceso de de memcache en caso de 
		 * no presentarse un error regresara un
		 * valor booleano false, en caso de 
		 * error retorna array error mensaje
		 * 
		 * @return array
		 */
		public function errorDescripcion() {
			switch($this->objeto->getResultCode()):
				case \Memcached::RES_SUCCESS : return (boolean) false;
					break;
				case \Memcached::RES_FAILURE : return array('codigo' => \Memcached::RES_FAILURE, 'mensaje' => 'La operación falló, error desconocido.');
					break;
				case \Memcached::RES_HOST_LOOKUP_FAILURE : return array('codigo' => \Memcached::RES_HOST_LOOKUP_FAILURE, 'mensaje' => 'Falló la resolución DNS.');
					break;
				case \Memcached::RES_UNKNOWN_READ_FAILURE : return array('codigo' => \Memcached::RES_UNKNOWN_READ_FAILURE, 'mensaje' => 'Fallo al leer datos de la red.');
					break;
				case \Memcached::RES_PROTOCOL_ERROR : return array('codigo' => \Memcached::RES_PROTOCOL_ERROR, 'mensaje' => 'Comando erróneo del protocolo memcached.');
					break;
				case \Memcached::RES_CLIENT_ERROR : return array('codigo' => \Memcached::RES_CLIENT_ERROR, 'mensaje' => 'Error en el lado del cliente.');
					break;
				case \Memcached::RES_SERVER_ERROR : return array('codigo' => \Memcached::RES_SERVER_ERROR, 'mensaje' => 'Error en el lado del servidor.');
					break;
				case \Memcached::RES_WRITE_FAILURE : return array('codigo' => \Memcached::RES_WRITE_FAILURE, 'mensaje' => 'Fallo al escribir datos en la red.');
					break;
				case \Memcached::RES_DATA_EXISTS : return array('codigo' => \Memcached::RES_DATA_EXISTS, 'mensaje' => 'Fallo al comparar e intercambiar: el ítem que se intenta guardar ha sido modificado desde la última vez que se obtuvo.');
					break;
				case \Memcached::RES_NOTSTORED : return array('codigo' => \Memcached::RES_NOTSTORED, 'mensaje' => 'El ítem no fue guardado: pero no a causa de un error. Normalmente significa que no se cumplió la condición para un comando "add [agregar]" o "replace [reemplazar]", o que el ítem está en una cola para su eliminación.');
					break;
				case \Memcached::RES_NOTFOUND : return array('codigo' => \Memcached::RES_NOTFOUND, 'mensaje' => 'No se encontró el ítem con esta clave (mediante la operación [get] o [cas]).');
					break;
				case \Memcached::RES_PARTIAL_READ : return array('codigo' => \Memcached::RES_PARTIAL_READ, 'mensaje' => 'Error de lectura parcial de datos en la red.');
					break;
				case \Memcached::RES_SOME_ERRORS : return array('codigo' => \Memcached::RES_SOME_ERRORS, 'mensaje' => 'Algunos errores sucedieron durante una obtención múltiple.');
					break;
				case \Memcached::RES_NO_SERVERS : return array('codigo' => \Memcached::RES_NO_SERVERS, 'mensaje' => 'La lista de servidores está vacía.');
					break;
				case \Memcached::RES_END : return array('codigo' => \Memcached::RES_END, 'mensaje' => 'Final del conjunto de resultados.');
					break;
				case \Memcached::RES_ERRNO : return array('codigo' => \Memcached::RES_ERRNO, 'mensaje' => 'Error del sistema.');
					break;
				case \Memcached::RES_BUFFERED : return array('codigo' => \Memcached::RES_BUFFERED, 'mensaje' => 'La operación estaba almacenada en búfer.');
					break;
				case \Memcached::RES_TIMEOUT : return array('codigo' => \Memcached::RES_TIMEOUT, 'mensaje' => 'La operación expiró.');
					break;
				case \Memcached::RES_BAD_KEY_PROVIDED : return array('codigo' => \Memcached::RES_BAD_KEY_PROVIDED, 'mensaje' => 'Clave errónea.');
					break;
				case \Memcached::RES_CONNECTION_SOCKET_CREATE_FAILURE : return array('codigo' => \Memcached::RES_CONNECTION_SOCKET_CREATE_FAILURE, 'mensaje' => 'Fallo al crear el socket de red.');
					break;
				case \Memcached::RES_PAYLOAD_FAILURE : return array('codigo' => \Memcached::RES_PAYLOAD_FAILURE, 'mensaje' => 'Error de carga: no se pudo comprimir/descomprimir o serializar/deserializar el valor.');
					break;
				default : return array('codigo' => '??', 'mensaje' => 'Error desconocido');
					break;
			endswitch;
		}
		
		/**
		 * Memcached::cerrarConexion()
		 * 
		 * Cierra la conexion con el servidor de Memcached
		 * retorna true finalizado correcto
		 * retorna false si se presenta un problemas o error
		 * 
		 * @return bool
		 */
		public function cerrarConexion() {
			return $this->objeto->quit();
		}
		
		/**
		 * Memcached::limpiarMemoria()
		 * 
		 * Invalida todos los valores almacenados 
		 * por memecached y no los dejan disponibles
		 * retorna true procedimiento correcto
		 * false error
		 * 
		 * @return bool
		 */
		public function limpiarMemoria() {
			return $this->objeto->flush();
		}
	}