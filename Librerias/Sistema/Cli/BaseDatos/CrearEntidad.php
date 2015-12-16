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
	use \Sistema\Utilidades\ConfigAcceso;
	use \Sistema\Utilidades\ConfigBaseDatos;
	use \Symfony\Component\Console\Command\Command;
	use \Symfony\Component\Console\Input\InputArgument;
	use \Symfony\Component\Console\Input\InputInterface;
	use \Symfony\Component\Console\Input\InputOption;
	use \Symfony\Component\Console\Output\OutputInterface;
	
	class CrearEntidad extends Command {
		
		private $aplicacion = false;
		private $conexion = false;
		private $entorno = 'Desarrollo';
		private $dirEntidades = false;
		private $dirProxy = false;
		
		/**
		 * CrearEntidad::configure()
		 * 
		 * Genera los parametros basicos de configuracion
		 * @return void
		 */
		protected function configure() {
			$this->setName('neural:bd:entidades');
			$this->setDescription('Genera las entidades de la base de datos en la aplicación seleccionada');
			$this->addArgument('app', InputArgument::REQUIRED, 'Aplicación donde se generara las entidades');
			$this->addArgument('conexion', InputArgument::REQUIRED, 'Nombre de la conexión a la base de datos');
		}
		
		/**
		 * CrearEntidad::execute()
		 * 
		 * Genera el proceso de ejecucion del script
		 * indicado
		 * 
		 * @param mixed $input
		 * @param mixed $output
		 * @return void
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			$this->aplicacion = $input->getArgument('app');
			$this->conexion = $input->getArgument('conexion');
			$this->appExistencia();
		}
		
		/**
		 * CrearEntidad::appExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * de la aplicacion para la ruta correspondiente
		 * de las entidades
		 * 
		 * @return void
		 */
		private function appExistencia() {
			if(ConfigAcceso::appExistencia($this->aplicacion) == true):
				$directorio = ConfigAcceso::leer($this->aplicacion, 'fuente', 'directorio');
				$this->dirEntidades = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $this->entorno, 'Fuente', 'Complementos', 'ORM', 'Entidades'));
				$this->dirProxy = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, $this->entorno, 'Fuente', 'Complementos', 'ORM', 'Proxy'));
				$this->entidadesExistencia();
			else:
				throw new \RuntimeException(sprintf('La aplicación: %s, no existe en la configuarción de accesos', $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::entidadesExistencia()
		 * 
		 * Genera la validacion de la existencia del directorio
		 * de entidades
		 * 
		 * @return void
		 */
		private function entidadesExistencia() {
			if(is_dir($this->dirEntidades) == true):
				$this->entidadesEscritura();
			else:
				throw new \RuntimeException(sprintf('El directorio de Entidades no existe en el entorno: %s, de la aplicación: %s', $this->entorno, $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::entidadesEscritura()
		 * 
		 * Genera la validacion si es posible escribir en
		 * el directorio
		 * 
		 * @return void
		 */
		private function entidadesEscritura() {
			if(is_writable($this->dirEntidades) == true):
				$this->proxyExistencia();
			else:
				throw new \RuntimeException(sprintf('El directorio de Entidades no tiene permisos de escritura en el entorno: %s, de la aplicación: %s', $this->entorno, $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::proxyExistencia()
		 * 
		 * Genera la validacion de la existencia del directorio
		 * de proxy
		 * 
		 * @return void
		 */
		private function proxyExistencia() {
			if(is_dir($this->dirProxy) == true):
				$this->proxyEscritura();
			else:
				throw new \RuntimeException(sprintf('El directorio de Proxy no existe en el entorno: %s, de la aplicación: %s', $this->entorno, $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::proxyEscritura()
		 * 
		 * Genera la validacion si es posible escribir en
		 * el directorio
		 * 
		 * @return void
		 */
		private function proxyEscritura() {
			if(is_writable($this->dirProxy) == true):
				$this->conexionExistencia();
			else:
				throw new \RuntimeException(sprintf('El directorio de Proxy no tiene permisos de escritura en el entorno: %s, de la aplicación: %s', $this->entorno, $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::conexionExistencia()
		 * 
		 * Genera la validacion de la existencia de la conexion
		 * @return void
		 */
		private function conexionExistencia() {
			if(ConfigBaseDatos::conexionExistencia($this->conexion) == true):
				$this->procesar();
			else:
				throw new \RuntimeException(sprintf('La conexion: %s no existe en el entorno: %s de la aplicación: %s', $this->conexion, $this->entorno, $this->aplicacion));
			endif;
		}
		
		/**
		 * CrearEntidad::procesar()
		 * 
		 * Genera la creacion de los archivos de 
		 * @return void
		 */
		private function procesar() {
			$entidad = new Entidades($this->aplicacion, $this->conexion, $this->entorno);
			$entidad->ejecutar();
			echo "\n".sprintf('Se ha finalizado el proceso correspondiente en la aplicación: %s', $this->aplicacion)."\n\n";
		}
	}