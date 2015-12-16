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
	use \Doctrine;
	
	use \Doctrine\Common\Cache\ArrayCache;
	use \Doctrine\Common\Cache\ApcCache;
	use \Doctrine\Common\Cache\MemcacheCache;
	use \Doctrine\Common\Cache\XcacheCache;
	use \Doctrine\Common\Cache\RedisCache;
	
	use \Doctrine\DBAL\DriverManager;
	use \Doctrine\ORM\Configuration;
	use \Doctrine\ORM\Tools\Setup;
	use \Doctrine\ORM\EntityManager;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigBaseDatos;
	use \Sistema\Utilidades\ConfigCache;
	
	class Conexion {
		
		private $aplicacion = false;
		private $confg = false;
		private $entorno = false;
		private $conexionDBAL = false;
		private $entityManager = false;
		
		/**
		 * Conexion::__construct()
		 * 
		 * Genera las variables necesarias para 
		 * el proceso correspondiente
		 * 
		 * @param string $conexion
		 * @param string $entorno
		 * @return void
		 */
		function __construct($conexion = false, $app = false, $entorno = false) {
			$this->entorno = $this->inputEntorno($entorno);
			$this->confg = $this->inputConexion($conexion);
			$this->aplicacion = $this->inputApp($app);
		}
		
		/**
		 * Conexion::inputApp()
		 * 
		 * Genera la seleccion del directorio
		 * de la aplicacion correspondiente
		 * @param string $app
		 * @return mixed
		 */
		private function inputApp($app = false) {
			if(is_string($app) == true):
				return $this->inputAppExistencia($app);
			else:
				return false;
			endif;
		}
		
		/**
		 * Conexion::inputAppExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * correspondiente
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputAppExistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return ConfigAcceso::leer($app, 'fuente', 'directorio');
			else:
				throw new Excepcion(sprintf('La aplicación: %s no existe en el archivo de configuración para el proceso de Conexion de la BD', $app), 0);
			endif;
		}
		
		/**
		 * Conexion::inputConexion()
		 * 
		 * Genera el proceso correspondiente
		 * de la seleccion de la conexion
		 * 
		 * @param string $conexion
		 * @return array
		 */
		private function inputConexion($conexion = false) {
			if(is_string($conexion) == true):
				return $this->inputConexionExistencia($conexion);
			elseif(is_array($conexion) == true):
				return $conexion;
			else:
				throw new Excepcion('Debe ingresar la conexión correspondiente para el proceso de Conexion de la BD', 0);
			endif;
		}
		
		/**
		 * Conexion::inputConexionExistencia()
		 * 
		 * Genera la vallidacion de la existencia
		 * de la conexion correspondiente
		 * 
		 * @param string $conexion
		 * @return array
		 */
		private function inputConexionExistencia($conexion = false) {
			if(ConfigBaseDatos::conexionExistencia($conexion) == true):
				return ConfigBaseDatos::leer($conexion, $this->entorno);
			else:
				throw new Excepcion(sprintf('La conexión: %s no existe en el archivo de configuración de Base de Datos', $conexion), 0);
			endif;
		}
		
		/**
		 * Conexion::inputEntorno()
		 * 
		 * Genera la validacion de la seleccion
		 * del entorno correspondiente
		 * 
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
		 * Conexion::inputEntornoValidar()
		 * 
		 * Genera el proceso de validacion del
		 * entorno asignado automatico por el sistema
		 * 
		 * @return string
		 */
		private function inputEntornoValidar() {
			if(defined('ENV_ENTORNO') == true):
				return $this->inputEntornoSeleccion(ENV_ENTORNO);
			else:
				throw new Excepcion('No se ha seleccionado en un entorno para el proceso de la Conexion a la BD', 0);
			endif;
		}
		
		/**
		 * Conexion::inputEntornoSeleccion()
		 * 
		 * Genera la validacion de la seleccion de
		 * el entorno correspondiente
		 * 
		 * @param string $entorno
		 * @return string
		 */
		private function inputEntornoSeleccion($entorno = false) {
			if(array_key_exists($entorno, array_flip(array('Desarrollo', 'Produccion'))) == true):
				return $entorno;
			else:
				throw new Excepcion(sprintf('El entorno: %s no es correcto para el proceso de Conexion a la BD', $entorno), 0);
			endif;
		}
		
		/**
		 * Conexion::doctrineDBAL()
		 * 
		 * Genera el objeto de conexion correspondiente
		 * a la conexion configurada
		 * 
		 * @return object
		 */
		public function doctrineDBAL() {
			if(is_object($this->conexionDBAL) == true):
				return $this->conexionDBAL;
			else:
				return $this->conexionDBAL = DriverManager::getConnection($this->confg);
			endif;
		}
		
		/**
		 * Conexion::doctrineORM()
		 * 
		 * Genera el objeto correspondiente para el 
		 * manejo del ORM de doctrine
		 * 
		 * @return object
		 */
		public function doctrineORM() {
			if(is_string($this->aplicacion) == true):
				return $this->doctrineORMSeleccion();
			else:
				throw new Excepcion('Es necesario ingresar la aplicación de la cual se tomara base las entidades del proceso de Conexion de la BD', 0);
			endif;
		}
		
		/**
		 * Conexion::doctrineORMSeleccion()
		 * 
		 * Genera la seleccion si ya se ha generado una 
		 * conexion para el manejo del ORM
		 * 
		 * @return object
		 */
		private function doctrineORMSeleccion() {
			if(is_object($this->entityManager) == true):
				return $this->entityManager;
			else:
				return $this->entityManager = $this->doctrineORMProceso();
			endif;
		}
		
		/**
		 * Conexion::doctrineORMProceso()
		 * 
		 * Genera el proceso de la entity manager
		 * @return object
		 */
		private function doctrineORMProceso() {
			$entidades = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->aplicacion, $this->entorno, 'Fuente', 'Complementos', 'ORM', 'Entidades'));
			$isDevMode = ($this->entorno == 'Produccion') ? (boolean) false : (boolean) true;
			//$confg = Setup::createAnnotationMetadataConfiguration($entidades, $isDevMode);
			$confg = new Configuration;
			
			$driverImpl = $confg->newDefaultAnnotationDriver($entidades);
			$confg->setMetadataDriverImpl($driverImpl);
			
			if($isDevMode == false):
				if(ConfigCache::leer('reservado', 'doctrine', 'habilitado') == true):
					$driver = ConfigCache::leer('reservado', 'doctrine', 'driver');
					$cache = $this->{$driver}($confg);
					$confg->setMetadataCacheImpl($cache);
					$confg->setQueryCacheImpl($cache);
					$confg->setResultCacheImpl($cache);
				endif;
				
				$confg->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
			else:
				$cache = new \Doctrine\Common\Cache\ArrayCache;
				$confg->setMetadataCacheImpl($cache);
				$confg->setQueryCacheImpl($cache);
				$confg->setResultCacheImpl($cache);
				$confg->setAutoGenerateProxyClasses(true);
			endif;
			
			$confg->setProxyDir(implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->aplicacion, $this->entorno, 'Fuente', 'Complementos', 'ORM', 'Proxy')));
			$confg->setProxyNamespace('Proxy');
			
			return EntityManager::create($this->confg, $confg);
		}
		
		/**
		 * Conexion::memcached()
		 * 
		 * Genera el proceso de conexion a servidor memcached
		 * 
		 * @param object $confg
		 * @return void
		 */
		private function memcached() {
			$parametros = ConfigCache::leer('driver', 'memcached');
			$OPT_BINARY_PROTOCOL = (is_bool($parametros['usuario']) == false AND is_bool($parametros['clave']) == false) ? (boolean) true : (boolean) false;
			
			$memcached = new \Memcached();
			$memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, $OPT_BINARY_PROTOCOL);
			$memcached->addServer($parametros['servidor'], $parametros['puerto']);
			
			if(is_bool($parametros['usuario']) == false AND is_bool($parametros['clave']) == false):
				$memcached->setSaslAuthData($parametros['usuario'], $parametros['clave']);
			endif;
			
			$cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
			$cacheDriver->setMemcached($memcached);
			
			return $cacheDriver;
		}
	}