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
	
	namespace Cli\Publico;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Symfony\Component\Console\Command\Command;
	use \Symfony\Component\Console\Input\InputArgument;
	use \Symfony\Component\Console\Input\InputInterface;
	use \Symfony\Component\Console\Input\InputOption;
	use \Symfony\Component\Console\Output\OutputInterface;
	
	class WebPublico extends Command {
		
		private $dirSrc = false;
		private $dirApp = false;
		private $archivos = false;
		
		/**
		 * WebPublico::configure()
		 * 
		 * Genera los parametros basicos de configuracion
		 * @return void
		 */
		protected function configure() {
			$this->setName('neural:web:publico');
			$this->setDescription('Genera la actualizacion de los archivos publicos de las aplicaciones');
			$this->addOption('todo', 't', InputOption::VALUE_NONE, 'Actualiza los archivos publicos de todas las aplicaciones');
			$this->addOption('app', 'a', InputOption::VALUE_REQUIRED, 'Indica la aplicacion la cual se debe publicar los archivos publicos', false);
		}
		
		/**
		 * WebPublico::execute()
		 * 
		 * Genera el proceso de ejecucion del script
		 * indicado
		 * 
		 * @param mixed $input
		 * @param mixed $output
		 * @return raw
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			if($input->getOption('todo') == true):
				$this->ejecutarTodo();
				
				$output->writeln('');
				$output->writeln('<info>Se ha generado el proceso</info>');
				$output->writeln('');
			else:
				$this->appValidacion($input->getOption('app'));
				
				$output->writeln('');
				$output->writeln('<info>Se ha generado el proceso</info>');
				$output->writeln('');
			endif;
		}
		
		/**
		 * WebPublico::appValidacion()
		 * 
		 * Genera la validacion de ingreso
		 * 
		 * @param string $app
		 * @return void
		 */
		private function appValidacion($app = false) {
			if(is_string($app) == true):
				$this->appexistencia($app);
			else:
				throw new \RuntimeException('Debe ingresar una aplicación para generar el proceso');
			endif;
		}
		
		/**
		 * WebPublico::appexistencia()
		 * 
		 * Genera la validacion de la existencia de la 
		 * aplicacion
		 * 
		 * @param string $app
		 * @return void
		 */
		private function appexistencia($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				$this->appProceso($app);
			else:
				throw new \RuntimeException(sprintf('La aplicación: %s, no existe en la configuración de accesos en el proceso de neural:web:publico', $app));
			endif;
		}
		
		/**
		 * WebPublico::appProceso()
		 * 
		 * Genera el proceso de copia de archivos
		 * @param string $app
		 * @return void
		 */
		private function appProceso($app = false) {
			$directorio = ConfigAcceso::leer($app, 'fuente', 'directorio');
			$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $app));
			$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $app), \hash('adler32', 'Desarrollo'));
			
			$this->dirSrc = implode(DIRECTORY_SEPARATOR, array(ROOT_SRC, \hash('adler32', $app), \hash('adler32', 'Desarrollo')));
			$this->dirApp = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, 'Desarrollo', 'Publico'));
			
			$this->directorioLectura($this->dirApp);
			
			$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $app), \hash('adler32', 'Produccion'));
			$this->dirSrc = implode(DIRECTORY_SEPARATOR, array(ROOT_SRC, \hash('adler32', $app), \hash('adler32', 'Produccion')));
			$this->dirApp = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio, 'Produccion', 'Publico'));
			
			$this->directorioLectura($this->dirApp);
			$this->directorioCopia();
			
			$this->dirSrc = false;
			$this->dirApp = false;
			$this->archivos = false;
		}
		
		/**
		 * WebPublico::ejecutarTodo()
		 * 
		 * Ejecuta el proceso de lectura del archivo de configuracion
		 * de accesos y genera el proceso correspondiente
		 * 
		 * @return void
		 */
		private function ejecutarTodo() {
			$directorios = $this->todoArrayDirectorios();
			
			foreach ($directorios AS $directorio):
				$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $directorio['app']));
				$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $directorio['app']), \hash('adler32', 'Desarrollo'));
				
				$this->dirSrc = implode(DIRECTORY_SEPARATOR, array(ROOT_SRC, \hash('adler32', $directorio['app']), \hash('adler32', 'Desarrollo')));
				$this->dirApp = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio['directorio'], 'Desarrollo', 'Publico'));
				
				$this->directorioLectura($this->dirApp);
				
				$this->directorioAppSrc(ROOT_SRC, \hash('adler32', $directorio['app']), \hash('adler32', 'Produccion'));
				$this->dirSrc = implode(DIRECTORY_SEPARATOR, array(ROOT_SRC, \hash('adler32', $directorio['app']), \hash('adler32', 'Produccion')));
				$this->dirApp = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $directorio['directorio'], 'Produccion', 'Publico'));
				
				$this->directorioLectura($this->dirApp);
				$this->directorioCopia();
				
				$this->dirSrc = false;
				$this->dirApp = false;
				$this->archivos = false;
			endforeach;
		}
		
		/**
		 * WebPublico::directorioCopia()
		 * 
		 * Genera la copia de los archivos correspondientes
		 * @return void
		 */
		private function directorioCopia() {
			if(is_array($this->archivos) == true):
				foreach ($this->archivos AS $archivo):
					copy($archivo['origen'], $archivo['copia']);
					unset($archivo);
				endforeach;
			endif;
		}
		
		/**
		 * WebPublico::directorioAppSrc()
		 * 
		 * Genera el proceso de creacion de directorio
		 * en el Src publico
		 * 
		 * @return void
		 */
		private function directorioAppSrc() {
			$directorio = implode(DIRECTORY_SEPARATOR, func_get_args());
			if(is_dir($directorio) == false):
				mkdir($directorio);
			endif;
			unset($directorio);
		}
		
		/**
		 * WebPublico::directorioLectura()
		 * 
		 * Genera la lectura del directorio correspondiente
		 * @param string $directorio
		 * @return void
		 */
		private function directorioLectura($directorio = false) {
			$abrir = opendir($directorio);
			while ($archivo = readdir($abrir)):
				if($archivo != '.' AND $archivo != '..'):
					$this->directorioSeleccion($directorio, $archivo);
				endif;
			endwhile;
			closedir($abrir);
			unset($abrir, $directorio, $archivo);
		}
		
		/**
		 * WebPublico::directorioSeleccion()
		 * 
		 * Genera la validacion si es archivo o directorio
		 * y se procesa la peticion
		 * 
		 * @return void
		 */
		private function directorioSeleccion() {
			$parametro = implode(DIRECTORY_SEPARATOR, func_get_args());
			if(is_dir($parametro) == true):
				$param = str_replace($this->dirApp, $this->dirSrc, $parametro);
				$this->directorioAppSrc($param);
				$this->directorioLectura($parametro);
			else:
				$this->archivos[] = array('origen' => $parametro, 'copia' => str_replace($this->dirApp, $this->dirSrc, $parametro));
			endif;
			unset($parametro);
		}
		
		/**
		 * WebPublico::todoArrayDirectorios()
		 * 
		 * Obtiene todos los directorios fuente de las
		 * aplicaciones
		 * 
		 * @return array
		 */
		private function todoArrayDirectorios() {
			$matriz = ConfigAcceso::leer();
			foreach ($matriz as $app => $array):
				$lista[] = array('app' => $app, 'directorio' => $array['fuente']['directorio']);
			endforeach;
			unset($matriz);
			return $lista;
		}
	}