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
	
	namespace Cli\Formulario;
	use \Sistema\Utilidades\ConfigAcceso;
	use \Symfony\Component\Console\Command\Command;
	use \Symfony\Component\Console\Input\InputArgument;
	use \Symfony\Component\Console\Input\InputInterface;
	use \Symfony\Component\Console\Input\InputOption;
	use \Symfony\Component\Console\Output\OutputInterface;
	
	class Desarrollo extends Command {
		
		private $archivo = false;
		private $clase = false;
		private $entorno = 'Desarrollo';
		private $namespace = false;
		private $metodosFormato = false;
		private $directorio = false;
		private $metodo = false;
		private $input = false;
		private $output = false;
		
		/**
		 * Desarrollo::configure()
		 * 
		 * Genera los parametros basicos de configuracion
		 * @return void
		 */
		protected function configure() {
			$this->setName('neural:formulario:desarrollo');
			$this->setDescription('Genera la creación de las validaciones de formularios');
			$this->addArgument('app', InputArgument::REQUIRED, 'Aplicación donde se generara el proceso');
			$this->addArgument('namespace', InputArgument::REQUIRED, 'Nombre de la clase donde se ejecutara la validación');
			$this->addArgument('metodo', InputArgument::REQUIRED, 'Metodo de Envio del Formulario POST - GET');
			$this->addArgument('campos', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Campos del formulario a validar');
		}
		
		/**
		 * Desarrollo::execute()
		 * 
		 * Genera el proceso de ejecucion del script
		 * indicado
		 * 
		 * @param mixed $input
		 * @param mixed $output
		 * @return raw
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			$this->input = $input;
			$this->output = $output;
			$this->directorio = $this->inputExistenciaApp($input->getArgument('app'));
			$this->namespace = $this->inputNamespaceFormato($input->getArgument('namespace'));
			$this->metodo = $this->inputMetodo($input->getArgument('metodo'));
			$this->clase = end($this->namespace);
			$this->directorioExistencia();
			$this->archivo = $this->archivoExistencia();
			$this->formatoCampos($input->getArgument('campos'));
			$this->creacionArchivo();
			
			$output->writeln('');
			$output->writeln('<info>Se ha finalizado el proceso de creación del validador de formularios</info>');
			$output->writeln('');
		}
		
		/**
		 * Desarrollo::creacionArchivo()
		 * 
		 * Genera el proceso de creacion del archivo
		 * @return void
		 */
		private function creacionArchivo() {
			if(count($this->namespace) > 2):
				mkdir(dirname($this->archivo), 0777, true);
			endif;
			touch($this->archivo);
			$fopen = fopen($this->archivo, 'w');
			fwrite($fopen, $this->plantillaPHP());
			fclose($fopen);
		}
		
		/**
		 * Desarrollo::inputMetodo()
		 * 
		 * Genera la validacion del metodo del formulario
		 * 
		 * @param string $metodo
		 * @return string
		 */
		private function inputMetodo($metodo = false) {
			if(array_key_exists(mb_strtolower($metodo), array_flip(array('post', 'get'))) == true):
				return mb_strtolower($metodo);
			else:
				throw new \RuntimeException(sprintf('El metodo: %s no es valido, solo es soportado [ POST - GET ]', $metodo));
			endif;
		}
		
		/**
		 * Desarrollo::formatoCampos()
		 * 
		 * Genera el proceso de formato de parametros
		 * @param bool $array
		 * @return void
		 */
		private function formatoCampos($array = false) {
			foreach ($array AS $valor):
				$this->formatoCamposSeleccion($valor);
			endforeach;
		}
		
		/**
		 * Desarrollo::formatoCamposSeleccion()
		 * 
		 * Separa el formato correspondiente
		 * para los diferentes parametros
		 * 
		 * @param string $texto
		 * @return void
		 */
		private function formatoCamposSeleccion($texto = false) {
			$array = explode('::', $texto);
			foreach ($array AS $valor):
				$info = explode(':', $valor);
				$lista[mb_strtolower($info[0])] = trim($info[1]);
			endforeach;
			$this->formatoCamposValidacion($lista);
		}
		
		/**
		 * Desarrollo::formatoCamposValidacion()
		 * 
		 * Genera la validacion de los diferentes campos
		 * @param array $array
		 * @return void
		 */
		private function formatoCamposValidacion($array = false) {
			$param = array('campo', 'columna', 'validacion', 'existencia', 'tipo');
			foreach($param AS $valor):
				if(array_key_exists($valor, $array) == false):
					throw new \RuntimeException(sprintf('No esta definido el parametro: %s en el campo', $valor));
				endif;
			endforeach;
			$this->formatos($array);
		}
		
		/**
		 * Desarrollo::formatos()
		 * 
		 * Genera el proceso de construccion de los campos
		 * de validacion
		 * 
		 * @param array $array
		 * @return void
		 */
		private function formatos($array = false) {
			$array['tipo'] = $this->formatoTipo($array['tipo'], $array['campo']);
			$this->metodosFormato['propiedad'][] = $this->plantillaPropiedad($array);
			$this->metodosFormato['get'][] = $this->plantillaGetMetodo($array);
			$this->metodosFormato['set'][] = $this->plantillaSetMetodo($array);
		}
		
		/**
		 * Desarrollo::archivoExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * del formulario correspondiente
		 * 
		 * @return void
		 */
		private function archivoExistencia() {
			$dir = implode(DIRECTORY_SEPARATOR, array($this->directorio, $this->entorno, 'Fuente', 'Complementos', 'Formularios'));
			if(is_dir($dir) == true):
				return $this->archivoEscritura($dir);
			else:
				throw new \RuntimeException(sprintf('El directorio de Formularios de la apliación [ %s ] no existe', $this->input->getArgument('app')));
			endif;
		}
		
		/**
		 * Desarrollo::archivoEscritura()
		 * 
		 * Genera la validacion de la escritura del directorio
		 * 
		 * @param string $directorio
		 * @return void
		 */
		private function archivoEscritura($directorio = false) {
			if(is_writable($directorio) == true):
				return $this->archivoExistenciaArchivo();
			else:
				throw new \RuntimeException(sprintf('El directorio de Formularios de la apliación [ %s ] no es posible escribir en el directorio', $this->input->getArgument('app')));
			endif;
		}
		
		/**
		 * Desarrollo::archivoExistenciaArchivo()
		 * 
		 * Genera el proceso de validacion del archivo
		 * @return void
		 */
		private function archivoExistenciaArchivo() {
			$archivo = implode(DIRECTORY_SEPARATOR, array_merge(array($this->directorio, $this->entorno, 'Fuente', 'Complementos'), $this->namespace));
			if(file_exists($archivo.'.php') == false):
				return $archivo.'.php';
			else:
				throw new \RuntimeException(sprintf('El Archivo de validación de Formularios de la apliación [ %s ] ya existe en la ruta especificada', $this->input->getArgument('app')));
			endif;
		}
		
		/**
		 * Desarrollo::directorioExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * del directorio de la aplicacion
		 * 
		 * @return void
		 */
		private function directorioExistencia() {
			if(is_dir($this->directorio) == false):
				throw new \RuntimeException(sprintf('El directorio de la apliación [ %s ] no existe', $this->input->getArgument('app')));
			endif;
		}
		
		/**
		 * Desarrollo::inputNamespaceFormato()
		 * 
		 * Genera el array correspondiente del 
		 * namespace correspondiente
		 * 
		 * @param string $namespace
		 * @return array
		 */
		private function inputNamespaceFormato($namespace = false) {
			return array_merge(array('Formularios'), explode(':', $namespace));
		}
		
		/**
		 * Desarrollo::inputExistenciaApp()
		 * 
		 * Genera la validacion de la existencia de 
		 * la aplicacion y retorna el directorio base
		 * 
		 * @param string $app
		 * @return string
		 */
		private function inputExistenciaApp($app = false) {
			if(ConfigAcceso::appExistencia($app) == true):
				return implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, ConfigAcceso::leer($app, 'fuente', 'directorio')));
			else:
				throw new \RuntimeException(sprintf('La aplicación: %s no se encuentra registrada en el archivo de configuración de accesos', $app));
			endif;
		}
		
		/**
		 * Desarrollo::formatoValidacion()
		 * 
		 * Genera el formato de validacion correspondiente
		 * @param string $validacion
		 * @return string
		 */
		private function formatoValidacion($validacion = false) {
			$array = explode('-', $validacion);
			foreach ($array AS $valor):
				$explode = explode(',', $valor);
				$lista[$explode[0]] = $explode[1];
			endforeach;
			return json_encode($lista);
		}
		
		/**
		 * Desarrollo::formatoTipo()
		 * 
		 * Genera el formato del tipo de datos
		 * @param string $tipo
		 * @param string $campo
		 * @return string
		 */
		private function formatoTipo($tipo = false, $campo = false) {
			$llaves = array('booleano' => 'boolean', 'entero' => 'integer', 'flotante' => 'float', 'texto' => 'string', 'nulo' => 'null');
			if(array_key_exists($tipo, $llaves) == true):
				return $tipo;
			else:
				throw new \RuntimeException(sprintf('El tipo: %s no es valido para el campo: %s, Solo son validos: %s', $tipo, $campo, implode(', ', array_flip($llaves))));
			endif;
		}
		
		/**
		 * Desarrollo::plantillaPropiedad()
		 * 
		 * Genera la plantilla de la propiedad para el
		 * procedimiento correspondiente
		 * 
		 * @param array $array
		 * @return string
		 */
		private function plantillaPropiedad($array = false) {
			$validacion = $this->formatoValidacion($array['validacion']);
			return <<<EOT
		/**
		 * validacion::{$array['campo']}
		 * 
		 * @Columna "{$array['columna']}"
		 * @Validacion {$validacion}
		 * @Existencia {$array['existencia']}
		 * @Tipo "{$array['tipo']}"
		 */
		private \${$array['campo']};
EOT;
		}
		
		/**
		 * Desarrollo::plantillaGetMetodo()
		 * 
		 * Genera la plantilla del metodo para
		 * obtener los datos correspondientes
		 * 
		 * @param array $array
		 * @return string
		 */
		private function plantillaGetMetodo($array = false) {
			$campo = ucfirst(mb_strtolower($array['campo']));
			return <<<EOT
		/**
		 * validacion::get{$campo}()
		 * 
		 * @return {$array['tipo']}
		 */
		public function get{$campo}() {
			return \$this->{$array['campo']};
		}
EOT;
		}
		
		/**
		 * Desarrollo::plantillaSetMetodo()
		 * 
		 * Genera la plantilla del metodo de asignacion
		 * de informacion para la clase
		 * 
		 * @param array $array
		 * @return string
		 */
		private function plantillaSetMetodo($array = false) {
			
			$campo = ucfirst(mb_strtolower($array['campo']));
			return <<<EOT
		/**
		 * validacion::set{$campo}()
		 * 
		 * @param {$array['tipo']} \${$campo}
		 * @return void
		 */
		public function  set{$campo}(\${$array['campo']} = false) {
			\$this->{$array['campo']} = \${$array['campo']};
		}
EOT;
		}
		
		/**
		 * Desarrollo::plantillaPHP()
		 * 
		 * Genera la plantilla correspondiente para el archivo
		 * de la validacion del formulario
		 * 
		 * @return string
		 */
		private function plantillaPHP() {
			if(count($this->namespace) > 1):
				array_pop($this->namespace);
			endif;
			$namespace = implode('\\', $this->namespace);
			$propiedad = implode("\n\n", $this->metodosFormato['propiedad']);
			$get = implode("\n\n", $this->metodosFormato['get']);
			$set = implode("\n\n", $this->metodosFormato['set']);
			
			return <<<EOT
<?php
	
	namespace {$namespace};
	use \Neural\Excepcion;
	
	/**
	 * Validacion de Formulario
	 * 
	 * @Formulario "{$namespace}\\{$this->clase}"
	 * @Metodo "post"
	 * @Tipo "unidimensional"
	 */
	class {$this->clase} {
		
{$propiedad}

{$get}

{$set}

	}
EOT;
		}
	}