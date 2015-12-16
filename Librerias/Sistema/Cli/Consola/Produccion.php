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
	
	namespace Cli\Consola;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Symfony\Component\Console\Command\Command;
	use \Symfony\Component\Console\Input\InputArgument;
	use \Symfony\Component\Console\Input\InputInterface;
	use \Symfony\Component\Console\Input\InputOption;
	use \Symfony\Component\Console\Output\OutputInterface;
	
	class Produccion extends Command {
		
		private $aplicacion = false;
		private $directorio = false;
		private $entorno = 'Produccion';
		private $objeto = false;
		private $appRuta = false;
		private $consolaRuta = false;
		private $parametros = false;
		
		/**
		 * Produccion::configure()
		 * 
		 * Genera los parametros basicos de configuracion
		 * @return void
		 */
		protected function configure() {
			$this->setName('consola:app:produccion');
			$this->setDescription('Ejecuta el archivo de consola en entorno de Producción');
			$this->addArgument('app', InputArgument::REQUIRED, 'Aplicacion a ejecutar');
			$this->addArgument('claseConsola', InputArgument::REQUIRED, 'Clase a ejecutar');
			$this->addArgument('parametros', InputArgument::IS_ARRAY, 'Lista de parametros separados por espacio');
		}
		
		/**
		 * Produccion::execute()
		 * 
		 * Genera el proceso de ejecucion del script
		 * indicado
		 * 
		 * @param mixed $input
		 * @param mixed $output
		 * @return raw
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			$this->aplicacion = $input->getArgument('app');
			$this->objeto = explode(':', $input->getArgument('claseConsola'));
			$this->parametros = $input->getArgument('parametros');
			
			if(ConfigAcceso::appExistencia($this->aplicacion) == true):
				$this->appDirectorio();
			else:
				throw new \RuntimeException('La aplicación indicada no existe en el archivo de configuración de accesos');
			endif;
		}
		
		/**
		 * Produccion::appDirectorio()
		 * 
		 * Genera la consulta del directorio correspondiente
		 * @return void
		 */
		private function appDirectorio() {
			$this->directorio = ConfigAcceso::leer($this->aplicacion, 'fuente', 'directorio');
			$this->appRuta = implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio));
			$this->consolaRuta = implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos', 'Consola'));
			if(is_dir($this->appRuta) == true):
				$this->appDirectorioLectura();
			else:
				throw new \RuntimeException(sprintf('El directorio de la aplicación: %s, no existe', $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appDirectorioLectura()
		 * 
		 * Genera la validacion de lectura del directorio
		 * de la aplicacion
		 * 
		 * @return void
		 */
		private function appDirectorioLectura() {
			if(is_readable($this->appRuta) == true):
				$this->appConsolaDirectorio();
			else:
				throw new \RuntimeException(sprintf('El directorio de la aplicación: %s, no tiene permisos de lectura', $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appConsolaDirectorio()
		 * 
		 * Genera la validacion de la existencia del 
		 * directorio de consola de complementos
		 * 
		 * @return void
		 */
		private function appConsolaDirectorio() {
			if(is_dir($this->consolaRuta) == true):
				$this->appConsolaDirectorioLectura();
			else:
				throw new \RuntimeException(sprintf('El directorio de Consola de la aplicación: %s, no existe', $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appConsolaDirectorioLectura()
		 * 
		 * Gener ala validacion de la lectura del 
		 * directorio de consola
		 * 
		 * @return void
		 */
		private function appConsolaDirectorioLectura() {
			if(is_readable($this->consolaRuta) == true):
				$this->appConsolaExistencia();
			else:
				throw new \RuntimeException(sprintf('El directorio de Consola de la aplicación: %s, no tiene permisos de lectura', $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appConsolaExistencia()
		 * 
		 * Valida la existencia del archivo a ejecutarse
		 * @return void
		 */
		private function appConsolaExistencia() {
			$archivo = implode(DIRECTORY_SEPARATOR, array_merge(array($this->consolaRuta), $this->objeto)).'.php';
			if(file_exists($archivo) == true):
				$this->appConsolaLectura($archivo);
			else:
				throw new \RuntimeException(sprintf('El Archivo de Consola: %s, de la aplicación: %s, no existe', $this->objeto, $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appConsolaLectura()
		 * 
		 * Genera la validacion de lectura del archivo
		 * @param string $archivo
		 * @return void
		 */
		private function appConsolaLectura($archivo = false) {
			if(is_readable($archivo)== true):
				$this->appConsolaOpciones();
			else:
				throw new \RuntimeException(sprintf('El Archivo de Consola: %s, de la aplicación: %s, no tiene permisos de lectura', $this->objeto, $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::appConsolaOpciones()
		 * 
		 * Genera la carga de las opciones adicionales
		 * que se requieren para la autocarga de las diferentes
		 * opciones que se requieren para ejecutar el archivo
		 * 
		 * @return void
		 */
		private function appConsolaOpciones() {
			define('ENV_ENTORNO', $this->entorno);
			define('ENV_TIPO', 'MVC');
			date_default_timezone_set(ConfigAcceso::leer($this->aplicacion, 'sistema', 'tiempo', 'zona'));
			
			require implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Configuracion', 'Parametros.php'));
			
			$consola = new \AutoCargador('Consola', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos')));
			$consola->registrar();
			
			$entidades = new \AutoCargador('Entidades', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos', 'ORM')));
		 	$entidades->registrar();
		 	
		 	$formulario = new \AutoCargador('Formularios', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos')));
		 	$formulario->registrar();
		 	
		 	
			$interface = new \AutoCargador('Interfaces', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos')));
		 	$interface->registrar();
		 	
		 	Autoloader::register(implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos', 'ORM', 'Proxy')), 'Proxy');
		 	
			$utilidades = new \AutoCargador('Utilidades', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Complementos')));
		 	$utilidades->registrar();
		 	
		 	$modeloMVC = new \AutoCargador('Modelo', implode(DIRECTORY_SEPARATOR, array($this->appRuta, $this->entorno, 'Fuente', 'Sistema')));
		 	$modeloMVC->registrarModelo();
		 	
		 	$this->consolaObjeto();
		}
		
		/**
		 * Produccion::consolaObjeto()
		 *
		 * Valida si el objeto existe dentro 
		 * @return void
		 */
		private function consolaObjeto() {
			$clase = '\\Consola\\'.implode('\\', $this->objeto);
			if(class_exists($clase) == true):
				$this->consolaObjetoMetodo($clase);
			else:
				throw new \RuntimeException(sprintf('La clase: %, no existe dentro del archivo: %s, de la aplicación: %s', $this->objeto, $this->objeto, $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::consolaObjetoMetodo()
		 * 
		 * Genera la validacion de la existencia del metodo
		 * inicial de carga
		 * 
		 * @param string $clase
		 * @return void
		 */
		private function consolaObjetoMetodo($clase = false) {
			$metodos = array_flip(get_class_methods($clase));
			if(array_key_exists('ejecutar', $metodos) == true):
				$this->consolaObjetoEjecutar($clase);
			elseif(method_exists($clase, 'ejecutar') == true):
				throw new \RuntimeException(sprintf('El metodo: ejecutar no es posible ejecutarlo ya que puede ser privado o protegido en la clase: %, aplicación: %s', $this->objeto, $this->aplicacion));
			else:
				throw new \RuntimeException(sprintf('El metodo: ejecutar no existe en la clase: %, aplicación: %s', $this->objeto, $this->aplicacion));
			endif;
		}
		
		/**
		 * Produccion::consolaObjetoEjecutar()
		 * 
		 * Ejecuta el objeto correspondiente
		 * @param string $clase
		 * @return void
		 */
		private function consolaObjetoEjecutar($clase = false) {
			$parametros = (count($this->parametros) >= 1) ? $this->parametros : array(false);
			call_user_func_array(array(new $clase, 'ejecutar'), $parametros);
		}
	}