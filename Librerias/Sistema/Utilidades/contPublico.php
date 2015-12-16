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
	
	namespace Sistema\Utilidades;
	use \Mvc\Controlador;
	use \Neural\Excepcion;
	use \Sistema\Utilidades\ConfigAcceso;
	
	class contPublico extends Controlador {
		
		private $visualizar = array('bmp', 'css', 'gif', 'htm', 'html', 'ico',
		'jpg', 'jpge', 'js', 'pdf', 'png', 'swf', 'txt', 'xml');
		
		private $negativo = array('aab', 'ai', 'asp', 'aspx', 'bat', 'bd', 'bin',
		'bsh', 'c', 'c++', 'cat', 'class', 'com', 'conf', 'cpio', 'cpp', 'cpt',
		'crl', 'crt', 'csh', 'dat', 'dll', 'do', 'dump', 'el', 'elc', 'eps', 'exe', 'f',
		'f77', 'f90', 'for', 'fpx', 'frl', 'gsp', 'gss', 'h', 'hdf', 'hh', 'hlb',
		'hpg', 'hpgl', 'hqx', 'hta', 'htaccess', 'htc', 'htt', 'htx', 'ice', 'ima',
		'imap', 'inf', 'info', 'ins', 'ip', 'iv', 'ivr', 'ivy', 'jam', 'jar', 'jav',
		'java', 'jcm', 'json', 'jsp', 'ksh', 'lha', 'log', 'lsp', 'lzh', 'lzx', 'm',
		'man', 'map', 'mar', 'mbd', 'mc$', 'me', 'mht', 'mhtm', 'mhtml', 'mif', 'mime',
		'mm', 'mpc', 'mpp', 'mpt', 'mpv', 'mpx', 'mrc', 'ms', 'nc', 'ncm', 'nix', 'nsc',
		'nvd', 'o', 'oda', 'omc', 'omcd', 'omcr', 'p', 'p10', 'p12', 'p7a', 'p7c',
		'p7m', 'p7r', 'p7s', 'part', 'pas', 'pcl', 'pdb', 'phar', 'php', 'phtml',
		'pkg', 'pko', 'pl', 'plx', 'pm', 'pm4', 'pm5', 'pnm', 'pot', 'pov', 'pre',
		'prt', 'ps', 'pvu', 'pwz', 'py', 'pyc', 'rm', 'saveme', 'sbk', 'scm', 'sdk',
		'sdml', 'sdp', 'sdr', 'sea', 'set', 'sgm', 'sgml', 'sh', 'shar', 'shtml',
		'sit', 'skd', 'skp', 'skt', 'sl', 'smi', 'so', 'sol', 'spc', 'spl', 'spr',
		'sprite', 'sql', 'sqlite', 'src', 'ssi', 'sst', 'step', 'stl', 'stp', 'sv4cpio',
		'sv4crc', 't', 'talk', 'tbk', 'tcl', 'tcsh', 'tsv', 'unv', 'uri', 'vcd',
		'vda', 'web', 'wmf', 'wmlc', 'wsc', 'wsrc', 'wtk', 'xpix', 'xyz', 'z',
		'zoo', 'zsh');
		
		private $directorio = false;
		
		/**
		 * contPublico::__construct()
		 * 
		 * Genera la variable correspondiente al directorio
		 * de la aplicacion actual
		 * 
		 * @return void
		 */
		function __construct() {
			parent::__construct();
			$this->directorio = ConfigAcceso::leer(APP, 'fuente', 'directorio');
		}
		
		/**
		 * contPublico::Index()
		 * 
		 * Genera el proceso de visualizacion del archivo
		 * publico solicitado
		 * 
		 * @return raw
		 */
		public function Index() {
			$this->publico();
		}
		
		/**
		 * contPublico::publico()
		 * 
		 * Genera el proceso de visualizacion del archivo
		 * publico solicitado
		 * 
		 * @return raw
		 */
		public function publico() {
			$this->publicoExistencia(implode(DIRECTORY_SEPARATOR, array(ROOT_APPS, $this->directorio, ENV_ENTORNO, 'Publico')));
		}
		
		/**
		 * contPublico::protegido()
		 * 
		 * Genera el proceso de visualizacion del archivo
		 * publico solicitado
		 * 
		 * @return raw
		 */
		public function reservado() {
			$this->publicoExistencia(implode(DIRECTORY_SEPARATOR, array(ROOT_LIBRERIA, 'Reservado', 'Publico')));
		}
		
		/**
		 * contPublico::publicoExistencia()
		 * 
		 * Genera la validacion de la existencia
		 * del directorio publico correspondiente
		 * 
		 * @param bool $ruta
		 * @return void
		 */
		private function publicoExistencia($ruta = false) {
			if(is_dir($ruta) == true):
				$this->publicoLectura($ruta);
			else:
				throw new Excepcion(sprintf('No existe el directorio publico de la aplicación: %s', APP), 0, APP);
			endif;
		}
		
		/**
		 * contPublico::publicoLectura()
		 * 
		 * Genera la validacion si 
		 * @param bool $ruta
		 * @return void
		 */
		private function publicoLectura($ruta = false) {
			if(is_readable($ruta) == true):
				$this->parametros($ruta);
			else:
				throw new Excepcion(sprintf('No es posible leer el directorio publico de la aplicación: %s', APP), 0, APP);
			endif;
		}
		
		/**
		 * contPublico::parametros()
		 * 
		 * Genera la validacion si hay parametros
		 * para buscar el archivo en el directorio
		 * publico correspondiente
		 * 
		 * @param string $ruta
		 * @return void
		 */
		private function parametros($ruta = false) {
			if(is_array($this->url['mvc']['parametro']) == true):
				$this->archivoExistencia(implode(DIRECTORY_SEPARATOR, array_merge(array($ruta), $this->url['mvc']['parametro'])));
			else:
				throw new Excepcion('No hay datos para procesar', 0, APP);
			endif;
		}
		
		/**
		 * contPublico::archivoExistencia()
		 * 
		 * Genera la validacion de la existencia de
		 * archivo indicado
		 * 
		 * @param string $archivo
		 * @return raw
		 */
		private function archivoExistencia($archivo = false) {
			if(file_exists($archivo) == true):
				$this->archivoLectura($archivo);
			else:
				throw new Excepcion(sprintf('El archivo publico no existe en la aplicación: %s', APP), 0, APP);
			endif;
		}
		
		/**
		 * contPublico::archivoLectura()
		 * 
		 * Genera la validacion de la lectura del
		 * archivo publico
		 * 
		 * @param string $archivo
		 * @return raw
		 */
		private function archivoLectura($archivo = false) {
			if(is_readable($archivo) == true):
				$this->extensionProhibido($archivo);
			else:
				throw new Excepcion(sprintf('El archivo publico no es posible leerlo en la aplicación: %s', APP), 0, APP);
			endif;
		}
		
		/**
		 * contPublico::extensionProhibido()
		 * 
		 * Genera la validacion si es un archivo permitido
		 * @param string $archivo
		 * @return raw
		 */
		private function extensionProhibido($archivo = false) {
			$publico = pathinfo($archivo);
			if(array_key_exists($publico['extension'], array_flip($this->negativo)) == false):
				$this->extensionAdmitido($publico);
			else:
				throw new Excepcion(sprintf('No es posible observar el archivo: %s en la aplicación: %s', $publico['basename'], APP), 0, APP);
			endif;
		}
		
		/**
		 * contPublico::extensionAdmitido()
		 *
		 * genera la validacion de los archivos admitidos
		 * y mostrar el archivo correspondiente
		 *  
		 * @param array $array
		 * @return raw
		 */
		private function extensionAdmitido($array = false) {
			if(array_key_exists($array['extension'], array_flip($this->visualizar)) == true):
				$this->observar($array);
			else:
				$this->descargar($array);
			endif;
		}
		
		/**
		 * contPublico::visualizar()
		 * 
		 * Genera la visualizacion del archivo
		 * correspondiente
		 * 
		 * @param array $array
		 * @return raw
		 */
		private function observar($array = false) {
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			$this->cabecera->header($array['extension']);
			header("Content-Length: ".filesize($array['dirname'].DIRECTORY_SEPARATOR.$array['basename']));
			header('Content-Disposition: inline; filename='.sha1($array['filename']).'.'.$array['extension']);
			header('Content-Transfer-Encoding: binary');
			$Fopen = fopen($array['dirname'].DIRECTORY_SEPARATOR.$array['basename'], 'rb');
			$Buffer = fread($Fopen, filesize($array['dirname'].DIRECTORY_SEPARATOR.$array['basename']));
			fclose ($Fopen);
			print $Buffer;
			exit();
		}
		
		/**
		 * contPublico::descargar()
		 * 
		 * Genera el proceso de descarga del archivo
		 * @param array $array
		 * @return raw
		 */
		private function descargar($array = false) {
			$this->cabecera->headerDescarga($array['dirname'].DIRECTORY_SEPARATOR.$array['basename'], true);
			exit();
		}
	}