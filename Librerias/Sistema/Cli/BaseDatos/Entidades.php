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
	
	namespace Cli\BaseDatos;
	use \Doctrine\Common\Cache\ArrayCache;
	use \Doctrine\ORM\Configuration;
	use \Doctrine\ORM\EntityManager;
	use \Doctrine\ORM\Tools\EntityGenerator;
	use \Doctrine\ORM\Mapping\Driver\DatabaseDriver;
	use \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigBaseDatos;
	
	class Entidades {
		
		private $conexion = false;
		private $confg = false;
		private $directorio = false;
		private $entorno = false;
		
		private $dirEntidades = false;
		private $dirProxy = false;
		
		/**
		 * Entidades::__construct()
		 * 
		 * Genera la variable correspondiente
		 * de configuracion
		 * 
		 * @param string $app
		 * @param string $entorno
		 * @return void
		 */
		function __construct($app = false, $conexion = false, $entorno = false) {
			$this->directorio = ConfigAcceso::leer($app, 'fuente', 'directorio');
			$this->confg = ConfigBaseDatos::leer($conexion, $entorno);
			$this->conexion = $conexion;
			$this->dirEntidades = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $entorno, 'Fuente', 'Complementos', 'ORM', 'Entidades'));
			$this->dirProxy = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, $entorno, 'Fuente', 'Complementos', 'ORM', 'Proxy'));
		}
		
		/**
		 * Entidades::ejecutar()
		 * 
		 * Ejecuta el proceso de creacion de las
		 * entidades correspondientes
		 * 
		 * @return void
		 */
		public function ejecutar() {
			$this->configuracion();
		}
		
		/**
		 * Entidades::configuracion()
		 * 
		 * Genera el proceso de configuracion y preparacion
		 * de los directorios de las entidades
		 * 
		 * @return object
		 */
		private function configuracion() {
			$confg = new Configuration();
			$confg->setMetadataDriverImpl($confg->newDefaultAnnotationDriver($this->dirEntidades));
			$confg->setMetadataCacheImpl(new ArrayCache);
			$confg->setProxyDir($this->dirProxy);
			$confg->setProxyNamespace('Proxy');
			$confg->setAutoGenerateProxyClasses(true);
			$confg->setEntityNamespaces(array('Entidades\\'.$this->conexion.'\\' => $this->dirEntidades));
			
			$this->entityManager($confg);
		}
		
		/**
		 * Entidades::entityManager()
		 * 
		 * Prepara el proceso de la creacion de las entidades
		 * @param object $configuracion
		 * @return object
		 */
		private function entityManager($configuracion = false) {
			$entidad = EntityManager::create($this->confg, $configuracion);
			$entidad->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
			$entidad->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
			
			// fetch metadata
			$driver = new DatabaseDriver($entidad->getConnection()->getSchemaManager());
			$driver->setNamespace('Entidades\\'.$this->conexion.'\\'); //Agrega el namespace Entidad\Nombre de la tabla
			$entidad->getConfiguration()->setMetadataDriverImpl($driver);
			
			$cmf = new DisconnectedClassMetadataFactory();
			$cmf->setEntityManager($entidad);	// we must set the EntityManager
			
			$classes = $driver->getAllClassNames();
			
			$metadata = array();
			foreach ($classes as $class) :
				//any unsupported table/schema could be handled here to exclude some classes
				if (true):
					$metadata[] = $cmf->getMetadataFor($class);
				endif;
			endforeach;
			
			$this->generador($metadata);
		}
		
		/**
		 * Entidades::generador()
		 * 
		 * Genera el proceso correspondiente 
		 * @param array $metadata
		 * @return void
		 */
		private function generador($metadata = false) {
			$generator = new EntityGenerator();
			$generator->setAnnotationPrefix('');   // edit: quick fix for No Metadata Classes to process
			$generator->setUpdateEntityIfExists(true);	// only update if class already exists
			$generator->setRegenerateEntityIfExists(true);	// this will overwrite the existing classes
			$generator->setGenerateStubMethods(true);
			$generator->setGenerateAnnotations(true);
			$generator->generate($metadata, dirname($this->dirEntidades));
		}
	}