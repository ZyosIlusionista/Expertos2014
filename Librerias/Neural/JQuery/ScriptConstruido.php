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
	
	namespace Neural\JQuery;
	use \Neural\Excepcion;
	
	class ScriptConstruido {
		
		private $idPrincipal = false;
		private $idSecundario = false;
		private $punteroSeleccion = false;
		private $tipoPeticion = false;
		private $urlSeleccion = false;
		public $jQuery = 'jQuery';
		
		/**
		 * ScriptConstruido::inputPrincipal()
		 * 
		 * Validacion de la variable
		 * @return void
		 */
		private function inputValidar($variable = false, $nombre = false) {
			if(is_string($this->{$variable}) == false):
				throw new Excepcion(sprintf('Es necesario indicar: %s, para el proceso del ScriptConstruido', $nombre), 0);
			endif;
		}
		
		/**
		 * ScriptConstruido::idBase()
		 * 
		 * Asigna el id base para el script
		 * @param string $id
		 * @return object
		 */
		public function idBase($id = false) {
			if(is_string($id) == true):
				$this->idPrincipal = $id;
			else:
				throw new Excepcion('Debe ingresar el ID Base para el script en el proceso de ScriptConstruido', 0);
			endif;
			return $this;
		}
		
		/**
		 * ScriptConstruido::idDestino()
		 * 
		 * Asigna el id de destino
		 * @param string $id
		 * @return object
		 */
		public function idDestino($id = false) {
			if(is_string($id) == true):
				$this->idSecundario = $id;
			else:
				throw new Excepcion('Debe ingresar el ID Destino para el script en el proceso de ScriptConstruido', 0);
			endif;
			return $this;
		}
		
		/**
		 * ScriptConstruido::peticion()
		 * 
		 * Asigna el tipo de peticion, ya sea POST o GET
		 * @param string $tipo
		 * @return object
		 */
		public function peticion($tipo = 'post') {
			if(is_string($tipo) == true):
				$this->tipoPeticion = (array_key_exists(mb_strtolower($tipo), array_flip(array('post', 'get'))) == true) ? mb_strtolower($tipo) : 'post';
			else:
				throw new Excepcion('Indique el tipo de petición [ POST - GET ] en el proceso de ScriptConstruido', 0);
			endif;
			return $this;
		}
		
		/**
		 * ScriptConstruido::url()
		 * 
		 * Asigna la url de la peticion
		 * @param string $url
		 * @return object
		 */
		public function url($url = false) {
			if(is_string($url) == true):
				$this->urlSeleccion = $url;
			else:
				throw new Excepcion('Indique la url para la petición en el proceso de ScriptConstruido', 0); 
			endif;
			return $this;
		}
		
		/**
		 * ScriptConstruido::puntero()
		 * 
		 * Asigna el nombre de la variable enviada
		 * @param string $nombre
		 * @return object
		 */
		public function puntero($nombre = false) {
			if(is_string($nombre) == true):
				$this->punteroSeleccion = $nombre;
			else:
				throw new Excepcion('Debe ingresar el puntero para enviar los datos en el proceso de ScriptConstruido', 0);
			endif;
			return $this;
		}
		
		/**
		 * ScriptConstruido::scriptSelectDependiente()
		 * 
		 * Genera un proceso simple de carga de datos el
		 * cual puede ser utilizado como select dependiente
		 * 
		 * @return string
		 */
		public function scriptSelectDependiente() {
			$this->inputValidar('idPrincipal', 'ID Base');
			$this->inputValidar('idSecundario', 'ID Destino');
			$this->inputValidar('urlSeleccion', 'URL');
			$this->inputValidar('tipoPeticion', 'Tipo de Petición [ POST - GET ]');
			$this->inputValidar('punteroSeleccion', 'Nombre de la variable enviada');
			
			$script = <<<EOT
	<script type="text/javascript">
		{$this->jQuery}(document).ready(function() {
			{$this->jQuery}("#{$this->idPrincipal}").change(function() {
				{$this->jQuery}("#{$this->idPrincipal} option:selected").each(function() {
					{$this->jQuery}.{$this->tipoPeticion}("{$this->urlSeleccion}", { {$this->punteroSeleccion} : {$this->jQuery}(this).val() }, function(respuesta) {
						{$this->jQuery}("#{$this->idSecundario}").html(respuesta);
					});
				});
			});
		});
	</script>
EOT;
			$this->idPrincipal = false;
			$this->idSecundario = false;
			$this->urlSeleccion = false;
			$this->tipoPeticion = false;
			$this->punteroSeleccion = false;
			return $script;
		}
	}