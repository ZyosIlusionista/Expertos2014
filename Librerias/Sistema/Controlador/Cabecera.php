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
	
	namespace Sistema\Controlador;
	use \Neural\Excepcion;
	
	class Cabecera {
		
		/**
		 * Cabecera::header()
		 * 
		 * Genera la cabecera de forma automatica
		 * segun las extensiones registradas
		 * 
		 * @param string $extension
		 * @return stream
		 */
		public function header($extension = false) {
			if(is_string($extension) == true):
				$this->headerExtension($extension);
			else:
				throw new Excepcion('Debe ingresar la extensión del tipo de archivo para la cabecera', 0);
			endif;
		}
		
		/**
		 * Cabecera::headerExtension()
		 * 
		 * Selecciona la cabecera correspondiente dependiendo
		 * de la extension del archivo
		 * 
		 * @param string $extension
		 * @return stream
		 */
		private function headerExtension($extension = false) {
			if($extension == 'bmp'):
				header('Content-Type: image/bmp');
			elseif($extension == 'css'):
				header('Content-Type: text/css');
			elseif($extension == 'html'):
				header('Content-Type: text/html');
			elseif($extension == 'htt'):
				header('Content-Type: text/webviewhtml');
			elseif($extension == 'ico'):
				header('Content-Type: image/x-icon');
			elseif($extension == 'gif'):
				header('Content-Type: image/gif');
			elseif($extension == 'jpe' OR $extension == 'jpeg' OR $extension == 'jpg'):
				header('Content-Type: image/jpeg');
			elseif($extension == 'js'):
				header('Content-Type: text/javascript');
			elseif($extension == 'pdf'):
				header('Content-Type: application/pdf');
			elseif($extension == 'txt'):
				header('Content-Type: text/plain');
			elseif($extension == 'zip'):
				header('Content-Type: application/octet-stream');
			elseif($extension == 'rar'):
				header('Content-Type: application/octet-stream');
			elseif($extension == 'png'):
				header('Content-Type: image/png');
			elseif($extension == 'xml'):
				header('Content-Type: text/xml');
			elseif($extension == 'json'):
				header('Content-Type: application/json');
			else:
				throw new Excepcion(sprintf('No se encuentra la cabecera indicada para la extension: %s', $extension), 0);
			endif;
		}
		
		/**
		 * Cabecera::headerDescarga()
		 * 
		 * Genera la descarga del archivo correspondiente
		 * la ruta debe ser la ruta completa el path del
		 * archivo a descargar y es posible codificar el
		 * nombre del archivo para su descarga
		 * 
		 * @param string $archivo
		 * @param bool $codificar
		 * @return raw
		 */
		public function headerDescarga($archivo = false, $codificar = false) {
			if(file_exists($archivo) == true):
				$this->headerArchivoDescargaLectura($archivo, $codificar);
			else:
				throw new Excepcion('El archivo no existe para generar la cabecera de descarga', 0);
			endif;
		}
		
		/**
		 * Cabecera::headerArchivoDescargaLectura()
		 * 
		 * genera la validacion de lectura del archivo para
		 * la descarga correspondiente
		 * 
		 * @param string $archivo
		 * @param bool $codificar
		 * @return raw
		 */
		private function headerArchivoDescargaLectura($archivo = false, $codificar = false) {
			if(is_readable($archivo) == true):
				$this->headerArchivoDownload($archivo, $codificar);
			else:
				throw new Excepcion('El archivo no es posible leerlo para generar la cabecera de descarga', 0);
			endif;
		}
		
		/**
		 * Cabecera::headerArchivoDownload()
		 * 
		 * Genera el proceso de descarga
		 * @param string $archivo
		 * @param bool $codificar
		 * @return raw
		 */
		private function headerArchivoDownload($archivo = false, $codificar = false) {
			$array = pathinfo($archivo);
			$nombre = ($codificar == true) ? sha1($array['filename']).'.'.$array['extension'] : $array['filename'].'.'.$array['extension'];
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$nombre);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: '.filesize($archivo));
			ob_clean();
			flush();
			readfile($archivo);
		}
		
		/**
		 * Cabecera::noCache()
		 * 
		 * Aplica la cabecera de no guardar cache en el navegador
		 * @return stream
		 */
		public function noCache() {
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Pragma: no-cache');
		}
		
		/**
		 * Cabecera::redireccion()
		 * 
		 * Genera el proceso de redireccion a la 
		 * url correspondiente
		 * 
		 * @param string $direccion
		 * @return stream
		 */
		public function redireccion($direccion = false) {
			if(is_string($direccion) == true):
				header("Location: ".$direccion);
			else:
				throw new Excepcion('Debe ingresar la dirección a la cual redireccionar');
			endif;
		}
	}