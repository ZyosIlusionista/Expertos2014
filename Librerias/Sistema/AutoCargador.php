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
	use \Neural\Excepcion;
	
	/**
	 * Autocargador de clases
	 * 
	 * Genera el proceso de la carga de las clases
	 * correspondientes
	 */
	class AutoCargador {
		
		private $namespace = false;
		private $ruta = false;
		private $separador = '\\';
		private $extension = '.php';
		
		function __construct($namespace = false, $ruta = false) {
			$this->namespace = $namespace;
			$this->ruta = $ruta;
		}
		
		public function getSeparador() {
			return $this->separador;
		}
		
		public function setRuta($ruta = false) {
			$this->ruta = $ruta;
		}
		
		public function getRuta() {
			return $this->ruta;
		}
		
		public function setExtension($extension = false) {
			$this->extension = $extension;
		}
		
		public function getExtension() {
			return $this->extension;
		}
		
		public function registrar() {
			spl_autoload_register(array($this, 'cargarClase'));
		}
		
		public function registrarControlador() {
			spl_autoload_register(array($this, 'cargarClaseControlador'));
		}
		
		public function registrarModelo() {
			spl_autoload_register(array($this, 'cargarClaseModelo'));
		}
		
		public function anulaRegistro() {
			spl_autoload_unregister(array($this, 'cargarClase'));
		}
		
		private function cargarClase($clase) {
			if($this->namespace == null OR $this->namespace.$this->separador === substr($clase, 0, strlen($this->namespace.$this->separador))):
				$archivo = '';
				$namespace = '';
				$posicion = strripos($clase, $this->separador);				
				if($posicion !== false):
					$namespace = substr($clase, 0, $posicion);
					$clase= substr($clase, $posicion + 1);
					$archivo = str_replace($this->separador, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
				endif;
				$archivo .= str_replace('_', DIRECTORY_SEPARATOR, $clase) . $this->extension;
				//require ($this->ruta !== null ? $this->ruta . DIRECTORY_SEPARATOR : '') . $archivo;
				$archivo = ($this->ruta !== null ? $this->ruta . DIRECTORY_SEPARATOR : '') . $archivo;
				if(file_exists($archivo) == true):
					require $archivo;
				else:
					throw new Excepcion(sprintf('La clase: [ %s ] del namespace: [ %s ] solicitado no existe o esta mal enrutado', $clase, $this->namespace), 0);
				endif;
			endif;
		}
		
		private function cargarClaseControlador($clase) {
			if($this->namespace == null OR $this->namespace.$this->separador === substr($clase, 0, strlen($this->namespace.$this->separador))):
				$archivo = '';
				$namespace = '';
				$posicion = strripos($clase, $this->separador);
				
				if($posicion !== false):
					$namespace = substr($clase, 0, $posicion);
					$info = explode('\\', $namespace);
					$clase = substr($clase, $posicion + 1);
					if(isset($info[1]) == true AND mb_strtolower($info[1]) == 'mvc'):
						$archivo = implode(DIRECTORY_SEPARATOR, array($this->ruta, 'MVC', 'Controladores', $clase));
					else:
						$archivo = implode(DIRECTORY_SEPARATOR, array($this->ruta, 'Modulos', $info[2], 'Controladores', $clase));
					endif;
				endif;
				if(file_exists($archivo.$this->extension) == true):
					require $archivo.$this->extension;
				else:
					throw new Excepcion(sprintf('La clase: [ %s ] del namespace: [ %s ] solicitado no existe o esta mal enrutado', $clase, $this->namespace), 0);
				endif;
			endif;
		}
		
		private function cargarClaseModelo($clase) {
			if($this->namespace == null OR $this->namespace.$this->separador === substr($clase, 0, strlen($this->namespace.$this->separador))):
				$archivo = '';
				$namespace = '';
				$posicion = strripos($clase, $this->separador);
				
				if($posicion !== false):
					$namespace = substr($clase, 0, $posicion);
					$info = explode('\\', $namespace);
					$clase = substr($clase, $posicion + 1);
					if (isset($info[1]) == true AND mb_strtolower($info[1]) == 'mvc'):
						$archivo = implode(DIRECTORY_SEPARATOR, array($this->ruta, 'MVC', 'Modelos', $clase));
					else:
						$archivo = implode(DIRECTORY_SEPARATOR, array($this->ruta, 'Modulos', $info[2], 'Modelos', $clase));
					endif;
				endif;
				if(file_exists($archivo.$this->extension) == true):
					require $archivo.$this->extension;
				else:
					throw new Excepcion(sprintf('La clase: [ %s ] del namespace: [ %s ] solicitado no existe o esta mal enrutado', $clase, $this->namespace), 0);
				endif;
			endif;
		}
	}